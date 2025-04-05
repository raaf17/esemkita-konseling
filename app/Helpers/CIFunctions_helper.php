<?php

use App\Libraries\CIAuth;
use App\Modules\Users\Models\UserModel;
use App\Modules\Settings\Models\Setting;
use Mpdf\Mpdf;

if (!function_exists('get_user')) {
    function get_user()
    {
        if (CIAuth::check()) {
            $user = new UserModel();

            return $user->asObject()->where('id', CIAuth::id())->first();
        } else {
            return null;
        }
    }
}

if (!function_exists('get_settings')) {
    function get_settings()
    {
        $settings = new Setting();
        $settings_data = $settings->asObject()->first();

        if (!$settings_data) {
            $data = array(
                'setting_meta_title' => 'Bimbingan Konseling SMKN 1 Boyolangu',
                'setting_meta_description' => null,
                'setting_meta_keyword' => null,
                'setting_nama_sekolah' => 'SMKN 1 Boyolangu',
                'setting_kepala_sekolah' => 'Trisno Wibowo',
                'setting_alamat' => null,
                'setting_latitude' => null,
                'setting_longitude' => null,
                'setting_email' => 'smkn1boyolangu@yahoo.co.id',
                'setting_telepon' => null,
                'setting_logo' => null,
                'setting_favicon' => null
            );

            $settings->save($data);
            $new_settings_data = $settings->asObject()->first();

            return $new_settings_data;
        } else {
            return $settings_data;
        }
    }
}

if (!function_exists('get_main_menu')) {
    function get_main_menu()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('sys_menu')
            ->select('sys_menu.*, sys_akses.akses, sys_akses.tambah, sys_akses.edit, sys_akses.hapus, sys_role.role')
            ->join('sys_akses', 'sys_akses.kode_menu = sys_menu.kode_menu', 'left')
            ->join('sys_role', 'sys_role.role = sys_akses.role', 'left')
            ->where([
                'sys_role.role' => get_user()->role,
                'sys_akses.akses' => '1',
                'level' => 'main_menu',
                'aktif' => '1',
            ])
            ->orderBy('sys_menu.kode_menu', 'ASC');

        return $builder->get()->getResultObject();
    }
}

if (!function_exists('get_sub_menu')) {
    function get_sub_menu()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('sys_menu')
            ->select('sys_menu.*, sys_akses.akses, sys_akses.tambah, sys_akses.edit, sys_akses.hapus, sys_role.role')
            ->join('sys_akses', 'sys_akses.kode_menu = sys_menu.kode_menu', 'left')
            ->join('sys_role', 'sys_role.role = sys_akses.role', 'left')
            ->where([
                'sys_role.role' => get_user()->role, 
                'sys_akses.akses' => '1', 
                'level' => 'sub_menu',
                'aktif' => '1',
            ])
            ->orderBy('sys_menu.kode_menu', 'ASC');

        return $builder->get()->getResultObject();
    }
}

if (!function_exists('input_checkbox')) {
    function input_checkbox($no, $aktif, $jenis)
    {
        $isChecked = '';
        if ($jenis == 'akses' && $aktif->akses == '1') {
            $isChecked = 'checked';
        } elseif ($jenis == 'tambah' && $aktif->tambah == '1') {
            $isChecked = 'checked';
        } elseif ($jenis == 'edit' && $aktif->edit == '1') {
            $isChecked = 'checked';
        } elseif ($jenis == 'hapus' && $aktif->hapus == '1') {
            $isChecked = 'checked';
        }

        if ($jenis == 'akses') {
            $nama_akses = 'Akses';
        } elseif ($jenis == 'tambah') {
            $nama_akses = 'Tambah';
        } elseif ($jenis == 'edit') {
            $nama_akses = 'Edit';
        } elseif ($jenis == 'hapus') {
            $nama_akses = 'Hapus';
        }

        return '<td>
                    <div class="d-flex">
                        <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid me-4">
                            <input class="form-check-input" type="checkbox" value="" name="' . $jenis . $no . '" ' . $isChecked . ' id="customSwitch' . $jenis . $no . '" />
                            <span class="form-check-label">' . $nama_akses . '</span>
                        </label>
                    </div>
                </td>';
    }
}

if (!function_exists('current_route_name')) {
    function current_route_name()
    {
        $router = \CodeIgniter\Config\Services::router();
        $route_name = $router->getMatchedRouteOptions()['as'];

        return $route_name;
    }
}

if (!function_exists('count_data')) {
    function count_data($table)
    {
        $db = \Config\Database::connect();
        return $db->table($table)->countAllResults();
    }
}
