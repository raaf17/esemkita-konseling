<?php

namespace App\Modules\Guru\Controllers;

use App\Modules\Guru\Models\GuruModel;
use App\Modules\Users\Models\UserModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GuruController extends \CodeIgniter\Controller
{
    protected $helpers = ['CIFunctions'];
    protected $guru;
    protected $users;
    protected $db;

    public function __construct()
    {
        $this->guru = new GuruModel();
        $this->users = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Guru',
            'validation' => \Config\Services::validation(),
        ];

        return view('App\Modules\Guru\Views\index', $data);
    }

    public function listData()
    {
        $request = \Config\Services::request();
        $datamodel = new GuruModel();

        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getVar("start");

            foreach ($lists as $list) {
                $no++;
                $row = [];

                $buttonDetail = '<div class="d-flex"><a href="#" class="btn btn-info detail-guru-btn mr-1" data-id="' . $list->id . '"><i class="fas fa-eye"></i></a>';
                $buttonEdit = '<a href="#" class="btn btn-warning btn-action edit-guru-btn mr-1" data-id="' . $list->id . '"><i class="fas fa-pencil-alt"></i></a>';
                $buttonDelete = '<a href="#" class="btn btn-danger delete-guru-btn" data-id="' . $list->id . '"><i class="fas fa-trash"></i></a></div>';

                $row[] = '<div class="custom-checkbox custom-control"><input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" name="id[]" value="' . $list->id . '"><label for="checkbox-2" class="custom-control-label">&nbsp;</label></div>';
                $row[] = $list->nama_guru;
                $row[] = $list->nip;

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
                'nama_guru' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama guru harus diisi',
                    ]
                ],
                'nip' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'NIP harus diisi',
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
                $data_guru = [
                    'nama_guru' => $this->request->getVar('nama_guru'),
                    'nip' => $this->request->getVar('nip'),
                    'tanggal_lahir' => $this->request->getVar('tanggal_lahir'),
                    'no_handphone' => $this->request->getVar('no_handphone'),
                ];
                $this->guru->insert($data_guru);

                $data_user = [
                    'nama' => $this->request->getVar('nama_guru'),
                    'username' => '',
                    'email' => '',
                    'password' => password_hash('12345678', PASSWORD_BCRYPT),
                    'role' => 'Guru',
                    'foto' => '',
                ];
                $query = $this->users->insert($data_user);

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Data Guru baru berhasil ditambahkan'
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

    public function getGuru()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        if ($request->isAjax()) {
            $id = $request->getVar('id');

            $guru = $db->table('guru')->where('id', $id)->get()->getRowObject();
            $id_guru = $guru->id;
            $users = $db->table('users')->where('id', $id_guru)->get()->getRowObject();
            $data = [
                'guru' => $guru,
                'users' => $users,
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
                'nama_guru' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama guru harus diisi',
                    ]
                ],
                'nip' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'NIP harus diisi',
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
                    'nip' => $this->request->getVar('nip'),
                    'nama_guru' => $this->request->getVar('nama_guru'),
                    'tanggal_lahir' => $this->request->getVar('tanggal_lahir'),
                    'no_handphone' => $this->request->getVar('no_handphone'),
                ];
                $this->guru->where('id', $id)->set($data_siswa)->update();

                $guru = $db->table('guru')->select('nama_guru')->where('id', $id)->get()->getRowObject();
                $data_user = [
                    'nama' => $this->request->getVar('nama_guru'),
                    'username' => '',
                    'email' => '',
                    'role' => 'Guru',
                    'foto' => '',
                ];
                $query = $this->users->where('nama', $guru->nama_guru)->set($data_user)->update();

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Data Guru berhasil diupdate'
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

            $guru = $db->table('guru')->select('nama_guru')->where('id', $id)->get()->getRowObject();

            if (!$guru) {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'msg' => 'NIP tidak ditemukan'
                ]);
            }

            $this->users->where('nama', $guru->nama_guru)->delete();
            $query = $this->guru->delete($id);

            if ($query !== FALSE) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Data Guru berhasil dihapus'
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
        $guru = $this->guru->findAll();

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

        $activeWorksheet->mergeCells('A1:E1');
        $activeWorksheet->setCellValue('A1', 'DATA GURU');
        $activeWorksheet->getStyle('A1')->applyFromArray($styleArrayCenterBold20px);

        $activeWorksheet->mergeCells('A2:E2');
        $activeWorksheet->setCellValue('A2', $nama_sekolah);
        $activeWorksheet->getStyle('A2')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('A3:E3');
        $activeWorksheet->setCellValue('A3', $tanggal_hari_ini);
        $activeWorksheet->getStyle('A3')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('A4:E4');
        $activeWorksheet->setCellValue('A4', $pengunduh);
        $activeWorksheet->getStyle('A4')->applyFromArray($styleArraycenter);

        $activeWorksheet->getStyle('A1:E1')->getFont()->setName('Consolas');
        $activeWorksheet->getStyle('A2:E2')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('A3:E3')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('A4:E4')->getFont()->setName('Consolas')->setSize(10);

        foreach (range('A', 'E') as $columnID) {
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

        $activeWorksheet->getStyle('A6:E6')->applyFromArray($styleArray);
        $activeWorksheet->setCellValue('A6', 'NO');
        $activeWorksheet->setCellValue('B6', 'NAMA GURU');
        $activeWorksheet->setCellValue('C6', 'NIP');
        $activeWorksheet->setCellValue('D6', 'TANGGAL LAHIR');
        $activeWorksheet->setCellValue('E6', 'NO. HANDPHONE');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $column = 7;
        $no = 1;
        foreach ($guru as $key => $value) {
            $activeWorksheet->setCellValue('A' . $column, $no);
            $activeWorksheet->setCellValue('B' . $column, $value->nama_guru);
            $activeWorksheet->setCellValue('C' . $column, $value->nip);
            $activeWorksheet->setCellValue('D' . $column, $value->tanggal_lahir);
            $activeWorksheet->setCellValue('E' . $column, $value->no_handphone);

            $column++;
            $no++;
        }

        $dirPath = 'report/export/';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $filename = 'data-guru_' . date('d-m-y-H-i-s') . '.xlsx';
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
                $guru = $spreadsheet->getActiveSheet()->toArray();

                foreach ($guru as $key => $value) {
                    if ($key < 6) {
                        continue;
                    }

                    if (!empty($value[1])) {
                        $nama_guru = $value[1];
                        $nip = $value[2];
                        $tanggal_lahir = $value[3];
                        $no_handphone = $value[4];
                        $exists = $this->guru->where('nama_guru', $nama_guru)->countAllResults();

                        if ($exists == 0) {
                            $data = [
                                'nama_guru' => $nama_guru,
                                'nip' => $nip,
                                'tanggal_lahir' => $tanggal_lahir,
                                'no_handphone' => $no_handphone,
                            ];
                            $this->guru->insert($data);

                            $data_user = [
                                'nama' => $nama_guru,
                                'username' => '',
                                'email' => '',
                                'password' => password_hash('12345678', PASSWORD_BCRYPT),
                                'role' => 'Guru',
                                'foto' => '',
                            ];
                            $this->users->insert($data_user);
                        }
                    }
                }

                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Guru baru berhasil ditambahkan'
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

    public function getDetail()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        if ($request->isAJAX()) {
            $id = $request->getVar('id');
            $data = $db->table('guru')
                ->select('*')
                ->where('id', $id)
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

    public function multipleDelete()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $id = $request->getPost('id');

            if (!empty($id)) {
                $jumlahData = count($id);
                $querySuccess = true;

                foreach ($id as $item) {
                    $query = $this->guru->delete($item);
                    if (!$query) {
                        $querySuccess = false;
                        break;
                    }
                }

                if ($querySuccess) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => "$jumlahData data guru berhasil dihapus"
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
