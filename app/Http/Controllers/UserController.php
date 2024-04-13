<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $data = [
            'message' => 'Get All Users',
            'data' => $users,
        ];
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'address' => 'required|string',
        ]);

        // Hash password sebelum menyimpan
        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);

        $data = [
            'message' => 'User successfully created',
            'data' => $user,
        ];
        return response()->json($data, 201);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            $data = [
                'message' => 'User not found',
            ];
            return response()->json($data, 404);
        }

        $data = [
            'message' => 'Get User Details',
            'data' => $user,
        ];
        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            $data = [
                'message' => 'User not found',
            ];
            return response()->json($data, 404);
        }

        $validatedData = $request->validate([
            'name' => 'string',
            'email' => [
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'string|min:6',
            'address' => 'string',
        ]);

        if ($request->has('password')) {
            // Hash password jika ada
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $user->update($validatedData);

        $data = [
            'message' => 'User successfully updated',
            'data' => $user,
        ];
        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            $data = [
                'message' => 'User not found',
            ];
            return response()->json($data, 404);
        }

        $user->delete();

        $data = [
            'message' => 'User successfully deleted',
        ];
        return response()->json($data, 200);
    }
}
?>
