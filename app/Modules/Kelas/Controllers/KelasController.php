<?php

namespace App\Modules\Kelas\Controllers;

use App\Controllers\BaseController;
use App\Modules\Guru\Models\GuruModel;
use App\Modules\Jurusan\Models\JurusanModel;
use App\Modules\Kelas\Models\KelasModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class KelasController extends BaseController
{
    protected $helpers = ['CIFunctions'];
    protected $kelas;
    protected $guru;
    protected $jurusan;
    protected $db;

    public function __construct()
    {
        $this->kelas = new KelasModel();
        $this->guru = new GuruModel();
        $this->jurusan = new JurusanModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $kelas = $db->table('kelas')->get()->getFirstRow();

        $data = [
            'title' => 'Kelas',
            'kelas' => $kelas,
            'choice_of_major' => $this->jurusan->orderBy('nama_jurusan', 'asc')->findAll(),
            'choice_of_teacher' => $this->guru->orderBy('nama_guru', 'asc')->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('App\Modules\Kelas\Views\index', $data);
    }


    public function listData()
    {
        $request = \Config\Services::request();
        $datamodel = new KelasModel();

        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getVar("start");

            foreach ($lists as $list) {
                $no++;
                $row = [];

                $buttonEdit = '<div class="d-flex"><button type="button" class="btn btn-warning mr-1" onclick="onEdit(\'' . $list->id . '\')"><i class="fas fa-pencil-alt"></i></button>';
                $buttonDelete = '<button type="button" class="btn btn-danger" onclick="onDelete(\'' . $list->id . '\')"><i class="fas fa-trash"></i></button></div>';

                $row[] = '<div class="custom-checkbox custom-control"><input type="checkbox" class="custom-control-input check" name="id[]" value="' . $list->id . '"><label for="" class="custom-control-label"></label></div>';
                $row[] = $list->nama_kelas;
                $row[] = $list->nama_jurusan;
                $row[] = $list->nama_guru;

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
                'nama_kelas' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama kelas harus diisi',
                    ]
                ],
                'id_jurusan' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama jurusan harus diisi',
                    ]
                ],
                'id_guru' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama guru harus diisi',
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
                    'nama_kelas' => $this->request->getVar('nama_kelas'),
                    'id_jurusan' => $this->request->getVar('id_jurusan'),
                    'id_guru' => $this->request->getVar('id_guru'),
                ];
                $query = $this->kelas->insert($data);

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Kelas baru berhasil ditambahkan'
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

    public function getKelas()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        if ($request->isAjax()) {
            $id = $request->getVar('id');

            $kelas = $db->table('kelas')->where('id', $id)->get()->getRowObject();
            $id_jurusan = $kelas->id_jurusan;
            $id_guru = $kelas->id_guru;

            $data = [
                'kelas' => $kelas,
                'choice_of_major' => $db->table('jurusan')->select('id')->where('id', $id_jurusan)->get()->getRowObject(),
                'choice_of_teacher' => $db->table('guru')->select('id')->where('id', $id_guru)->get()->getRowObject(),
            ];

            return $this->response->setJSON([
                'data' => $kelas
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
                'nama_kelas' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama kelas harus diisi',
                    ]
                ],
                'id_jurusan' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama jurusan harus diisi',
                    ]
                ],
                'id_guru' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama guru harus diisi',
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
                    'nama_kelas' => $this->request->getVar('nama_kelas'),
                    'id_jurusan' => $this->request->getVar('id_jurusan'),
                    'id_guru' => $this->request->getVar('id_guru'),
                ];
                $query = $this->kelas->where('id', $id)->set($data)->update();

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Kelas berhasil diupdate'
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
            $query = $this->kelas->delete($id);

            if ($query !== FALSE) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Kelas berhasil dihapus'
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
        $kelas = $db->table('kelas')->select('kelas.*, jurusan.nama_jurusan, guru.nama_guru')
            ->join('jurusan', 'jurusan.id = kelas.id_jurusan', 'left')
            ->join('guru', 'guru.id = kelas.id_guru', 'left')
            ->orderBy('nama_kelas', 'asc')
            ->get()
            ->getResultObject();

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

        $activeWorksheet->mergeCells('A1:D1');
        $activeWorksheet->setCellValue('A1', 'DATA KELAS');
        $activeWorksheet->getStyle('A1')->applyFromArray($styleArrayCenterBold20px);

        $activeWorksheet->mergeCells('A2:D2');
        $activeWorksheet->setCellValue('A2', $nama_sekolah);
        $activeWorksheet->getStyle('A2')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('A3:D3');
        $activeWorksheet->setCellValue('A3', $tanggal_hari_ini);
        $activeWorksheet->getStyle('A3')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('A4:D4');
        $activeWorksheet->setCellValue('A4', $pengunduh);
        $activeWorksheet->getStyle('A4')->applyFromArray($styleArraycenter);

        $activeWorksheet->getStyle('A1:D1')->getFont()->setName('Consolas');
        $activeWorksheet->getStyle('A2:D2')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('A3:D3')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('A4:D4')->getFont()->setName('Consolas')->setSize(10);

        foreach (range('A', 'D') as $columnID) {
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

        $activeWorksheet->getStyle('A6:D6')->applyFromArray($styleArray);
        $activeWorksheet->setCellValue('A6', 'NO');
        $activeWorksheet->setCellValue('B6', 'NAMA KELAS');
        $activeWorksheet->setCellValue('C6', 'NAMA JURUSAN');
        $activeWorksheet->setCellValue('D6', 'NAMA GURU');

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
            $activeWorksheet->setCellValue('B' . $column, $value->nama_kelas);
            $activeWorksheet->setCellValue('C' . $column, $value->nama_jurusan);
            $activeWorksheet->setCellValue('D' . $column, $value->nama_guru);

            $column++;
            $no++;
        }

        $dirPath = 'report/export/';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $filename = 'data-kelas_' . date('d-m-y-H-i-s') . '.xlsx';
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
                $kelas = $spreadsheet->getActiveSheet()->toArray();
                $db = \Config\Database::connect();

                foreach ($kelas as $key => $value) {
                    if ($key < 6) {
                        continue;
                    }

                    if (!empty($value[1])) {
                        $nama_kelas = $value[1];
                        $nama_jurusan = $value[2];
                        $nama_guru = $value[3];

                        $jurusan = $db->table('jurusan')->where('nama_jurusan', $nama_jurusan)->get()->getRowObject();
                        $id_jurusan = $jurusan ? $jurusan->id : null;

                        $guru = $db->table('guru')->where('nama_guru', $nama_guru)->get()->getRowObject();
                        $id_guru = $guru ? $guru->id : null;

                        $exists = $this->kelas->where('nama_kelas', $nama_kelas)->countAllResults();

                        if ($exists == 0) {
                            $data = [
                                'nama_kelas' => $nama_kelas,
                                'id_jurusan' => $id_jurusan,
                                'id_guru' => $id_guru,
                            ];
                            $this->kelas->insert($data);
                        }
                    }
                }

                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Kelas baru berhasil ditambahkan'
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
                    $query = $this->kelas->delete($item);
                    if (!$query) {
                        $querySuccess = false;
                        break;
                    }
                }

                if ($querySuccess) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => "$jumlahData data kelas berhasil dihapus"
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

    public function comboboxJurusan()
    {
        $data = $this->jurusan
            ->select(['id', 'nama_jurusan'])
            ->orderBy('nama_jurusan', 'ASC')
            ->findAll();

        return $this->response->setJSON($data);
    }

    public function comboboxGuru()
    {
        $data = $this->guru
            ->select(['id', 'nama_guru'])
            ->orderBy('nama_guru', 'ASC')
            ->findAll();

        return $this->response->setJSON($data);
    }
}
