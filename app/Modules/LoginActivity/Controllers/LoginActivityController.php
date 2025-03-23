<?php

namespace App\Modules\LoginActivity\Controllers;

use App\Controllers\BaseController;
use App\Modules\LoginActivity\Models\LoginActivityModel;
use Carbon\Carbon;

class LoginActivityController extends BaseController
{
    protected $helpers = ['CIFunctions'];
    protected $loginactivity;
    protected $db;

    public function __construct()
    {
        $this->loginactivity = new LoginActivityModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $data = [
            'title' => 'Aktivitas Login',
            'validation' => \Config\Services::validation(),
        ];

        return view('App\Modules\LoginActivity\Views\index', $data);
    }

    public function listData()
    {
        $request = \Config\Services::request();
        $datamodel = new LoginActivityModel();

        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getVar("start");

            foreach ($lists as $list) {
                $no++;
                $row = [];

                $buttonDelete = '<a href="#" class="btn btn-sm btn-light-danger delete-loginactivity-btn p-0 p-2" data-id="' . $list->id . '"><i class="ki-outline ki-basket fs-4"></i></a>';

                $row[] = '<div class="form-check form-check-sm form-check-custom form-check-solid"><input class="form-check-input check" type="checkbox" name="id[]" value="' . $list->id . '" /></div>';
                $row[] = $list->nama;
                $row[] = $list->action;
                $row[] = $list->ip;
                $row[] = $list->browser;
                $row[] = $list->perangkat;
                $row[] = Carbon::parse($list->created_at)->format('M j, Y');

                $row[] = $buttonDelete;
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

    public function delete()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $id = $request->getVar('id');
            $query = $this->loginactivity->delete($id);

            if ($query !== FALSE) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Data login activity berhasil dihapus'
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
                    $query = $this->loginactivity->delete($item);
                    if (!$query) {
                        $querySuccess = false;
                        break;
                    }
                }

                if ($querySuccess) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => "$jumlahData data loginactivity berhasil dihapus"
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
