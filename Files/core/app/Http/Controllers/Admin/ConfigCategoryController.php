<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\AdminResource;
use App\Models\Category;
use App\Models\Form;
use App\Models\Subcategory;
use App\Models\Skill;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConfigCategoryController extends Controller
{
    public function index()
    {
        $pageTitle  = 'All Categories';
        $categories = Category::searchable(['name'])
            ->with(['requestForm', 'quoteForm'])
            ->withCount('subcategories')
            ->withCount('jobs')
            ->orderBy('id', 'DESC')
            ->paginate(getPaginate());

        $requestForms = Form::query()->requestForms()->orderBy('act')->get(['id', 'act']);
        $quoteForms = Form::query()->quoteForms()->orderBy('act')->get(['id', 'act']);

        $formOptions = [
            'request' => $requestForms->map(fn ($f) => ['id' => $f->id, 'act' => $f->act])->values()->all(),
            'quote' => $quoteForms->map(fn ($f) => ['id' => $f->id, 'act' => $f->act])->values()->all(),
        ];

        return Inertia::render('Admin/Categories/Index', [
            'pageTitle' => $pageTitle,
            'categories' => AdminResource::categories($categories, $formOptions),
        ]);
    }

    public function store(Request $request, $id = 0)
    {
        $imageValidation = $id ? 'nullable' : 'required';
        $request->validate(
            [
                'name'             => 'required',
                'image'            => ["$imageValidation", new FileTypeValidate(['jpg', 'jpeg', 'png'])],
                'request_form_id'  => 'nullable|exists:forms,id',
                'quote_form_id'    => 'nullable|exists:forms,id',
            ]
        );

        if ($error = MarketplaceFormController::validateFormAssignment(
            $request->filled('request_form_id') ? (int) $request->request_form_id : null,
            'request'
        )) {
            $notify[] = ['error', $error];
            return back()->withNotify($notify);
        }

        if ($error = MarketplaceFormController::validateFormAssignment(
            $request->filled('quote_form_id') ? (int) $request->quote_form_id : null,
            'quote'
        )) {
            $notify[] = ['error', $error];
            return back()->withNotify($notify);
        }
        if ($id) {
            $category     = Category::findOrFail($id);
            $notification = 'Category updated successfully';
        } else {
            $category     = new Category();
            $notification = 'Category added successfully';
        }

        if ($request->hasFile('image')) {
            try {
                $category->image = fileUploader($request->image, getFilePath('category'), getFileSize('category'), @$category->image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload category image'];
                return back()->withNotify($notify);
            }
        }

        $category->name = $request->name;
        $category->request_form_id = $request->request_form_id ?: null;
        $category->quote_form_id = $request->quote_form_id ?: null;
        $category->save();
        $notify[] = ['success',  $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Category::changeStatus($id);
    }

    public function feature($id)
    {
        return Category::changeStatus($id, 'is_featured');
    }


    public function subcategories($catId = 0)
    {
        $parent = $catId ? Category::findOrFail($catId) : null;
        $pageTitle = $parent
            ? 'Subcategories — ' . $parent->name
            : 'All Subcategories';

        $categories = Category::orderBy('name')->get(['id', 'name']);
        $subcategoryCollection = Subcategory::searchable(['name'])
            ->with('category')
            ->withCount(['jobs' => function ($query) {
                $query->published()->approved();
            }]);

        if ($catId) {
            $subcategoryCollection->where('category_id', $catId);
        }

        $subcategories = $subcategoryCollection->orderBy('id', 'DESC')->paginate(getPaginate());

        return Inertia::render('Admin/Categories/Subcategories', [
            'pageTitle' => $pageTitle,
            'subcategories' => AdminResource::subcategories($subcategories, $categories, $parent),
        ]);
    }

    public function subcategoryStore(Request $request, $id = 0)
    {
        $request->validate(
            [
                'category_id' => 'required|exists:categories,id',
                'name'        => 'required',
            ]
        );

        if ($id) {
            $subcategory     = Subcategory::findOrFail($id);
            $notification = 'Subcategory updated successfully';
        } else {
            $subcategory     = new Subcategory();
            $notification = 'Subcategory added successfully';
        }

        $subcategory->category_id = $request->category_id;
        $subcategory->name = $request->name;
        $subcategory->save();
        $notify[] = ['success',  $notification];
        return back()->withNotify($notify);
    }

    public function subcategoryStatus($id)
    {
        return Subcategory::changeStatus($id);
    }

    public function subcategoryFeature($id)
    {
        return Subcategory::changeStatus($id, 'is_featured');
    }

    public function skills()
    {
        $pageTitle     = 'All Skills';
        $skills = Skill::searchable(['name'])->with('category')->orderBy('id', 'DESC')->paginate(getPaginate());
        $categories = Category::active()->orderBy('name')->get(['id', 'name']);
        return Inertia::render('Admin/Categories/Skills', [
            'pageTitle' => $pageTitle,
            'skills' => AdminResource::skills($skills),
            'categories' => $categories,
        ]);
    }

    public function skillStore(Request $request, $id = 0)
    {
        $request->validate([
            'name' => 'required|string|max:40',
            'category_id' => 'nullable|integer|exists:categories,id',
        ]);

        if ($id) {
            $skill     = Skill::findOrFail($id);
            $notification = 'Skill updated successfully';
        } else {
            $skill     = new Skill();
            $notification = 'Skill added successfully';
        }

        $skill->name = $request->name;
        $skill->category_id = $request->category_id ?: null;
        $skill->save();
        $notify[] = ['success',  $notification];
        return back()->withNotify($notify);
    }

    public function skillStatus($id)
    {
        return Skill::changeStatus($id);
    }
}
