<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Firebase;
use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'firebase_iid' => 'required'
        ]);
        
        $user = $request->user()->load('foto');
        // create or update firebase iid
        Firebase::updateOrCreate(
            [
                'instance_id' => $request->firebase_iid,
            ],
            ['last_akses' => now(), 'karyawan_id' => $user->id]

        );

        return responseMessage('success', 'Berhasil menambah atau memperbaharui data');
    }
}
