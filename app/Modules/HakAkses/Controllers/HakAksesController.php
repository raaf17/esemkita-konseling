<?php

namespace App\Modules\HakAkses\Controllers;

use App\Controllers\BaseController;
use App\Modules\HakAkses\Models\AksesModel;
use App\Modules\HakAkses\Models\MenuModel;
use App\Modules\HakAkses\Models\RoleModel;
use CodeIgniter\HTTP\ResponseInterface;

class HakAksesController extends BaseController
{
    protected $helpers = ['CIFunctions'];
    protected $role;
    protected $menu;
    protected $akses;
    protected $db;

    public function __construct()
    {
        $this->role = new RoleModel();
        $this->menu = new MenuModel();
        $this->akses = new AksesModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title' => 'Role',
            'validation' => \Config\Services::validation(),
            'list_menu' => $this->listMenu()
        ];

        return view('App\Modules\HakAkses\Views\index', $data);
    }

    public function listData()
    {
        $request = \Config\Services::request();
        $datamodel = new RoleModel();

        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getPost("start");

            foreach ($lists as $list) {
                $no++;
                $row = [];

                $buttonEditRole = '<div class="d-flex"><a href="" class="btn btn-sm btn-light-primary btn-flex btn-action p-0 p-2 me-2" data-id="' . $list->role . '"><i class="ki-outline ki-key-square fs-4"></i></a>';
                $buttonEdit = '<a href="" class="btn btn-sm btn-light-warning btn-flex edit-role-btn p-0 p-2 me-2" data-id="' . $list->id . '"><i class="ki-outline ki-notepad-edit fs-4"></i></a>';
                $buttonDelete = '<a href="" class="btn btn-sm btn-light-danger btn-flex delete-role-btn p-0 p-2" data-id="' . $list->id . '"><i class="ki-outline ki-basket fs-4"></i></a></div>';

                $row[] = '<div class="form-check form-check-sm form-check-custom form-check-solid"><input class="form-check-input check" type="checkbox" name="id[]" value="' . $list->id . '" /></div>';
                $row[] = $list->role;

                $row[] = $buttonEditRole . " " . $buttonEdit . " " . $buttonDelete;
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

    public function listMenu()
    {
        $menu = $this->menu->where('aktif', '1')->findAll();
        foreach ($menu as $mn) {
            $ada = false;
            foreach ($this->listAkses() as $a) {
                if ($mn->kode_menu == $a->kode_menu) {
                    $ada = true;
                }
            }

            if (!$ada) {
                $insert[] = [
                    'kode_menu' => $mn->kode_menu,
                    'role' => $this->request->getPost('role'),
                    'akses' => '0',
                    'tambah' => '0',
                    'edit' => '0',
                    'hapus' => '0',
                    'user' => get_user()->nama
                ];
            }
        }

        if (isset($insert)) {
            $this->db->table('sys_akses')->insertBatch($insert);
        }

        return $this->db->table('sys_menu')->select('sys_menu.nama_menu, sys_menu.level, sys_akses.*')
            ->join('sys_akses', 'sys_akses.kode_menu = sys_menu.kode_menu', 'left')
            ->where('sys_akses.role', $this->request->getPost('role'))
            ->orderBy('sys_menu.kode_menu')
            ->get()->getResultObject();
    }

    // public function listMenu()
    // {
    //     $role = $this->request->getPost('role');

    //     // If no role is provided, stop the process.
    //     // if (empty($role)) {
    //     //     return [];
    //     // }

    //     $menu = $this->menu->where('aktif', '1')->findAll();
    //     $insert = [];

    //     foreach ($menu as $mn) {
    //         // Check if this role and menu combination already exists in the database
    //         $exists = $this->db->table('sys_akses')
    //             ->where('kode_menu', $mn->kode_menu)
    //             ->where('role', $role)
    //             ->countAllResults();

    //         if ($exists == 0) { // If no entry exists for this role and menu
    //             $insert[] = [
    //                 'kode_menu' => $mn->kode_menu,
    //                 'role' => $role,
    //                 'akses' => '0',
    //                 'tambah' => '0',
    //                 'edit' => '0',
    //                 'hapus' => '0',
    //                 'user' => get_user()->nama
    //             ];
    //         }
    //     }

    //     // Only insert new records if there are entries to insert
    //     if (!empty($insert)) {
    //         $this->db->table('sys_akses')->insertBatch($insert);
    //     }

    //     // Return the menu items with access rights for the given role
    //     return $this->db->table('sys_menu')->select('sys_menu.nama_menu, sys_menu.level, sys_akses.*')
    //         ->join('sys_akses', 'sys_akses.kode_menu = sys_menu.kode_menu', 'left')
    //         ->where('sys_akses.role', $role)
    //         ->orderBy('sys_menu.kode_menu')
    //         ->get()->getResultObject();
    // }

    public function listAkses()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('sys_akses')
            ->select('sys_akses.*')
            ->where(['sys_akses.role' => $this->request->getPost('role')]);

        return $builder->get()->getResultObject();
    }

    public function editHakAkses()
    {
        $data = [
            'role' => $this->request->getVar('role'),
            'list_menu' => $this->listMenu()
        ];

        return view('backend/hakakses/modal', $data);
    }

    public function simpanHakAkses()
    {
        $list = $this->listMenu();
        $no = 0;
        foreach ($list as $ls) {
            $data[] = [
                'kode_menu' => $ls->kode_menu,
                'akses' => ($this->request->getPost('akses' . $no) ? 1 : 0),
                'tambah' => ($this->request->getPost('tambah' . $no) ? 1 : 0),
                'edit' => ($this->request->getPost('edit' . $no) ? 1 : 0),
                'hapus' => ($this->request->getPost('hapus' . $no) ? 1 : 0),
            ];
            $no++;
        }
        $role_user = $this->request->getPost('role_user');
        $this->db->table('sys_akses')->where('role', $role_user)->updateBatch($data, 'kode_menu');

        session()->setFlashdata('message', 'Hak akses berhasil diupdate');

        return redirect()->route('hakakses');
    }

    public function store()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $validation = \Config\Services::validation();

            $this->validate([
                'role' => [
                    'rules' => 'required|is_unique[sys_role.role]',
                    'errors' => [
                        'required' => 'Role harus diisi',
                        'is_unique' => 'Role ini sudah ada',
                    ]
                ]
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
                    'role' => $this->request->getVar('role'),
                ];
                $query = $this->role->insert($data);

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Role baru berhasil ditambahkan'
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

    public function getRole()
    {
        $request = \Config\Services::request();

        if ($request->isAjax()) {
            $id = $request->getVar('id');
            $data = $this->role->find($id);

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
                'role' => [
                    'rules' => 'required|is_unique[sys_role.role]',
                    'errors' => [
                        'required' => 'Role harus diisi',
                        'is_unique' => 'Role ini sudah ada',
                    ]
                ]
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
                    'role' => $this->request->getVar('role'),
                ];
                $query = $this->role->where('id', $id)->set($data)->update();

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Role berhasil diupdate'
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
            $query = $this->role->delete($id);

            if ($query !== FALSE) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Role berhasil dihapus'
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

    public function multipleDelete()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $id = $request->getPost('id');

            if (!empty($id)) {
                $jumlahData = count($id);
                $querySuccess = true;

                foreach ($id as $item) {
                    $query = $this->role->delete($item);
                    if (!$query) {
                        $querySuccess = false;
                        break;
                    }
                }

                if ($querySuccess) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => "$jumlahData data role berhasil dihapus"
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
