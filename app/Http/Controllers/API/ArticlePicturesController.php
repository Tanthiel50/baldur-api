<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\ArticlePictures;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ArticlePicturesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $articlePictures = ArticlePictures::all();

            return response()->json($articlePictures, 200);
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
        try{
            $validationArticlePicture = Validator::make($request->all(), [
                'article_id' => 'required',
                'picture' => 'required|image',
                'article_id' => 'required|exists:articles,id',
            ]);

            if ($validationArticlePicture->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validationArticlePicture->errors(),
                    'error' => $validationArticlePicture->errors(),
                ], 400);
            }

            $picturePath = null;
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $filename = $file->hashName();
                $file->storeAs('public/images/article_pictures', $filename);
                $picturePath = 'images/article_pictures/' . $filename;
            }

            $articlePicture = ArticlePictures::create([
                'article_id' => $request->article_id,
                'picturePath' => $picturePath,
                'pictureTitle' => $request->pictureTitle
            ]);

        
    return response()->json([
        'status' => true,
        'message' => 'Article picture created',
        'data' => $articlePicture
    ], 201);
    }catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $articlePicture = ArticlePictures::with('article')
            ->where('id', $id)->first()
            ->findOrFail($id);

            return response()->json($articlePicture, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Photo non trouvé : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $articlePicture = ArticlePictures::where('id', $id)->first();

            if($request->has('pictureTitle')){
                $articlePicture->pictureTitle = $request->pictureTitle;
        }

        $articlePicture->save();

        return response()->json([
            'status' => true,
            'message' => 'Article picture updated',
            'data' => $articlePicture
        ], 200);
    }catch (\Exception $e){
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $articlePicture = ArticlePictures::where('id', $id)->first();
            $articlePicture->delete();

            return response()->json([
                'status' => true,
                'message' => 'Article picture deleted'
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la suppression de la photo : ' . $e->getMessage()
            ], 500);
        }
    }
}
