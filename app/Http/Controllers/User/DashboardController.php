<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * User dashboard
     */
    public function index()
    {
        return redirect()->route('user.account.index');
    }
}
