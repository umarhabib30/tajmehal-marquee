<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'active' => 'Dashboard ',
            'title' => 'dashboard',
            'heading' => 'dashboard',
        ];

        return view('admin.index', $data);
    }
}
