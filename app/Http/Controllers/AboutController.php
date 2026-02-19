<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $about_title = Setting::get('about_title', 'Tentang Kami');
        $about_description = Setting::get('about_description', 'Selamat datang di Cosmetiqu');

        $teamMembers = User::teamMembers()->get();

        return view('about', compact('about_title', 'about_description', 'teamMembers'));
    }
}
