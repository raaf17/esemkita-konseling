<?php

namespace App\Modules\Konseling\Controllers;

use App\Controllers\BaseController;
use App\Modules\Guru\Models\GuruModel;
use App\Modules\Konseling\Models\KonselingModel;
use App\Modules\Layanan\Models\LayananModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class KonselingController extends BaseController
{
    protected $helpers = ['CIFunctions'];
    protected $konseling;
    protected $guru;
    protected $layanan;
    protected $db;

    public function __construct()
    {
        $this->konseling = new KonselingModel();
        $this->guru = new GuruModel();
        $this->layanan = new LayananModel();
    }

    public function index()
    {
        if (get_user()->role == 'SuperAdmin' || get_user()->role == 'Guru') {
            $count_status_pending = $this->konseling->where(['status' => 0])->countAllResults();
            $count_status_approve = $this->konseling->where(['status' => 1])->countAllResults();
            $count_status_unapprove = $this->konseling->where(['status' => 2])->countAllResults();
            $count_status_done = $this->konseling->whereIn('status', [1, 2])->countAllResults();
        } else {
            $db = \Config\Database::connect();
            $siswa = $db->table('siswa')->select('id')->where('nama_siswa', get_user()->nama)->get()->getRowObject();
            $count_status_pending = $this->konseling->where(['status' => 0])->where('id_siswa', $siswa->id)->countAllResults();
            $count_status_approve = $this->konseling->where(['status' => 1])->where('id_siswa', $siswa->id)->countAllResults();
            $count_status_unapprove = $this->konseling->where(['status' => 2])->where('id_siswa', $siswa->id)->countAllResults();
            $count_status_done = $this->konseling->whereIn('status', [1, 2])->where('id_siswa', $siswa->id)->countAllResults();
        }

        $data = [
            'title' => 'Konseling',
            'count_status_pending' => $count_status_pending,
            'count_status_approve' => $count_status_approve,
            'count_status_unapprove' => $count_status_unapprove,
            'count_status_done' => $count_status_done,
            'guru_option' => $this->guru->orderBy('nama_guru', 'asc')->findAll(),
            'layanan_option' => $this->layanan->orderBy('nama_layanan', 'asc')->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('App\Modules\Konseling\Views\index', $data);
    }

    public function listData()
    {
        $request = \Config\Services::request();
        $status = $this->request->getVar('status');
        $datamodel = new KonselingModel();

        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables($status);
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
                    $buttonDelete = '<a href="#" class="btn btn-sm btn-light-danger delete-konseling-btn p-0 p-2" data-id="' . $list->id . '"><i class="ki-outline ki-basket fs-4"></i></a></div>';

                    $actionButtons = (get_user()->role == 'SuperAdmin' || get_user()->role == 'Guru') ?
                        $buttonDetail . '<a href="#" class="btn btn-sm btn-light-success approve-konseling-btn me-2 p-0 p-2" data-id="' . $list->id . '"><i class="ki-outline ki-check-circle fs-4"></i></a>' .
                        '<a href="#" class="btn btn-sm btn-light-danger unapprove-konseling-btn p-0 p-2" data-id="' . $list->id . '"><i class="ki-outline ki-cross-square fs-4"></i></a></div>' :
                        $buttonDetail . $buttonDelete;
                }

                $row[] = '<div class="form-check form-check-sm form-check-custom form-check-solid"><input class="form-check-input" type="checkbox" value="1" /></div>';
                $row[] = $list->nama_siswa;
                $row[] = $list->nama_kelas;
                $row[] = $list->nama_layanan;
                $row[] = date('d M Y', strtotime($list->tanggal)) . ' - ' . date('h:i A', strtotime($list->jam));
                $row[] = '<span class="badge badge-' . ($list->status == 1 ? 'success' : ($list->status == 2 ? 'danger' : 'warning')) . '" style="padding: 6px 8px; border-radius: 25px;">' . ($list->status == 1 ? 'Disetujui' : ($list->status == 2 ? 'Ditolak' : 'Menunggu')) . '</span>';
                $row[] = $list->deskripsi;

                $row[] = $actionButtons;
                $data[] = $row;
            }

            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $datamodel->count_all(),
                "recordsFiltered" => $datamodel->count_filtered($status),
                "data" => $data
            ];

            return $this->response->setJSON($output);
        }
    }

    public function store()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $validation = \Config\Services::validation();

            $this->validate([
                'id_guru' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama guru harus diisi',
                    ]
                ],
                'id_layanan' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Jenis konseling harus diisi',
                    ]
                ],
                'tanggal' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Tanggal harus diisi',
                    ]
                ],
                'jam' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Jam harus diisi',
                    ]
                ],
                'deskripsi' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Deskripsi harus diisi',
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
                $db = \Config\Database::connect();
                $siswa = $db->table('siswa')->where('nama_siswa', get_user()->nama)->get()->getRowObject();
                $data = [
                    'id_kelas' => $siswa->id_kelas,
                    'id_guru' => $this->request->getVar('id_guru'),
                    'id_layanan' => $this->request->getVar('id_layanan'),
                    'id_siswa' => $siswa->id,
                    'tanggal' => $this->request->getVar('tanggal'),
                    'jam' => $this->request->getVar('jam'),
                    'deskripsi' => $this->request->getVar('deskripsi'),
                ];
                $query = $this->konseling->insert($data);

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Pengajuan konseling berhasil dibuat'
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

    public function delete()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $id = $request->getVar('id');
            $query = $this->konseling->delete($id);

            if ($query !== FALSE) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Pengajuan konseling berhasil dihapus'
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

    public function approve()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $id = $request->getVar('id');
            $query = $this->konseling->where('id', $id)->set(['status' => 1])->update();

            if ($query !== FALSE) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Konseling disetujui'
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

    public function unapprove()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $id = $request->getVar('id');
            $query = $this->konseling->where('id', $id)->set(['status' => 2])->update();

            if ($query !== FALSE) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Konseling ditolak'
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

    public function getDetail()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        if ($request->isAJAX()) {
            $id = $request->getVar('id');
            $data = $db->table('konseling')
                ->select('konseling.*, kelas.nama_kelas, guru.nama_guru, layanan.nama_layanan, siswa.nama_siswa')
                ->join('kelas', 'kelas.id = konseling.id_kelas', 'left')
                ->join('guru', 'guru.id = konseling.id_guru', 'left')
                ->join('layanan', 'layanan.id = konseling.id_layanan', 'left')
                ->join('siswa', 'siswa.id = konseling.id_siswa', 'left')
                ->where('konseling.id', $id)
                ->get()->getRowObject();

            if ($data) {
                return $this->response->setJSON([
                    'data' => $data
                ]);
            } else {
                return $this->response->setJSON([
                    'data' => null
                ]);
            }
        }
    }

    public function export()
    {
        $db = \Config\Database::connect();
        $kelas = $db->table('konseling')->select('konseling.*, kelas.nama_kelas, guru.nama_guru, layanan.nama_layanan, siswa.nama_siswa')
            ->join('kelas', 'kelas.id = konseling.id_kelas', 'left')
            ->join('guru', 'guru.id = konseling.id_guru', 'left')
            ->join('layanan', 'layanan.id = konseling.id_layanan', 'left')
            ->join('siswa', 'siswa.id = konseling.id_siswa', 'left')
            ->get()->getResultObject();

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();

        $tanggal = date('d-m-Y - H:i');
        $pengunduh = 'Pengunduh : ' . get_user()->nama;
        $tanggal_hari_ini = 'Tanggal : ' . $tanggal;
        if (get_settings()->setting_nama_sekolah == null) {
            $nama_sekolah = 'Sekolah Saya';
        } else {
            $nama_sekolah = get_settings()->setting_nama_sekolah;
        }

        $styleArrayCenterBold = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $styleArrayCenterBold20px = [
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $styleArraycenter = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $activeWorksheet->mergeCells('A1:H1');
        $activeWorksheet->setCellValue('A1', 'DATA KONSELING');
        $activeWorksheet->getStyle('A1')->applyFromArray($styleArrayCenterBold20px);

        $activeWorksheet->mergeCells('A2:H2');
        $activeWorksheet->setCellValue('A2', $nama_sekolah);
        $activeWorksheet->getStyle('A2')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('A3:H3');
        $activeWorksheet->setCellValue('A3', $tanggal_hari_ini);
        $activeWorksheet->getStyle('A3')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('A4:H4');
        $activeWorksheet->setCellValue('A4', $pengunduh);
        $activeWorksheet->getStyle('A4')->applyFromArray($styleArraycenter);

        $activeWorksheet->getStyle('A1:H1')->getFont()->setName('Consolas');
        $activeWorksheet->getStyle('A2:H2')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('A3:H3')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('A4:H4')->getFont()->setName('Consolas')->setSize(10);

        foreach (range('A', 'H') as $columnID) {
            $activeWorksheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '5a8ed1',
                ],
                'endColor' => [
                    'argb' => '5a8ed1',
                ],
            ],
        ];

        $activeWorksheet->getStyle('A6:H6')->applyFromArray($styleArray);
        $activeWorksheet->setCellValue('A6', 'NO');
        $activeWorksheet->setCellValue('B6', 'NAMA SISWA');
        $activeWorksheet->setCellValue('C6', 'KELAS');
        $activeWorksheet->setCellValue('D6', 'JENIS KONSELING');
        $activeWorksheet->setCellValue('E6', 'TANGGAL');
        $activeWorksheet->setCellValue('F6', 'JAM');
        $activeWorksheet->setCellValue('G6', 'STATUS');
        $activeWorksheet->setCellValue('H6', 'DESKRIPSI');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $column = 7;
        $no = 1;
        foreach ($kelas as $key => $value) {
            $activeWorksheet->setCellValue('A' . $column, $no);
            $activeWorksheet->setCellValue('B' . $column, $value->nama_siswa);
            $activeWorksheet->setCellValue('C' . $column, $value->nama_kelas);
            $activeWorksheet->setCellValue('D' . $column, $value->nama_layanan);
            $activeWorksheet->setCellValue('E' . $column, $value->tanggal);
            $activeWorksheet->setCellValue('F' . $column, $value->jam);
            if ($value->status == 1) {
                $status = 'Disetujui';
            } elseif ($value->status == 2) {
                $status = 'Ditolak';
            } else {
                $status = 'Menunggu';
            }
            $activeWorksheet->setCellValue('G' . $column, $status);
            $activeWorksheet->setCellValue('H' . $column, $value->deskripsi);

            $column++;
            $no++;
        }

        $dirPath = 'report/export/';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $filename = 'data-konseling_' . date('d-m-y-H-i-s') . '.xlsx';
        $filePath = $dirPath . $filename;

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
}
