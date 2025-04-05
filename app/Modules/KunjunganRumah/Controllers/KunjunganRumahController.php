<?php

namespace App\Modules\KunjunganRumah\Controllers;

use App\Controllers\BaseController;
use App\Modules\Guru\Models\GuruModel;
use App\Modules\Kelas\Models\KelasModel;
use App\Modules\KunjunganRumah\Models\KunjunganRumahModel;
use App\Modules\Siswa\Models\SiswaModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use Exception;

class KunjunganRumahController extends BaseController
{
    protected $helpers = ['CIFunctions', 'CIPdf'];
    protected $kunjunganrumah;
    protected $siswa;
    protected $kelas;
    protected $guru;
    protected $db;

    public function __construct()
    {
        $this->kunjunganrumah = new KunjunganRumahModel();
        $this->siswa = new SiswaModel();
        $this->kelas = new KelasModel();
        $this->guru = new GuruModel();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $kunjungan_rumah = $db->table('mutasi')->get()->getFirstRow();

        $data = [
            'title' => 'Kunjungan Rumah',
            'count_status_pending' => $this->kunjunganrumah->where(['status' => 0])->countAllResults(),
            'count_status_done' => $this->kunjunganrumah->where(['status' => 1])->countAllResults(),
            'kunjungan_rumah' => $kunjungan_rumah,
            'pilihan_siswa' => $this->siswa->orderBy('nama_siswa', 'asc')->findAll(),
            'kelas' => $this->kelas->orderBy('nama_kelas', 'asc')->findAll(),
            'guru' => $this->guru->orderBy('nama_guru', 'asc')->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('App\Modules\KunjunganRumah\Views\index', $data);
    }

    public function listData()
    {
        $request = \Config\Services::request();
        $status = $this->request->getVar('status');
        $datamodel = new KunjunganRumahModel();

        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables($status);
            $data = [];
            $no = $request->getPost("start");

            foreach ($lists as $list) {
                $no++;
                $row = [];

                if ($list->status == 1) {
                    $buttonPdf = '<a href="#" class="btn btn-danger btn-center pdf-kunjunganrumah-btn mr-1" data-id="' . $list->id . '"><i class="fas fa-file-pdf"></i></a>';
                    $buttonDetail = '<a href="#" class="btn btn-primary btn-center detail-kunjunganrumah-btn" data-id="' . $list->id . '"><i class="fas fa-eye"></i></a>';
                    $actionButtons = $buttonPdf . $buttonDetail;
                } else {
                    $buttonPdf = '<div class="d-flex"><a href="#" class="btn btn-danger btn-center pdf-kunjunganrumah-btn mr-1" data-id="' . $list->id . '"><i class="fas fa-file-pdf"></i></a>';
                    $buttonDetail = '<a href="#" class="btn btn-primary detail-kunjunganrumah-btn mr-1" data-id="' . $list->id . '"><i class="fas fa-eye"></i></a>';
                    $buttonEdit = '<a href="#" class="btn btn-warning btn-action edit-kunjunganrumah-btn mr-1" data-id="' . $list->id . '"><i class="fas fa-pencil-alt"></i></a>';
                    $buttonDone = '<a href="#" class="btn btn-success done-kunjunganrumah-btn mr-1" data-id="' . $list->id . '"><i class="fas fa-check-circle"></i></a>';
                    $actionButtons = $buttonPdf . $buttonDetail . $buttonEdit . $buttonDone;
                }

                $row[] = '<div class="custom-checkbox custom-control"><input type="checkbox" class="custom-control-input check" name="id[]" value="' . $list->id . '"><label for="" class="custom-control-label"></label></div>';
                $row[] = $list->nama_siswa;
                $row[] = $list->alamat;
                $row[] = $list->anggota_keluarga;
                $row[] = date('d M Y', strtotime($list->tanggal)) . ' - ' . date('h:i A', strtotime($list->jam));
                if ($list->status == 1) {
                    $status = '<span class="badge badge-primary" style="padding: 6px 8px; border-radius: 25px;">Selesai</span>';
                } else {
                    $status = '<span class="badge badge-warning" style="padding: 6px 8px; border-radius: 25px;">Menunggu</span>';
                }
                $row[] = $status;

                $row[] = $actionButtons;
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
                'id_siswa' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama siswa harus diisi',
                    ]
                ],
                'id_kelas' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Kelas harus diisi',
                    ]
                ],
                'id_guru' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Guru harus diisi',
                    ]
                ],
                'alamat' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Alamat harus diisi',
                    ]
                ],
                'tanggal' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Tanggal harus diisi',
                    ]
                ],
                'jam' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Jam harus diisi',
                    ]
                ],
                'anggota_keluarga' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Anggota keluarga harus diisi',
                    ]
                ],
                'ringkasan_masalah' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Ringkasan masalah harus diisi',
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
                    'id_kelas' => $this->request->getVar('id_kelas'),
                    'id_guru' => $this->request->getVar('id_guru'),
                    'id_siswa' => $this->request->getVar('id_siswa'),
                    'alamat' => $this->request->getVar('alamat'),
                    'tanggal' => $this->request->getVar('tanggal'),
                    'jam' => $this->request->getVar('jam'),
                    'anggota_keluarga' => $this->request->getVar('anggota_keluarga'),
                    'ringkasan_masalah' => $this->request->getVar('ringkasan_masalah'),
                    'hasil_kunjungan' => $this->request->getVar('hasil_kunjungan'),
                    'rencana_tindak_lanjut' => $this->request->getVar('rencana_tindak_lanjut'),
                    'catatan_khusus' => $this->request->getVar('catatan_khusus'),
                    'keterangan' => $this->request->getVar('keterangan'),
                ];
                $query = $this->kunjunganrumah->insert($data);

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Surat kunjungan rumah berhasil dibuat'
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

    public function getKunjunganRumah()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        if ($request->isAjax()) {
            $id = $request->getVar('id');
            $data = $db->table('kunjungan_rumah')->where('id', $id)->get()->getRowObject();

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
                'id_siswa' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama siswa harus diisi',
                    ]
                ],
                'id_kelas' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Kelas harus diisi',
                    ]
                ],
                'id_guru' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Guru harus diisi',
                    ]
                ],
                'alamat' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Alamat harus diisi',
                    ]
                ],
                'tanggal' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Tanggal harus diisi',
                    ]
                ],
                'jam' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Jam harus diisi',
                    ]
                ],
                'anggota_keluarga' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Anggota keluarga harus diisi',
                    ]
                ],
                'ringkasan_masalah' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Ringkasan masalah harus diisi',
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
                    'id_kelas' => $this->request->getVar('id_kelas'),
                    'id_guru' => $this->request->getVar('id_guru'),
                    'id_siswa' => $this->request->getVar('id_siswa'),
                    'alamat' => $this->request->getVar('alamat'),
                    'tanggal' => $this->request->getVar('tanggal'),
                    'jam' => $this->request->getVar('jam'),
                    'anggota_keluarga' => $this->request->getVar('anggota_keluarga'),
                    'ringkasan_masalah' => $this->request->getVar('ringkasan_masalah'),
                    'hasil_kunjungan' => $this->request->getVar('hasil_kunjungan'),
                    'rencana_tindak_lanjut' => $this->request->getVar('rencana_tindak_lanjut'),
                    'catatan_khusus' => $this->request->getVar('catatan_khusus'),
                    'keterangan' => $this->request->getVar('keterangan'),
                ];
                $query = $this->kunjunganrumah->where('id', $id)->set($data)->update();

                if ($query !== FALSE) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'token' => csrf_hash(),
                        'msg' => 'Surat kunjungan rumah berhasil diupdate'
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
            $query = $this->kunjunganrumah->delete($id);

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

    public function done()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $id = $request->getVar('id');
            $query = $this->kunjunganrumah->where('id', $id)->set(['status' => 1])->update();

            if ($query !== FALSE) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Siswa sudah dikunjungi'
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
            $data = $db->table('kunjungan_rumah')
                ->select('kunjungan_rumah.*, kelas.nama_kelas, guru.nama_guru, siswa.nama_siswa')
                ->join('kelas', 'kelas.id = kunjungan_rumah.id_kelas', 'left')
                ->join('guru', 'guru.id = kunjungan_rumah.id_guru', 'left')
                ->join('siswa', 'siswa.id = kunjungan_rumah.id_siswa', 'left')
                ->where('kunjungan_rumah.id', $id)
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
        $siswa = $db->table('kunjungan_rumah')->select('kunjungan_rumah.*, kelas.nama_kelas, guru.nama_guru, siswa.nama_siswa')
            ->join('kelas', 'kelas.id = kunjungan_rumah.id_kelas', 'left')
            ->join('guru', 'guru.id = kunjungan_rumah.id_guru', 'left')
            ->join('siswa', 'siswa.id = kunjungan_rumah.id_siswa', 'left')
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

        $activeWorksheet->mergeCells('A1:N1');
        $activeWorksheet->setCellValue('A1', 'DATA KUNJUNGAN RUMAH');
        $activeWorksheet->getStyle('A1')->applyFromArray($styleArrayCenterBold20px);

        $activeWorksheet->mergeCells('A2:N2');
        $activeWorksheet->setCellValue('A2', $nama_sekolah);
        $activeWorksheet->getStyle('A2')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('A3:N3');
        $activeWorksheet->setCellValue('A3', $tanggal_hari_ini);
        $activeWorksheet->getStyle('A3')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('A4:N4');
        $activeWorksheet->setCellValue('A4', $pengunduh);
        $activeWorksheet->getStyle('A4')->applyFromArray($styleArraycenter);

        $activeWorksheet->getStyle('A1:N1')->getFont()->setName('Consolas');
        $activeWorksheet->getStyle('A2:N2')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('A3:N3')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('A4:N4')->getFont()->setName('Consolas')->setSize(10);

        foreach (range('A', 'N') as $columnID) {
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

        $activeWorksheet->getStyle('A6:N6')->applyFromArray($styleArray);
        $activeWorksheet->setCellValue('A6', 'NO');
        $activeWorksheet->setCellValue('B6', 'NAMA SISWA');
        $activeWorksheet->setCellValue('C6', 'KELAS');
        $activeWorksheet->setCellValue('D6', 'GURU YANG MENGUNJUNGI');
        $activeWorksheet->setCellValue('E6', 'ALAMAT');
        $activeWorksheet->setCellValue('F6', 'TANGGAL');
        $activeWorksheet->setCellValue('G6', 'JAM');
        $activeWorksheet->setCellValue('H6', 'ANGGOTA KELUARGA');
        $activeWorksheet->setCellValue('I6', 'RINGKASAN MASALAH');
        $activeWorksheet->setCellValue('J6', 'HASIL KUNJUNGAN');
        $activeWorksheet->setCellValue('K6', 'RENCANA TINDAK LANJUT');
        $activeWorksheet->setCellValue('L6', 'CATATAN KHUSUS');
        $activeWorksheet->setCellValue('M6', 'KETERANGAN');
        $activeWorksheet->setCellValue('N6', 'STATUS');

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
            $activeWorksheet->setCellValue('B' . $column, $value->nama_siswa);
            $activeWorksheet->setCellValue('C' . $column, $value->nama_kelas);
            $activeWorksheet->setCellValue('D' . $column, $value->nama_guru);
            $activeWorksheet->setCellValue('E' . $column, $value->alamat);
            $activeWorksheet->setCellValue('F' . $column, $value->tanggal);
            $activeWorksheet->setCellValue('G' . $column, $value->jam);
            $activeWorksheet->setCellValue('H' . $column, $value->anggota_keluarga);
            $activeWorksheet->setCellValue('I' . $column, $value->ringkasan_masalah);
            $activeWorksheet->setCellValue('J' . $column, $value->hasil_kunjungan);
            $activeWorksheet->setCellValue('K' . $column, $value->rencana_tindak_lanjut);
            $activeWorksheet->setCellValue('L' . $column, $value->catatan_khusus);
            $activeWorksheet->setCellValue('M' . $column, $value->keterangan);
            if ($value->status == 1) {
                $status = 'Sudah Dikunjungi';
            } else {
                $status = 'Belum Dikunjungi';
            }
            $activeWorksheet->setCellValue('N' . $column, $status);

            $column++;
            $no++;
        }

        $dirPath = 'report/export/';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $filename = 'data-kunjungan-rumah_' . date('d-m-y-H-i-s') . '.xlsx';
        $filePath = $dirPath . $filename;

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    // public function pdfSurat()
    // {
    //     $request = \Config\Services::request();
    //     $db = \Config\Database::connect();

    //     if ($request->isAJAX()) {
    //         $id = $request->getVar('id');
    //         $data = $db->table('kunjungan_rumah')
    //             ->select('kunjungan_rumah.*, kelas.nama_kelas, guru.nama_guru, siswa.nama_siswa')
    //             ->join('kelas', 'kelas.id = kunjungan_rumah.id_kelas', 'left')
    //             ->join('guru', 'guru.id = kunjungan_rumah.id_guru', 'left')
    //             ->join('siswa', 'siswa.id = kunjungan_rumah.id_siswa', 'left')
    //             ->where('kunjungan_rumah.id', $id)
    //             ->get()->getRowObject();

    //         if (!$data) {
    //             return $this->response->setStatusCode(404)
    //                 ->setJSON(['error' => 'Data not found']);
    //         }

    //         Carbon::setLocale('id');

    //         $hari = \Carbon\Carbon::parse($data->tanggal)->translatedFormat('l');
    //         $jam = \Carbon\Carbon::parse($data->jam)->format('H:i');
    //         $tanggal = \Carbon\Carbon::parse($data->tanggal)->format('d F Y');

    //         $html = '
    //             <!DOCTYPE html>
    //             <html lang="id">
    //             <head>
    //                 <meta charset="UTF-8">
    //                 <meta name="viewport" content="width=device-width, initial-scale=1.0">
    //                 <title>Surat Panggilan Orang Tua</title>
    //                 <style>
    //                     body { font-family: Arial, sans-serif; line-height: 1.5; }
    //                     .container { width: 650px; margin: 0 auto; padding: 20px; }
    //                     .header { text-align: center; margin-bottom: 20px; }
    //                     .header h3, .header h2, .title h2 { margin: 0; }
    //                     .title { text-align: center; margin-bottom: 20px; }
    //                     .content { margin-bottom: 40px; }
    //                     table { width: 100%; margin-bottom: 20px; }
    //                     .footer { margin-top: 40px; text-align: center; }
    //                 </style>
    //             </head>
    //             <body>
    //                 <div class="container">
    //                     <div class="header">
    //                         <h3>PEMERINTAH PROVINSI JAWA TIMUR</h3>
    //                         <h3>DINAS PENDIDIKAN</h3>
    //                         <h2>' . get_settings()->setting_nama_sekolah . '</h2>
    //                         <p>Jl. Ki Mangun Sarkoro, Beji - Boyolangu - Tulungagung Telp./Fax : (0336) 444112</p>
    //                         <p style="margin-top: -15px;">' . get_settings()->setting_email . '</p>
    //                     </div>
    //                     <hr>
    //                     <div class="title">
    //                         <h2>SURAT PANGGILAN</h2>
    //                         <p>No: ......../SPS/SDM.3/2023</p>
    //                     </div>
    //                     <div class="content">
    //                         <p>Assalamu\'alaikum Wr. Wb.</p>
    //                         <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bersama surat ini kami mengharapkan kehadiran orang tua/wali murid dari :</p>
    //                         <table>
    //                             <tr><td>Nama</td><td>:</td><td>' . $data->nama_siswa . '</td></tr>
    //                             <tr><td>Kelas</td><td>:</td><td>' . $data->nama_kelas . '</td></tr>
    //                         </table>
    //                         <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Untuk dapat datang menghadap hari <b>' . $hari . '</b> pukul <b>' . $jam . '</b> WIB di Ruang BK ' . get_settings()->setting_nama_sekolah . ' dikarenakan anak kita tersebut di atas <b>' . $data->ringkasan_masalah . '</b>.</p>
    //                         <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Demikianlah Surat Panggilan ini kami sampaikan kepada orang tua/wali murud semoga dapat dimaklumi, atas kehadirannya kami ucapkan terima kasih.</p>
    //                         <p>Wassalamu\'alaikum Wr. Wb.</p>
    //                     </div>
    //                     <div class="footer">
    //                         <p style="relative: absolute; margin-left: 400px;">Tulungagung, ' . $tanggal . '</p>
    //                         <table style="margin-top: -16px;">
    //                             <tr><td><p style="relative: absolute; margin-left: 460px; margin-bottom: -48px;"></p></td></tr>
    //                             <tr><td>Wali Kelas,</td><td>Guru BK,</td></tr>
    //                             <tr><td><br><br><br></td></tr>
    //                             <tr><td>.............................</td><td>.............................</td></tr>
    //                         </table>
    //                     </div>
    //                 </div>
    //             </body>
    //             </html>';

    //         $dompdf = new Dompdf();
    //         $dompdf->loadHtml($html);
    //         $dompdf->setPaper('A4', 'portrait');
    //         $dompdf->render();

    //         return $this->response->setHeader('Content-Type', 'application/pdf')
    //             ->setHeader('Content-Disposition', 'inline; filename="' . $data->nama_siswa . '.pdf"')
    //             ->setBody($dompdf->output());
    //     }
    // }

    public function pdfSurat()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        $id = $request->getVar('id');

        $data = $db->table('kunjungan_rumah')
            ->select('kunjungan_rumah.*, kelas.nama_kelas, guru.nama_guru, siswa.nama_siswa')
            ->join('kelas', 'kelas.id = kunjungan_rumah.id_kelas', 'left')
            ->join('guru', 'guru.id = kunjungan_rumah.id_guru', 'left')
            ->join('siswa', 'siswa.id = kunjungan_rumah.id_siswa', 'left')
            ->where('kunjungan_rumah.id', $id)
            ->get()->getRowObject();

        if (!$data) {
            return $this->response->setStatusCode(404)
                ->setJSON(['error' => 'Data not found']);
        }

        $hari = \Carbon\Carbon::parse($data->tanggal)->translatedFormat('l');
        $jam = \Carbon\Carbon::parse($data->jam)->format('H:i');
        $tanggal = \Carbon\Carbon::parse($data->tanggal)->format('d F Y');

        $html = '
            <!DOCTYPE html>
                    <html lang="id">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Surat Panggilan Orang Tua</title>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h3>PEMERINTAH PROVINSI JAWA TIMUR</h3>
                                <h3>DINAS PENDIDIKAN</h3>
                                <h2>' . get_settings()->setting_nama_sekolah . '</h2>
                                <p>Jl. Ki Mangun Sarkoro, Beji - Boyolangu - Tulungagung Telp./Fax : (0336) 444112</p>
                                <p style="margin-top: -15px;">' . get_settings()->setting_email . '</p>
                            </div>
                            <hr>
                            <div class="title">
                                <h2>SURAT PANGGILAN</h2>
                                <p>No: ......../SPS/SDM.3/2023</p>
                            </div>
                            <div class="content">
                                <p>Assalamu\'alaikum Wr. Wb.</p>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bersama surat ini kami mengharapkan kehadiran orang tua/wali murid dari :</p>
                                <table>
                                    <tr><td>Nama</td><td>:</td><td>' . $data->nama_siswa . '</td></tr>
                                    <tr><td>Kelas</td><td>:</td><td>' . $data->nama_kelas . '</td></tr>
                                </table>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Untuk dapat datang menghadap hari <b>' . $hari . '</b> pukul <b>' . $jam . '</b> WIB di Ruang BK ' . get_settings()->setting_nama_sekolah . ' dikarenakan anak kita tersebut di atas <b>' . $data->ringkasan_masalah . '</b>.</p>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Demikianlah Surat Panggilan ini kami sampaikan kepada orang tua/wali murud semoga dapat dimaklumi, atas kehadirannya kami ucapkan terima kasih.</p>
                                <p>Wassalamu\'alaikum Wr. Wb.</p>
                            </div>
                            <div class="footer">
                                <p style="relative: absolute; margin-left: 400px;">Tulungagung, ' . $tanggal . '</p>
                                <table style="margin-top: -16px;">
                                    <tr><td><p style="relative: absolute; margin-left: 460px; margin-bottom: -48px;"></p></td></tr>
                                    <tr><td>Wali Kelas,</td><td>Guru BK,</td></tr>
                                    <tr><td><br><br><br></td></tr>
                                    <tr><td>.............................</td><td>.............................</td></tr>
                                </table>
                            </div>
                        </div>
                    </body>
                    </html>';

        createPdf(array(
            'data'          => $html,
            'json'          => true,
            'paper_size'    => 'LEGAL-L',
            'file_name'     => 'Surat Panggilan Orang Tua',
            'title'         => 'Surat Panggilan Orang Tua',
            'stylesheet'    => ROOTPATH . './assets/letter/print.css',
            'margin'        => '10 8 10 10',
            'font_face'     => 'freesans',
            'font_size'     => '8',
        ));
    }
}
