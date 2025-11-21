<?php

use App\Models\Menu;
use App\Models\SetupApplication;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

if (!function_exists('convertDate')) {
    function convertDate($date)
    {
        $date_indo = explode("-", $date);
        if (count($date_indo) !== 3) {
            return '';
        }
        return "$date_indo[2]-$date_indo[1]-$date_indo[0]";
    }
}

if (!function_exists('validasiTanggal')) {
    function validasiTanggal($date, $indo = true)
    {
        $date_indo = explode("-", $date);
        if ($indo && (count($date_indo) !== 3 or strlen($date_indo[2]) != 4 or strlen($date_indo[1]) != 2 or strlen($date_indo[0]) != 2)) {
            throw new \Exception('invalid date');
        } else if (!$indo && (count($date_indo) !== 3 or strlen($date_indo[2]) != 2 or strlen($date_indo[1]) != 2 or strlen($date_indo[0]) != 4)) {
            throw new \Exception('invalid date');
        }

        return true;
    }
}

if (!function_exists('getNavigations')) {
    function getNavigations()
    {
        if (Cache::has('navigation')) {
            $nav = Cache::get('navigation');
        } else {
            $nav = Menu::with(['subMenus' => function ($query) {
                $query->orderBy('no_urut');
            }])->orderBy('jenis_bisnis')
                ->where('aktif', 1)
                ->whereNull('main_menu')
                ->get()
                ->groupBy('jenis_bisnis');

            Cache::forever('navigation', $nav);
        }
        return $nav;
    }
}

if (!function_exists('getConfig')) {
    function getConfig($key)
    {
        if (Cache::has('config')) {
            $config = Cache::get('config');
        } else {
            $config = SetupApplication::all()->mapWithKeys(function ($item) {
                $value = $item->value;
                if (!is_numeric($value) and json_decode($value) and json_last_error() == JSON_ERROR_NONE) {
                    $value = json_decode($value);
                } elseif (is_countable($value)) {
                    $value = (int) $value;
                }
                return [$item->name => $value];
            });
            Cache::forever('config', $config);
        }

        return $config[$key] ?? null;
    }
}

if (!function_exists('getCurrentPeriode')) {
    function getCurrentPeriode(){
        $tanggal_closing = getConfig('tanggal_closing_transaksi');
        $hari_ini = now();
        $periode = date('Ym');

        if ((int)$hari_ini->format('d') > (int)$tanggal_closing) {
            $periode += 1;
        }

        return $periode;
    }
}

if (!function_exists('getFullDate')) {
    function getFullDate($date, $day = false)
    {
        $date = date('Y-m-d', strtotime($date));

        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        $hari = array(
            'Sun' => 'Minggu',
            'Mon' => 'Senin',
            'Tue' => 'Selasa',
            'Wed' => 'Rabu',
            'Thu' => 'Kamis',
            'Fri' => 'Jum\'at',
            'Sat' => 'Sabtu'
        );

        $pecahkan = explode('-', $date);
        $fulldate = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];

        if ($day) {
            return $hari[date('D', strtotime($date))] . ', ' . $fulldate;
        }

        return $fulldate;
    }
}

if (!function_exists('getEnumValues')) {
    function getEnumValues($table, $column)
    {
        $type = DB::select(DB::raw("SHOW COLUMNS FROM $table WHERE Field = '{$column}'"))[0]->Type;
        preg_match('/^enum((.*))$/', $type, $matches);
        $enum = [];
        foreach (explode(',', $matches[1]) as $value) {
            $v2 = trim(trim($value, "('"), "')");
            // $v2 = trim(trim(trim($value, "'"), "("), ")");
            $enum[] = $v2;
        }
        return $enum;
        // return $matches;
    }
}

if (!function_exists('numbering')) {
    function numbering($table, $key, $format, $digit = 4)
    {
        $max = DB::table($table)
            ->select(DB::raw("MAX($key) as kode"))
            ->where("$key", "like", "$format%")
            ->first();

        $last_nomor = substr($max->kode, strlen($format), $digit);
        $next_nomor = $format . sprintf("%0{$digit}s",(int) $last_nomor + 1);
        return $next_nomor;
    }
}

if (!function_exists('formatNomorHp')) {
    function formatNomorHp($nohp)
    {
        if (substr($nohp, 0, 1) == '0') {
            $nohp = '62' . trim(substr(preg_replace("/[^0-9]/", "", $nohp), 1, 12));
        }
        return $nohp;
    }
}

