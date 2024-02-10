<?php

namespace App\Http\Controllers\API;

use App\Models\Articles;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $articles = Articles::with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->get();

            return response()->json($articles, 200);
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

            $validationArticle = Validator::make($request->all(), [
                'articleTitle' => 'required',
                'articleThumbnail' => 'required|image',
                'articleThumbnailTitle' => 'nullable|string',
                'articleContent' => 'required',
                'category_id' => 'required',
                'articleSlug' => 'nullable|string'
            ]);

            if ($validationArticle->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validationArticle->errors(),
                    'error' => $validationArticle->errors(),
                ], 400);
            }

            // Gérer le thumbnail
            $thumbnailPath = uniqid() . '.' . request('articleThumbnail')->extension();
            request('articleThumbnail')->storeAs('public/images/article_thumbnails', $thumbnailPath);

            // $thumbnailPath = $request->file('articleThumbnail')->store('public/images/article_thumbnails');
            // $thumbnailPath = Str::replaceFirst('public/', '', $thumbnailPath);

            $article = Articles::create([
                'articleTitle' => $request->articleTitle,
                'articleThumbnail' => $thumbnailPath,
                'articleThumbnailTitle' => $request->articleThumbnailTitle ?? $request->articleTitle,
                'articleContent' => $request->articleContent,
                'user_id' => Auth::id(),
                'articleSlug' => Str::slug($request->articleTitle),
                'category_id' => $request->category_id
            ]);

            return response()->json([
                'data' => $article,
                'status' => true,
                'message' => 'Article created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la création de l\'article : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Articles $articles, $id)
    {
        try{
            $article = Articles::with(['user', 'category'])
            ->where('id', $id)
            ->findOrFail($id);

            return response()->json($article, 200);
        }catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Article non trouvé : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try{

            $validationArticle = Validator::make($request->all(), [
                'articleTitle' => 'required',
                'articleThumbnail' => 'nullable|image',
                'articleThumbnailTitle' => 'nullable|string',
                'articleContent' => 'required',
                'category_id' => 'required',
            ]);

            if ($validationArticle->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validationArticle->errors(),
                    'error' => $validationArticle->errors(),
                ], 400);
            }

            $fileName = null;

            if (request('articleThumbnail')) {
                $fileName = uniqid() . '.' . $request->thumbnail->extension();
                $request->thumbnail->storeAs('public/images/article_thumbnails', $fileName);
            }

            $article = Articles::findOrFail($id);

            // Recupération de l'article
            $article->update([
                'articleTitle' => $request->articleTitle,
                'articleThumbnail' => $fileName ? $fileName : $article->articleThumbnail,
                'articleThumbnailTitle' => $request->articleThumbnailTitle,
                'articleContent' => $request->articleContent,
                'category_id' => $request->category_id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Article updated successfully'
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la mise à jour de l\'article : ' . $e->getMessage()
            ], 500);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($articles)
    {
        try{
            $article = Articles::findOrFail($articles);
            $article->delete();

            return response()->json([
                'status' => true,
                'message' => 'Article deleted successfully'
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la suppression de l\'article : ' . $e->getMessage()
            ], 500);
        }
    }
}
