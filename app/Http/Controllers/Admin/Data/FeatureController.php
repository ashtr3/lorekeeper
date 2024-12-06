<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use App\Models\Feature\Feature;
use App\Models\Feature\FeatureAllele;
use App\Models\Feature\FeatureCategory;
use App\Models\Feature\FeatureGene;
use App\Models\Feature\FeatureLocus;
use App\Models\Rarity;
use App\Models\Species\Species;
use App\Models\Species\Subtype;
use App\Services\FeatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeatureController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Admin / Feature Controller
    |--------------------------------------------------------------------------
    |
    | Handles creation/editing of character feature categories and features
    | (AKA traits, which is a reserved keyword in PHP and thus can't be used).
    |
    */

    /**********************************************************************************************

        FEATURE CATEGORIES

    **********************************************************************************************/

    /**
     * Shows the feature category index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex() {
        return view('admin.features.feature_categories', [
            'categories' => FeatureCategory::orderBy('sort', 'DESC')->get(),
        ]);
    }

    /**
     * Shows the create feature category page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateFeatureCategory() {
        return view('admin.features.create_edit_feature_category', [
            'category' => new FeatureCategory,
        ]);
    }

    /**
     * Shows the edit feature category page.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditFeatureCategory($id) {
        $category = FeatureCategory::find($id);
        if (!$category) {
            abort(404);
        }

        return view('admin.features.create_edit_feature_category', [
            'category' => $category,
        ]);
    }

    /**
     * Creates or edits a feature category.
     *
     * @param App\Services\FeatureService $service
     * @param int|null                    $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditFeatureCategory(Request $request, FeatureService $service, $id = null) {
        $id ? $request->validate(FeatureCategory::$updateRules) : $request->validate(FeatureCategory::$createRules);
        $data = $request->only([
            'name', 'description', 'image', 'remove_image', 'is_visible',
        ]);
        if ($id && $service->updateFeatureCategory(FeatureCategory::find($id), $data, Auth::user())) {
            flash('Category updated successfully.')->success();
        } elseif (!$id && $category = $service->createFeatureCategory($data, Auth::user())) {
            flash('Category created successfully.')->success();

            return redirect()->to('admin/data/trait-categories/edit/'.$category->id);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Gets the feature category deletion modal.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteFeatureCategory($id) {
        $category = FeatureCategory::find($id);

        return view('admin.features._delete_feature_category', [
            'category' => $category,
        ]);
    }

    /**
     * Creates or edits a feature category.
     *
     * @param App\Services\FeatureService $service
     * @param int|null                    $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteFeatureCategory(Request $request, FeatureService $service, $id) {
        if ($id && $service->deleteFeatureCategory(FeatureCategory::find($id), Auth::user())) {
            flash('Category deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->to('admin/data/trait-categories');
    }

    /**
     * Sorts feature categories.
     *
     * @param App\Services\FeatureService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSortFeatureCategory(Request $request, FeatureService $service) {
        if ($service->sortFeatureCategory($request->get('sort'))) {
            flash('Category order updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**********************************************************************************************

        FEATURE LOCI

    **********************************************************************************************/

    /**
     * Shows the feature loci index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getFeatureLociIndex() {
        return view('admin.features.feature_loci', [
            'loci' => FeatureLocus::orderBy('sort', 'DESC')->get(),
        ]);
    }

    /**
     * Shows the create feature locus page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateFeatureLocus() {
        return view('admin.features.create_edit_feature_locus', [
            'locus' => new FeatureLocus,
        ]);
    }

    /**
     * Shows the edit feature locus page.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditFeatureLocus($id) {
        $locus = FeatureLocus::find($id);
        if (!$locus) {
            abort(404);
        }

        return view('admin.features.create_edit_feature_locus', [
            'locus' => $locus,
        ]);
    }

    /**
     * Creates or edits a feature locus.
     *
     * @param App\Services\FeatureService $service
     * @param int|null                    $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditFeatureLocus(Request $request, FeatureService $service, $id = null) {
        $id ? $request->validate(FeatureLocus::$updateRules) : $request->validate(FeatureLocus::$createRules);
        $data = $request->only([
            'name', 'default_allele', 'description', 'is_visible',
        ]);
        if ($id && $service->updateFeatureLocus(FeatureLocus::find($id), $data, Auth::user())) {
            flash('Locus updated successfully.')->success();
        } elseif (!$id && $locus = $service->createFeatureLocus($data, Auth::user())) {
            flash('Locus created successfully.')->success();

            return redirect()->to('admin/data/trait-loci/edit/'.$locus->id);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Gets the feature locus deletion modal.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteFeatureLocus($id) {
        $locus = FeatureLocus::find($id);

        return view('admin.features._delete_feature_locus', [
            'locus' => $locus,
        ]);
    }

    /**
     * Deletes a feature locus.
     *
     * @param App\Services\FeatureService $service
     * @param int|null                    $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteFeatureLocus(Request $request, FeatureService $service, $id) {
        if ($id && $service->deleteFeatureLocus(FeatureLocus::find($id), Auth::user())) {
            flash('Locus deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->to('admin/data/trait-loci');
    }

    /**
     * Sorts feature locus.
     *
     * @param App\Services\FeatureService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSortFeatureLocus(Request $request, FeatureService $service) {
        if ($service->sortFeatureLocus($request->get('sort'))) {
            flash('Locus order updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**********************************************************************************************

        FEATURE ALLELES

    **********************************************************************************************/

    /**
     * Shows the create feature allele modal.
     *
     * @param mixed $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateFeatureAllele($id) {
        $locus = FeatureLocus::find($id);
        if (!$locus) {
            abort(404);
        }

        return view('admin.features._create_edit_feature_allele', [
            'allele' => new FeatureAllele,
            'locus'  => $locus,
        ]);
    }

    /**
     * Shows the edit feature allele modal.
     *
     * @param int   $id
     * @param mixed $allele
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditFeatureAllele($id, $allele) {
        $locus = FeatureLocus::find($id);
        $allele = FeatureAllele::find($allele);
        if (!$locus || !$allele) {
            abort(404);
        }

        $allele->feature_locus_id = $locus->id;

        return view('admin.features._create_edit_feature_allele', [
            'allele' => $allele,
            'locus'  => $locus,
        ]);
    }

    /**
     * Creates or edits a feature allele.
     *
     * @param App\Services\FeatureService $service
     * @param int|null                    $id
     * @param mixed|null                  $allele
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditFeatureAllele(Request $request, FeatureService $service, $id, $allele = null) {
        $allele ? $request->validate(FeatureAllele::$updateRules) : $request->validate(FeatureAllele::$createRules);
        $data = $request->only([
            'feature_locus_id', 'allele', 'description', 'is_visible',
        ]);

        if ($allele && $service->updateFeatureAllele(FeatureAllele::find($allele), $data, Auth::user())) {
            flash('Allele updated successfully.')->success();
        } elseif (!$allele && $service->createFeatureAllele($data, Auth::user())) {
            flash('Allele created successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Gets the feature allele deletion modal.
     *
     * @param mixed $allele
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteFeatureAllele($allele) {
        $allele = FeatureAllele::find($allele);

        return view('admin.features._delete_feature_allele', [
            'allele' => $allele,
        ]);
    }

    /**
     * Deletes a feature allele.
     *
     * @param App\Services\FeatureService $service
     * @param mixed                       $allele
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteFeatureAllele(Request $request, FeatureService $service, $allele) {
        if ($allele && $service->deleteFeatureAllele(FeatureAllele::find($allele), Auth::user())) {
            flash('Allele deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Sorts feature alleles.
     *
     * @param App\Services\FeatureService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSortFeatureAllele(Request $request, FeatureService $service) {
        if ($service->sortFeatureAllele($request->get('sort'))) {
            flash('Allele order updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**********************************************************************************************

        FEATURE GENETICS

    **********************************************************************************************/

    /**
     * Shows the create feature genetics modal.
     *
     * @param mixed $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateFeatureGenetics($id) {
        $gene = new FeatureGene;
        $gene->feature_id = $id;

        return view('admin.features._create_edit_feature_genetics', [
            'gene' => $gene,
            'loci' => ['0' => 'Select Locus'] + FeatureLocus::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Shows the edit feature genetics modal.
     *
     * @param int   $id
     * @param mixed $allele
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditFeatureGenetics($id, $allele) {
        $gene = FeatureGene::where('feature_id', $id)
            ->where('feature_allele_id', $allele)
            ->with('allele.locus.alleles')
            ->first();
        if (!$gene) {
            abort(404);
        }

        return view('admin.features._create_edit_feature_genetics', [
            'gene'    => $gene,
            'loci'    => ['0' => 'Select Locus'] + FeatureLocus::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
        ]);
    }

    public function getLocusAlleles($id) {
        $alleles = FeatureAllele::where('feature_locus_id', $id)
            ->orderBy('sort', 'DESC')
            ->pluck('allele', 'id')
            ->toArray();

        return response()->json(['0' => 'Select Allele'] + $alleles);
    }

    /**
     * Creates or edits feature genetics.
     *
     * @param App\Services\FeatureService $service
     * @param int|null                    $id
     * @param mixed|null                  $allele
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditFeatureGenetics(Request $request, FeatureService $service, $id, $allele = null) {
        $allele ? $request->validate(FeatureGene::$updateRules) : $request->validate(FeatureGene::$createRules);
        $data = $request->only([
            'feature_id', 'feature_allele_id', 'allow_homozygous', 'allow_heterozygous', 'allow_absent',
        ]);

        if ($allele && $service->updateFeatureGene(FeatureGene::where('feature_id', $id)->where('feature_allele_id', $allele)->first(), $data, Auth::user())) {
            flash('Genetic requirement updated successfully.')->success();
        } elseif (!$allele && $service->createFeatureGene($data, Auth::user())) {
            flash('Genetic requirement created successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Gets the feature genetics deletion modal.
     *
     * @param int   $id
     * @param mixed $allele
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteFeatureGenetics($id, $allele) {
        $gene = FeatureGene::with('feature')->with('allele')->where('feature_id', $id)->where('feature_allele_id', $allele)->first();

        return view('admin.features._delete_feature_genetics', [
            'gene' => $gene,
        ]);
    }

    /**
     * Deletes feature genetics.
     *
     * @param App\Services\FeatureService $service
     * @param int|null                    $id
     * @param mixed                       $allele
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteFeatureGenetics(Request $request, FeatureService $service, $id, $allele) {
        $gene = FeatureGene::where('feature_id', $id)->where('feature_allele_id', $allele)->first();
        if ($gene && $service->deleteFeatureGene($gene, Auth::user())) {
            flash('Genetic requirement deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**********************************************************************************************

        FEATURES

    **********************************************************************************************/

    /**
     * Shows the feature index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getFeatureIndex(Request $request) {
        $query = Feature::query();
        $data = $request->only(['rarity_id', 'feature_category_id', 'species_id', 'subtype_id', 'name']);
        if (isset($data['rarity_id']) && $data['rarity_id'] != 'none') {
            $query->where('rarity_id', $data['rarity_id']);
        }
        if (isset($data['feature_category_id']) && $data['feature_category_id'] != 'none') {
            $query->where('feature_category_id', $data['feature_category_id']);
        }
        if (isset($data['species_id']) && $data['species_id'] != 'none') {
            $query->where('species_id', $data['species_id']);
        }
        if (isset($data['subtype_id']) && $data['subtype_id'] != 'none') {
            $query->where('subtype_id', $data['subtype_id']);
        }
        if (isset($data['name'])) {
            $query->where('name', 'LIKE', '%'.$data['name'].'%');
        }

        return view('admin.features.features', [
            'features'   => $query->paginate(20)->appends($request->query()),
            'rarities'   => ['none' => 'Any Rarity'] + Rarity::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
            'specieses'  => ['none' => 'Any Species'] + Species::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
            'subtypes'   => ['none' => 'Any Subtype'] + Subtype::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
            'categories' => ['none' => 'Any Category'] + FeatureCategory::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Shows the create feature page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateFeature() {
        return view('admin.features.create_edit_feature', [
            'feature'    => new Feature,
            'features'   => Feature::pluck('name', 'id')->toArray(),
            'loci'       => FeatureLocus::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
            'rarities'   => ['none' => 'Select a Rarity'] + Rarity::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
            'specieses'  => ['none' => 'No restriction'] + Species::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
            'subtypes'   => ['none' => 'No subtype'] + Subtype::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
            'categories' => ['none' => 'No category'] + FeatureCategory::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Shows the edit feature page.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditFeature($id) {
        $feature = Feature::find($id);
        if (!$feature) {
            abort(404);
        }

        return view('admin.features.create_edit_feature', [
            'feature'    => $feature,
            'features'   => Feature::pluck('name', 'id')->toArray(),
            'loci'       => FeatureLocus::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
            'rarities'   => ['none' => 'Select a Rarity'] + Rarity::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
            'specieses'  => ['none' => 'No restriction'] + Species::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
            'subtypes'   => ['none' => 'No subtype'] + Subtype::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
            'categories' => ['none' => 'No category'] + FeatureCategory::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Creates or edits a feature.
     *
     * @param App\Services\FeatureService $service
     * @param int|null                    $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditFeature(Request $request, FeatureService $service, $id = null) {
        $id ? $request->validate(Feature::$updateRules) : $request->validate(Feature::$createRules);
        $data = $request->only([
            'name', 'species_id', 'subtype_id', 'rarity_id', 'feature_category_id', 'description', 'image', 'remove_image', 'is_visible', 'is_genetic',
            'trait_overrides', 'gene_requirements',
        ]);
        if ($id && $service->updateFeature(Feature::find($id), $data, Auth::user())) {
            flash('Trait updated successfully.')->success();
        } elseif (!$id && $feature = $service->createFeature($data, Auth::user())) {
            flash('Trait created successfully.')->success();

            return redirect()->to('admin/data/traits/edit/'.$feature->id);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Gets the feature deletion modal.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteFeature($id) {
        $feature = Feature::find($id);

        return view('admin.features._delete_feature', [
            'feature' => $feature,
        ]);
    }

    /**
     * Deletes a feature.
     *
     * @param App\Services\FeatureService $service
     * @param int                         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteFeature(Request $request, FeatureService $service, $id) {
        if ($id && $service->deleteFeature(Feature::find($id), Auth::user())) {
            flash('Trait deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->to('admin/data/traits');
    }

    /**
     * Shows the edit subtype portion of the modal.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateEditFeatureSubtype(Request $request) {
        $species = $request->input('species');
        $subtype_id = $request->input('subtype_id');

        return view('admin.features._create_edit_feature_subtype', [
            'subtypes'   => ['0' => 'Select Subtype'] + Subtype::where('species_id', '=', $species)->orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
            'subtype_id' => $subtype_id,
        ]);
    }
}
