<?php

namespace App\Http\Controllers;

use App\Models\Joke;
use Illuminate\Http\Request;

class JokeController extends Controller
{
    public function index(Request $request)
    {
        $query = Joke::query();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $limit = $request->get('limit', 100);

        $jokes = $query->latest()->limit($limit)->get();

        return response()->json([
            'success' => true,
            'data' => $jokes,
            'total' => $jokes->count()
        ]);
    }

    public function types()
    {
        $types = Joke::distinct()->pluck('type');
        return response()->json([
            'success' => true,
            'types' => $types
        ]);
    }
}
