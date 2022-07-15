<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\PresentRepositoryInterface;
use Auth;

class PresentList extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request,
    PresentRepositoryInterface $presentRepository,)
    {
        
        $data['title'] = __('Kehadiran');
        $data['presents'] = $presentRepository->getByTeacher(Auth::user()->teacher->id);
        return view('datatables.teacher-present', $data);
    }
}
