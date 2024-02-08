<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Articleategories;
use App\Models\ArticleCategories;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ArticleCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $articleCategories = ArticleCategories::with('articles')->orderBy('categoryName', 'asc')->get();

            return response()->json($articleCategories, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validationArticleCategory = Validator::make($request->all(), [
                'categoryName' => 'required',
                'categoryDescription' => 'required'
            ]);

            if ($validationArticleCategory->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validationArticleCategory->errors(),
                    'error' => $validationArticleCategory->errors(),
                ], 400);
            }

            $articleCategory = ArticleCategories::create([
                'categoryName' => $request->categoryName,
                'categoryDescription' => $request->categoryDescription
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Article category created',
                'data' => $articleCategory
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ArticleCategories $articleategories)
    {
        try {
            $articleCategory = ArticleCategories::with('articles')
            ->where('id', $articleategories->id)->first()
            ->findOrFail($articleategories->id);

            return response()->json($articleCategory, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Catégorie non trouvée : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ArticleCategories $articleategories)
    {
        try {
            $validationArticleCategory = Validator::make($request->all(), [
                'categoryName' => 'required',
                'categoryDescription' => 'required'
            ]);

            if ($validationArticleCategory->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validationArticleCategory->errors(),
                    'error' => $validationArticleCategory->errors(),
                ], 400);
            }

            $articleCategory = ArticleCategories::where('id', $articleategories->id)->first();
            $articleCategory->update([
                'categoryName' => $request->categoryName,
                'categoryDescription' => $request->categoryDescription
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Category updated',
                'data' => $articleCategory
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArticleCategories $articleategories)
    {
        try{
            $articleCategory = ArticleCategories::where('id', $articleategories->id)->first();
            $articleCategory->delete();

            return response()->json([
                'status' => true,
                'message' => 'Category deleted'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la suppression de la catégorie : ' . $e->getMessage()
            ], 500);
        }
    }
}
