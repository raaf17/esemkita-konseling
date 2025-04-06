<?php

namespace App\Client\Home\Controllers;

use App\Controllers\BaseController;
use App\Modules\Konseling\Models\KonselingModel;
use App\Modules\Laporan\Models\LaporanModel;
use App\Modules\Users\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class HomeController extends BaseController
{
    protected $helpers = ['CIFunctions'];
    protected $konseling;
    protected $laporan;
    protected $user;

    public function __construct()
    {
        $this->konseling = new KonselingModel();
        $this->laporan = new LaporanModel();
        $this->user = new UserModel();
    }

    public function index()
    {
        $id = get_user()->id;
        $db = \Config\Database::connect();

        return view('App\Client\Home\Views\index');
    }
}
