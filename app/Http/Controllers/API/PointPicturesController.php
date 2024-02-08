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
            $pointPictures = PointPictures::with('interestPoint')->orderBy('created_at', 'desc')->get();

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
        try{
            $validationPointPicture = Validator::make($request->all(), [
                'interest_point_id' => 'required',
                'picture' => 'required'
            ]);

            if ($validationPointPicture->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validationPointPicture->errors(),
                    'error' => $validationPointPicture->errors(),
                ], 400);
            }

            $pointPicture = PointPictures::create([
                'interest_point_id' => $request->interest_point_id,
                'picture' => $request->picture
            ]);

            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $file = $file->storeAs('public/images/point_pictures');
                $file = str_replace('public/images', '', $file);

                $pointPicture->picture()->create([
                    'picturePath' => $file
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Point picture created',
                'data' => $pointPicture
            ], 201);
        }catch (\Exception $e){
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
            $pointPicture = PointPictures::with('interestPoint')
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
            $validationPointPicture = Validator::make(['id' => $id], [
                'interest_point_id' => 'required',
                'picture' => 'required'
            ]);
            if($validationPointPicture->fails()){
                return response()->json([
                    'status' => false,
                    'message' => $validationPointPicture->errors(),
                    'error' => $validationPointPicture->errors(),
                ], 400);
            }
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $file = $file->storeAs('public/images/point_pictures');
                $file = str_replace('public/images', '', $file);
            }

            $pointPicture = PointPictures::where('id', $id)->first();

            $pointPicture->update([
                'interest_point_id' => $request->interest_point_id,
                'picture' => $request->picture,
                'pictureTitle' => $request->pictureTitle
            ]);

            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $file = $file->storeAs('public/images/point_pictures');
                $file = str_replace('public/images', '', $file);

                $pointPicture->picture()->update([
                    'picturePath' => $file
                ]);
                    
        }
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
