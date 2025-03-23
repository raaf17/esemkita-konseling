<?php

namespace App\Libraries;

use Ramsey\Uuid\Uuid;
use PascalDeVink\ShortUuid\ShortUuid;

class GenerateUuid
{
    public function uuid()
    {
        $uuid = Uuid::uuid4();
        $shortUuid = new ShortUuid();

        return $shortUuid->encode($uuid);
    }
}
