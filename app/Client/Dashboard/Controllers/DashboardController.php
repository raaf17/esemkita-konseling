<?php

namespace App\Modules\Dashboard\Controllers;

use App\Controllers\BaseController;
use App\Modules\Konseling\Models\KonselingAllModel;
use App\Modules\Konseling\Models\KonselingModel;
use App\Modules\Laporan\Models\LaporanModel;
use App\Modules\Users\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    protected $helpers = ['CIFunctions'];
    protected $konseling;
    protected $laporan;
    protected $user;

    public function __construct()
    {
        $this->konseling = new KonselingModel();
        $this->laporan = new LaporanModel();
        $this->user = new UserModel();
    }

    public function index()
    {
        $id = get_user()->id;
        $db = \Config\Database::connect();
        $siswa = $db->table('siswa')->select('id')->where('nama_siswa', get_user()->nama)->get()->getRowObject();
        if (get_user()->role == 'SuperAdmin' || get_user()->role == 'Guru') {
            $count_status_konseling_all = $db->table('konseling')->countAllResults();
            $count_status_laporan_all = $db->table('laporan')->countAllResults();
        } else {
            $siswa = $db->table('siswa')->select('id')->where('nama_siswa', get_user()->nama)->get()->getRowObject();
            $count_status_konseling_all = $db->table('konseling')->where('id_siswa', $siswa->id)->countAllResults();
            $count_status_laporan_all = $db->table('laporan')->where('id_siswa', $siswa->id)->countAllResults();
        }
        $data = [
            'title' => 'Dashboard',
            'konselings' => $this->konseling->allKonseling(),
            'laporans' => $this->laporan->allLaporan(),
            'count_status_konseling_all' => $count_status_konseling_all,
            'count_status_laporan_all' => $count_status_laporan_all,
            'user' => $this->user->detailUser($id)
        ];

        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $konselingData = $this->konseling->DashboardKonseling();
            return $this->response->setJSON(['data' => $konselingData]);
        }

        return view('App\Modules\Dashboard\Views\index', $data);
    }

    public function listDataAll()
    {
        $request = \Config\Services::request();
        $datamodel = new KonselingModel();

        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getPost("start");

            foreach ($lists as $list) {
                $no++;
                $row = [];

                if ($list->status == 1 || $list->status == 2) {
                    $buttonDetail = '<a href="#" class="btn btn-sm btn-light-primary btn-center p-0 p-2 detail-konseling-btn" data-id="' . $list->id . '"><i class="ki-outline ki-eye fs-4"></i></a>';
                    $actionButtons = $buttonDetail;
                } else {
                    $buttonDetail = '<div class="d-flex"><a href="#" class="btn btn-sm btn-light-primary p-0 p-2 detail-konseling-btn me-2" data-id="' . $list->id . '"><i class="ki-outline ki-eye fs-4"></i></a>';

                    $actionButtons = (get_user()->role == 'SuperAdmin' || get_user()->role == 'Guru') ?
                        $buttonDetail . '<a href="#" class="btn btn-sm btn-light-success approve-konseling-btn me-2 p-0 p-2" data-id="' . $list->id . '"><i class="ki-outline ki-check-circle fs-4"></i></a>' .
                        '<a href="#" class="btn btn-sm btn-light-danger unapprove-konseling-btn p-0 p-2" data-id="' . $list->id . '"><i class="ki-outline ki-cross-square fs-4"></i></a></div>' :
                        $buttonDetail;
                }

                $row[] = '<div class="form-check form-check-sm form-check-custom form-check-solid"><input class="form-check-input" type="checkbox" value="1" /></div>';
                $row[] = $list->nama_layanan;
                $row[] = date('d F Y', strtotime($list->tanggal)) . ' - ' . $list->jam;
                $row[] = '<span class="badge badge-' . ($list->status == 1 ? 'success' : ($list->status == 2 ? 'danger' : 'warning')) . '" style="padding: 6px 8px; border-radius: 25px;">' . ($list->status == 1 ? 'Disetujui' : ($list->status == 2 ? 'Ditolak' : 'Menunggu')) . '</span>';
                $row[] = $list->deskripsi;

                $row[] = $actionButtons;
                $data[] = $row;
            }

            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $datamodel->count_all(),
                "recordsFiltered" => $datamodel->count_filtered(),
                "data" => $data
            ];

            return $this->response->setJSON($output);
        }
    }
}
