<?php

namespace App\Modules\Jurusan\Controllers;

use App\Controllers\BaseController;
use App\Modules\Guru\Models\GuruModel;
use App\Modules\Jurusan\Models\JurusanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\HTTP\ResponseInterface;

class JurusanController extends BaseController
{
    protected $helpers = ['CIFunctions'];
    protected $jurusan;
    protected $guru;
    protected $db;

    public function __construct()
    {
        $this->jurusan = new JurusanModel();
        $this->guru = new GuruModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $jurusan = $db->table('jurusan')->get()->getFirstRow();

        $data = [
            'title' => 'Jurusan',
            'jurusan' => $jurusan,
            'pilihan_guru' => $this->guru->orderBy('nama_guru', 'asc')->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('App\Modules\Jurusan\Views\index', $data);
    }

    public function listData()
    {
        $request = \Config\Services::request();
        $datamodel = new JurusanModel();

        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getPost("start");

            foreach ($lists as $list) {
                $no++;
                $row = [];

                $buttonEdit = '<div class="d-flex"><a href="#" class="btn btn-warning btn-action edit-jurusan-btn mr-1" data-id="' . $list->id . '"><i class="fas fa-pencil-alt"></i></a>';
                $buttonDelete = '<a href="#" class="btn btn-danger delete-jurusan-btn" data-id="' . $list->id . '"><i class="fas fa-trash"></i></a></div>';

                $row[] = '<div class="custom-checkbox custom-control"><input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" name="id[]" value="' . $list->id . '"><label for="checkbox-2" class="custom-control-label">&nbsp;</label></div>';
                $row[] = $list->nama_jurusan;
                $row[] = $list->nama_guru;

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
                'nama_jurusan' => [
                    'rules' => 'required|is_unique[jurusan.nama_jurusan]',
                    'errors' => [
                        'required' => 'Nama jurusan harus diisi',
                        'is_unique' => 'Nama jurusan ini sudah ada',
                    ]
                ],
                'id_guru' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama jurusan harus diisi',
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
                    'id_guru' => $this->request->getVar('id_guru'),
                    'nama_jurusan' => $this->request->getVar('nama_jurusan'),
                ];
                $query = $this->jurusan->insert($data);

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Jurusan baru berhasil ditambahkan'
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

    public function getJurusan()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        if ($request->isAjax()) {
            $id = $request->getVar('id');

            $jurusan = $db->table('jurusan')->where('id', $id)->get()->getRowObject();
            $id_guru = $jurusan->id_guru;

            $data = [
                'jurusan' => $jurusan,
                'pilihan_guru' => $db->table('guru')->select('id, nama_guru')->where('id', $id_guru)->get()->getRowObject(),
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
                'nama_jurusan' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama jurusan harus diisi',
                    ]
                ],
                'id_guru' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama jurusan harus diisi',
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
                    'id_guru' => $this->request->getVar('id_guru'),
                    'nama_jurusan' => $this->request->getVar('nama_jurusan'),
                ];
                $query = $this->jurusan->where('id', $id)->set($data)->update();

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Jurusan berhasil diupdate'
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
            $query = $this->jurusan->delete($id);

            if ($query !== FALSE) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Jurusan berhasil dihapus'
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
        $jurusan = $db->table('jurusan')->select('jurusan.*, nama_guru')
            ->join('guru', 'guru.id = jurusan.id_guru')
            ->orderBy('nama_jurusan', 'asc')
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
        $activeWorksheet->setCellValue('A1', 'DATA JURUSAN');
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
        $activeWorksheet->setCellValue('B6', 'NAMA JURUSAN');
        $activeWorksheet->setCellValue('C6', 'KEPALA JURUSAN');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $column = 7;
        $no = 1;
        foreach ($jurusan as $key => $value) {
            $activeWorksheet->setCellValue('A' . $column, $no);
            $activeWorksheet->setCellValue('B' . $column, $value->nama_jurusan);
            $activeWorksheet->setCellValue('C' . $column, $value->nama_guru);

            $column++;
            $no++;
        }

        $dirPath = 'report/export/';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $filename = 'data-jurusan_' . date('d-m-y-H-i-s') . '.xlsx';
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
                $jurusan = $spreadsheet->getActiveSheet()->toArray();
                $db = \Config\Database::connect();

                foreach ($jurusan as $key => $value) {
                    if ($key < 6) {
                        continue;
                    }

                    if (!empty($value[1])) {
                        $nama_jurusan = $value[1];
                        $nama_guru = $value[2];

                        $guru = $db->table('guru')->where('nama_guru', $nama_guru)->get()->getRowObject();
                        $id_guru = $guru ? $guru->id : null;

                        $exists = $this->jurusan->where('nama_jurusan', $nama_jurusan)->countAllResults();

                        if ($exists == 0) {
                            $data = [
                                'id_guru' => $id_guru,
                                'nama_jurusan' => $nama_jurusan,
                            ];
                            $this->jurusan->insert($data);
                        }
                    }
                }

                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Jurusan baru berhasil ditambahkan'
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

    // public function multipleDelete()
    // {
    //     $request = \Config\Services::request();

    //     if ($request->isAJAX()) {
    //         $id = $request->getPost('id', true);
    //         $jumlahData = count($id);

    //         for ($i = 0; $i < $jumlahData; $i++) {
    //             $query = $this->jurusan->delete($id);
    //             $this->jurusan->delete('jurusan', ['id' => $id[$i]]);
    //         }

    //         if ($query !== FALSE) {
    //             return $this->response->setJSON([
    //                 'status' => 1,
    //                 'token' => csrf_hash(),
    //                 'msg' => `$jumlahData data jurusan berhasil dihapus`
    //             ]);
    //         } else {
    //             return $this->response->setJSON([
    //                 'status' => 0,
    //                 'token' => csrf_hash(),
    //                 'msg' => 'Ada yang tidak beres'
    //             ]);
    //         }
    //     }
    // }

    public function multipleDelete()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $id = $request->getPost('id');

            if (!empty($id)) {
                $jumlahData = count($id);
                $querySuccess = true;

                foreach ($id as $item) {
                    $query = $this->jurusan->delete($item);
                    if (!$query) {
                        $querySuccess = false;
                        break;
                    }
                }

                if ($querySuccess) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => "$jumlahData data jurusan berhasil dihapus"
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
