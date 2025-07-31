<?php

namespace App\Http\Controllers;

use App\Models\profile_photos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ProfilePhotosController extends Controller
{
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'user_id' => 'required',
            'role' => 'required',
        ]);
    
        $role = $request->role == 0 ? 'super_admin' : ($request->role == 1 ? 'admin' : 'student');
        $file = $request->file('photo');
        $originalName = $file->getClientOriginalName();
        $storedName = Str ::uuid().'.'.$file->getClientOriginalExtension();
        // $folder = 'uploads/photos/' . now()->format('Y/m/d');
        $folder = 'uploads/photos/' . $role;
        $path = $file->storeAs($folder, $storedName, 'public');
    
        
        $photo = new profile_photos();
        $photo->user_id = $request->user_id;
        $photo->original_name = $originalName;
        $photo->stored_name = $storedName;
        $photo->path = $path;
        $photo->save();

        if($request->user_id) {
            $user = User::where('id', $request->user_id)->first();
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

