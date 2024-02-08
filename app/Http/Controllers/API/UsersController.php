<?php

namespace App\Http\Controllers\API;

use App\Models\User;
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
                'avatar' => 'nullable'
            ]);

            if ($validationUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validationUser->errors(),
                    'error' => $validationUser->errors(),
                ], 400);
            }

            $user = User::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'bio' => $request->bio,
                'avatar' => $request->avatar
            ]);

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $file = $file->storeAs('public/images/avatars');
                $file = str_replace('public/images', '', $file);

                $user->avatar()->create([
                    'avatarPath' => $file
                ]);
            }

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
                'email' => 'required|email',
                'role' => 'required',
                'bio' => 'nullable',
                'avatar' => 'nullable'
            ]);
            if($validationUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => $validationUser->errors(),
                    'error' => $validationUser->errors(),
                ], 400);
        $filename=null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = $file->storeAs('public/images/avatars');
            $filename = str_replace('public/images', '', $filename);
        }

        $user = User::where('id', $id)->first();

        $user->update([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'role' => $request->role,
            'bio' => $request->bio,
            'avatar' => $filename
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $file = $file->storeAs('public/images/avatars');
            $file = str_replace('public/images', '', $file);

            $user->avatar()->create([
                'avatarPath' => $file
            ]);
        }
    }

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
