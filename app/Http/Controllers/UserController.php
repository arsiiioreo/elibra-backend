<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public static function user() {
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
                ['id', 'last_name', 'middle_initial', 'first_name', 'role', 'email'],
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
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
