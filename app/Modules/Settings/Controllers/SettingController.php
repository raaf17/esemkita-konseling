<?php

namespace App\Modules\Settings\Controllers;

use App\Controllers\BaseController;
use App\Libraries\GenerateUuid;
use App\Modules\Settings\Models\Setting;

class SettingController extends BaseController
{
    protected $helpers = ['CIFunctions'];

    public function index()
    {
        $id = new GenerateUuid();
        $data = [
            'title' => 'Pengaturan',
            'validation' => \Config\Services::validation(),
            'id' => $id->uuid(),
        ];

        return view('App\Modules\Settings\Views\index', $data);
    }

    public function updateGeneralSettings()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $validation = \Config\Services::validation();

            $this->validate([
                'setting_meta_title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama meta title harus diisi'
                    ]
                ],
            ]);

            if ($validation->run() === FALSE) {
                $errors = $validation->getErrors();

                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'error' => $errors
                ]);
            } else {
                $settings = new Setting();
                $setting_id = $settings->asObject()->first()->id;
                $update = $settings->where('id', $setting_id)
                    ->set([
                        'setting_meta_title' => $request->getVar('setting_meta_title'),
                        'setting_meta_description' => $request->getVar('setting_meta_description'),
                        'setting_meta_keyword' => $request->getVar('setting_meta_keyword'),
                    ])->update();

                if ($update) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Pengaturan umum berhasil di update'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'token' => csrf_hash(),
                        'msg' => 'Ada yang tidak beres'
                    ]);
                }
            }
        }
    }

    public function updateSchoolSettings()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $validation = \Config\Services::validation();

            $this->validate([
                'setting_nama_sekolah' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama Sekolah harus diisi'
                    ]
                ],
            ]);

            if ($validation->run() === FALSE) {
                $errors = $validation->getErrors();

                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'error' => $errors
                ]);
            } else {
                $settings = new Setting();
                $setting_id = $settings->asObject()->first()->id;
                $update = $settings->where('id', $setting_id)
                    ->set([
                        'setting_nama_sekolah' => $request->getVar('setting_nama_sekolah'),
                        'setting_kepala_sekolah' => $request->getVar('setting_kepala_sekolah'),
                        'setting_alamat' => $request->getVar('setting_alamat'),
                        'setting_latitude' => $request->getVar('setting_latitude'),
                        'setting_longitude' => $request->getVar('setting_longitude'),
                        'setting_email' => $request->getVar('setting_email'),
                        'setting_telepon' => $request->getVar('setting_telepon'),
                    ])->update();

                if ($update) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Pengaturan sekolah berhasil di update'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'token' => csrf_hash(),
                        'msg' => 'Ada yang tidak beres'
                    ]);
                }
            }
        }
    }

    public function updateLogoSetting()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $settings = new Setting();
            $path = 'img/icons/';
            $file = $request->getFile('setting_logo');
            $setting_data = $settings->asObject()->first();
            $old_setting_logo = $setting_data->setting_logo;
            $new_filename = 'BK' . $file->getRandomName();

            if ($file->move($path, $new_filename)) {
                if ($old_setting_logo != null && file_exists($path . $old_setting_logo)) {
                    unlink($path . $old_setting_logo);
                }

                $update = $settings->where('id', $setting_data->id)
                    ->set(['setting_logo' => $new_filename])
                    ->update();

                if ($update) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Selesai!, Logo BK berhasil diupdate'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'token' => csrf_hash(),
                        'msg' => 'Ada yang tidak beres saat upload logo'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'msg' => 'Ada yang tidak beres saat upload logo baru'
                ]);
            }
        }
    }

    public function updateFaviconSetting()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $settings = new Setting();
            $path = 'img/icons/';
            $file = $request->getFile('setting_favicon');
            $setting_data = $settings->asObject()->first();
            $old_setting_logo = $setting_data->setting_favicon;
            $new_filename = 'BK' . $file->getRandomName();

            if ($file->move($path, $new_filename)) {
                if ($old_setting_logo != null && file_exists($path . $old_setting_logo)) {
                    unlink($path . $old_setting_logo);
                }

                $update = $settings->where('id', $setting_data->id)
                    ->set(['setting_favicon' => $new_filename])
                    ->update();

                if ($update) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Selesai!, Favicon BK berhasil diupdate'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'token' => csrf_hash(),
                        'msg' => 'Ada yang tidak beres saat upload favicon'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'msg' => 'Ada yang tidak beres saat upload favicon baru'
                ]);
            }
        }
    }
}
