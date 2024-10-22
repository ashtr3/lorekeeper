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
use App\Models\Feature\Feature;
use App\Models\Feature\FeatureCategory;
use App\Models\Rarity;
use App\Models\Species\Species;
use App\Models\Species\Subtype;
use App\Models\User\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ImportCharacters extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:characters';

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

        $csv = $this->getCSV('data/tsv/import-master.tsv');
        while (($row = $this->getRow($csv)) !== false) {
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
            foreach (CharacterCategoryEnum::cases() as $categoryCase) {
                $values = $categoryCase->getValues();
                $category = CharacterCategory::where('name', $values['name'])->where('code', $values['code'])->first();
                if (!$category) {
                    $category = CharacterCategory::create([
                        'code' => $values['code'],
                        'name' => $values['name'],
                    ]);
                } else {
                    $this->line('Character category already exists: '.$values['name']);
                }
                $categories[$categoryCase->value] = $category;
            }

            return $categories;
        } catch (Exception $e) {
            $this->error('Error creating character categories.');
        }
    }

    protected function createRarities(): array {
        try {
            $rarities = [];
            foreach (RarityEnum::cases() as $rarityCase) {
                $values = $rarityCase->getValues();
                $rarity = Rarity::where('name', $values['name'])->first();
                if (!$rarity) {
                    $rarity = Rarity::create([
                        'name' => $values['name'],
                    ]);
                } else {
                    $this->line('Rarity already exists: '.$values['name']);
                }
                $rarities[$rarityCase->value] = $rarity;
            }

            return $rarities;
        } catch (Exception $e) {
            $this->error('Error creating rarities.');
        }
    }

    protected function createSpecies(): array {
        try {
            $specieses = [];
            foreach (SpeciesEnum::cases() as $speciesCase) {
                $values = $speciesCase->getValues();
                $species = Species::where('name', $values['name'])->first();
                if (!$species) {
                    $species = Species::create([
                        'name' => $values['name'],
                    ]);
                } else {
                    $this->line('Species already exists: '.$values['name']);
                }
                $specieses[$speciesCase->value] = $species;
            }

            return $specieses;
        } catch (Exception $e) {
            $this->error('Error creating species.');
        }
    }

    protected function createSubtypes($specieses): array {
        try {
            $subtypes = [];
            foreach (SubtypeEnum::cases() as $subtypeCase) {
                $values = $subtypeCase->getValues();
                $species = $specieses[$subtypeCase->getSpecies()->value];
                $subtype = Subtype::where('species_id', $species->id)->where('name', $values['name'])->first();
                if (!$subtype) {
                    $subtype = Subtype::create([
                        'species_id' => $species->id,
                        'name'       => $values['name'],
                    ]);
                } else {
                    $this->line('Subtype already exists: '.$values['name']);
                }
                $subtypes[$subtypeCase->value] = $subtype;
            }

            return $subtypes;
        } catch (Exception $e) {
            $this->error('Error creating subtypes.');
        }
    }

    protected function createFeatureCategories(): array {
        try {
            $featureCategories = [];
            foreach (FeatureCategoryEnum::cases() as $featureCategoryCase) {
                $values = $featureCategoryCase->getValues();
                $featureCategory = FeatureCategory::where('name', $values['name'])->first();
                if (!$featureCategory) {
                    $featureCategory = FeatureCategory::create([
                        'name' => $values['name'],
                    ]);
                } else {
                    $this->line('Feature category already exists: '.$values['name']);
                }
                $featureCategories[$featureCategoryCase->value] = $featureCategory;
            }

            return $featureCategories;
        } catch (Exception $e) {
            $this->error('Error creating feature categories.');
        }
    }

    protected function createFeatures($featureCategories, $rarities): array {
        try {
            $features = [];
            foreach (FeatureEnum::cases() as $featureCase) {
                $values = $featureCase->getValues();
                $rarity = $rarities[$featureCase->getRarity()->value];

                if ($featureCase->getCategory() != null) {
                    $category = $featureCategories[$featureCase->getCategory()->value];
                    $feature = Feature::where('feature_category_id', $category->id)->where('rarity_id', $rarity->id)->where('name', $values['name'])->first();
                } else {
                    $feature = Feature::where('rarity_id', $rarity->id)->where('name', $values['name'])->first();
                }

                if (!$feature) {
                    $feature = Feature::create([
                        'feature_category_id' => $category != null ? $category->id : null,
                        'rarity_id'           => $rarity->id,
                        'name'                => $values['name'],
                    ]);
                } else {
                    $this->line('Feature already exists: '.$values['name']);
                }
                $features[$featureCase->value] = $feature;
            }

            return $features;
        } catch (Exception $e) {
            $this->error('Error creating features.');
        }
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
            $character = User::whereHas('aliases', function ($query) {
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

    protected function getCSV($path): array|false {
        $filePath = base_path($path);

        if (!file_exists($filePath)) {
            $this->error("File does not exist: {$path}");

            return false;
        }

        $file = fopen($filePath, 'r');
        $this->getRow($file);

        return $file;
    }

    protected function getRow($csv): array|false {
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

    protected function saveImage($image, $dir, $name, $copy = false) {
        $fullDir = public_path($dir);
        if (!file_exists($fullDir)) {
            if (!mkdir($fullDir, 0755, true)) {
                $this->error('Failed to create image directory.');

                return false;
            }
            chmod($fullDir, 0755);
        }
        if ($copy) {
            File::copy($image, $fullDir.'/'.$name);
        } else {
            File::move($image, $fullDir.'/'.$name);
        }
        chmod($fullDir.'/'.$name, 0755);

        return true;
    }

    protected function cropThumbnail($characterImage) {
        try {
            $fullPath = $characterImage->imagePath.'/'.$characterImage->imageFileName;

            $this->info($fullPath);
            $image = Image::make($fullPath);

            $canvas = Image::canvas($image->width(), $image->width());
            $image = $canvas->insert($image, 'center');

            $image->resize(config('lorekeeper.settings.masterlist_thumbnails.width'), config('lorekeeper.settings.masterlist_thumbnails.height'));

            $thumbPath = $characterImage->thumbnailPath.'/'.$characterImage->thumbnailFileName;
            $image->save($thumbPath, 100, config('lorekeeper.settings.masterlist_image_format'));
        } catch (Exception $e) {
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
        $comments = $row[6];
        $comments_html = $row[7];

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
