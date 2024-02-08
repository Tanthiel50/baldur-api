<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\InterestPoints;
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
            $interestPoints = InterestPoints::with('pointPicture', 'pointCategories')->orderBy('created_at', 'desc')->get();

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
    public function store(Request $request)
    {
        try{
            $validationInterestPoint = Validator::make($request->all(), [
                'pointName' => 'required',
                'pointTitle' => 'required',
                'pointSlug' => 'required',
                'pointDescription' => 'required',
                'pointThumbnail' => 'required',
                'pointThumbnailTitle' => 'required',
                'user_id' => 'required',
                'pointAdress' => 'required',
                'pointSpeciality' => 'required',
                'pointPicture_id' => 'required',
                'pointCategories_id' => 'required'
            ]);

            if ($validationInterestPoint->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validationInterestPoint->errors(),
                    'error' => $validationInterestPoint->errors(),
                ], 400);
            }

            $validationInterestPoint = InterestPoints::create([
                'pointName' => $request->pointName,
                'pointTitle' => $request->pointTitle,
                'pointSlug' => $request->pointSlug,
                'pointDescription' => $request->pointDescription,
                'pointThumbnail' => $request->pointThumbnail,
                'pointThumbnailTitle' => $request->pointThumbnailTitle,
                'user_id' => $request->user_id,
                'pointAdress' => $request->pointAdress,
                'pointSpeciality' => $request->pointSpeciality,
                'pointPicture_id' => $request->pointPicture_id,
                'pointCategories_id' => $request->pointCategories_id
            ]);

            if($request->hasFile('pointPictures')){
                foreach ($request->file('pointPictures') as $picture) {
                    $file = $picture->store('public/images/point_pictures');
                    $file = str_replace('public/images', '', $file);

                    $validationInterestPoint->pointPictures()->create([
                        'pictureTitle' => $request->pointTitle,
                        'picturePath' => $file
                    ]);
                }
            }

            if($request->hasFile('pointThumbnail')){
                foreach ($request->file('pointThumbnail') as $picture) {
                    $file = $picture->store('public/images/point_thumbnails');
                    $file = str_replace('public/images', '', $file);

                    $validationInterestPoint->pointPictures()->create([
                        'pointThumbnailTitle' => $request->pointTitle,
                        'pointThumbnail' => $file
                    ]);
                }
            }
            return response()->json([
                'status' => true,
                'message' => 'Interest point created',
                'data' => $validationInterestPoint
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
            $interestPoint = InterestPoints::with(['user', 'pointPicture', 'pointCategories', 'pointPictures'])
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
    public function update(Request $request, string $id)
    {
        try{
            $validationInterestPoint = Validator::make($request->all(), [
                'pointName' => 'required',
                'pointTitle' => 'required',
                'pointSlug' => 'required',
                'pointDescription' => 'required',
                'pointThumbnail' => 'required',
                'pointThumbnailTitle' => 'required',
                'user_id' => 'required',
                'pointAdress' => 'required',
                'pointSpeciality' => 'required',
                'pointPicture_id' => 'required',
                'pointCategories_id' => 'required'
            ]);
            if ($validationInterestPoint->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validationInterestPoint->errors(),
                    'error' => $validationInterestPoint->errors(),
                ], 400);
            }

            $filename = null;

            if($request->hasFile('pointThumbnail')){
                $filename = $request->pointThumbnail->store('public/images/point_thumbnails');
                $filename = str_replace('public/images', '', $filename);
            }

            $interestPoint = InterestPoints::where('id', $id)->first();

            $interestPoint->update([
                'pointName' => $request->pointName,
                'pointTitle' => $request->pointTitle,
                'pointSlug' => $request->pointSlug,
                'pointDescription' => $request->pointDescription,
                'pointThumbnail' => $filename,
                'pointThumbnailTitle' => $request->pointThumbnailTitle,
                'user_id' => $request->user_id,
                'pointAdress' => $request->pointAdress,
                'pointSpeciality' => $request->pointSpeciality,
                'pointPicture_id' => $request->pointPicture_id,
                'pointCategories_id' => $request->pointCategories_id
            ]);

            if($request->hasFile('pointPictures')){
                foreach ($request->file('pointPictures') as $picture) {
                    $file = $picture->store('public/images/point_pictures');
                    $file = str_replace('public/images', '', $file);

                    $interestPoint->pointPictures()->create([
                        'pictureTitle' => $request->pointTitle,
                        'picturePath' => $file
                    ]);
                }
            }

            if($request->hasFile('pointThumbnail')){
                foreach ($request->file('pointThumbnail') as $picture) {
                    $file = $picture->store('public/images/point_thumbnails');
                    $file = str_replace('public/images', '', $file);

                    $interestPoint->pointPictures()->create([
                        'pointThumbnailTitle' => $request->pointTitle,
                        'pointThumbnail' => $file
                    ]);
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Interest point updated',
                'data' => $interestPoint
            ], 200);
        }catch (\Exception $e) {
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
}
