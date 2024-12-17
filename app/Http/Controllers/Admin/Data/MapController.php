<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use App\Models\Character\CharacterMap;
use App\Models\Character\CharacterMapCategory;
use App\Models\Feature\FeatureLocus;
use App\Services\MapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MapController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin / Map Controller
    |--------------------------------------------------------------------------
    |
    | Handles creation/editing of character map categories and maps.
    |
    */

    /**********************************************************************************************

        CHARACTER MAP CATEGORIES

    **********************************************************************************************/

    /**
     * Shows the character map category index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getMapCategoryIndex() {
        return view('admin.maps.map_categories', [
            'categories' => CharacterMapCategory::orderBy('sort', 'DESC')->get(),
        ]);
    }

    /**
     * Shows the create character map category page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateMapCategory() {
        return view('admin.maps._create_edit_map_category', [
            'category' => new CharacterMapCategory,
        ]);
    }

    /**
     * Shows the edit character map category page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditMapCategory($id) {
        $category = CharacterMapCategory::find($id);
        if (!$category) {
            abort(404);
        }

        return view('admin.maps._create_edit_map_category', [
            'category' => $category,
        ]);
    }

    /**
     * Creates or edits a character map category.
     *
     * @param App\Services\MapService $service
     * @param int|null                $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditMapCategory(Request $request, MapService $service, $id = null) {
        $id ? $request->validate(CharacterMapCategory::$updateRules) : $request->validate(CharacterMapCategory::$createRules);
        $data = $request->only(['name']);

        if ($id && $service->updateMapCategory(CharacterMapCategory::find($id), $data, Auth::user())) {
            flash('Category updated successfully.')->success();
        } elseif (!$id && $service->createMapCategory($data, Auth::user())) {
            flash('Category created successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Gets the character map category deletion modal.
     *
     * @param int|null $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDeleteMapCategory($id) {
        $category = CharacterMapCategory::find($id);

        return view('admin.maps._delete_map_category', [
            'category' => $category,
        ]);
    }

    /**
     * Deletes a character map category.
     *
     * @param App\Services\MapService $service
     * @param int                     $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteMapCategory(Request $request, MapService $service, $id) {
        if ($id && $service->deleteMapCategory(CharacterMapCategory::find($id), Auth::user())) {
            flash('Category deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->to('admin/data/map-categories');
    }

    /**
     * Sorts character map categories.
     *
     * @param App\Services\MapService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSortMapCategory(Request $request, MapService $service) {
        if ($service->sortMapCategory($request->get('sort'))) {
            flash('Category order updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**********************************************************************************************

        CHARACTER MAPS

    **********************************************************************************************/

    /**
     * Shows the character map index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getMapIndex() {
        return view('admin.maps.maps', [
            'maps' => CharacterMap::orderBy('sort', 'DESC')->get(),
        ]);
    }

    /**
     * Shows the create character map page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateMap() {
        return view('admin.maps.create_edit_map', [
            'map'        => new CharacterMap,
            'categories' => ['0' => 'Select Category'] + CharacterMapCategory::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
            'loci'       => ['0' => 'Select Locus'] + FeatureLocus::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Shows the edit character map page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditMap($id) {
        $map = CharacterMap::find($id);
        if (!$map) {
            abort(404);
        }

        return view('admin.maps.create_edit_map', [
            'map'        => $map,
            'categories' => ['0' => 'Select Category'] + CharacterMapCategory::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
            'loci'       => ['0' => 'Select Locus'] + FeatureLocus::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Creates or edits a character map.
     *
     * @param App\Services\MapService $service
     * @param int|null                $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditMap(Request $request, MapService $service, $id = null) {
        $id ? $request->validate(CharacterMap::$updateRules) : $request->validate(CharacterMap::$createRules);
        $data = $request->only(['category_id', 'name', 'conversions', 'genetics']);

        if ($id && $service->updateMap(CharacterMap::find($id), $data, Auth::user())) {
            flash('Map updated successfully.')->success();
        } elseif (!$id && $map = $service->createMap($data, Auth::user())) {
            flash('Map created successfully.')->success();
            return redirect()->to('admin/data/maps/edit/'.$map->id);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Gets the character map deletion modal.
     *
     * @param int|null $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDeleteMap($id) {
        $map = CharacterMap::find($id);

        return view('admin.maps._delete_map', [
            'map' => $map,
        ]);
    }

    /**
     * Deletes a character map.
     *
     * @param App\Services\MapService $service
     * @param int                     $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteMap(Request $request, MapService $service, $id) {
        if ($id && $service->deleteMap(CharacterMap::find($id), Auth::user())) {
            flash('Map deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->to('admin/data/maps');
    }

    /**
     * Sorts character maps.
     *
     * @param App\Services\MapService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSortMap(Request $request, MapService $service) {
        if ($service->sortMap($request->get('sort'))) {
            flash('Map order updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }
}
