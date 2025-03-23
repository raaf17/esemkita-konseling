<?php

namespace App\Modules\Siswa\Models;

use CodeIgniter\Model;
use PascalDeVink\ShortUuid\ShortUuid;
use Ramsey\Uuid\Uuid;

class SiswaModel extends Model
{
    protected $table            = 'siswa';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'nisn', 'id_kelas', 'nama_siswa', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'no_handphone', 'nama_ayah', 'nama_ibu', 'jenis_kelamin', 'agama', 'status_keluarga', 'created_at', 'updated_at', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = false;
    protected $beforeInsert     = ['generateUuid'];

    protected $column_order = array(null, 'nisn', 'nama_siswa', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', null);
    protected $column_search = array('nisn', 'nama_siswa', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin');
    protected $order = array('nama_kelas' => 'asc');
    protected $request;
    protected $db;
    protected $dt;

    function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->request = \Config\Services::request();

        $this->dt = $this->db->table($this->table)->select('siswa.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left');
    }

    private function _get_datatables_query()
    {
        $i = 0;
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
            $this->dt->orderBy(key($order), $order[key($order)])->orderBy('nama_siswa', 'asc');
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($this->request->getPost('length') != -1)
            $this->dt->limit($this->request->getPost('length'), $this->request->getPost('start'));
        $query = $this->dt->get();
        return $query->getResult();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->dt->countAllResults();
    }

    public function count_all()
    {
        $tbl_storage = $this->db->table($this->table);
        return $tbl_storage->countAllResults();
    }

    public function generateUuid(array $data)
    {
        $shortUuid = new ShortUuid();
        $uuid = Uuid::uuid4();
        $data['data']['id'] = $shortUuid->encode($uuid);

        return $data;
    }

    public function DashboardJenisKelaminSiswa()
    {
        $db = \Config\Database::connect();
        $query = $db->table('siswa')
            ->select('siswa.jenis_kelamin, COUNT(siswa.id) AS jumlah')
            ->join('kelas', 'kelas.id = siswa.id_kelas')
            ->groupBy('siswa.jenis_kelamin')
            ->get();

        return $query->getResultArray();
    }

    public function DashboardAgamaSiswa()
    {
        $db = \Config\Database::connect();
        $query = $db->table('siswa')
            ->select('siswa.agama, COUNT(siswa.id) AS jumlah')
            ->join('kelas', 'kelas.id = siswa.id_kelas')
            ->groupBy('siswa.agama')
            ->get();

        return $query->getResultArray();
    }

    public function DashboardStatusSiswa()
    {
        $db = \Config\Database::connect();
        $query = $db->table('siswa')
            ->select('siswa.status_keluarga, COUNT(siswa.id) AS jumlah')
            ->join('kelas', 'kelas.id = siswa.id_kelas')
            ->groupBy('siswa.status_keluarga')
            ->get();

        return $query->getResultArray();
    }
}
