<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $admin = User::where('is_super_admin', true)->firstOrFail();

        return view('pages.about', compact('admin'));
    }
}
