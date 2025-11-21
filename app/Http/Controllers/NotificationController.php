<?php

namespace App\Http\Controllers;

use App\Models\User;

class NotificationController extends Controller
{
    public function global()
    {

        $notifs = auth()->user()->unReadNotifications;

        $id_user = [];
        foreach ($notifs as $notif) {
            array_push($id_user, $notif->data['user']);
        }

        $users = User::whereIn('username', $id_user)->get();

        $list_notif = '';
        if (count($notifs) == 0) {
            $list_notif .= '<div class="media d-flex align-items-start">Tidak ada notifikasi</div>';
        } else {
            foreach ($notifs as $notif) {
                foreach ($users as $user) {
                    if ($user->username == $notif->data['user']) {
                        $list_notif .= '<a class="d-flex" href="' . url($notif->data['url']) . encrypt($notif->data['no_transaksi']) . '">
                        <div class="media d-flex align-items-start">
                        <div class="media-left">
                        <div class="avatar"><img src="' . asset('storage/images/profile/small_' . $user->foto) . '" alt="avatar" width="32" height="32"></div>
                        </div>
                        <div class="media-body">
                        <p class="media-heading"><span class="font-weight-bolder">' . $user->name . '</p>
                        <small class="text-dark">' . $notif->data['message'] . '</small></br>
                        <span class="badge badge-info mb-50 mt-50">' . $notif->data['jenis'] . '</span>
                        </div>
                        </div>
                        </a>';
                    }
                }
            }
        }
        return ["count" => count($notifs), "data" => $list_notif];
    }

    public function other()
    {
        $list_notif = '';
        $list_notif .= '<div class="media d-flex align-items-start">Belum ada leads</div>';

        return ['count' => 0, 'data' => $list_notif];
    }
}
