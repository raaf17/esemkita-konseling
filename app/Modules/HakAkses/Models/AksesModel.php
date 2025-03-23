<?php

namespace App\Modules\HakAkses\Models;

use CodeIgniter\Model;

class AksesModel extends Model
{
    protected $table            = 'sys_akses';
    protected $returnType       = 'object';
    protected $allowedFields    = ['kode_menu', 'role', 'akses', 'tambah', 'edit', 'hapus', 'user', 'created_at', 'updated_at', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = false;
}
