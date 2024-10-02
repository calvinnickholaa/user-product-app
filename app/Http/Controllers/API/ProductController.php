<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $take = $request->query('take', 10);
        $skip = $request->query('skip', 0);
        $search = $request->query('search');

        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $products = $query->skip($skip)->take($take)->latest()->get();

        if ($products->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Product is empty'
            ], Response::HTTP_NOT_FOUND);
        } else {
            $productData = $products->map(function ($product) {
                return [
                    'name' => $product->name,
                    'kuantitas' => $product->kuantitas
                ];
            });

            return response()->json([
                'data' => $productData,
                'message' => 'List products',
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        }
    }


    public function store(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'message' => 'Token not exists, please login first'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'kuantitas' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            Product::create([
                'name' => $request->name,
                'kuantitas' => $request->kuantitas,
            ]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Product stored to DB',
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error storing data :' . $e->getMessage());

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Failed stored data to DB'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        $product = Product::where('id', $id)->first();

        if ($product) {
            return response()->json([
                'status' => Response::HTTP_OK,
                'data' => [
                    $product->name,
                    $product->kuantitas
                ]
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Product not found'
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

        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Product not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'kuantitas' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            $product->update([
                'name' => $request->name,
                'kuantitas' => $request->kuantitas,
            ]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Product updated',
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
        $product = Product::find($id);

        try {
            $product->delete();
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Product deleted'
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
