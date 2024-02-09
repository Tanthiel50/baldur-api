<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $users = User::with('articles')->orderBy('created_at', 'desc')->get();

            return response()->json($users, 200);
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
            $validationUser = Validator::make($request->all(), [
                'firstName' => 'required',
                'lastName' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'role' => 'required',
                'bio' => 'nullable',
                'userAvatar' => 'nullable|image',
                'userSlug' => 'nullable'
            ]);

            if ($validationUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validationUser->errors(),
                    'error' => $validationUser->errors(),
                ], 400);
            }

            // Gérer le slug
        $slug = $request->pointSlug ? Str::slug($request->userAvatar) : Str::slug($request->firstName);

        // Gérer le thumbnail
        $thumbnailPath = $request->file('userAvatar')->store('public/images/avatar');
        $thumbnailPath = Str::replaceFirst('public/', '', $thumbnailPath);

            $user = User::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'bio' => $request->bio,
                'avatar' => $request->avatar,
                'userSlug' => $slug,
                'userAvatar' => $thumbnailPath ?? 'default.jpg'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User created',
                'data' => $user
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
            $user = User::with('articles')
            ->where('id', $id)
            ->orderBy('created_at', 'desc')
            ->findOrFail($id);

            return response()->json($user, 200);
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
            $validationUser = Validator::make($request->all(), [
                'firstName' => 'required',
                'lastName' => 'required',
                'role' => 'required',
                'bio' => 'nullable',
                'userAvatar' => 'nullable|image',
                'userSlug' => 'nullable'
            ]);
            if($validationUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => $validationUser->errors(),
                    'error' => $validationUser->errors(),
                ], 400);
            }

            $fileName=null;

            if ($request('userAvatar')) {
                $fileName = uniqid() . '.' .
                $request->thumbnail->extension();
                $request->thumbnail->storeAs('public/images/avatar', $fileName);
        }

        $user = User::findOrFail($id);

        $user->update([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'role' => $request->role,
            'bio' => $request->bio,
            'userAvatar' => $fileName,
            'userSlug' => 'nullable'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User updated'
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
            $user = User::where('id', $id)->first();
            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'User deleted'
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
