<?php

namespace App\Http\Controllers\API;

use App\Models\Articles;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $articles = Articles::with(['user', 'category', 'articlePictures'])
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
                'articleThumbnail' => 'required',
                'articleThumbnailTitle' => 'required',
                'articleContent' => 'required',
                'category_id' => 'required'
            ]);

            if ($validationArticle->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validationArticle->errors(),
                    'error' => $validationArticle->errors(),
                ], 400);
            }

            $article = Articles::create([
                'articleTitle' => $request->articleTitle,
                'articleThumbnail' => $request->articleThumbnail,
                'articleThumbnailTitle' => $request->articleThumbnailTitle,
                'articleContent' => $request->articleContent,
                'user_id' => auth()->user()->id,
                'articleSlug' => Str::slug($request->articleTitle),
                'category_id' => $request->category_id
            ]);

            if ($request->hasFile('articlePictures')) {
                foreach ($request->file('articlePictures') as $picture) {
                    $file = $picture->store('public/images/article_pictures');
                    $file = str_replace('public/images', '', $file);

                    $article->articlePictures()->create([
                        'pictureTitle' => $request->articleTitle,
                        'picturePath' => $file
                    ]);
                }
            }

            if ($request->hasFile('articleThumbnail')) {
                foreach ($request->file('articleThumbnail') as $picture) {
                    $file = $picture->store('public/images/article_thumbnails');
                    $file = str_replace('public/images', '', $file);

                    $article->articlePictures()->create([
                        'articleThumbnailTitle' => $request->articleTitle,
                        'articleThumbnail' => $file
                    ]);
                }
            }

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
    public function show(Articles $articles)
    {
        try{
            $article = Articles::with(['user', 'category', 'articlePictures'])
            ->where('id', $articles->id)
            ->findOrFail($articles->id);

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
    public function update(Request $request, Articles $articles)
    {
        try{

            $validationArticle = Validator::make($request->all(), [
                'articleTitle' => 'required',
                'articleThumbnail' => 'required',
                'articleThumbnailTitle' => 'required',
                'articleContent' => 'required',
                'category_id' => 'required'
            ]);

            if ($validationArticle->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validationArticle->errors(),
                    'error' => $validationArticle->errors(),
                ], 400);
            }

            $filename = null;

            if ($request->hasFile('articleThumbnail')) {
                $filename = $request->file('articleThumbnail')->store('public/images/article_thumbnails');
                $filename = str_replace('public/images', '', $filename);
            }

            $picturename = null;

            if ($request->hasFile('articlePictures')) {
                $picturename = $request->file('articlePictures')->store('public/images/article_pictures');
                $picturename = str_replace('public/images', '', $picturename);
            }

            $article = Articles::findOrFail($articles->id);

            // Recupération de l'article
            $article->update([
                'articleTitle' => $request->articleTitle,
                'articleThumbnail' => $request->articleThumbnail,
                'articleThumbnailTitle' => $request->articleThumbnailTitle,
                'articleContent' => $request->articleContent,
                'category_id' => $request->category_id
            ]);

            if ($request->hasFile('articlePictures')) {
                foreach ($request->file('articlePictures') as $picture) {
                    $file = $picture->store('public/images/article_pictures');
                    $file = str_replace('public/images', '', $file);

                    $article->articlePictures()->create([
                        'pictureTitle' => $request->articleTitle,
                        'picturePath' => $file
                    ]);
                }
            }

            if ($request->hasFile('articleThumbnail')) {
                foreach ($request->file('articleThumbnail') as $picture) {
                    $file = $picture->store('public/images/article_thumbnails');
                    $file = str_replace('public/images', '', $file);

                    $article->articlePictures()->create([
                        'articleThumbnailTitle' => $request->articleTitle,
                        'articleThumbnail' => $file
                    ]);
                }
            }

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
    public function destroy(Articles $articles)
    {
        try{
            $article = Articles::findOrFail($articles->id);
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
