<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public static function user()
    {
        return auth('api')->user();
    }

    private function validation(Request $request): array
    {
        $validated = Validator::make($request->all(), [
            'query'    => 'nullable|string|max:255',
            'sort'     => 'nullable|in:last_name,status,created_at',
            'order'    => 'nullable|in:asc,desc',
            'role'     => 'nullable|in:0,1,2',
            'status'   => 'nullable|in:0,1,2',
            'page'     => 'nullable|integer|min:1',
            'entries'  => 'nullable|integer|min:1|max:100',
        ])->validate();

        return array_merge([
            'query'     => null,
            'sort'      => 'created_at',
            'order'     => 'desc',
            'role'      => '',
            'status'    => '',
            'page'      => 1,
            'entries'   => 25,
        ], $validated);
    }

    private function userValidation(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'middle_initial' => 'sometimes|string|max:2',
            'last_name' => 'sometimes|string|max:255',
            'sex' => 'sometimes|in:male,female',
            'email' => [
                'sometimes',
                'email',
                // exclude current user from email uniqueness check (for updates)
                'unique:users,email' . ($user ? ',' . $user->id : ''),
            ],
            'contact_number' => 'sometimes|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    }

    /**
     * Display all users.
     */
    public function index(Request $request)
    {
        $validated = $this->validation($request);

        $users = User::query()
            ->whereNull('deleted_at')
            ->when($validated['query'], function ($q, $search) {
                $terms = explode(' ', $search);
                foreach ($terms as $term) {
                    $q->where(function ($inner) use ($term) {
                        $inner
                            ->where('last_name', 'like', "%$term%")
                            ->orWhere('first_name', 'like', "%$term%")
                            ->orWhere('email', 'like', "%$term%");
                    });
                }
            })
            ->when(isset($validated['role']) && $validated['role'] !== '', function ($q) use ($validated) {
                $q->where('role', $validated['role']);
            })
            ->when(isset($validated['status']) && $validated['status'] !== '', function ($q) use ($validated) {
                $q->where('status', $validated['status']);
            })
            ->when(isset($validated['campus_id']) && $validated['campus_id'] !== '', function ($q) use ($validated) {
                $q->where('campus_id', $validated['campus_id']);
            })
            ->orderBy($validated['sort'], $validated['order'])
            ->paginate(
                $validated['entries'],
                ['id', 'last_name', 'middle_initial', 'first_name', 'role', 'email', 'status', 'pending_registration_approval'],
                'page',
                $validated['page']
            );

        // Loop through the paginated data (inside `data` key)
        foreach ($users as $user) {
            if ($user->role === '2') {
                $user->patron();
            } elseif ($user->role === '1') {
                $user->roleText = "Librarian";
            }

            $user->status = $user->status === '0' ? 'Active' : ($user->status === '1' ? 'Suspended' : 'Expired');

            switch ($user->role) {
                case '0':
                    $user->role = "Administrator";
                    break;
                case '1':
                    $user->role = "Librarian";
                    break;
                case '2':
                    $user->role = "Patron";
                    break;
                default:
                    $user->role = "Unknown";
                    break;
            }
        }

        return response()->json($users);
    }

    public function details($id)
    {
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->role === '2') {
            $user->patron();
        } elseif ($user->role === '1') {
            $user->roleText = "Librarian";
        }

        switch ($user->role) {
            case '0':
                $user->role = "Administrator";
                break;
            case '1':
                $user->role = "Librarian";
                break;
            case '2':
                $user->role = "Patron";
                break;
            default:
                $user->role = "Unknown";
                break;
        }

        return response()->json($user);
    }

    public function approveUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // if (!$user->pending_registration_approval) {
        //     return response()->json(['message' => 'User registration is already approved'], 400);
        // }

        $user->pending_registration_approval = '0';
        $user->save();

        return response()->json(['message' => 'User registration approved successfully']);
    }

    public function rejectUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(["message" => "User not found"], 400);
        }

        $user->pending_registration_approval = "2";
        $user->save();

        return response()->json(["message" => "User registration rejected successfully"]);
    }

    public function updateInfo(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(["message" => "User not found"], 400);
        }

        $data = Validator::make($request->all(), [
            "last_name" => "required|string|max:255",
            "first_name" => "required|string|max:255",
            "middle_initial" => "nullable|string|max:1",
            "sex" => "required",
            "contact_number" => "nullable",
            "email" => "required",
            "username" => "nullable",
        ]);

        if ($data->fails()) {
            return response()->json(["message" => $data->errors()->first()], 400);
        }

        $upcase = [
            "last_name" => ucwords(strtolower($request->last_name)),
            "first_name" => ucwords(strtolower($request->first_name)),
            "middle_initial" => strtoupper($request->middle_initial)
        ];

        $user->last_name = $upcase["last_name"] ?? $user->last_name;
        $user->first_name = $upcase["first_name"] ?? $user->first_name;
        $user->middle_initial = $upcase["middle_initial"] ?? $user->middle_initial;
        $user->sex = $data->sex ?? $user->sex;
        $user->contact_number = $data->contact_number ?? $user->contact_number;
        $user->email = $data->email ?? $user->email;
        $user->username = $data->username ?? $user->username;
        $user->save();

        return  response()->json(["message" => "Info updated successfully"], 200);
    }













    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $authUser = auth('api')->user();

        if (!$authUser || !($authUser instanceof \App\Models\User)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized user',
            ], 401);
        }

        // Validate user input
        $validated = $this->userValidation($request, $authUser);    

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');

            // Generate a unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Move the file to storage/app/public/profile_images
            $file->move(storage_path('app/public/profile_images/'), $filename);

            // Delete old profile photo record if exists
            if ($authUser->profile_picture) {
                $oldPhoto = \App\Models\ProfilePhotos::find($authUser->profile_picture);
                if ($oldPhoto) {
                    $oldPath = storage_path('app/public/profile_images/' . $oldPhoto->stored_name);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                    $oldPhoto->delete();
                }
            }

            // Insert new photo record
            $photo = \App\Models\ProfilePhotos::create([
                'user_id' => $authUser->id,
                'original_name' => $file->getClientOriginalName(),
                'stored_name' => $filename,
                'path' => 'storage/profile_images/' . $filename, // relative path for asset()
            ]);

            // Update user's profile_picture column to reference new photo ID
            $validated['profile_picture'] = $photo->id;
        }

        // Update user
        $authUser->update($validated);
        $authUser->refresh();

        // Load full photo info for response
        $authUser->profile_photo = $authUser->profile_picture 
            ? \App\Models\ProfilePhotos::find($authUser->profile_picture) 
            : null;

        // Return updated user info
        return response()->json([
            'status' => 'success',
            'message' => 'User information updated successfully!',
            'user' => $authUser,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
