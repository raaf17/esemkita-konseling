<?php

namespace App\Modules\Grafik\Controllers;

use App\Controllers\BaseController;
use App\Modules\Kelas\Models\KelasModel;
use App\Modules\Siswa\Models\SiswaModel;

class GrafikController extends BaseController
{
    protected $helpers = ['CIFunctions'];
    protected $siswa;
    protected $kelas;

    public function __construct()
    {
        $this->siswa = new SiswaModel();
        $this->kelas = new KelasModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Grafik',
            'pilihan_kelas' => $this->kelas->orderBy('nama_kelas', 'asc')->findAll(),
        ];

        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $data = [
                'siswa_data_jk' => $this->siswa->DashboardJenisKelaminSiswa(),
                'siswa_data_agama' => $this->siswa->DashboardAgamaSiswa(),
                'siswa_data_status' => $this->siswa->DashboardStatusSiswa(),
            ];
            return $this->response->setJSON(['data' => $data]);
        }

        return view('App\Modules\Grafik\Views\index', $data);
    }
}
