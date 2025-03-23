<?php

namespace App\Libraries;

use App\Modules\LoginActivity\Models\LoginActivityModel;

class CIAuth
{
    public static function setCIAuth($result)
    {
        $login_activity = new LoginActivityModel();
        $session = session();
        $array = ['logged_in' => true];
        $userdata = $result;
        $session_data = [
            'userdata' => $userdata,
            'logged_in' => $array,
        ];
        $session->set($session_data);

        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'IP tidak dikenali';
        }

        $browser = '';
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Edg')) {
            $browser = 'Microsoft Edge';
        } else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape')) {
            $browser = 'Netscape';
        } else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) {
            $browser = 'Firefox';
        } else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) {
            $browser = 'Chrome';
        } else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera')) {
            $browser = 'Opera';
        } else if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') || strpos($_SERVER['HTTP_USER_AGENT'], 'Trident')) {
            $browser = 'Internet Explorer';
        } else {
            $browser = 'Lainnya';
        }


        $deteksi_perangkat = preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
        if ($deteksi_perangkat) {
            $perangkat = "HP/Tablet";
        } else {
            $perangkat = "Komputer/Laptop";
        }

        $params = [
            'id_user' => $userdata->id,
            'action' => 'Berhasil login',
            'ip' => $ipaddress,
            'browser' => $browser,
            'perangkat' => $perangkat,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ];
        $login_activity->insert($params);
    }

    public static function id()
    {
        $session = session();

        if ($session->has('logged_in') && $session->has('userdata')) {
            $userdata = $session->get('userdata');
            return $userdata->id ?? null;
        }

        return null;
    }

    public static function check()
    {
        $session = session();

        return $session->has('logged_in');
    }

    public static function forget()
    {
        $session = session();
        $session->remove('logged_in');
        $session->remove('userdata');
    }

    public static function user()
    {
        $session = session();

        if ($session->has('logged_in')) {
            if ($session->has('userdata')) {
                return $session->get('userdata');
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
