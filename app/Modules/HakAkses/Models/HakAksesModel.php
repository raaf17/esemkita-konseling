<?php

namespace App\Modules\HakAkses\Models;

use CodeIgniter\Model;

class HakAksesModel extends Model
{
    protected $table            = 'sys_menu';
    protected $returnType       = 'object';
    protected $allowedFields    = ['kode_menu', 'nama_menu', 'url', 'icon', 'level', 'main_menu', 'aktif', 'no_urut', 'user', 'created_at', 'updated_at', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = false;
}
