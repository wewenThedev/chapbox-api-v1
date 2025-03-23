<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;


use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(?Request $request)
    {
        //$categories = Category::all();
        $categories = Category::with(['products'])->get();
        //$categories = Category::paginate(5);
        //return Category::all();
        //return response()->json($categories, 200);
        return response()->json(['categories' => $categories], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        //
        $category = Category::create($request->validated());
        return response()->json($category, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return $category;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());
        return response()->json($category, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $categoryTodelete = $category;
        $category->delete();
        return response()->json(['success' => 'La catégorie de produit '.$categoryTodelete->name.' a été supprimée avec succès'], 204);
        //return response()->json(null, 204);

    }

    public function getCategoriesByStock(){

    }
}
