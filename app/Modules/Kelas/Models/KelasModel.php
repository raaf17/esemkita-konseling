<?php

namespace App\Modules\Kelas\Models;

use CodeIgniter\Model;
use PascalDeVink\ShortUuid\ShortUuid;
use Ramsey\Uuid\Uuid;

class KelasModel extends Model
{
    protected $table            = 'kelas';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'id_jurusan', 'id_guru', 'nama_kelas', 'created_at', 'updated_at', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = false;
    protected $beforeInsert     = ['generateUuid'];

    protected $column_order = array(null, 'nama_kelas', 'nama_jurusan', 'nama_guru', null);
    protected $column_search = array('nama_kelas', 'nama_jurusan', 'nama_guru');
    protected $order = array('nama_kelas' => 'asc');
    protected $request;
    protected $db;
    protected $dt;

    function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->request = \Config\Services::request();

        $this->dt = $this->db->table($this->table)->select('kelas.*, jurusan.nama_jurusan, guru.nama_guru')
            ->join('jurusan', 'jurusan.id = kelas.id_jurusan', 'left')
            ->join('guru', 'guru.id = kelas.id_guru', 'left');
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
            $this->dt->orderBy(key($order), $order[key($order)]);
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
}
