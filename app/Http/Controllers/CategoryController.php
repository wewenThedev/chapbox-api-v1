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
    public function index()
    {
        Category::create([
            'name' => 'Produits frais',
        ]);
        Category::create([
            'name' => 'Produits laitiers',
        ]);
        Category::create([
            'name' => 'Fruits et légumes',
        ]);
        Category::create([
            'name' => 'Produits surgelés',
        ]);Category::create([
            'name' => 'Sauces et vinaigrettes',
        ]);
        Category::create([
            'name' => 'Huiles Végétales',
        ]);
        Category::create([
            'name' => 'Bonbons et sucreries',
        ]);
        Category::create([
            'name' => 'Conserves',
        ]);
        Category::create([
            'name' => 'Boissons alcoolisées',
        ]);
        Category::create([
            'name' => 'Boissons non alcoolisées',
        ]);
        Category::create([
            'name' => 'Hygiène',
        ]);
        Category::create([
            'name' => 'Électroménagers',
        ]);
        Category::create([
            'name' => 'Jouets',
        ]);
        Category::create([
            'name' => 'Vêtements',
        ]);
        Category::create([
            'name' => 'Boulangerie et Patisserie',
        ]);
        Category::create([
            'name' => 'Électronique',
        ]);

        //return Category::all();
        return Category::paginate(2);
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
    public function show(string $id /*Category $category*/)
    {
        $category = Category::findOrFail($id);
        return $category;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id /*Category $category*/)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());
        return response()->json($category);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id /*Category $category*/)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(null, 204);

    }
}
