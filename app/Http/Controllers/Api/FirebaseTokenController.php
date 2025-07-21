<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FirebaseToken;

class FirebaseTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        FirebaseToken::updateOrCreate(
            ['token' => $request->token],
            ['updated_at' => now()]
        );

        return response()->json(['success' => true]);
    }
}
