<?php

namespace App\Modules\Settings\Models;

use CodeIgniter\Model;
use PascalDeVink\ShortUuid\ShortUuid;
use Ramsey\Uuid\Uuid;

class Setting extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['setting_meta_title', 'setting_meta_description', 'setting_meta_keyword', 'setting_nama_sekolah', 'setting_kepala_sekolah', 'setting_alamat', 'setting_latitude', 'setting_longitude', 'setting_email', 'setting_telepon', 'setting_logo', 'setting_favicon', 'created_at', 'updated_at', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = false;
    protected $beforeInsert     = ['generateUuid'];

    public function generateUuid(array $data)
    {
        $shortUuid = new ShortUuid();
        $uuid = Uuid::uuid4();
        $data['data']['id'] = $shortUuid->encode($uuid);

        return $data;
    }
}
