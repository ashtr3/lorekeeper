<?php

namespace App\Services;

use App\Models\Character\CharacterMap;
use App\Models\Character\CharacterMapCategory;
use Exception;
use Illuminate\Support\Facades\DB;

class MapService extends Service {
    /*
    |--------------------------------------------------------------------------
    | Map Service
    |--------------------------------------------------------------------------
    |
    | Handles the creation and editing of map categories and maps.
    |
    */

    /**********************************************************************************************

        CHARACTER MAP CATEGORIES

    **********************************************************************************************/

    /**
     * Create a character map category.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return \App\Models\Character\CharacterMapCategory
     */
    public function createMapCategory($data, $user) {
        DB::beginTransaction();

        try {
            $category = CharacterMapCategory::create($data);

            if (!$this->logAdminAction($user, 'Created Character Map Category', "Created {$category->displayName} category")) {
                throw new Exception('Failed to log admin action.');
            }

            return $this->commitReturn($category);
        } catch (Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Update a character map category.
     *
     * @param \App\Models\Character\CharacterMapCategory $category
     * @param array                                      $data
     * @param \App\Models\User\User                      $user
     *
     * @return \App\Models\Character\CharacterMapCategory
     */
    public function updateMapCategory($category, $data, $user) {
        DB::beginTransaction();

        try {
            // More specific validation
            if (CharacterMapCategory::where('name', $data['name'])->where('id', '!=', $category->id)->exists()) {
                throw new Exception('The name has already been taken.');
            }

            $category->update($data);

            if (!$this->logAdminAction($user, 'Updated Character Map Category', "Updated {$category->displayName} category")) {
                throw new Exception('Failed to log admin action.');
            }

            return $this->commitReturn($category);
        } catch (Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Delete a character map category.
     *
     * @param \App\Models\Character\CharacterMapCategory $category
     * @param \App\Models\User\User                      $user
     *
     * @return bool
     */
    public function deleteMapCategory($category, $user) {
        DB::beginTransaction();

        try {
            // Check first if the category is currently in use
            if (CharacterMap::where('category_id', $category->id)->exists()) {
                throw new Exception('A map with this category exists. Please change its category first.');
            }

            if (!$this->logAdminAction($user, 'Delete Character Map Category', "Deleted {$category->name} category")) {
                throw new Exception('Failed to log admin action.');
            }

            $category->delete();

            return $this->commitReturn(true);
        } catch (Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Sorts character map category order.
     *
     * @param string $data
     *
     * @return bool
     */
    public function sortMapCategory($data) {
        DB::beginTransaction();

        try {
            // explode the sort array and reverse it since the order is inverted
            $sort = array_reverse(explode(',', $data));

            foreach ($sort as $key => $s) {
                CharacterMapCategory::where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
        } catch (Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**********************************************************************************************

        CHARACTER MAPS

    **********************************************************************************************/

    /**
     * Create a character map.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return \App\Models\Character\CharacterMap
     */
    public function createMap($data, $user) {
        DB::beginTransaction();

        try {
            if (CharacterMap::where('name', $data['name'])->exists()) {
                throw new Exception('The name has already been taken.');
            }
            if ((isset($data['category_id']) && $data['category_id']) && !CharacterMapCategory::where('id', $data['category_id'])->exists()) {
                throw new Exception('The selected character map category is invalid.');
            }

            $data = $this->prepareMapData($data);

            $map = CharacterMap::create([
                'category_id' => $data['category_id'],
                'name'        => $data['name'],
                'conversions' => $data['conversions'],
            ]);

            $this->updateMapGenetics($data, $map);

            if (!$this->logAdminAction($user, 'Created Character Map', "Created {$map->displayName} map")) {
                throw new Exception('Failed to log admin action.');
            }

            return $this->commitReturn($map);
        } catch (Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Update a character map.
     *
     * @param \App\Models\Character\CharacterMap $map
     * @param array                              $data
     * @param \App\Models\User\User              $user
     *
     * @return \App\Models\Character\CharacterMap
     */
    public function updateMap($map, $data, $user) {
        DB::beginTransaction();

        try {
            if (CharacterMap::where('name', $data['name'])->where('id', '!=', $map->id)->exists()) {
                throw new Exception('The name has already been taken.');
            }
            if ((isset($data['category_id']) && $data['category_id']) && !CharacterMapCategory::where('id', $data['category_id'])->exists()) {
                throw new Exception('The selected character map category is invalid.');
            }

            $data = $this->prepareMapData($data);

            $map->update([
                'category_id' => $data['category_id'],
                'name'        => $data['name'],
                'conversions' => $data['conversions'],
            ]);

            $this->updateMapGenetics($data, $map);

            if (!$this->logAdminAction($user, 'Updated Character Map', "Updated {$map->displayName} map")) {
                throw new Exception('Failed to log admin action.');
            }

            return $this->commitReturn($map);
        } catch (Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Delete a character map.
     *
     * @param \App\Models\Character\CharacterMap $map
     * @param \App\Models\User\User              $user
     *
     * @return bool
     */
    public function deleteMap($map, $user) {
        DB::beginTransaction();

        try {
            if (!$this->logAdminAction($user, 'Deleted Character Map', "Deleted #{$map->id} (".$map->category->displayName.') map')) {
                throw new Exception('Failed to log admin action.');
            }

            $map->genetics()->delete();
            $map->delete();

            return $this->commitReturn(true);
        } catch (Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Sorts character map order.
     *
     * @param string $data
     *
     * @return bool
     */
    public function sortMap($data) {
        DB::beginTransaction();

        try {
            // explode the sort array and reverse it since the order is inverted
            $sort = array_reverse(explode(',', $data));

            foreach ($sort as $key => $s) {
                CharacterMap::where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
        } catch (Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**********************************************************************************************

        CHARACTER MAP GENETICS

    **********************************************************************************************/

    /**
     * Update character map genetics.
     *
     * @param array                              $data
     * @param \App\Models\Character\CharacterMap $map
     *
     * @return bool
     */
    public function updateMapGenetics($data, $map) {
        try {
            $map->genetics()->delete();
            if ($this->hasDuplicateLoci($data)) {
                throw new Exception('Cannot have multiple rows for the same alleles.');
            }
            foreach ($data['genetics'] as $gene) {
                if (is_null($gene['locus_id'])) {
                    continue;
                }
                $map->genetics()->create([
                    'locus_id'            => $gene['locus_id'],
                    'primary_allele_id'   => $gene['primary_allele_id'],
                    'secondary_allele_id' => $gene['secondary_allele_id'],
                ]);
            }
        } catch (Exception $e) {
            $this->setError('error', $e->getMessage());
        }
    }

    /**
     * Checks if data contains duplicate loci.
     *
     * @param array $data
     *
     * @return bool
     */
    private function hasDuplicateLoci($data) {
        $alleles = array_column($data['genetics'], 'locus_id');

        return count($alleles) !== count(array_unique($alleles));
    }

    private function prepareMapData($data) {
        // Filter out null and 0 values from each subarray in 'conversions'
        $data['conversions'] = array_map(function ($subArray) {
            // Filter each subarray by removing null and 0 values
            $subArray = array_filter($subArray, function ($value) {
                return $value !== null && $value !== 0;
            });
            return array_values($subArray);
        }, $data['conversions']);

        // Remove empty subarrays from 'conversions' and reindex the array
        $data['conversions'] = array_filter($data['conversions'], function ($subArray) {
            // Ensure that the subarray is not empty after filtering
            return !empty($subArray);
        });

        // Re-index the array so keys start from 0 and there are no gaps
        $data['conversions'] = array_values($data['conversions']);

        foreach ($data['genetics'] as $key => $gene) {
            if (!isset($gene['locus_id']) || $gene['locus_id'] == '0') {
                unset($data['genetics'][$key]);
            } else {
                if (!isset($gene['primary_allele_id']) || $gene['primary_allele_id'] == '0') {
                    $gene['primary_allele_id'] = null;
                }
                if (!isset($gene['secondary_allele_id']) || $gene['secondary_allele_id'] == '0') {
                    $gene['secondary_allele_id'] = null;
                }
            }
        }

        return $data;
    }
}
