<?php

namespace App\Http\Controllers\Member;

use App\DataTables\MemberPresentDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresentController extends Controller
{
    public function __invoke(MemberPresentDataTable $dataTable)
    {
        $data['title'] = "Kehadiran";
        $user = Auth::user();
        $dataTable->setUser($user->id);
        return $dataTable->render('datatables.datatable', $data);
    }
}
