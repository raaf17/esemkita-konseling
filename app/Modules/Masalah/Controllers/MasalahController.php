<?php

namespace App\Modules\Masalah\Controllers;

use App\Controllers\BaseController;
use App\Modules\Masalah\Models\MainMasalahModel;
use App\Modules\Masalah\Models\SubMasalahModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MasalahController extends BaseController
{
    protected $helpers = ['CIFunctions'];
    protected $sub_masalah;
    protected $main_masalah;
    protected $db;

    public function __construct()
    {
        $this->sub_masalah = new SubMasalahModel();
        $this->main_masalah = new MainMasalahModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $sub_masalah = $db->table('sub_masalah')->get()->getFirstRow();

        $data = [
            'title' => 'Masalah',
            'sub_masalah' => $sub_masalah,
            'option_main_masalah' => $this->main_masalah->orderBy('nama_main_masalah', 'asc')->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('App\Modules\Masalah\Views\index', $data);
    }

    public function listDataSubMasalah()
    {
        $request = \Config\Services::request();
        $datamodel = new SubMasalahModel();

        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getPost("start");

            foreach ($lists as $list) {
                $no++;
                $row = [];

                $buttonEdit = '<div class="d-flex"><a href="#" class="btn btn-sm btn-light-warning btn-action edit-sub-masalah-btn p-0 p-2 me-2" data-id="' . $list->id . '"><i class="ki-outline ki-notepad-edit fs-4"></i></a>';
                $buttonDelete = '<a href="#" class="btn btn-sm btn-light-danger delete-sub-masalah-btn p-0 p-2" data-id="' . $list->id . '"><i class="ki-outline ki-basket fs-4"></i></a></div>';

                $row[] = '<div class="form-check form-check-sm form-check-custom form-check-solid"><input class="form-check-input" type="checkbox" value="1" /></div>';
                $row[] = $list->nama_main_masalah;
                $row[] = $list->nama_sub_masalah;

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

    public function listDataMainMasalah()
    {
        $request = \Config\Services::request();
        $datamodel = new MainMasalahModel();

        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getPost("start");

            foreach ($lists as $list) {
                $no++;
                $row = [];

                $buttonEdit = '<div class="d-flex"><a href="#" class="btn btn-sm btn-light-warning btn-action edit-main-masalah-btn p-0 p-2 me-2" data-id="' . $list->id . '"><i class="ki-outline ki-notepad-edit fs-4"></i></a>';
                $buttonDelete = '<a href="#" class="btn btn-sm btn-light-danger delete-main-masalah-btn p-0 p-2" data-id="' . $list->id . '"><i class="ki-outline ki-basket fs-4"></i></a></div>';

                $row[] = '<div class="form-check form-check-sm form-check-custom form-check-solid"><input class="form-check-input" type="checkbox" value="1" /></div>';
                $row[] = $list->nama_main_masalah;

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
                'id_main_masalah' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Kelompok masalah harus diisi',
                    ]
                ],
                'nama_sub_masalah' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama sub masalah harus diisi',
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
                    'id_main_masalah' => $this->request->getVar('id_main_masalah'),
                    'nama_sub_masalah' => $this->request->getVar('nama_sub_masalah'),
                ];
                $query = $this->sub_masalah->insert($data);

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Masalah baru berhasil ditambahkan'
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

    public function getSubMasalah()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        if ($request->isAjax()) {
            $id = $request->getVar('id');

            $sub_masalah = $db->table('sub_masalah')->where('id', $id)->get()->getRowObject();
            $id_main_masalah = $sub_masalah->id_main_masalah;

            $data = [
                'sub_masalah' => $sub_masalah,
                'option_main_masalah' => $db->table('main_masalah')->select('id, nama_main_masalah')->where('id', $id_main_masalah)->get()->getRowObject(),
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
                'id_main_masalah' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Kelompok masalah harus diisi',
                    ]
                ],
                'nama_sub_masalah' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama sub masalah harus diisi',
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
                    'id_main_masalah' => $this->request->getVar('id_main_masalah'),
                    'nama_sub_masalah' => $this->request->getVar('nama_sub_masalah'),
                ];
                $query = $this->sub_masalah->where('id', $id)->set($data)->update();

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Masalah berhasil diupdate'
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
            $query = $this->sub_masalah->delete($id);

            if ($query !== FALSE) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Masalah berhasil dihapus'
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

    public function storeMain()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $validation = \Config\Services::validation();

            $this->validate([
                'nama_main_masalah' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Kelompok masalah harus diisi',
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
                    'nama_main_masalah' => $this->request->getVar('nama_main_masalah'),
                ];
                $query = $this->main_masalah->insert($data);

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Data kelompok masalah baru berhasil ditambahkan'
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

    public function getMainMasalah()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        if ($request->isAjax()) {
            $id = $request->getVar('id');
            $data = $this->main_masalah->find($id);

            return $this->response->setJSON([
                'data' => $data
            ]);
        }
    }

    public function updateMain()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $id = $request->getVar('id');
            $validation = \Config\Services::validation();

            $this->validate([
                'nama_main_masalah' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Kelompok masalah harus diisi',
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
                    'nama_main_masalah' => $this->request->getVar('nama_main_masalah'),
                ];
                $query = $this->main_masalah->where('id', $id)->set($data)->update();

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Data kelompok masalah berhasil diupdate'
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

    public function deleteMain()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        if ($request->isAJAX()) {
            $id = $request->getVar('id');
            $query = $this->main_masalah->delete($id);

            if ($query !== FALSE) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Data kelompok masalah berhasil dihapus'
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

    public function exportSubMasalah()
    {
        $db = \Config\Database::connect();
        $sub_masalah = $db->table('sub_masalah')->select('sub_masalah.*, main_masalah.nama_main_masalah')
            ->join('main_masalah', 'main_masalah.id = sub_masalah.id_main_masalah')
            ->orderBy('main_masalah.nama_main_masalah', 'asc')
            ->get()->getResultObject();

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();

        $tanggal = date('d-m-Y - H:i');
        $tanggal_hari_ini = 'Tanggal unduh : ' . $tanggal;
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

        $activeWorksheet->mergeCells('A1:C1');
        $activeWorksheet->setCellValue('A1', 'DATA MASALAH');
        $activeWorksheet->getStyle('A1')->applyFromArray($styleArrayCenterBold20px);

        $activeWorksheet->mergeCells('A2:C2');
        $activeWorksheet->setCellValue('A2', $nama_sekolah);
        $activeWorksheet->getStyle('A2')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('A3:C3');
        $activeWorksheet->setCellValue('A3', $tanggal_hari_ini);
        $activeWorksheet->getStyle('A3')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('A4:C4');
        $activeWorksheet->setCellValue('A4', $pengunduh);
        $activeWorksheet->getStyle('A4')->applyFromArray($styleArraycenter);

        $activeWorksheet->getStyle('A1:C1')->getFont()->setName('Consolas');
        $activeWorksheet->getStyle('A2:C2')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('A3:C3')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('A4:C4')->getFont()->setName('Consolas')->setSize(10);

        foreach (range('A', 'C') as $columnID) {
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

        $activeWorksheet->getStyle('A6:C6')->applyFromArray($styleArray);
        $activeWorksheet->setCellValue('A6', 'NO');
        $activeWorksheet->setCellValue('B6', 'KELOMPOK');
        $activeWorksheet->setCellValue('C6', 'MASALAH');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $column = 7;
        $no = 1;
        foreach ($sub_masalah as $key => $value) {
            $activeWorksheet->setCellValue('A' . $column, $no);
            $activeWorksheet->setCellValue('B' . $column, $value->nama_main_masalah);
            $activeWorksheet->setCellValue('C' . $column, $value->nama_sub_masalah);

            $column++;
            $no++;
        }

        $dirPath = 'report/export/';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $filename = 'data-masalah_' . date('d-m-y-H-i-s') . '.xlsx';
        $filePath = $dirPath . $filename;

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function exportMainMasalah()
    {
        $main_masalah = $this->main_masalah->findAll();

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();

        $tanggal = date('d-m-Y - H:i');
        $tanggal_hari_ini = 'Tanggal unduh : ' . $tanggal;
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

        $activeWorksheet->mergeCells('A1:B1');
        $activeWorksheet->setCellValue('A1', 'DATA KELOMPOK MASALAH');
        $activeWorksheet->getStyle('A1')->applyFromArray($styleArrayCenterBold20px);

        $activeWorksheet->mergeCells('A2:B2');
        $activeWorksheet->setCellValue('A2', $nama_sekolah);
        $activeWorksheet->getStyle('A2')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('A3:B3');
        $activeWorksheet->setCellValue('A3', $tanggal_hari_ini);
        $activeWorksheet->getStyle('A3')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('A4:B4');
        $activeWorksheet->setCellValue('A4', $pengunduh);
        $activeWorksheet->getStyle('A4')->applyFromArray($styleArraycenter);

        $activeWorksheet->getStyle('A1:B1')->getFont()->setName('Consolas');
        $activeWorksheet->getStyle('A2:B2')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('A3:B3')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('A4:B4')->getFont()->setName('Consolas')->setSize(10);

        foreach (range('A', 'B') as $columnID) {
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

        $activeWorksheet->getStyle('A6:B6')->applyFromArray($styleArray);
        $activeWorksheet->setCellValue('A6', 'NO');
        $activeWorksheet->setCellValue('B6', 'KELOMPOK MASALAH');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $column = 7;
        $no = 1;
        foreach ($main_masalah as $key => $value) {
            $activeWorksheet->setCellValue('A' . $column, $no);
            $activeWorksheet->setCellValue('B' . $column, $value->nama_main_masalah);

            $column++;
            $no++;
        }

        $dirPath = 'report/export/';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $filename = 'data-kelompok-masalah_' . date('d-m-y-H-i-s') . '.xlsx';
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
