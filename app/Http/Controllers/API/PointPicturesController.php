<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\PointPictures;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PointPicturesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
        try{
            $pointPictures = PointPictures::with('interestPoints')->orderBy('created_at', 'desc')->get();

            return response()->json($pointPictures, 200);
        }catch (\Exception $e){
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
            // Validation pour s'assurer que le point_id est fourni et que l'image est présente
            $validationPointPicture = Validator::make($request->all(), [
                'picture' => 'required|image', // Le champ 'picture' représente le fichier image téléchargé
                'pictureTitle' => 'required|string', // Titre de l'image, optionnel
                'point_id' => 'required|exists:interest_points,id', // S'assurer que l'ID du point d'intérêt existe si fourni
            ]);
    
            if ($validationPointPicture->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validationPointPicture->errors(),
                    'error' => $validationPointPicture->errors(),
                ], 400);
            }
    
            $picturePath = null;
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $filename = $file->hashName(); // Générer un nom de fichier unique
                $file->storeAs('public/images/point_pictures', $filename);
                $picturePath = 'images/point_pictures/' . $filename; // Chemin relatif pour l'accès public
            }
    
            // Création de l'instance PointPictures avec les données fournies
            $pointPicture = PointPictures::create([
                'point_id' => $request->point_id,
                'pictureTitle' => $request->pictureTitle,
                'picturePath' => $picturePath, // Utilisez le chemin généré
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Point picture created',
                'data' => $pointPicture
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
    public function show(string $id)
    {
        try{
            $pointPicture = PointPictures::with('interestPoints')
            ->where('id', $id)->first();

            return response()->json($pointPicture, 200);
        }catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $pointPicture = PointPictures::where('id', $id)->firstOrFail();
            
            if ($request->has('pictureTitle')) {
                $pointPicture->pictureTitle = $request->pictureTitle;
            }

            $pointPicture->save();

            return response()->json([
                'status' => true,
                'message' => 'Point picture updated',
                'data' => $pointPicture
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
            $pointPicture = PointPictures::where('id', $id)->first();
            $pointPicture->delete();

            return response()->json([
                'status' => true,
                'message' => 'Point picture deleted'
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
