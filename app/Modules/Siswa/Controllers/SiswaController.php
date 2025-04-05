<?php

namespace App\Modules\Siswa\Controllers;

use App\Controllers\BaseController;
use App\Modules\Kelas\Models\KelasModel;
use App\Modules\Siswa\Models\SiswaModel;
use App\Modules\Users\Models\UserModel;
use Carbon\Carbon;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SiswaController extends BaseController
{
    protected $helpers = ['CIFunctions'];
    protected $kelas;
    protected $siswa;
    protected $users;
    protected $db;

    public function __construct()
    {
        $this->kelas = new KelasModel();
        $this->siswa = new SiswaModel();
        $this->users = new UserModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $data = [
            'title' => 'Siswa',
            'kelas' => $this->kelas->orderBy('nama_kelas', 'asc')->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('App\Modules\Siswa\Views\index', $data);
    }


    public function listData()
    {
        $request = \Config\Services::request();
        $datamodel = new SiswaModel();

        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getVar("start");

            foreach ($lists as $list) {
                $no++;
                $row = [];

                $buttonDetail = '<div class="d-flex"><a href="#" class="btn btn-info detail-siswa-btn mr-1" data-id="' . $list->id . '"><i class="fas fa-eye"></i></a>';
                $buttonEdit = '<a href="#" class="btn btn-warning btn-action edit-siswa-btn mr-1" data-id="' . $list->id . '"><i class="fas fa-pencil-alt"></i></a>';
                $buttonDelete = '<a href="#" class="btn btn-danger delete-siswa-btn" data-id="' . $list->id . '"><i class="fas fa-trash"></i></a></div>';

                $row[] = '<div class="custom-checkbox custom-control"><input type="checkbox" class="custom-control-input check" name="id[]" value="' . $list->id . '"><label for="" class="custom-control-label"></label></div>';
                $row[] = $list->nisn;
                $row[] = $list->nama_siswa;
                $row[] = $list->nama_kelas;
                $row[] = $list->tempat_lahir;
                $row[] = Carbon::parse($list->tanggal_lahir)->format('M j, Y');
                $row[] = $list->jenis_kelamin;

                $row[] = $buttonDetail . $buttonEdit . $buttonDelete;
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
                'nisn' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'NISN harus diisi',
                    ]
                ],
                'nama_siswa' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama siswa harus diisi',
                    ]
                ],
                'id_kelas' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama kelas harus diisi',
                    ]
                ],
                'tempat_lahir' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Tempat lahir harus diisi',
                    ]
                ],
                'tanggal_lahir' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Tanggal lahir harus diisi',
                    ]
                ],
                'alamat' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Alamat harus diisi',
                    ]
                ],
                'nama_ayah' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama ayah harus diisi',
                    ]
                ],
                'nama_ibu' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama ibu harus diisi',
                    ]
                ],
                'jenis_kelamin' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Jenis kelamin harus diisi',
                    ]
                ],
                'agama' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Agama harus diisi',
                    ]
                ],
                'status_keluarga' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Status Keluarga harus diisi',
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
                $data_siswa = [
                    'nisn' => $this->request->getVar('nisn'),
                    'id_kelas' => $this->request->getVar('id_kelas'),
                    'nama_siswa' => $this->request->getVar('nama_siswa'),
                    'tempat_lahir' => $this->request->getVar('tempat_lahir'),
                    'tanggal_lahir' => $this->request->getVar('tanggal_lahir'),
                    'alamat' => $this->request->getVar('alamat'),
                    'no_handphone' => $this->request->getVar('no_handphone'),
                    'nama_ayah' => $this->request->getVar('nama_ayah'),
                    'nama_ibu' => $this->request->getVar('nama_ibu'),
                    'jenis_kelamin' => $this->request->getVar('jenis_kelamin'),
                    'agama' => $this->request->getVar('agama'),
                    'status_keluarga' => $this->request->getVar('status_keluarga'),
                ];

                $this->siswa->insert($data_siswa);

                $data_user = [
                    'nama' => $this->request->getVar('nama_siswa'),
                    'username' => '',
                    'email' => '',
                    'password' => password_hash('12345678', PASSWORD_BCRYPT),
                    'role' => 'Siswa',
                    'foto' => '',
                ];

                $query = $this->users->insert($data_user);

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Siswa baru berhasil ditambahkan'
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

    public function getSiswa()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        if ($request->isAjax()) {
            $id = $request->getVar('id');
            $data = $db->table('siswa')->where('id', $id)->get()->getRowObject();

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
                'nisn' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'NISN harus diisi',
                    ]
                ],
                'nama_siswa' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama siswa harus diisi',
                    ]
                ],
                'id_kelas' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama kelas harus diisi',
                    ]
                ],
                'tempat_lahir' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Tempat lahir harus diisi',
                    ]
                ],
                'tanggal_lahir' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Tanggal lahir harus diisi',
                    ]
                ],
                'alamat' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Alamat harus diisi',
                    ]
                ],
                'nama_ayah' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama ayah harus diisi',
                    ]
                ],
                'nama_ibu' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama ibu harus diisi',
                    ]
                ],
                'jenis_kelamin' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Jenis kelamin harus diisi',
                    ]
                ],
                'agama' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Agama harus diisi',
                    ]
                ],
                'status_keluarga' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Status Keluarga harus diisi',
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
                $data_siswa = [
                    'nisn' => $this->request->getVar('nisn'),
                    'id_kelas' => $this->request->getVar('id_kelas'),
                    'nama_siswa' => $this->request->getVar('nama_siswa'),
                    'tempat_lahir' => $this->request->getVar('tempat_lahir'),
                    'tanggal_lahir' => $this->request->getVar('tanggal_lahir'),
                    'alamat' => $this->request->getVar('alamat'),
                    'no_handphone' => $this->request->getVar('no_handphone'),
                    'nama_ayah' => $this->request->getVar('nama_ayah'),
                    'nama_ibu' => $this->request->getVar('nama_ibu'),
                    'jenis_kelamin' => $this->request->getVar('jenis_kelamin'),
                    'agama' => $this->request->getVar('agama'),
                    'status_keluarga' => $this->request->getVar('status_keluarga'),
                ];
                $query = $this->siswa->where('id', $id)->set($data_siswa)->update();

                $siswa = $db->table('siswa')->where('id', $id)->get()->getRowObject();
                $nama_siswa = $siswa->nama_siswa;
                $data_user = [
                    'nama' => $this->request->getVar('nama_siswa'),
                    'username' => '',
                    'email' => '',
                    'password' => password_hash('12345678', PASSWORD_BCRYPT),
                    'role' => 'Siswa',
                    'foto' => '',
                ];
                $query = $this->users->where('nama', $nama_siswa)->set($data_user)->update();

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Siswa berhasil diupdate'
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
        $db = \Config\Database::connect();

        if ($request->isAJAX()) {
            $id = $request->getVar('id');

            $siswa = $db->table('siswa')->select('nama_siswa')->where('id', $id)->get()->getRowObject();

            if (!$siswa) {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'msg' => 'NISN tidak ditemukan'
                ]);
            }

            $this->users->where('nama', $siswa->nama_siswa)->delete();
            $query = $this->siswa->delete($id);

            if ($query !== FALSE) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Siswa berhasil dihapus'
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
            $data = $db->table('siswa')
                ->select('siswa.*, kelas.nama_kelas')
                ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
                ->where('siswa.id', $id)
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
        $siswa = $db->table('siswa')->select('siswa.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.id = siswa.id_kelas')
            ->orderBy('kelas.nama_kelas', 'asc')
            ->orderBy('siswa.nama_siswa', 'asc')
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

        $activeWorksheet->mergeCells('A1:M1');
        $activeWorksheet->setCellValue('A1', 'DATA SISWA');
        $activeWorksheet->getStyle('A1')->applyFromArray($styleArrayCenterBold20px);

        $activeWorksheet->mergeCells('A2:M2');
        $activeWorksheet->setCellValue('A2', $nama_sekolah);
        $activeWorksheet->getStyle('A2')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('A3:M3');
        $activeWorksheet->setCellValue('A3', $tanggal_hari_ini);
        $activeWorksheet->getStyle('A3')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('A4:M4');
        $activeWorksheet->setCellValue('A4', $pengunduh);
        $activeWorksheet->getStyle('A4')->applyFromArray($styleArraycenter);

        $activeWorksheet->getStyle('A1:M1')->getFont()->setName('Consolas');
        $activeWorksheet->getStyle('A2:M2')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('A3:M3')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('A4:M4')->getFont()->setName('Consolas')->setSize(10);

        foreach (range('A', 'M') as $columnID) {
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

        $activeWorksheet->getStyle('A6:M6')->applyFromArray($styleArray);
        $activeWorksheet->setCellValue('A6', 'NO');
        $activeWorksheet->setCellValue('B6', 'NISN');
        $activeWorksheet->setCellValue('C6', 'NAMA SISWA');
        $activeWorksheet->setCellValue('D6', 'KELAS');
        $activeWorksheet->setCellValue('E6', 'TEMPAT LAHIR');
        $activeWorksheet->setCellValue('F6', 'TANGGAL LAHIR');
        $activeWorksheet->setCellValue('G6', 'ALAMAT');
        $activeWorksheet->setCellValue('H6', 'NO. HANDPHONE');
        $activeWorksheet->setCellValue('I6', 'NAMA AYAH');
        $activeWorksheet->setCellValue('J6', 'NAMA IBU');
        $activeWorksheet->setCellValue('K6', 'JENIS KELAMIN');
        $activeWorksheet->setCellValue('L6', 'AGAMA');
        $activeWorksheet->setCellValue('M6', 'STATUS KELUARGA');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $column = 7;
        $no = 1;
        foreach ($siswa as $key => $value) {
            $activeWorksheet->setCellValue('A' . $column, $no);
            $activeWorksheet->setCellValue('B' . $column, $value->nisn);
            $activeWorksheet->setCellValue('C' . $column, $value->nama_siswa);
            $activeWorksheet->setCellValue('D' . $column, $value->nama_kelas);
            $activeWorksheet->setCellValue('E' . $column, $value->tempat_lahir);
            $activeWorksheet->setCellValue('F' . $column, $value->tanggal_lahir);
            $activeWorksheet->setCellValue('G' . $column, $value->alamat);
            $activeWorksheet->setCellValue('H' . $column, $value->no_handphone);
            $activeWorksheet->setCellValue('I' . $column, $value->nama_ayah);
            $activeWorksheet->setCellValue('J' . $column, $value->nama_ibu);
            $activeWorksheet->setCellValue('K' . $column, $value->jenis_kelamin);
            $activeWorksheet->setCellValue('L' . $column, $value->agama);
            $activeWorksheet->setCellValue('M' . $column, $value->status_keluarga);

            $column++;
            $no++;
        }

        $dirPath = 'report/export/';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $filename = 'data-siswa_' . date('d-m-y-H-i-s') . '.xlsx';
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
                $siswa = $spreadsheet->getActiveSheet()->toArray();
                $db = \Config\Database::connect();

                foreach ($siswa as $key => $value) {
                    if ($key < 6) {
                        continue;
                    }

                    if (!empty($value[1])) {
                        $nisn = $value[1];
                        $nama_siswa = $value[2];
                        $nama_kelas = $value[3];
                        $tempat_lahir = $value[4];
                        $tanggal_lahir = $value[5];
                        $alamat = $value[6];
                        $no_handphone = $value[7];
                        $nama_ayah = $value[8];
                        $nama_ibu = $value[9];
                        $jenis_kelamin = $value[10];
                        $agama = $value[11];
                        $status_keluarga = $value[12];
                        $email = $value[13];

                        $kelas = $db->table('kelas')->where('nama_kelas', $nama_kelas)->get()->getRowObject();
                        $id_kelas = $kelas ? $kelas->id : null;

                        $exists = $this->siswa->where('nisn', $nisn)->countAllResults();

                        if ($exists == 0) {
                            $data_siswa = [
                                'nisn' => $nisn,
                                'id_kelas' => $id_kelas,
                                'nama_siswa' => $nama_siswa,
                                'tempat_lahir' => $tempat_lahir,
                                'tanggal_lahir' => $tanggal_lahir,
                                'alamat' => $alamat,
                                'no_handphone' => $no_handphone,
                                'nama_ayah' => $nama_ayah,
                                'nama_ibu' => $nama_ibu,
                                'jenis_kelamin' => $jenis_kelamin,
                                'agama' => $agama,
                                'status_keluarga' => $status_keluarga,
                            ];
                            $this->siswa->insert($data_siswa);

                            $data_user = [
                                'nama' => $nama_siswa,
                                'username' => '',
                                'email' => $email,
                                'password' => password_hash('12345678', PASSWORD_BCRYPT),
                                'role' => 'Siswa',
                                'foto' => ''
                            ];
                            $this->users->insert($data_user);
                        }
                    }
                }

                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Siswa baru berhasil ditambahkan'
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
                    $query = $this->siswa->delete($item);
                    if (!$query) {
                        $querySuccess = false;
                        break;
                    }
                }

                if ($querySuccess) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => "$jumlahData data siswa berhasil dihapus"
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
