<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MobileCheckoutController extends Controller
{
    public function show(User $user): RedirectResponse
    {
        Auth::login($user);

        return redirect()->route('cart.index');
    }
}
