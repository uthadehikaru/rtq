<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\NotificationsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(NotificationsDataTable $dataTable)
    {
        $data['title'] = "Notifications";
        return $dataTable->render('datatables.datatable', $data);
    }
}
