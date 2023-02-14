<?php

namespace App\Http\Controllers\Member;

use App\DataTables\MemberIqobDataTable;
use App\DataTables\MemberPresentDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IqobController extends Controller
{
    public function __invoke(MemberIqobDataTable $dataTable)
    {
        $data['title'] = "Iqob";
        $user = Auth::user();
        $dataTable->setUser($user->id);
        return $dataTable->render('datatables.datatable', $data);
    }
}
