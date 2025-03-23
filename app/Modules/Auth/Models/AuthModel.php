<?php

namespace App\Modules\Auth\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['id_pegawai', 'username', 'password', 'status', 'role', 'created_at', 'updated_at', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = false;
}
