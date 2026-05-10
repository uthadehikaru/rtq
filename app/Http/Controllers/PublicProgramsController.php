<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\View\View;

class PublicProgramsController extends Controller
{
    public function index(): View
    {
        $programs = Program::query()
            ->whereNotNull('published_at')
            ->latest()
            ->paginate(9);

        return view('programs.index', [
            'programs' => $programs,
        ]);
    }
}
