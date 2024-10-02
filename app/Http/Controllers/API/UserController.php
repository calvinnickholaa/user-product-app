<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $take = $request->query('take', 10);
        $skip = $request->query('skip', 0);
        $search = $request->query('search');

        $query = User::with('role');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $users = $query->skip($skip)->take($take)->latest()->get();

        if ($users->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'User is empty'
            ], Response::HTTP_NOT_FOUND);
        } else {
            // Ambil data pengguna dan nama role
            $userData = $users->map(function ($user) {
                return [
                    'name' => $user->name,
                    'role' => $user->role->role
                ];
            });

            return response()->json([
                'data' => $userData,
                'message' => 'List users',
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        }
    }

    public function show($id)
    {
        $user = User::where('id', $id)->first();

        if ($user) {
            return response()->json([
                'status' => Response::HTTP_OK,
                'data' => [
                    $user->name,
                    $user->role->role
                ]
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function update(Request $request, $id)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'message' => 'Token not exists update failed, please login first'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'User updated',
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error update data :' . $e->getMessage());

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Failed update data'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);

        try {
            $user->delete();
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'User deleted'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error delete data :' . $e->getMessage());

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Failed delete data'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
