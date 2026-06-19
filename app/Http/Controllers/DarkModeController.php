<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DarkModeController extends Controller
{
    public function toggle()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->update(['dark_mode' => !$user->dark_mode]);

        return back();
    }
}
