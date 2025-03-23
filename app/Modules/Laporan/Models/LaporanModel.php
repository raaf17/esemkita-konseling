<?php

namespace App\Modules\Laporan\Models;

use CodeIgniter\Model;
use PascalDeVink\ShortUuid\ShortUuid;
use Ramsey\Uuid\Uuid;

class LaporanModel extends Model
{
    protected $table            = 'laporan';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'id_siswa', 'nama_terlapor', 'judul', 'tanggal', 'lokasi', 'deskripsi', 'status', 'foto', 'created_at', 'updated_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = false;
    protected $beforeInsert     = ['generateUuid'];

    protected $column_order = array(null, 'nama_terlapor', 'judul', 'tanggal', 'lokasi', 'status', 'foto', null);
    protected $column_search = array('nama_terlapor');
    protected $order = array('created_at' => 'desc');
    protected $request;
    protected $db;
    protected $dt;

    function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->request = \Config\Services::request();
        $userdata = session()->get('userdata');

        if ($userdata->role == 'SuperAdmin' || $userdata->role == 'Guru') {
            $this->dt = $this->db->table($this->table)
                ->select('laporan.*, siswa.nama_siswa')
                ->join('siswa', 'siswa.id = laporan.id_siswa', 'left');
        } else {
            $siswa = $this->db->table('siswa')->select('id')->where('nama_siswa', $userdata->nama)->get()->getRowObject();
            $this->dt = $this->db->table($this->table)
                ->select('laporan.*, siswa.nama_siswa')
                ->join('siswa', 'siswa.id = laporan.id_siswa', 'left')
                ->where('id_siswa', $siswa->id);
        }
    }

    private function _get_datatables_query($status = null)
    {
        $i = 0;
        if ($status == 'pending') {
            $this->dt->where('laporan.status', 0);
        } elseif ($status == 'processed') {
            $this->dt->where('laporan.status', 1);
        } elseif ($status == 'rejected') {
            $this->dt->where('laporan.status', 2);
        } elseif ($status == 'done') {
            $this->dt->whereIn('laporan.status', [1, 2]);
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

    public function allLaporan()
    {
        $db = \Config\Database::connect();
        $query = $db->table('laporan')
            ->select('laporan.*, siswa.nama_siswa')
            ->join('siswa', 'siswa.id = laporan.id_siswa')
            ->limit(4)
            ->orderBy('laporan.created_at', 'DESC');

        return $query->get()->getResultObject();
    }
}
