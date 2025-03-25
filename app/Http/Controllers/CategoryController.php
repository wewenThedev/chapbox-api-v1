<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;


use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    public function productsByCategory($category){

        $productsByCategory = Category::with(['products'])->where('id', $category)->get();
        return response()->json(['productsByCategory' => $productsByCategory]);
    }

    public function fetchTopCategories()
{
    $categories = Category::withCount('products') // Compter les produits par catégorie
        ->orderByDesc('products_count') // Trier par nombre de produits (optionnel)
        ->take(6) // Prendre seulement 6 catégories
        ->with('products') // Charger les produits associés (optionnel)
        ->get();

    return response()->json(['categories' => $categories]);
}

    //products categry filtre

    /**
     * Display a listing of the resource.
     */
    public function index(?Request $request)
    {
        $categories = Category::all();

        //$categories = Category::with(['products'])->get();
        
        //$categories = Category::with(['products'])->paginate(20);
        ///$categories = Category::paginate(20);

        //$totalProductsByCategory = Category::withCount('products')->pluck('products_count','id')->toArray();
        //dd($totalProductsByCategory);
        
        return response()->json(['categories' => $categories], 200);
        //return response()->json(['totalProductsByCategory' => $totalProductsByCategory], 200);
    }

    public function categoryWithTotalProducts(){

        $totalProductsByCategory = Category::with(['products'])->withCount('products')->get();
        
        return response()->json(['categories' => $totalProductsByCategory], 200);

    }

    public function updateCategoriesDescription(UpdateCategoryRequest $request){
        $categories = Category::where('description', null)->get();

        foreach ($categories as $category) {
            $category->description = $request->description;
            $category->save();
        }

        return response()->json(['success'=> 'Descritpion des categories mises à jour avec succès'],200);
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
