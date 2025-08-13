<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class Pagecontroller extends Controller
{
    //
    public function volunteer(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $users = User::all();

        return response()->json(['users' => $users], 200);
    }
}
