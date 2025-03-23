<?php

namespace App\Modules\KunjunganRumah\Models;

use CodeIgniter\Model;
use PascalDeVink\ShortUuid\ShortUuid;
use Ramsey\Uuid\Uuid;

class KunjunganRumahModel extends Model
{
    protected $table            = 'kunjungan_rumah';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'id_kelas', 'id_guru', 'id_siswa', 'alamat', 'tanggal', 'jam', 'anggota_keluarga', 'ringkasan_masalah', 'hasil_kunjungan', 'rencana_tindak_lanjut', 'catatan_khusus', 'keterangan', 'status', 'created_at', 'updated_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = false;
    protected $beforeInsert     = ['generateUuid'];

    protected $column_order = array(null, 'nama_siswa', 'alamat', 'anggota_keluarga', 'tanggal', 'jam', 'status', null);
    protected $column_search = array('nama_siswa');
    protected $order = array('created_at' => 'desc');
    protected $request;
    protected $db;
    protected $dt;

    function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->request = \Config\Services::request();

        $this->dt = $this->db->table($this->table)->select('kunjungan_rumah.*, kelas.nama_kelas, guru.nama_guru, siswa.nama_siswa')
            ->join('kelas', 'kelas.id = kunjungan_rumah.id_kelas', 'left')
            ->join('guru', 'guru.id = kunjungan_rumah.id_guru', 'left')
            ->join('siswa', 'siswa.id = kunjungan_rumah.id_siswa', 'left');
    }

    private function _get_datatables_query($status = null)
    {
        $i = 0;
        if ($status == 'pending') {
            $this->dt->where('kunjungan_rumah.status', 0);
        } elseif ($status == 'done') {
            $this->dt->where('kunjungan_rumah.status', 1);
        }

        foreach ($this->column_search as $item) {
            if ($this->request->getPost('search')['value']) {
                if ($i === 0) {
                    $this->dt->groupStart();
                    $this->dt->like($item, $this->request->getPost('search')['value']);
                } else {
                    $this->dt->orLike($item, $this->request->getPost('search')['value']);
                }
                if (count($this->column_search) - 1 == $i)
                    $this->dt->groupEnd();
            }
            $i++;
        }

        if ($this->request->getPost('order')) {
            $this->dt->orderBy($this->column_order[$this->request->getPost('order')['0']['column']], $this->request->getPost('order')['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->dt->orderBy(key($order), $order[key($order)]);
        }
    }

    function get_datatables($status = null)
    {
        $this->_get_datatables_query($status);
        if ($this->request->getPost('length') != -1)
            $this->dt->limit($this->request->getPost('length'), $this->request->getPost('start'));
        $query = $this->dt->get();
        return $query->getResult();
    }

    function count_filtered($status = null)
    {
        $this->_get_datatables_query($status);
        return $this->dt->countAllResults();
    }

    public function count_all()
    {
        return $this->dt->countAllResults();
    }

    public function generateUuid(array $data)
    {
        $shortUuid = new ShortUuid();
        $uuid = Uuid::uuid4();
        $data['data']['id'] = $shortUuid->encode($uuid);

        return $data;
    }
}
