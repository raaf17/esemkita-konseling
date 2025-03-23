<?php

namespace App\Modules\Profile\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Modules\Users\Models\UserModel;

class ProfileController extends BaseController
{
    protected $helpers = ['CIFunctions'];
    protected $user;

    public function __construct()
    {
        $this->user = new UserModel();
    }

    public function index()
    {
        $id = get_user()->id;
        $data = [
            'title' => 'Profil Pengguna',
            'validation' => \Config\Services::validation(),
            'user' => $this->user->detailUser($id)
        ];

        return view('App\Modules\Profile\Views\index', $data);
    }

    public function updateProfile()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $user_id = CIAuth::id();

        $rules = [
            'nama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama lengkap harus diisi'
                ]
            ],
            'username' => [
                'rules' => 'required|min_length[4]|is_unique[users.username,id,' . $user_id . ']',
                'errors' => [
                    'required' => 'Username harus diisi',
                    'min_length' => 'Username harus memiliki minimal karakter',
                    'is_unique' => 'Username sudah ada!'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            $data = [
                'title' => 'Profil Pengguna',
                'validation' => \Config\Services::validation(),
            ];

            return view('backend/profile/index', $data);
        } else {
            $update = $this->user->where('id', $user_id)
                ->set([
                    'nama' => $request->getVar('nama'),
                    'username' => $request->getVar('username'),
                    'email' => $request->getVar('email'),
                ])->update();

            if ($update) {
                $user_info = $this->user->find($user_id);

                return json_encode([
                    'status' => 1,
                    'user_info' => $user_info,
                    'msg' => 'Detail profil anda berhasil diupdate'
                ]);
            } else {
                return json_encode([
                    'status' => 0,
                    'msg' => 'Ada yang tidak beres'
                ]);
            }
        }
    }

    public function updateProfilePicture()
    {
        $request = \Config\Services::request();
        $user_id = CIAuth::id();
        $user_info = $this->user->asObject()->where('id', $user_id)->first();

        $path = 'img/users/';
        $file = $request->getFile('user_profile_file');
        $old_picture = $user_info->foto;
        $new_filename = 'UIMG_' . $file->getRandomName();

        // Image manipulation
        $upload_image = \Config\Services::image()
            ->withFile($file)
            ->resize(450, 450, true, 'height')
            ->save($path . $new_filename);

        if ($upload_image) {
            if ($old_picture != null && file_exists($path . $new_filename)) {
                unlink($path . $old_picture);
            }

            $this->user->where('id', $user_info->id)
                ->set(['foto' => $new_filename])
                ->update();

            return json_encode([
                'status' => 1,
                'msg' => 'Selesai, Foto profil Anda berhasil diupdate'
            ]);
        } else {
            return json_encode([
                'status' => 0,
                'msg' => 'Ada yang tidak beres'
            ]);
        }
    }

    public function changePassword()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $validation = \Config\Services::validation();
            $user_id = CIAuth::id();
            $user_info = $this->user->asObject()->where('id', $user_id)->first();

            // Validation the form
            $this->validate([
                'password_sekarang' => [
                    'rules' => 'required|min_length[5]|check_current_password[password_sekarang]',
                    'errors' => [
                        'required' => 'Masukkan password sekarang',
                        'min_length' => 'Password harus memiliki minimal 5 karakter',
                        'check_current_password' => 'Password sekarang salah'
                    ]
                ],
                'password_baru' => [
                    'rules' => 'required|min_length[5]|max_length[20]|is_password_strong[password_baru]',
                    'errors' => [
                        'required' => 'Password baru harus diisi',
                        'min_length' => 'Password baru harus memiliki minimal 5 karakter',
                        'max_length' => 'Kata sandi baru tidak boleh lebih dari 20 karakter',
                        'is_password_strong' => 'Kata sandi minimal harus mengandung 1 huruf besar, 1 huruf kecil, 1 angka, dan 1 karakter khusus.'
                    ]
                ],
                'konfirmasi_password' => [
                    'rules' => 'required|matches[password_baru]',
                    'errors' => [
                        'required' => 'Konfirmasi password baru',
                        'matches' => 'Ketidakcocokan kata sandi'
                    ]
                ]
            ]);

            if ($validation->run() === FALSE) {
                $errors = $validation->getErrors();

                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'error' => $errors
                ]);
            } else {
                // Update user(backend) password in DB
                $this->user->where('id', $user_info->id)
                    ->set(['password' => Hash::make($request->getVar('password_baru'))])
                    ->update();

                // Send email
                $mail_data = array(
                    'user' => $user_info,
                    'password_baru' => $request->getVar('password_baru')
                );

                $view = \Config\Services::renderer();
                $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates/password-changed-email-template');

                $mailConfig = array(
                    'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                    'mail_from_name' => env('EMAIL_FROM_NAME'),
                    'mail_recipient_email' => $user_info->email,
                    'mail_recipient_name' => $user_info->nama,
                    'mail_subject' => 'Password telah diubah',
                    'mail_body' => $mail_body,
                );

                sendEmail($mailConfig);

                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Selesai! Password anda berhasil diupdate'
                ]);
            }
        }
    }
}
