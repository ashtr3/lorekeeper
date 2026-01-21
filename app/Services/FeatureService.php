<?php

namespace App\Services;

use App\Models\Character\Character;
use App\Models\Character\CharacterFeature;
use App\Models\Feature\Feature;
use App\Models\Feature\FeatureAllele;
use App\Models\Feature\FeatureCategory;
use App\Models\Feature\FeatureLocus;
use App\Models\Feature\FeatureOverride;
use App\Models\Feature\FeatureGene;
use App\Models\Species\Species;
use App\Models\Species\Subtype;
use Illuminate\Support\Facades\DB;

class FeatureService extends Service {
    /*
    |--------------------------------------------------------------------------
    | Feature Service
    |--------------------------------------------------------------------------
    |
    | Handles the creation and editing of feature categories and features.
    |
    */

    /**********************************************************************************************

        FEATURE CATEGORIES

    **********************************************************************************************/

    /**
     * Create a category.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return \App\Models\Feature\FeatureCategory|bool
     */
    public function createFeatureCategory($data, $user) {
        DB::beginTransaction();

        try {
            $data = $this->populateCategoryData($data);

            $image = null;
            if (isset($data['image']) && $data['image']) {
                $data['has_image'] = 1;
                $data['hash'] = randomString(10);
                $image = $data['image'];
                unset($data['image']);
            } else {
                $data['has_image'] = 0;
            }

            $category = FeatureCategory::create($data);

            if (!$this->logAdminAction($user, 'Created Feature Category', 'Created '.$category->displayName)) {
                throw new \Exception('Failed to log admin action.');
            }

            if ($image) {
                $this->handleImage($image, $category->categoryImagePath, $category->categoryImageFileName);
            }

            return $this->commitReturn($category);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Update a category.
     *
     * @param \App\Models\Feature\FeatureCategory $category
     * @param array                               $data
     * @param \App\Models\User\User               $user
     *
     * @return \App\Models\Feature\FeatureCategory|bool
     */
    public function updateFeatureCategory($category, $data, $user) {
        DB::beginTransaction();

        try {
            // More specific validation
            if (FeatureCategory::where('name', $data['name'])->where('id', '!=', $category->id)->exists()) {
                throw new \Exception('The name has already been taken.');
            }

            $data = $this->populateCategoryData($data, $category);

            $image = null;
            if (isset($data['image']) && $data['image']) {
                $data['has_image'] = 1;
                $data['hash'] = randomString(10);
                $image = $data['image'];
                unset($data['image']);
            }

            $category->update($data);

            if (!$this->logAdminAction($user, 'Updated Feature Category', 'Updated '.$category->displayName)) {
                throw new \Exception('Failed to log admin action.');
            }

            if (!$this->logAdminAction($user, 'Updated Feature Category', 'Updated '.$category->displayName)) {
                throw new \Exception('Failed to log admin action.');
            }

            if ($category) {
                $this->handleImage($image, $category->categoryImagePath, $category->categoryImageFileName);
            }

            return $this->commitReturn($category);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Delete a category.
     *
     * @param \App\Models\Feature\FeatureCategory $category
     * @param mixed                               $user
     *
     * @return bool
     */
    public function deleteFeatureCategory($category, $user) {
        DB::beginTransaction();

        try {
            // Check first if the category is currently in use
            if (Feature::where('feature_category_id', $category->id)->exists()) {
                throw new \Exception('A trait with this category exists. Please change its category first.');
            }

            if (!$this->logAdminAction($user, 'Deleted Feature Category', 'Deleted '.$category->name)) {
                throw new \Exception('Failed to log admin action.');
            }

            if ($category->has_image) {
                $this->deleteImage($category->categoryImagePath, $category->categoryImageFileName);
            }
            $category->delete();

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Sorts category order.
     *
     * @param array $data
     *
     * @return bool
     */
    public function sortFeatureCategory($data) {
        DB::beginTransaction();

        try {
            // explode the sort array and reverse it since the order is inverted
            $sort = array_reverse(explode(',', $data));

            foreach ($sort as $key => $s) {
                FeatureCategory::where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**********************************************************************************************

        FEATURE LOCI

    **********************************************************************************************/

    /**
     * Create a locus.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return \App\Models\Feature\FeatureLocus|bool
     */
    public function createFeatureLocus($data, $user) {
        DB::beginTransaction();

        try {
            $data = $this->populateLocusData($data);

            $locus = FeatureLocus::create($data);

            if (!$this->logAdminAction($user, 'Created Feature Locus', 'Created '.$locus->displayName)) {
                throw new \Exception('Failed to log admin action.');
            }

            return $this->commitReturn($locus);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Update a locus.
     *
     * @param \App\Models\Feature\FeatureLocus $locus
     * @param array                            $data
     * @param \App\Models\User\User            $user
     *
     * @return \App\Models\Feature\FeatureLocus|bool
     */
    public function updateFeatureLocus($locus, $data, $user) {
        DB::beginTransaction();

        try {
            // More specific validation
            if (FeatureLocus::where('name', $data['name'])->where('id', '!=', $locus->id)->exists()) {
                throw new \Exception('The name has already been taken.');
            }

            $data = $this->populateLocusData($data);

            $locus->update($data);

            if (!$this->logAdminAction($user, 'Updated Feature Locus', 'Updated '.$locus->displayName)) {
                throw new \Exception('Failed to log admin action.');
            }

            return $this->commitReturn($locus);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Delete a locus.
     *
     * @param \App\Models\Feature\FeatureLocus $locus
     * @param mixed                            $user
     *
     * @return bool
     */
    public function deleteFeatureLocus($locus, $user) {
        DB::beginTransaction();

        try {
            $locusId = $locus->id;

            if (FeatureGene::whereHas('allele', function($query) use ($locusId) {
                $query->where('feature_locus_id', $locusId);
            })->exists()) {
                throw new \Exception('A trait with this locus exists. Please update the affected traits first.');
            }

            if (FeatureAllele::where('feature_locus_id', $locusId)->exists()) {
                throw new \Exception('An allele with this locus exists. Please change its locus first.');
            }

            if (!$this->logAdminAction($user, 'Deleted Feature Locus', 'Deleted '.$locus->name)) {
                throw new \Exception('Failed to log admin action.');
            }

            $locus->delete();

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Sorts category order.
     *
     * @param array $data
     *
     * @return bool
     */
    public function sortFeatureLocus($data) {
        DB::beginTransaction();

        try {
            // explode the sort array and reverse it since the order is inverted
            $sort = array_reverse(explode(',', $data));

            foreach ($sort as $key => $s) {
                FeatureLocus::where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**********************************************************************************************

        FEATURE ALLELES

    **********************************************************************************************/

    /**
     * Create an allele.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return \App\Models\Feature\FeatureAllele|bool
     */
    public function createFeatureAllele($data, $user) {
        DB::beginTransaction();

        try {
            $data = $this->populateAlleleData($data);

            $allele = FeatureAllele::create($data);

            if (!$this->logAdminAction($user, 'Created Feature Allele', 'Created '.$allele->displayName)) {
                throw new \Exception('Failed to log admin action.');
            }

            return $this->commitReturn($allele);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Update an allele.
     *
     * @param \App\Models\Feature\FeatureAllele $allele
     * @param array                             $data
     * @param \App\Models\User\User             $user
     *
     * @return \App\Models\Feature\FeatureAllele|bool
     */
    public function updateFeatureAllele($allele, $data, $user) {
        DB::beginTransaction();

        try {
            // More specific validation
            if (FeatureAllele::where('allele', $data['allele'])->where('id', '!=', $allele->id)->exists()) {
                throw new \Exception('The allele has already been taken.');
            }

            $data = $this->populateAlleleData($data);

            $allele->update($data);

            if (!$this->logAdminAction($user, 'Updated Feature Allele', 'Updated '.$allele->displayName)) {
                throw new \Exception('Failed to log admin action.');
            }

            return $this->commitReturn($allele);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Delete an allele.
     *
     * @param \App\Models\Feature\FeatureAllele $allele
     * @param mixed                             $user
     *
     * @return bool
     */
    public function deleteFeatureAllele($allele, $user) {
        DB::beginTransaction();

        try {
            if (FeatureGene::where('feature_allele_id', $allele->id)->exists()) {
                throw new \Exception('A trait with this allele exists. Please update the affected traits first.');
            }

            if (!$this->logAdminAction($user, 'Deleted Feature Allele', 'Deleted '.$allele->allele)) {
                throw new \Exception('Failed to log admin action.');
            }

            $allele->delete();

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Sorts allele order.
     *
     * @param array $data
     *
     * @return bool
     */
    public function sortFeatureAllele($data) {
        DB::beginTransaction();

        try {
            // explode the sort array and reverse it since the order is inverted
            $sort = array_reverse(explode(',', $data));

            foreach ($sort as $key => $s) {
                FeatureAllele::where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**********************************************************************************************

        FEATURES

    **********************************************************************************************/

    /**
     * Creates a new feature.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return \App\Models\Feature\Feature|bool
     */
    public function createFeature($data, $user) {
        DB::beginTransaction();

        try {
            if (isset($data['feature_category_id']) && $data['feature_category_id'] == 'none') {
                $data['feature_category_id'] = null;
            }
            if (isset($data['species_id']) && $data['species_id'] == 'none') {
                $data['species_id'] = null;
            }
            if (isset($data['subtype_id']) && $data['subtype_id'] == 'none') {
                $data['subtype_id'] = null;
            }

            if ((isset($data['feature_category_id']) && $data['feature_category_id']) && !FeatureCategory::where('id', $data['feature_category_id'])->exists()) {
                throw new \Exception('The selected trait category is invalid.');
            }
            if ((isset($data['species_id']) && $data['species_id']) && !Species::where('id', $data['species_id'])->exists()) {
                throw new \Exception('The selected species is invalid.');
            }
            if (isset($data['subtype_id']) && $data['subtype_id']) {
                $subtype = Subtype::find($data['subtype_id']);
                if (!(isset($data['species_id']) && $data['species_id'])) {
                    throw new \Exception('Species must be selected to select a subtype.');
                }
                if (!$subtype || $subtype->species_id != $data['species_id']) {
                    throw new \Exception('Selected subtype invalid or does not match species.');
                }
            }

            $data = $this->populateData($data);

            $image = null;
            if (isset($data['image']) && $data['image']) {
                $data['has_image'] = 1;
                $data['hash'] = randomString(10);
                $image = $data['image'];
                unset($data['image']);
            } else {
                $data['has_image'] = 0;
            }

            $feature = Feature::create($data);

            $this->updateFeatureOverrides($data, $feature);

            if ($feature->is_genetic) {
                $this->updateFeatureGenetics($data, $feature, $user);
                $this->updateFeatureOnCharacters($feature);
            }

            if (!$this->logAdminAction($user, 'Created Feature', 'Created '.$feature->displayName)) {
                throw new \Exception('Failed to log admin action.');
            }

            if ($image) {
                $this->handleImage($image, $feature->imagePath, $feature->imageFileName);
            }

            return $this->commitReturn($feature);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Updates a feature.
     *
     * @param \App\Models\Feature\Feature $feature
     * @param array                       $data
     * @param \App\Models\User\User       $user
     *
     * @return \App\Models\Feature\Feature|bool
     */
    public function updateFeature($feature, $data, $user) {
        DB::beginTransaction();

        try {
            if (isset($data['feature_category_id']) && $data['feature_category_id'] == 'none') {
                $data['feature_category_id'] = null;
            }
            if (isset($data['species_id']) && $data['species_id'] == 'none') {
                $data['species_id'] = null;
            }
            if (isset($data['subtype_id']) && $data['subtype_id'] == 'none') {
                $data['subtype_id'] = null;
            }

            // More specific validation
            if (Feature::where('name', $data['name'])->where('id', '!=', $feature->id)->exists()) {
                throw new \Exception('The name has already been taken.');
            }
            if ((isset($data['feature_category_id']) && $data['feature_category_id']) && !FeatureCategory::where('id', $data['feature_category_id'])->exists()) {
                throw new \Exception('The selected trait category is invalid.');
            }
            if ((isset($data['species_id']) && $data['species_id']) && !Species::where('id', $data['species_id'])->exists()) {
                throw new \Exception('The selected species is invalid.');
            }
            if (isset($data['subtype_id']) && $data['subtype_id']) {
                $subtype = Subtype::find($data['subtype_id']);
                if (!(isset($data['species_id']) && $data['species_id'])) {
                    throw new \Exception('Species must be selected to select a subtype.');
                }
                if (!$subtype || $subtype->species_id != $data['species_id']) {
                    throw new \Exception('Selected subtype invalid or does not match species.');
                }
            }

            $data = $this->populateData($data);

            $image = null;
            if (isset($data['image']) && $data['image']) {
                $data['has_image'] = 1;
                $data['hash'] = randomString(10);
                $image = $data['image'];
                unset($data['image']);
            }

            $feature->update($data);

            $this->updateFeatureOverrides($data, $feature);

            if ($feature->is_genetic) {
                $this->updateFeatureGenetics($data, $feature, $user);
                $this->updateFeatureOnCharacters($feature);
            } else {
                $feature->genetics()->delete();
            }

            if (!$this->logAdminAction($user, 'Updated Feature', 'Updated '.$feature->displayName)) {
                throw new \Exception('Failed to log admin action.');
            }

            if ($feature) {
                $this->handleImage($image, $feature->imagePath, $feature->imageFileName);
            }

            return $this->commitReturn($feature);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Deletes a feature.
     *
     * @param \App\Models\Feature\Feature $feature
     * @param mixed                       $user
     *
     * @return bool
     */
    public function deleteFeature($feature, $user) {
        DB::beginTransaction();

        try {
            // Check first if the feature is currently in use
            if (DB::table('character_features')->where('feature_id', $feature->id)->exists()) {
                throw new \Exception('A character with this trait exists. Please remove the trait first.');
            }

            if (!$this->logAdminAction($user, 'Deleted Feature', 'Deleted '.$feature->name)) {
                throw new \Exception('Failed to log admin action.');
            }

            if ($feature->has_image) {
                $this->deleteImage($feature->imagePath, $feature->imageFileName);
            }
            $feature->delete();

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    protected function updateFeatureOverrides($data, $feature) {
        DB::beginTransaction();

        try {
            FeatureOverride::where('override_id', $feature->id)->delete();
            foreach ($data['trait_overrides'] as $override) {
                if ($override == $feature->id) {
                    continue;
                }
                FeatureOverride::create([
                    'override_id' => $feature->id,
                    'hidden_id'   => $override,
                ]);
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    protected function updateFeatureGenetics($data, $feature) {
        try {
            $feature->genetics()->delete();
            if ($this->hasDuplicateRequirements($data)) {
                throw new \Exception('Cannot have multiple rows for the same alleles.');
            }
            foreach ($data['gene_requirements'] as $gene) {
                $gene = $this->populateGeneticRequirementData($gene);
                if (is_null($gene['locus_id'])) {
                    continue;
                }
                if (is_null($gene['allele_id'])) {
                    continue;
                }
                if ($gene['allow_homozygous'] === 0 && $gene['allow_heterozygous'] === 0 && $gene['allow_absent'] === 0) {
                    continue;
                }
                $feature->genetics()->create([
                    'feature_allele_id'  => $gene['allele_id'],
                    'allow_homozygous'   => $gene['allow_homozygous'],
                    'allow_heterozygous' => $gene['allow_heterozygous'],
                    'allow_absent'       => $gene['allow_absent'],
                ]);
            }
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
    }

    protected function updateFeatureOnCharacters($feature) {
        try {
            // Delete all instances of the feature
            CharacterFeature::where('feature_id', $feature->id)->delete();

            // Add feature to all characters meeting genetic requirements
            $characters = Character::hasGenotype()->with(['genetics', 'image.features'])->get();

            foreach ($characters as $character) {
                if ($character->canHaveGeneticFeature($feature)) {
                    CharacterFeature::create([
                        'character_image_id' => $character->image->id,
                        'feature_id'         => $feature->id,
                        'data'               => $feature->data,
                        'character_type'     => 'Character',
                    ]);
                }
                if ($character->isChimeric && $character->canHaveGeneticFeature($feature, true)) {
                    CharacterFeature::create([
                        'character_image_id' => $character->image->id,
                        'feature_id'         => $feature->id,
                        'data'               => $feature->data,
                        'character_type'     => 'Character',
                        'is_chimeric'        => 1,
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
    }

    private function hasDuplicateRequirements($data) {
        $alleles = array_column($data['gene_requirements'], 'allele_id');

        return count($alleles) !== count(array_unique($alleles));
    }

    /**
     * Handle category data.
     *
     * @param array                                    $data
     * @param \App\Models\Feature\FeatureCategory|null $category
     *
     * @return array
     */
    private function populateCategoryData($data, $category = null) {
        if (isset($data['description']) && $data['description']) {
            $data['parsed_description'] = parse($data['description']);
        }

        if (!isset($data['is_visible'])) {
            $data['is_visible'] = 0;
        }

        if (isset($data['remove_image'])) {
            if ($category && $category->has_image && $data['remove_image']) {
                $data['has_image'] = 0;
                $this->deleteImage($category->categoryImagePath, $category->categoryImageFileName);
            }
            unset($data['remove_image']);
        }

        return $data;
    }

    /**
     * Handle locus data.
     *
     * @param array $data
     *
     * @return array
     */
    private function populateLocusData($data) {
        if (isset($data['description']) && $data['description']) {
            $data['parsed_description'] = parse($data['description']);
        }

        if (!isset($data['default_allele_leads'])) {
            $data['default_allele_leads'] = 0;
        }

        if (!isset($data['restrict_chimeric'])) {
            $data['restrict_chimeric'] = 0;
        }

        if (!isset($data['is_visible'])) {
            $data['is_visible'] = 0;
        }

        return $data;
    }

    /**
     * Handle allele data.
     *
     * @param array $data
     *
     * @return array
     */
    private function populateAlleleData($data) {
        if (isset($data['description']) && $data['description']) {
            $data['parsed_description'] = parse($data['description']);
        }

        if (!isset($data['is_visible'])) {
            $data['is_visible'] = 0;
        }

        return $data;
    }

    /**
     * Handle genetic requirement data.
     *
     * @param array $data
     *
     * @return array
     */
    private function populateGeneticRequirementData($data) {
        if (!isset($data['allow_homozygous'])) {
            $data['allow_homozygous'] = 0;
        }
        if (!isset($data['allow_heterozygous'])) {
            $data['allow_heterozygous'] = 0;
        }
        if (!isset($data['allow_absent'])) {
            $data['allow_absent'] = 0;
        }

        return $data;
    }

    /**
     * Processes user input for creating/updating a feature.
     *
     * @param array                       $data
     * @param \App\Models\Feature\Feature $feature
     *
     * @return array
     */
    private function populateData($data, $feature = null) {
        if (isset($data['description']) && $data['description']) {
            $data['parsed_description'] = parse($data['description']);
        }
        if (isset($data['species_id']) && $data['species_id'] == 'none') {
            $data['species_id'] = null;
        }
        if (isset($data['feature_category_id']) && $data['feature_category_id'] == 'none') {
            $data['feature_category_id'] = null;
        }
        if (!isset($data['is_visible'])) {
            $data['is_visible'] = 0;
        }
        if (!isset($data['is_genetic'])) {
            $data['is_genetic'] = 0;
        }
        if (!isset($data['enables_chimerism'])) {
            $data['enables_chimerism'] = 0;
        }
        if (isset($data['remove_image'])) {
            if ($feature && $feature->has_image && $data['remove_image']) {
                $data['has_image'] = 0;
                $this->deleteImage($feature->imagePath, $feature->imageFileName);
            }
            unset($data['remove_image']);
        }

        return $data;
    }
}
