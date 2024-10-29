<?php

namespace App\Console\Commands;

use App\Enums\CharacterCategoryEnum;
use App\Enums\Defaults;
use App\Enums\FeatureCategoryEnum;
use App\Enums\FeatureEnum;
use App\Enums\Pattern;
use App\Enums\RarityEnum;
use App\Enums\SpeciesEnum;
use App\Enums\SubtypeEnum;
use App\Models\Character\Character;
use App\Models\Character\CharacterCategory;
use App\Models\Character\CharacterFeature;
use App\Models\Character\CharacterImage;
use App\Models\Feature\Feature;
use App\Models\Feature\FeatureCategory;
use App\Models\Rarity;
use App\Models\Species\Species;
use App\Models\Species\Subtype;
use App\Models\User\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImportCharacters extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'characters:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $user = $this->getAdminUser();
        $categories = $this->createCharacterCategories();
        $rarities = $this->createRarities();
        $species = $this->createSpecies();
        $subtypes = $this->createSubtypes($species);
        $featureCategories = $this->createFeatureCategories();
        $features = $this->createFeatures($featureCategories, $rarities);

        $file_name = $this->ask("Enter the output file name (exclude extension, must be in /data/tsv)");

        $csv = $this->getCSV($file_name);
        while (($row = $this->getRow($csv)) !== false) {
            $data = $this->getCharacterData($row, $categories, $rarities, $species, $subtypes, $features);
            $this->info(json_encode($data, JSON_PRETTY_PRINT));
        }
        fclose($csv);

        return 0;
    }

    /**********************************************************************************************

        DATA PROCESSING

    **********************************************************************************************/

    protected function createCharacterCategories(): array {
        try {
            $categories = [];
            foreach (config('lorekeeper.character-import.character_categories') as $key => $category) {
                ['code' => $code, 'name' => $name] = $category;
                $category = CharacterCategory::where('name', $name)->where('code', $code)->first();
                if (!$category) {
                    $category = CharacterCategory::create([
                        'code' => $code,
                        'name' => $name,
                    ]);
                } else {
                    $this->line('Character category already exists: '.$name);
                }
                $categories[$key] = $category;
            }

            return $categories;
        } catch (Exception $e) {
            $this->error('Error creating character categories.');
        }
    }

    protected function createRarities(): array {
        try {
            $rarities = [];
            foreach (config('lorekeeper.character-import.rarities') as $key => $rarity) {
                ['name' => $name] = $rarity;
                $rarity = Rarity::where('name', $name)->first();
                if (!$rarity) {
                    $rarity = Rarity::create([
                        'name' => $name,
                    ]);
                } else {
                    $this->line('Rarity already exists: '.$name);
                }
                $rarities[$key] = $rarity;
            }

            return $rarities;
        } catch (Exception $e) {
            $this->error('Error creating rarities.');
        }
    }

    protected function createSpecies(): array {
        try {
            $specieses = [];
            foreach (config('lorekeeper.character-import.species') as $key => $species) {
                ['name' => $name] = $species;
                $species = Species::where('name', $name)->first();
                if (!$species) {
                    $species = Species::create([
                        'name' => $name,
                    ]);
                } else {
                    $this->line('Species already exists: '.$name);
                }
                $specieses[$key] = $species;
            }

            return $specieses;
        } catch (Exception $e) {
            $this->error('Error creating species.');
        }
    }

    protected function createSubtypes($specieses): array {
        try {
            $subtypes = [];
            foreach (config('lorekeeper.character-import.subtypes') as $key => $subtype) {
                ['name' => $name, 'species' => $species] = $subtype;
                $species = $specieses[$species];
                $subtype = Subtype::where('species_id', $species->id)->where('name', $name)->first();
                if (!$subtype) {
                    $subtype = Subtype::create([
                        'species_id' => $species->id,
                        'name'       => $name,
                    ]);
                } else {
                    $this->line('Subtype already exists: '.$name);
                }
                $subtypes[$key] = $subtype;
            }

            return $subtypes;
        } catch (Exception $e) {
            $this->error('Error creating subtypes.');
        }
    }

    protected function createFeatureCategories(): array {
        try {
            $featureCategories = [];
            foreach (config('lorekeeper.character-import.feature_categories') as $key => $featureCategory) {
                ['name' => $name] = $featureCategory;
                $featureCategory = FeatureCategory::where('name', $name)->first();
                if (!$featureCategory) {
                    $featureCategory = FeatureCategory::create([
                        'name' => $name,
                    ]);
                } else {
                    $this->line('Feature category already exists: '.$name);
                }
                $featureCategories[$key] = $featureCategory;
            }

            return $featureCategories;
        } catch (Exception $e) {
            $this->error('Error creating feature categories.');
        }
    }

    protected function createFeatures($featureCategories, $rarities): array {
        try {
            $features = [];
            foreach (config('lorekeeper.character-import.features') as $key => $feature) {
                ['name' => $name, 'rarity' => $rarity, 'category' => $category] = $feature;
                $rarity = $rarities[$rarity];

                if ($category != null) {
                    $category = $featureCategories[$category];
                    $feature = Feature::where('feature_category_id', $category->id)->where('rarity_id', $rarity->id)->where('name', $name)->first();
                } else {
                    $feature = Feature::where('rarity_id', $rarity->id)->where('name', $name)->first();
                }

                if (!$feature) {
                    $feature = Feature::create([
                        'feature_category_id' => $category != null ? $category->id : null,
                        'rarity_id'           => $rarity->id,
                        'name'                => $name,
                    ]);
                } else {
                    $this->line('Feature already exists: '.$name);
                }
                $features[$key] = $feature;
            }

            return $features;
        } catch (Exception $e) {
            $this->error('Error creating features.');
        }
    }

    protected function handleCharacter($data): Character {
        try {
            $characterData = array_intersect_key($data, array_flip([
                'character_category_id', 
                'rarity_id', 'species_id', 'subtype_id', 'owner_url',
                'name', 'number', 'slug', 'description'
            ]));
            $characterData['parsed_description'] = parse($data['description']);

            $this->line('Creating character: '.$data['slug'].': '.$data['name']);
            $character = Character::create($characterData);
            $character->profile()->create([]);

            if ($character) {
                $this->line('Created: '.$data['slug'].': '.$data['name']);
            }
            return $character;
        } catch (Exception $e) {
            $this->error('Error creating character.');
        }
    }

    protected function handleCharacterFeatures($data, $character): array {
        try {
            $features = [];
            // Attach features
            foreach ($data['features'] as $feature) {
                $characterFeature = CharacterFeature::create([
                    'character_id' => $character->id,
                    'feature_id' => $feature->id
                ]);
                $features[] = $characterFeature->id;
            }
            return $features;
        } catch (Exception $e) {
            $this->error('Error creating character features.');
        }
    }

    protected function handleCharacterImage($data, $character): CharacterImage {
        try {
            $imageData = array_intersect_key($data, array_flip([
                'use_cropper', 'x0', 'x1', 'y0', 'y1'
            ]));
            $imageData['description'] = $data['image_description'];
            $imageData['parsed_description'] = parse($imageData['description']);
            $imageData['hash'] = randomString(10);
            $imageData['fullsize_hash'] = randomString(15);
            $imageData['sort'] = 0;
            $imageData['extension'] = (config('lorekeeper.settings.masterlist_image_format') ?? ($data['extension'] ?? $data['image']->getClientOriginalExtension()));
            $imageData['fullsize_extension'] = (config('lorekeeper.settings.masterlist_fullsizes_format') ?? ($data['fullsize_extension'] ?? $data['image']->getClientOriginalExtension()));
            $imageData['character_id'] = $character->id;

            $image = CharacterImage::create($imageData);

            $this->saveImage($data['image'], $image->imageDirectory, $image->imageFileName);

            return $image;
        } catch (Exception $e) {
            $this->error('Error creating character image.');
        }
    }

    protected function handleCharacterDesigner($data, $image) {
        try {
            if ($data['designer_url']) {
                DB::table('character_image_creators')->insert([
                    'character_image_id' => $image->id,
                    'type'               => 'Designer',
                    'url'                => $data['designer_url'],
                    'user_id'            => null
                ]);
            }
        } catch (Exception $e) {
            $this->error('Error creating character designer.');
        }
    }

    protected function createLog($senderId, $senderUrl, $recipientId, $recipientUrl, $characterId, $type, $data, $logType, $isUpdate = false, $oldData = null, $newData = null) {
        return DB::table($logType == 'character' ? 'character_log' : 'user_character_log')->insert(
            [
                'sender_id'     => $senderId,
                'sender_url'    => $senderUrl,
                'recipient_id'  => $recipientId,
                'recipient_url' => $recipientUrl,
                'character_id'  => $characterId,
                'log'           => $type.($data ? ' ('.$data.')' : ''),
                'log_type'      => $type,
                'data'          => $data,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ] + ($logType == 'character' ?
                [
                    'change_log' => $isUpdate ? json_encode([
                        'old' => $oldData,
                        'new' => $newData,
                    ]) : null,
                ] : [])
        );
    }

    /**********************************************************************************************

        DATA READING

    **********************************************************************************************/

    protected function getAdminUser(): User {
        try {
            $setting = DB::table('site_settings')->where('key', 'admin_user')->first();
            if (!$setting) {
                $this->error('Admin user setting not found.');
            }
            $userId = $setting->value;
            if (empty($userId)) {
                $this->error('Admin user ID is not set.');
            }
            $user = User::find($userId);
            if (!$user) {
                $this->error('Admin user not found.');
            }

            return $user;
        } catch (Exception $e) {
            $this->error('Error getting admin user.');
        }
    }

    protected function doesCharacterExist($slug): bool {
        try {
            $character = Character::where('slug', $slug)->first();

            return $character ? true : false;
        } catch (Exception $e) {
            $this->error('Error getting character ID.');
        }
    }

    protected function getUserIfExists($alias): User|false {
        try {
            $character = User::whereHas('aliases', function ($query) use ($alias) {
                $query->where('alias', $alias)->where('site', 'deviantart');
            })->first();

            return $character;
        } catch (Exception $e) {
            $this->error('Error getting user.');
        }
    }

    /**********************************************************************************************

        FILE PROCESSING

    **********************************************************************************************/

    protected function getCSV($file_name) {
        $filePath = base_path() . "/data/tsv/{$file_name}.tsv";

        if (!file_exists($filePath)) {
            $this->error("File does not exist: /data/tsv/{$file_name}.tsv");

            return false;
        }

        $file = fopen($filePath, 'r');
        $this->getRow($file);

        return $file;
    }

    protected function getRow($csv) {
        return fgetcsv($csv, 0, "\t");
    }

    protected function downloadImage($url): UploadedFile {
        $client = new Client([
            'verify' => false, // Disable SSL certificate verification
        ]);

        $response = $client->get($url);
        $tempDir = base_path('data/images/temp');
        $tempFilePath = $tempDir.'/'.uniqid('img_', true).'.'.$this->getFileExtension($response);

        file_put_contents($tempFilePath, $response->getBody()->getContents());
        $uploadedFile = new UploadedFile(
            $tempFilePath,
            basename($tempFilePath),
            $response->getHeaderLine('Content-Type'),
            null,
            true
        );

        return $uploadedFile;
    }

    protected function saveImage($image, $dir, $name) {
        if (!Storage::exists($dir)) {
            // Create the directory.
            if (!Storage::makeDirectory($dir)) {
                $this->error('Failed to create image directory.');
                return false;
            }
        }

        $content = file_get_contents($image);

        if (!Storage::put("$dir/$name", $content)) {
            $this->error('Failed to save image.');
            return false;
        }        
        return true;
    }

    protected function cropThumbnail($characterImage) {
        try {
            $this->info($characterImage->imageUrl);
            $content = file_get_contents($characterImage->imageUrl);
            $image = Image::make($content);

            $canvas = Image::canvas($image->width(), $image->width());
            $image = $canvas->insert($image, 'center');

            $image->resize(config('lorekeeper.settings.masterlist_thumbnails.width'), config('lorekeeper.settings.masterlist_thumbnails.height'));
            $image->encode(config('lorekeeper.settings.masterlist_image_format'), 100);

            Storage::put("$characterImage->imageDirectory/$characterImage->thumbnailFileName", $image);
        } catch (\Exception $e) {
            $this->error('Failed to create thumbnail.');
        }
    }

    /**********************************************************************************************

        PARSING

    **********************************************************************************************/

    protected function getCharacterData($row, $categories, $rarities, $species, $subtypes, $features): array {
        $id = $row[0];
        $name = $row[1];
        $url = $row[2];
        $image = $row[3];
        $info = $row[4];
        $info_html = $row[5];

        $data = [];
        $data['name'] = $name;
        $data['number'] = $id;
        $data['description'] = $info_html;

        $uploadedFile = $this->downloadImage($image);
        $data['image'] = $uploadedFile;
        $data['image_description'] = $url;

        return $data;
    }

    protected function getSlug($number, $categoryCode): string {
        $paddedNum = str_pad($number, 3, '0', STR_PAD_LEFT);

        return "{$categoryCode}-{$paddedNum}";
    }

    protected function getOwner($text): User|string|null {
        if (preg_match(Pattern::OWNER->value, $text, $matches)) {
            $alias = $matches[1];
            $user = $this->getUserIfExists($alias);
            if ($user) {
                return $user;
            } else {
                return "https://www.deviantart.com/{$alias}";
            }
        } else {
            return null;
        }
    }

    protected function getDesigner($text): User|string|null {
        if (preg_match(Pattern::DESIGNER->value, $text, $matches)) {
            $alias = $matches[1];
            $user = $this->getUserIfExists($alias);
            if ($user) {
                return $user;
            } else {
                return "https://www.deviantart.com/{$alias}";
            }
        } else {
            return null;
        }
    }

    protected function getCharacterCategory($text, $models): CharacterCategory|false {
        foreach (CharacterCategoryEnum::cases() as $categoryEnum) {
            $model = $models[$categoryEnum->value];
            $pattern = $categoryEnum->getPattern();
            if ($pattern && preg_match($pattern->value, $text, $matches)) {
                return $model;
            }
        }

        return $models[Defaults::CATEGORY->value];
    }

    protected function getRarity($text, $models): Rarity|false {
        foreach (RarityEnum::cases() as $rarityEnum) {
            $model = $models[$rarityEnum->value];
            $pattern = $rarityEnum->getPattern();
            if ($pattern && preg_match($pattern->value, $text, $matches)) {
                return $model;
            }
        }

        return $models[Defaults::RARITY->value];
    }

    protected function getSpecies($text, $models): Species|false {
        foreach (SpeciesEnum::cases() as $speciesEnum) {
            $model = $models[$speciesEnum->value];
            $pattern = $speciesEnum->getPattern();
            if ($pattern && preg_match($pattern->value, $text, $matches)) {
                return $model;
            }
        }

        return $models[Defaults::SPECIES->value];
    }

    protected function getSubtype($text, $models): Subtype|false {
        foreach (SubtypeEnum::cases() as $subtypeEnum) {
            $model = $models[$subtypeEnum->value];
            $pattern = $subtypeEnum->getPattern();
            if ($pattern && preg_match($pattern->value, $text, $matches)) {
                return $model;
            }
        }

        return $models[Defaults::SUBTYPE->value];
    }

    protected function getFeatures($text, $models): array {
        $features = [];
        foreach (FeatureEnum::cases() as $featureEnum) {
            $model = $models[$featureEnum->value];
            $pattern = $featureEnum->getPattern();
            if ($pattern && preg_match($pattern->value, $text, $matches)) {
                $features[] = $model;
            }
        }

        return $features;
    }

    private function getFileExtension($response): string {
        // Retrieve MIME type from the response headers
        $mimeType = $response->getHeaderLine('Content-Type');

        // Map MIME types to file extensions
        $mimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            // Add other MIME types as needed
        ];

        // Default to 'jpg' if MIME type is not recognized
        return $mimeTypes[$mimeType] ?? 'jpg';
    }
}
