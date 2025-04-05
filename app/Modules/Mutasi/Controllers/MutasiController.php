<?php

namespace App\Modules\Mutasi\Controllers;

use App\Controllers\BaseController;
use App\Modules\Mutasi\Models\MutasiModel;
use App\Modules\Siswa\Models\SiswaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MutasiController extends BaseController
{
    protected $helpers = ['CIFunctions'];
    protected $mutasi;
    protected $siswa;
    protected $db;

    public function __construct()
    {
        $this->mutasi = new MutasiModel();
        $this->siswa = new SiswaModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $mutasi = $db->table('mutasi')->get()->getFirstRow();

        $data = [
            'title' => 'Mutasi',
            'mutasi' => $mutasi,
            'pilihan_siswa' => $this->siswa->orderBy('nama_siswa', 'asc')->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('App\Modules\Mutasi\Views\index', $data);
    }

    public function listData()
    {
        $request = \Config\Services::request();
        $datamodel = new MutasiModel();

        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getVar("start");

            foreach ($lists as $list) {
                $no++;
                $row = [];

                $buttonEdit = '<div class="d-flex"><a href="#" class="btn btn-warning btn-action edit-mutasi-btn mr-1" data-id="' . $list->id . '"><i class="fas fa-pencil-alt"></i></a>';
                $buttonDelete = '<a href="#" class="btn btn-danger delete-mutasi-btn" data-id="' . $list->id . '"><i class="fas fa-trash"></i></a></div>';

                $row[] = '<div class="custom-checkbox custom-control"><input type="checkbox" class="custom-control-input check" name="id[]" value="' . $list->id . '"><label for="" class="custom-control-label"></label></div>';
                $row[] = date('d F Y', strtotime($list->tanggal_diterima));
                $row[] = $list->asal_sekolah;
                $row[] = $list->no_surat;
                $row[] = $list->nama_siswa;
                $row[] = $list->jenis_kelamin;
                $row[] = $list->alasan;

                $row[] = $buttonEdit . $buttonDelete;
                $data[] = $row;
            }

            $output = [
                "draw" => $request->getVar('draw'),
                "recordsTotal" => $datamodel->count_all(),
                "recordsFiltered" => $datamodel->count_filtered(),
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
                'tanggal_diterima' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Tanggal diterima harus diisi',
                    ]
                ],
                'asal_sekolah' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Asal Sekolah harus diisi',
                    ]
                ],
                'no_surat' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'No. surat harus diisi',
                    ]
                ],
                'id_siswa' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama siswa harus diisi',
                    ]
                ],
                'jenis_kelamin' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Jenis kelamin harus diisi',
                    ]
                ],
                'alasan' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Alasan harus diisi',
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
                $data = [
                    'tanggal_diterima' => $this->request->getVar('tanggal_diterima'),
                    'asal_sekolah' => $this->request->getVar('asal_sekolah'),
                    'no_surat' => $this->request->getVar('no_surat'),
                    'id_siswa' => $this->request->getVar('id_siswa'),
                    'jenis_kelamin' => $this->request->getVar('jenis_kelamin'),
                    'alasan' => $this->request->getVar('alasan'),
                ];
                $query = $this->mutasi->insert($data);

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Data mutasi baru berhasil ditambahkan'
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

    public function getMutasi()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        if ($request->isAjax()) {
            $id = $request->getVar('id');

            $mutasi = $db->table('mutasi')->where('id', $id)->get()->getRowObject();
            $id_siswa = $mutasi->id_siswa;
            $data = [
                'mutasi' => $mutasi,
                'pilihan_siswa' => $db->table('siswa')->select('id, nama_siswa')->where('id', $id_siswa)->get()->getRowObject(),
            ];

            return $this->response->setJSON([
                'data' => $data
            ]);
        }
    }

    public function update()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $id = $request->getVar('id');
            $validation = \Config\Services::validation();

            $this->validate([
                'tanggal_diterima' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Tanggal diterima harus diisi',
                    ]
                ],
                'asal_sekolah' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Asal Sekolah harus diisi',
                    ]
                ],
                'no_surat' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'No. surat harus diisi',
                    ]
                ],
                'id_siswa' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama siswa harus diisi',
                    ]
                ],
                'jenis_kelamin' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Jenis kelamin harus diisi',
                    ]
                ],
                'alasan' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Alasan harus diisi',
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
                $data = [
                    'tanggal_diterima' => $this->request->getVar('tanggal_diterima'),
                    'asal_sekolah' => $this->request->getVar('asal_sekolah'),
                    'no_surat' => $this->request->getVar('no_surat'),
                    'id_siswa' => $this->request->getVar('id_siswa'),
                    'jenis_kelamin' => $this->request->getVar('jenis_kelamin'),
                    'alasan' => $this->request->getVar('alasan'),
                ];
                $query = $this->mutasi->where('id', $id)->set($data)->update();

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Data mutasi berhasil diupdate'
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
            $query = $this->mutasi->delete($id);

            if ($query !== FALSE) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Data mutasi berhasil dihapus'
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

    public function export()
    {
        $db = \Config\Database::connect();
        $mutasi = $db->table('mutasi')->select('mutasi.*, siswa.nama_siswa')
            ->join('siswa', 'siswa.id = mutasi.id_siswa')
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
        $activeWorksheet->setCellValue('A1', 'DATA MUTASI');
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
        $activeWorksheet->setCellValue('B6', 'TANGGAL DITERIMA');
        $activeWorksheet->setCellValue('C6', 'ASAL SEKOLAH');
        $activeWorksheet->setCellValue('D6', 'NO. SURAT');
        $activeWorksheet->setCellValue('E6', 'NAMA SISWA');
        $activeWorksheet->setCellValue('F6', 'JK');
        $activeWorksheet->setCellValue('G6', 'ALASAN');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $column = 7;
        $no = 1;
        foreach ($mutasi as $key => $value) {
            $activeWorksheet->setCellValue('A' . $column, $no);
            $activeWorksheet->setCellValue('B' . $column, $value->tanggal_diterima);
            $activeWorksheet->setCellValue('C' . $column, $value->asal_sekolah);
            $activeWorksheet->setCellValue('D' . $column, $value->no_surat);
            $activeWorksheet->setCellValue('E' . $column, $value->nama_siswa);
            $activeWorksheet->setCellValue('F' . $column, $value->jenis_kelamin);
            $activeWorksheet->setCellValue('G' . $column, $value->alasan);

            $column++;
            $no++;
        }

        $dirPath = 'report/export/';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $filename = 'data-mutasi_' . date('d-m-y-H-i-s') . '.xlsx';
        $filePath = $dirPath . $filename;

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function import()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $validation = \Config\Services::validation();

            $file = $this->request->getFile('file');
            $extension = $file->getClientExtension();
            if ($extension == 'xlsx' || $extension == 'xls') {
                if ($extension == 'xls') {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }

                $spreadsheet = $reader->load($file);
                $mutasi = $spreadsheet->getActiveSheet()->toArray();

                foreach ($mutasi as $key => $value) {
                    if ($key < 5) {
                        continue;
                    }

                    if (!empty($value[1])) {
                        $tanggal_diterima = $value[1];
                        $asal_sekolah = $value[2];
                        $no_surat = $value[3];
                        $nama_siswa = $value[4];
                        $jenis_kelamin = $value[5];
                        $alasan = $value[6];
                        $exists = $this->mutasi->where('tanggal_diterima', $tanggal_diterima)->countAllResults();

                        if ($exists == 0) {
                            $data = [
                                'tanggal_diterima' => $tanggal_diterima,
                                'asal_sekolah' => $asal_sekolah,
                                'no_surat' => $no_surat,
                                'nama_siswa' => $nama_siswa,
                                'jenis_kelamin' => $jenis_kelamin,
                                'alasan' => $alasan,
                            ];
                            $this->mutasi->insert($data);
                        }
                    }
                }

                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'mutasi baru berhasil ditambahkan'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'msg' => 'Format file tidak didukung. Hanya file .xlsx dan .xls yang diperbolehkan.'
                ]);
            }
        } else {
            return $this->response->setJSON([
                'status' => 0,
                'token' => csrf_hash(),
                'msg' => 'Permintaan tidak valid.'
            ]);
        }
    }

    public function multipleDelete()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $id = $request->getPost('id');

            if (!empty($id)) {
                $jumlahData = count($id);
                $querySuccess = true;

                foreach ($id as $item) {
                    $query = $this->mutasi->delete($item);
                    if (!$query) {
                        $querySuccess = false;
                        break;
                    }
                }

                if ($querySuccess) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => "$jumlahData data mutasi berhasil dihapus"
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'token' => csrf_hash(),
                        'msg' => 'Ada kesalahan saat menghapus data'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'msg' => 'Tidak ada data yang dipilih'
                ]);
            }
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }
}
