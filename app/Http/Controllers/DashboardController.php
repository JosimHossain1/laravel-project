<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the authenticated dashboard with the registered users.
     */
    public function __invoke(): View
    {
        return view('dashboard', [
            'users' => User::query()->latest()->paginate(10),
        ]);
    }
}
