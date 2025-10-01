<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\profile_photos;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserController extends Controller
{
    /*
    //GET ALL USERS - ADMIN ONLY
    public function allUser() {
        $users = User::with('campus')->get();
        return response()->json($users);
    }

    //GET AUTHENTICATED USER PROFILE
    public function user(Request $request) {

        $user = User::with(['campus', 'profile_photos'])
            ->find($request->user()->id);
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'sex' => $user->sex,
            'contact_number' => $user->contact_number,
            'email' => $user->email,
            'role' => $user->role,
            // 'profile_picture' => $user->profile_picture ? asset(('storage/' . $user->profile_photos?->path)) : null,
            'profile_picture' => $user->profile_photos? [
                'url'           => asset('storage/' . $user->profile_photos->path),
                'original_name' => $user->profile_photos->original_name,
            ] : null,
            'pending_registration_approval' => $user->pending_registration_approval,
            'campus' => $user->campus ? [
                'id' => $user?->campus?->id,
                'campus' => $user?->campus?->campus,
                'abbrev' => $user?->campus?->abbrev,
                'address' => $user?->campus?->address,
            ] : null,
        ]);
    }

    //UPDATE USER PROFILE
    public function update(UserRequest $request) {
    
        $user = $request->user();
        $data = $request->validated();

    if ($request->hasFile('profile_picture')) {
        $file = $request->file('profile_picture');
        $path = $file->store('profile_images', 'public');

        $photo = profile_photos::create([
            'user_id' => $user->id,
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $file->hashName(),
            'path' => $path,
        ]);

        $data['profile_picture'] = $photo->id;
    }

        $user->update($data);

    $user->load(['campus', 'profile_photos']);

    return response()->json([
        'status' => 'success',
        'message' => 'Profile updated successfully',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'sex' => $user->sex,
                'contact_number' => $user->contact_number,
                'email' => $user->email,
                'role' => $user->role,
                'profile_picture' => $user->profile_photos ? [
                    'url'           => asset('storage/' . $user->profile_photos->path),
                    'original_name' => $user->profile_photos->original_name,
                ] : null,
                'pending_registration_approval' => $user->pending_registration_approval,
                'campus' => [
                    'id' => $user->campus?->id, 
                    'campus' => $user->campus?->campus,
                    'abbrev' => $user->campus?->abbrev,
                    'address' => $user->campus?->address,
                ],
            ]
        ]);
    }


    public function destroy()
    {
        $user = User::find(auth('api')->id());
        if ($user && $user->delete()) {
            return response()->json(['status' => 'success'], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to delete item'], 500);
        }
    }
    */ //OLD CONTROLLER

    
    // GET ALL USERS 
    public function allUser() {
        $users = User::with('campus')->get();
        return response()->json($users);
    }

    // GET AUTHENTICATED USER PROFILE
    public function user(Request $request)
    {
        $user = User::with(['campus', 'profile_photos'])
            ->find($request->user()->id);
        return response()->json($this->UserFormat($user));
    }

    // UPDATE AUTHENTICATED USER PROFILE
    public function update(UserRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $path = $file->store('profile_images', 'public');

            $photo = profile_photos::create([
                'user_id'       => $user->id,
                'original_name' => $file->getClientOriginalName(),
                'stored_name'   => $file->hashName(),
                'path'          => $path,
            ]);

            $data['profile_picture'] = $photo->id;
        }

        $user->update($data);
        $user->load(['campus', 'profile_photos']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Profile updated successfully',
            'user'    => $this->UserFormat($user),
        ]);
    }

    public function destroy()
    {
        $user = User::find(auth('api')->id());
        if ($user && $user->delete()) {
            return response()->json(['status' => 'success'], 200);
        }else{
            return response()->json(['status' => 'error', 'message' => 'Failed to delete item'], 500);
        };
    }

    // Storing in private function to avoid repetition and make it accessible onlly within the class
    private function UserFormat(User $user): array
    {
        return [
            'id'                            => $user->id,
            'name'                          => $user->name,
            'sex'                           => $user->sex,
            'contact_number'                => $user->contact_number,
            'email'                         => $user->email,
            'role'                          => $user->role,
            'profile_picture'               => $user->profile_photos ? [
                'url'           => asset('storage/' . $user->profile_photos->path),
                'original_name' => $user->profile_photos->original_name,
            ] : null,
            'pending_registration_approval' => $user->pending_registration_approval,
            'campus'                        => $user->campus ? [
                'id'     => $user->campus->id,
                'campus' => $user->campus->campus,
                'abbrev' => $user->campus->abbrev,
                'address'=> $user->campus->address,
            ] : null,
        ];
    }

    //Logout
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['status' => 'success', 'message' => 'Successfully logged out']);
    }
}
