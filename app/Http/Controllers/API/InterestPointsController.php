<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PointPictures;
use App\Models\InterestPoints;
use App\Models\PointCategories;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class InterestPointsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $interestPoints = InterestPoints::with(['pointCategories', 'user'])->orderBy('created_at', 'desc')->get();

            return response()->json($interestPoints, 200);
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
    public function store(Request $request, InterestPoints $interestPoints)
{
    try {
        // Validation
        $validator = Validator::make($request->all(), [
            'pointName' => 'required',
            'pointTitle' => 'required',
            'pointSlug' => 'nullable',
            'pointDescription' => 'required',
            'pointThumbnail' => 'required|image',
            'pointThumbnailTitle' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'pointAdress' => 'required',
            'pointSpeciality' => 'required',
            'pointCategories_id' => 'required',
            'pointContent' => 'required',
            'pointtips' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
                'error' => $validator->errors(),
            ], 400);
        }

        // Gérer le slug
        $slug = $request->pointSlug ? Str::slug($request->pointSlug) : Str::slug($request->pointTitle);

        // Gérer le thumbnail
        $thumbnailPath = $request->file('pointThumbnail')->store('public/images/point_thumbnails');
        $thumbnailPath = Str::replaceFirst('public/', '', $thumbnailPath);

        // Création du point d'intérêt
        $interestPoint = InterestPoints::create([
            'pointName' => $request->pointName,
            'pointTitle' => $request->pointTitle,
            'pointSlug' => $slug,
            'pointDescription' => $request->pointDescription,
            'pointThumbnail' => $thumbnailPath,
            'pointThumbnailTitle' => $request->pointThumbnailTitle ?? $request->pointTitle,
            'user_id' => auth()->user()->id,
            'pointAdress' => $request->pointAdress,
            'pointSpeciality' => $request->pointSpeciality,
            'pointCategories_id' => $request->pointCategories_id,
            'pointContent' => $request->pointContent,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Interest point created',
            'data' => $interestPoint
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
    public function show(string $id, InterestPoints $interestPoints)
    {
        try{
            $interestPoint = InterestPoints::with(['user', 'pointCategories'])
            ->where('id', $id)
            ->findOrFail($id);

            return response()->json($interestPoint, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, InterestPoints $interestPoints)
    {
        // dd($request->all());
        try {

            $validator = Validator::make(request()->all(), [
                'pointName' => 'required',
                'pointTitle' => 'required',
                'pointSlug' => 'required',
                'pointDescription' => 'required',
                'pointThumbnail' => 'nullable|image',
                'pointThumbnailTitle' => 'nullable|string',
                'pointAdress' => 'required',
                'pointSpeciality' => 'required',
                'pointCategories_id' => 'required',
                'pointContent' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    'error' => $validator->errors(),
                ], 400);
            }

            $fileName = null;

            if (request('thumbnail')) {
                $fileName = uniqid() . '.' . $request->thumbnail->extension();
                $request->thumbnail->storeAs('public/images/thumbnail', $fileName);
            }

            $interestPointSingle = InterestPoints::findOrFail($id);

            // dd($interestPointSingle);

            $interestPointSingle->update([
                'pointName' => $request->pointName,
                'pointTitle' => $request->pointTitle,
                'pointSlug' => $request->pointSlug,
                'pointDescription' => $request->pointDescription,
                'pointThumbnail' => $fileName ? $fileName : $interestPointSingle->pointThumbnail,
                'pointThumbnailTitle' => $request->pointThumbnailTitle,
                'pointAdress' => $request->pointAdress,
                'pointSpeciality' => $request->pointSpeciality,
                'pointCategories_id' => $request->pointCategories_id,
                'pointContent' => $request->pointContent
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Interest point updated',
                'data' => $interestPointSingle
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
    public function destroy(string $id, InterestPoints $interestPoints)
    {
        try{
            $interestPoint = InterestPoints::where('id', $id)->first();
            $interestPoint->delete();

            return response()->json([
                'status' => true,
                'message' => 'Interest point deleted'
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
 * Display a listing of the resource by category.
 */
public function getInterestPointsByCategory($categoryId)
{
    try {
        $interestPoints = InterestPoints::with('pointCategories')
            ->where('pointCategories_id', $categoryId) // Assurez-vous que c'est le nom correct de la colonne dans la table `interest_points`
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($interestPoints, 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}
