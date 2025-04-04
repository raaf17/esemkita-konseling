<?php

namespace App\Modules\Laporan\Controllers;

use App\Controllers\BaseController;
use App\Modules\Laporan\Models\LaporanApproveModel;
use App\Modules\Laporan\Models\LaporanDoneModel;
use App\Modules\Laporan\Models\LaporanModel;
use App\Modules\Laporan\Models\LaporanUnapproveModel;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController extends BaseController
{
    protected $helpers = ['CIFunctions'];
    protected $laporan;
    protected $db;

    public function __construct()
    {
        $this->laporan = new LaporanModel();
    }

    public function index()
    {
        if (get_user()->role == 'SuperAdmin' || get_user()->role == 'Guru') {
            $count_status_pending = $this->laporan->where(['status' => 0])->countAllResults();
            $count_status_approve = $this->laporan->where(['status' => 1])->countAllResults();
            $count_status_unapprove = $this->laporan->where(['status' => 2])->countAllResults();
            $count_status_done = $this->laporan->whereIn('status', [1, 2])->countAllResults();
        } else {
            $db = \Config\Database::connect();
            $siswa = $db->table('siswa')->select('id')->where('nama_siswa', get_user()->nama)->get()->getRowObject();
            $count_status_pending = $this->laporan->where(['status' => 0])->where('id_siswa', $siswa->id)->countAllResults();
            $count_status_approve = $this->laporan->where(['status' => 1])->where('id_siswa', $siswa->id)->countAllResults();
            $count_status_unapprove = $this->laporan->where(['status' => 2])->where('id_siswa', $siswa->id)->countAllResults();
            $count_status_done = $this->laporan->whereIn('status', [1, 2])->where('id_siswa', $siswa->id)->countAllResults();
        }

        $data = [
            'title' => 'Laporan',
            'count_status_pending' => $count_status_pending,
            'count_status_approve' => $count_status_approve,
            'count_status_unapprove' => $count_status_unapprove,
            'count_status_done' => $count_status_done,
            'validation' => \Config\Services::validation(),
        ];

        return view('App\Modules\Laporan\Views\index', $data);
    }

    public function listData()
    {
        $request = \Config\Services::request();
        $status = $this->request->getVar('status');
        $datamodel = new LaporanModel();

        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables($status);
            $data = [];
            $no = $request->getPost("start");

            foreach ($lists as $list) {
                $no++;
                $row = [];

                if ($list->status == 1 || $list->status == 2) {
                    $buttonDetail = '<a href="#" class="btn btn-primary detail-laporan-btn" data-id="' . $list->id . '"><i class="fas fa-eye"></i></a>';
                    $actionButtons = $buttonDetail;
                } else {
                    $buttonDetail = '<div class="d-flex"><a href="#" class="btn btn-primary detail-laporan-btn mr-1" data-id="' . $list->id . '"><i class="fas fa-eye"></i></a>';
                    $buttonDelete = '<a href="#" class="btn btn-danger delete-laporan-btn" data-id="' . $list->id . '"><i class="fas fa-trash"></i></a></div>';

                    $actionButtons = (get_user()->role == 'SuperAdmin' || get_user()->role == 'Guru') ?
                        $buttonDetail . '<a href="#" class="btn btn-success approve-laporan-btn mr-1" data-id="' . $list->id . '"><i class="fas fa-check-circle"></i></a>' .
                        '<a href="#" class="btn btn-danger unapprove-laporan-btn" data-id="' . $list->id . '"><i class="fas fa-circle-xmark"></i></a></div>' :
                        $buttonDetail . $buttonDelete;
                }

                $row[] = '<div class="custom-checkbox custom-control"><input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" name="id[]" value="' . $list->id . '"><label for="checkbox-2" class="custom-control-label">&nbsp;</label></div>';
                $row[] = $list->nama_terlapor;
                $row[] = $list->judul;
                $row[] = Carbon::parse($list->tanggal)->format('M j, Y');
                $row[] = $list->lokasi;
                $row[] = '<span class="badge badge-' . ($list->status == 1 ? 'success' : ($list->status == 2 ? 'danger' : 'warning')) . '" style="padding: 6px 8px; border-radius: 25px;">' . ($list->status == 1 ? 'Diproses' : ($list->status == 2 ? 'Ditolak' : 'Menunggu')) . '</span>';
                $row[] = '<img src="' . ($list->foto == null ? base_url() . '/img/bukti_laporan/sample.jpg' : base_url() . '/img/bukti_laporan/' . $list->foto) . '" style="width: 50px; border-radius: 2px;" alt="Bukti" />';

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
                'nama_terlapor' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama terlapor harus diisi',
                    ]
                ],
                'judul' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Pelanggaran harus diisi',
                    ]
                ],
                'tanggal' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Tanggal harus diisi',
                    ]
                ],
                'lokasi' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Lokasi harus diisi',
                    ]
                ],
                'deskripsi' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Deskripsi harus diisi',
                    ]
                ],
                'foto' => [
                    'rules' => 'uploaded[foto]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
                    'errors' => [
                        'uploaded' => 'Foto Bukti harus diunggah.',
                        'is_image' => 'File yang diunggah harus berupa gambar.',
                        'mime_in' => 'Format file yang diperbolehkan: jpg, jpeg, png.',
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
                $path = 'img/bukti_laporan/';
                $file = $this->request->getFile('foto');
                $new_filename = 'laporan_' . $file->getRandomName();
                $upload_image = \Config\Services::image()->withFile($file)->save($path . $new_filename);

                $db = \Config\Database::connect();
                $siswa = $db->table('siswa')->where('nama_siswa', get_user()->nama)->get()->getRowObject();
                $data = [
                    'id_siswa' => $siswa->id,
                    'nama_terlapor' => $this->request->getVar('nama_terlapor'),
                    'judul' => $this->request->getVar('judul'),
                    'tanggal' => $this->request->getVar('tanggal'),
                    'lokasi' => $this->request->getVar('lokasi'),
                    'deskripsi' => $this->request->getVar('deskripsi'),
                    'foto' => $new_filename,
                ];
                $query = $this->laporan->insert($data);

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Laporan berhasil dibuat'
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
            $query = $this->laporan->delete($id);

            if ($query !== FALSE) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Laporan berhasil dihapus'
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
            $query = $this->laporan->where('id', $id)->set(['status' => 1])->update();

            if ($query !== FALSE) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Laporan diproses'
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
            $query = $this->laporan->where('id', $id)->set(['status' => 2])->update();

            if ($query !== FALSE) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Laporan ditolak'
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
            $data = $db->table('laporan')
                ->select('laporan.*, siswa.nama_siswa')
                ->join('siswa', 'siswa.id = laporan.id_siswa', 'left')
                ->where('laporan.id', $id)
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
        $kelas = $db->table('laporan')->select('laporan.*, siswa.nama_siswa')
            ->join('siswa', 'siswa.id = laporan.id_siswa')
            ->get()->getResultObject();

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();

        $tanggal = date('d-m-Y - H:i');
        $tanggal_hari_ini = 'Tanggal : ' . $tanggal;
        $pengunduh = 'Pengunduh : ' . get_user()->nama;
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

        $activeWorksheet->mergeCells('A1:G1');
        $activeWorksheet->setCellValue('A1', 'DATA SISWA');
        $activeWorksheet->getStyle('A1')->applyFromArray($styleArrayCenterBold20px);

        $activeWorksheet->mergeCells('A2:G2');
        $activeWorksheet->setCellValue('A2', $nama_sekolah);
        $activeWorksheet->getStyle('A2')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('A3:G3');
        $activeWorksheet->setCellValue('A3', $tanggal_hari_ini);
        $activeWorksheet->getStyle('A3')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('A4:G4');
        $activeWorksheet->setCellValue('A4', $pengunduh);
        $activeWorksheet->getStyle('A4')->applyFromArray($styleArraycenter);

        $activeWorksheet->getStyle('A1:G1')->getFont()->setName('Consolas');
        $activeWorksheet->getStyle('A2:G2')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('A3:G3')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('A4:G4')->getFont()->setName('Consolas')->setSize(10);

        foreach (range('A', 'G') as $columnID) {
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

        $activeWorksheet->getStyle('A6:G6')->applyFromArray($styleArray);
        $activeWorksheet->setCellValue('A6', 'NO');
        $activeWorksheet->setCellValue('B6', 'NAMA TERLAPOR');
        $activeWorksheet->setCellValue('C6', 'JUDUL');
        $activeWorksheet->setCellValue('D6', 'TANGGAL');
        $activeWorksheet->setCellValue('E6', 'LOKASI');
        $activeWorksheet->setCellValue('F6', 'DESKRIPSI');
        $activeWorksheet->setCellValue('G6', 'STATUS');

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
            $activeWorksheet->setCellValue('B' . $column, $value->nama_terlapor);
            $activeWorksheet->setCellValue('C' . $column, $value->judul);
            $activeWorksheet->setCellValue('D' . $column, $value->tanggal);
            $activeWorksheet->setCellValue('E' . $column, $value->lokasi);
            $activeWorksheet->setCellValue('F' . $column, $value->deskripsi);
            if ($value->status == 1) {
                $status = 'Diproses';
            } elseif ($value->status == 2) {
                $status = 'Ditolak';
            } else {
                $status = 'Menunggu';
            }
            $activeWorksheet->setCellValue('G' . $column, $status);

            $column++;
            $no++;
        }

        $dirPath = 'report/export/';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $filename = 'data-laporan_' . date('d-m-y-H-i-s') . '.xlsx';
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
