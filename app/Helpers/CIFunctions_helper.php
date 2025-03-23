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
            ->where(['sys_role.role' => get_user()->role, 'sys_akses.akses' => '1', 'level' => 'main_menu'])
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
            ->where(['sys_role.role' => get_user()->role, 'sys_akses.akses' => '1', 'level' => 'sub_menu'])
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

if (!function_exists('createPdf')) {
    function createPdf(array $data = [])
    {
        $config = [
            'data'          => $data['data'] ?? '',
            'paper_size'    => $data['paper_size'] ?? '',
            'file_name'     => $data['file_name'] ?? '',
            'margin'        => $data['margin'] ?? '',
            'stylesheet'    => $data['stylesheet'] ?? '',
            'font_face'     => $data['font_face'] ?? '',
            'font_size'     => $data['font_size'] ?? '',
            'orientation'   => $data['orientation'] ?? '',
            'margin_hf'     => $data['margin_hf'] ?? '',
            'download'      => isset($data['download']) && $data['download'] === true,
            'title'         => $data['title'] ?? '',
            'header'        => $data['header'] ?? '',
            'footer'        => $data['footer'] ?? '',
            'json'          => isset($data['json']) && $data['json'] === true,
            'kwt'           => isset($data['kwt']) && $data['kwt'] === true,
            'save'          => isset($data['save']) && $data['save'] === true,
        ];

        $explode     = explode(' ', $config['margin']);
        $explode_hf  = explode(' ', $config['margin_hf']);
        $orientation = $config['orientation'] ?: 'L';
        $font_face   = $config['font_face'] ?: '';
        $font_size   = $config['font_size'] ?: '';
        $file_name   = $config['file_name'] ?: 'Laporan' . date('dMY');
        $title       = $config['title'] ?: 'Laporan';
        $header      = $config['header'] ?: '';
        $footer      = $config['footer'] ?: '';
        $json        = !empty($config['json']);

        ob_clean();
        $pdf = new Mpdf([
            'format' => $config['paper_size'],
            'orientation' => $orientation,
            'default_font_size' => $font_size,
            'default_font' => $font_face,
            'margin_left' => $explode[3] ?? '',
            'margin_right' => $explode[1] ?? '',
            'margin_top' => $explode[0] ?? '',
            'margin_bottom' => $explode[2] ?? '',
            'margin_header' => $explode_hf[0] ?? '',
            'margin_footer' => $explode_hf[1] ?? '',
        ]);

        // Load stylesheet
        $xstylesheet = '';
        if (is_array($config['stylesheet'])) {
            foreach ($config['stylesheet'] as $stylesheet) {
                $xstylesheet .= file_get_contents($stylesheet);
            }
        } else {
            $xstylesheet = file_get_contents($config['stylesheet']);
        }

        $pdf->WriteHTML($xstylesheet, 1);
        $pdf->SetTitle($title);
        $pdf->SetHTMLHeader($header);
        $pdf->SetHTMLFooter($footer);
        $pdf->setFooter('{PAGENO} / {nb}');
        $pdf->WriteHTML($config['data']);

        ob_end_clean();

        // Output PDF
        if ($config['save']) {
            $pdf->Output($file_name . '.pdf', 'F');
        } else {
            if ($json) {
                $pdfString = $pdf->Output('', 'S');
                return json_encode([
                    'success' => true,
                    'id' => $file_name,
                    'message' => 'Berhasil',
                    'record' => "data:application/pdf;base64," . base64_encode($pdfString)
                ]);
            } else {
                $pdf->Output($file_name . '.pdf', $config['download'] ? 'D' : 'I');
            }
        }
    }
}
