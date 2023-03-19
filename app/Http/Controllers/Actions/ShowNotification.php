<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;

class ShowNotification extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        $notification->markAsRead();

        if($notification->type=='App\Notifications\RegisteredUser')
        {
            return redirect($notification->data['url']);
        }
    }
}
