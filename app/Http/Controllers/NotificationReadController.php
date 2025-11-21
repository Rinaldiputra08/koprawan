<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationReadController extends Controller
{
    public function __invoke(Request $request)
    {
        foreach(auth()->user()->unreadNotifications as $notif){
            if($notif->data['no_transaksi'] == $request->id){
                $notif->markAsRead();
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil menandai sudah baca'.$request->id
        ]);
    }
}
