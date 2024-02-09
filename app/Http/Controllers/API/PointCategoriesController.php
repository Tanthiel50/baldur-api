<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PointCategories;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PointCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $pointCategories = PointCategories::with('interestPoints')->orderBy('pointCategoryName', 'asc')->get();

            return response()->json($pointCategories, 200);
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
            $validationPointCategory = Validator::make($request->all(), [
                'pointCategoryName' => 'required',
                'pointCategoryDescription' => 'required',
                'pointCategorySlug' => 'nullable'
            ]);

            if ($validationPointCategory->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validationPointCategory->errors(),
                    'error' => $validationPointCategory->errors(),
                ], 400);
            }

            $pointCategory = PointCategories::create([
                'pointCategoryName' => $request->pointCategoryName,
                'pointCategoryDescription' => $request->pointCategoryDescription,
                'pointCategorySlug' => $request->pointCategorySlug
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Point category created',
                'data' => $pointCategory
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
            $pointCategory = PointCategories::with('interestPoints')
            ->where('id', $id)->first();

            return response()->json($pointCategory, 200);
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
        try {
            // Pas besoin de valider pour 'nullable' puisqu'on ne met à jour que les champs présents
            $pointCategory = PointCategories::where('id', $id)->firstOrFail();
    
            // Mise à jour conditionnelle
            if ($request->has('pointCategoryName')) {
                $pointCategory->pointCategoryName = $request->pointCategoryName;
            }
            if ($request->has('pointCategoryDescription')) {
                $pointCategory->pointCategoryDescription = $request->pointCategoryDescription;
            }
    
            $pointCategory->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Point category updated',
                'data' => $pointCategory
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
    public function destroy(string $id)
    {
        try{
            $pointCategory = PointCategories::where('id', $id)->first();
            $pointCategory->delete();

            return response()->json([
                'status' => true,
                'message' => 'Point category deleted'
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
