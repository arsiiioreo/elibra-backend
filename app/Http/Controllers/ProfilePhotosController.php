<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ProfilePhotos;
use App\Models\User;

class ProfilePhotosController extends Controller
{
    public function uploadPhoto(Request $request)
    {
        $auth = auth('api')->user();
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        $role = $auth->role == 0 ? 'super_admin' : ($auth->role == 1 ? 'admin' : 'student');
        $file = $request->file('photo');
        $originalName = $file->getClientOriginalName();
        $storedName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        // $folder = 'uploads/photos/' . now()->format('Y/m/d');
        $folder = 'uploads/photos/' . $role;
        $path = $file->storeAs($folder, $storedName, 'public');


        $photo = new ProfilePhotos();
        $photo->user_id = $auth->id;
        $photo->original_name = $originalName;
        $photo->stored_name = $storedName;
        $photo->path = $path;
        $photo->save();

        if ($auth->id) {
            $user = User::where('id', $auth->id)->first();
            $user->profile_picture = $photo->id;
            $user->save();
        }

        return response()->json([
            'message' => 'Upload successful.',
            'photo' => $photo,
            'url' => asset('storage/' . $path),
        ]);
    }
}