if (!function_exists('selisihHari')) {
    function selilihHari($date1, $date2)
    {
        $diff = abs(strtotime($date2) - strtotime($date1));

        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

        return $days;
    }
}

if (!function_exists('addDays')) {
    function addDays($date, $days = 1, $format = 'Y-m-d')
    {
        if ($days < 0) {
            return date($format, strtotime(convertDate($date) . " $days days"));
        }
        return date($format, strtotime(convertDate($date) . " + $days days"));
    }
}

if (!function_exists('numberFormat')) {
    function numberFormat($number, $prefix = null)
    {
        return $prefix . number_format($number, 0, ',', '.');
    }
}

if (!function_exists('getListMonth')) {
    function getListMonth($tgl_awal, $tgl_akhir): array
    {
        $tgl_awal = new DateTime($tgl_awal);
        $tgl_akhir = new DateTime($tgl_akhir);
        $list = [];
        if ($tgl_awal->format('Y') == $tgl_akhir->format('Y')) {
            for ($i = (int)$tgl_awal->format('m'); $i <= (int)$tgl_akhir->format('m'); $i++) {
                array_push($list, sprintf('%02s', $i) . '-' . $tgl_awal->format('Y'));
            }
        } else {
            for ($i = (int)$tgl_awal->format('m'); $i <= 12; $i++) {
                array_push($list, sprintf('%02s', $i) . '-' . $tgl_awal->format('Y'));
            }
            if ($tgl_awal->format('Y') - $tgl_akhir->format('Y') == 1) {
                for ($i = 1; $i <= (int)$tgl_akhir->format('m'); $i++) {
                    array_push($list, sprintf('%02s', $i) . '-' . $tgl_akhir->format('Y'));
                }
            } else {
                for ($i = (int)$tgl_awal->format('Y') + 1; $i <= (int)$tgl_akhir->format('Y') - 1; $i++) {
                    for ($b = 1; $b <= 12; $b++) {
                        array_push($list, sprintf('%02s', $b) . '-' . $i);
                    }
                }
                for ($i = 1; $i <= (int)$tgl_akhir->format('m'); $i++) {
                    array_push($list, sprintf('%02s', $i) . '-' . $tgl_akhir->format('Y'));
                }
            }
        }

        return $list;
    }
}

if (!function_exists('responseMessage')) {
    function responseMessage($status = 'success', $message = 'Data berhasil disimpan', array $errors = [])
    {
        if (!$errors) {
            if ($message instanceof Throwable) {
                $message = ($message->getCode() or env('APP_ENV') == 'local') ? $message->getMessage() : 'Terjadi kesalahan, hubungi Tim IT';
            }

            return response()->json([
                'status' => $status,
                'message' => $message
            ]);
        }

        return response()->json([
            'messages' => 'The given data was invalid',
            'errors' => $errors
        ]);
    }
}

if (!function_exists('responseNotFound')) {
    function responseNotFound()
    {
        return response()->json([
            'status' => 'error',
            'message' => 'not found',
            'data' => null
        ], 404);
    }
}
if (!function_exists('responseError')) {
    function responseError($message = 'Terjadi kesalahan', $data = null)
    {
        if ($message instanceof Throwable) {
            $message = ($message->getCode() or env('APP_ENV') == 'local') ? $message->getMessage() : 'Terjadi kesalahan, silahkan coba lagi';
        }
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ], 403);
    }
}

if (!function_exists('markAsRead')) {
    function markAsRead(User $user, $no_transaksi)
    {
        foreach ($user->unreadNotifications as $notif) {
            if ($notif->data['no_transaksi'] == $no_transaksi) {
                $notif->markAsRead();
            }
        }
    }
}

if (!function_exists('getMonth')) {
    function getMonth($date, $index = false)
    {
        $months = array(
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        if (!$index) {
            $index = date('m', strtotime($date));
        }
        return $months[(int)$index];
    }
}

if (!function_exists('devide')) {
    function devide($total, $pembagi, $presisi = null)
    {
        if ($pembagi) {
            $devide = $total / $pembagi;

            if ($presisi !== null) {
                return round($devide, $presisi);
            }

            return $devide;
        }
        return 0;
    }
}

if (!function_exists('dateFormat')) {
    /**
     * Format date
     * @param string $date with a valid date format
     * @param string $format
     * @return string
     */
    function dateFormat($date, $format)
    {
        return date($format, strtotime($date));
    }
}
