<?php

namespace App\Http\Controllers;

use App\DataTables\ActivitiesDataTable;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ActivitiesDataTable $dataTable)
    {
        $data['title'] = 'Activities';

        return $dataTable->render('datatables.datatable', $data);
    }
}
