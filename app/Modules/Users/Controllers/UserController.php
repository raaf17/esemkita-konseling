<?php

namespace App\Modules\Users\Controllers;

use App\Controllers\BaseController;
use App\Modules\HakAkses\Models\RoleModel;
use App\Modules\Users\Models\UserModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserController extends BaseController
{
    protected $helpers = ['CIFunctions'];
    protected $users;
    protected $role;
    protected $db;

    public function __construct()
    {
        $this->users = new UserModel();
        $this->role = new RoleModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Pengguna',
            'roles' => $this->role->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('App\Modules\Users\Views\index', $data);
    }

    public function listData()
    {
        $request = \Config\Services::request();
        $datamodel = new UserModel();

        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getPost("start");

            foreach ($lists as $list) {
                $no++;
                $row = [];

                $buttonEdit = '<div class="d-flex"><a href="#" class="btn btn-warning btn-action edit-users-btn mr-1" data-id="' . $list->id . '"><i class="fas fa-pencil-alt"></i></a>';
                $buttonDelete = '<a href="#" class="btn btn-danger delete-users-btn" data-id="' . $list->id . '"><i class="fas fa-trash"></i></a></div>';

                $row[] = '<div class="custom-checkbox custom-control"><input type="checkbox" class="custom-control-input check" name="id[]" value="' . $list->id . '"><label for="" class="custom-control-label"></label></div>';
                $row[] = $list->nama;
                $row[] = $list->username;
                $row[] = $list->email;
                $row[] = $list->role;

                $row[] = $buttonEdit . $buttonDelete;
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

    public function store()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $validation = \Config\Services::validation();

            $this->validate([
                'nama' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama harus diisi',
                    ]
                ],
                'username' => [
                    'rules' => 'required|is_unique[users.username]',
                    'errors' => [
                        'required' => 'Username harus diisi',
                        'is_unique' => 'Username ini sudah ada',
                    ]
                ],
                'email' => [
                    'rules' => 'required|is_unique[users.email]',
                    'errors' => [
                        'required' => 'Email harus diisi',
                        'is_unique' => 'Email ini sudah ada',
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[8]|max_length[20]',
                    'errors' => [
                        'required' => 'Password wajib diisi',
                        'min_length' => 'Password minimal 8 karakter',
                        'max_length' => 'Password minimal 20 karakter',
                    ]
                ],
                'role' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Role users harus diisi',
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
                    'nama' => $this->request->getVar('nama'),
                    'username' => $this->request->getVar('username'),
                    'email' => $this->request->getVar('email'),
                    'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
                    'role' => $this->request->getVar('role'),
                    'foto' => ''
                ];
                $query = $this->users->insert($data);

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Pengguna baru berhasil ditambahkan'
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

    public function getUsers()
    {
        $request = \Config\Services::request();

        if ($request->isAjax()) {
            $id = $request->getVar('id');
            $data = $this->users->find($id);

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
                'nama' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama harus diisi',
                    ]
                ],
                'username' => [
                    'rules' => 'required|is_unique[users.username]',
                    'errors' => [
                        'required' => 'Username harus diisi',
                        'is_unique' => 'Username ini sudah ada',
                    ]
                ],
                'email' => [
                    'rules' => 'required|is_unique[users.email]',
                    'errors' => [
                        'required' => 'Email harus diisi',
                        'is_unique' => 'Email ini sudah ada',
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[8]|max_length[20]',
                    'errors' => [
                        'required' => 'Password wajib diisi',
                        'min_length' => 'Password minimal 8 karakter',
                        'max_length' => 'Password minimal 20 karakter',
                    ]
                ],
                'role' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Role users harus diisi',
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
                if ($this->request->getVar('password') == '') {
                    $password = $this->request->getVar('password_lama');
                } else {
                    $password = password_hash($this->request->getVar('password'), PASSWORD_BCRYPT);
                }

                $data = [
                    'nama' => $this->request->getVar('nama'),
                    'username' => $this->request->getVar('username'),
                    'email' => $this->request->getVar('email'),
                    'password' => $password,
                    'role' => $this->request->getVar('role'),
                    'foto' => ''
                ];
                $query = $this->users->where('id', $id)->set($data)->update();

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Pengguna berhasil diupdate'
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
            $query = $this->users->delete($id);

            if ($query !== FALSE) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Pengguna berhasil dihapus'
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
        $users = $this->users->findAll();

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

        $activeWorksheet->mergeCells('A1:E1');
        $activeWorksheet->setCellValue('A1', 'DATA PENGGUNA');
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
        $activeWorksheet->setCellValue('B6', 'NAMA PENGGUNA');
        $activeWorksheet->setCellValue('C6', 'USERNAME');
        $activeWorksheet->setCellValue('D6', 'EMAIL');
        $activeWorksheet->setCellValue('E6', 'ROLE');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $column = 7;
        $no = 1;
        foreach ($users as $key => $value) {
            $activeWorksheet->setCellValue('A' . $column, $no);
            $activeWorksheet->setCellValue('B' . $column, $value->nama);
            $activeWorksheet->setCellValue('C' . $column, $value->username);
            $activeWorksheet->setCellValue('D' . $column, $value->email);
            $activeWorksheet->setCellValue('E' . $column, $value->role);

            $column++;
            $no++;
        }

        $dirPath = 'report/export/';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $filename = 'data-pengguna_' . date('d-m-y-H-i-s') . '.xlsx';
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
                $users = $spreadsheet->getActiveSheet()->toArray();

                foreach ($users as $key => $value) {
                    if ($key < 5) {
                        continue;
                    }

                    if (!empty($value[1])) {
                        $nama = $value[1];
                        $username = $value[2];
                        $email = $value[3];
                        $password = $value[4];
                        $role = $value[5];
                        $exists = $this->users->where('nama', $nama)->countAllResults();

                        if ($exists == 0) {
                            $data = [
                                'nama' => $nama,
                                'username' => $username,
                                'email' => $email,
                                'password' => password_hash($password, PASSWORD_BCRYPT),
                                'role' => $role,
                            ];
                            $this->users->insert($data);
                        }
                    }
                }

                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Pengguna baru berhasil ditambahkan'
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
                    $query = $this->users->delete($item);
                    if (!$query) {
                        $querySuccess = false;
                        break;
                    }
                }

                if ($querySuccess) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => "$jumlahData data users berhasil dihapus"
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
