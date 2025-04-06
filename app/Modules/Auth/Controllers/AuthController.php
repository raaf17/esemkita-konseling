<?php

namespace App\Modules\Auth\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Modules\Users\Models\UserModel;
use App\Modules\Auth\Models\PasswordResetToken;
use Carbon\Carbon;
use PhpParser\Node\Expr\Cast;

class AuthController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions'];
    protected $user;

    public function __construct()
    {
        $this->user = new UserModel();
    }

    public function loginForm()
    {
        $data = [
            'title' => 'Login',
            'validation' => \Config\Services::validation(),
        ];

        return view('App\Modules\Auth\Views\login', $data);
    }

    public function loginHandler()
    {
        $fieldType = filter_var($this->request->getVar('login_id'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($fieldType == 'email') {
            $isValid = $this->validate([
                'login_id' => [
                    'rules' => 'required|valid_email|is_not_unique[users.email]',
                    'errors' => [
                        'required' => 'Email harus diisi',
                        'valid_email' => 'Silakan periksa kolom email, sepertinya tidak valid',
                        'is_not_unique' => 'Email tidak ada di sistem kami'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[5]|max_length[45]',
                    'errors' => [
                        'required' => 'Password harus diisi',
                        'min_length' => 'Password harus memiliki panjang minimal 5 karakter',
                        'max_length' => 'Password tidak boleh memiliki karakter lebih dari 45 karakter'
                    ]
                ]
            ]);
        } else {
            $isValid = $this->validate([
                'login_id' => [
                    'rules' => 'required|is_not_unique[users.username]',
                    'errors' => [
                        'required' => 'Username harus diisi',
                        'is_not_unique' => 'Username tidak ada di sistem kami.'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[5]|max_length[45]',
                    'errors' => [
                        'required' => 'Password harus diisi',
                        'min_length' => 'Password harus memiliki panjang minimal 5 karakter',
                        'max_length' => 'Password tidak boleh memiliki karakter lebih dari 45 karakter'
                    ]
                ]
            ]);
        }

        if (!$isValid) {
            return view('App\Modules\Auth\Views\login', [
                'title' => 'Login',
                'validation' => \Config\Services::validation(),
            ]);
        } else {
            $userInfo = $this->user->where($fieldType, $this->request->getVar('login_id'))->first();
            $check_password = Hash::check($this->request->getVar('password'), $userInfo->password);

            if (!$check_password) {
                return redirect()->route('login.form')->with('message', 'Password salah')->withInput();
            } else {
                CIAuth::setCIAuth($userInfo);

                if (get_user()->role == 'SuperAdmin') {
                    return redirect()->route('dashboard');
                } else {
                    return redirect()->route('home');
                }
            }
        }
    }

    public function forgotForm()
    {
        $data = array(
            'title' => 'Lupa Password',
            'validation' => \Config\Services::validation(),
        );

        return view('App\Modules\Auth\Views\forgot', $data);
    }

    public function sendPasswordResetLink()
    {
        $isValid = $this->validate([
            'email' => [
                'rules' => 'required|valid_email|is_not_unique[users.email]',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Silakan periksa kolom email. Tampaknya tidak valid.',
                    'is_not_unique' => 'Email tidak ada di sistem',
                ],
            ]
        ]);

        if (!$isValid) {
            return view('App\Modules\Auth\Views\forgot', [
                'title' => 'Lupa Password',
                'validation' => \Config\Services::validation(),
            ]);
        } else {
            $user_info = $this->user->asObject()->where('email', $this->request->getVar('email'))->first();

            // Generate token
            $token = bin2hex(openssl_random_pseudo_bytes(65));

            // Get reset password token
            $password_reset_token = new PasswordResetToken();
            $isOldTokenExists = $password_reset_token->asObject()->where('email', $user_info->email)->first();

            if ($isOldTokenExists) {
                // Update exiting token
                $password_reset_token->where('email', $user_info->email)
                    ->set(['token' => $token, 'created_at' => Carbon::now()])
                    ->update();
            } else {
                $password_reset_token->insert([
                    'email' => $user_info->email,
                    'token' =>  $token,
                    'created_at' => Carbon::now()
                ]);
            }

            // Create action link
            $actionLink = base_url(route_to('admin.reset-password', $token));

            $mail_data = array(
                'actionLink' => $actionLink,
                'user' => $user_info,
            );

            $view = \Config\Services::renderer();
            $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates/forgot-email-template');

            $mailConfig = array(
                'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                'mail_from_name' => env('EMAIL_FROM_NAME'),
                'mail_recipient_email' => $user_info->email,
                'mail_recipient_name' => $user_info->name,
                'mail_subject' => 'Reset Password',
                'mail_body' => $mail_body
            );

            // Send Email
            if (sendEmail($mailConfig)) {
                return redirect()->route('admin.forgot.form')->with('success', 'Kami telah mengirimkan tautan pengaturan ulang kata sandi Anda melalui email.');
            } else {
                return redirect()->route('admin.forgot.form')->with('fail', 'Ada yang salah');
            }
        }
    }

    public function resetPassword($token)
    {
        $passwordResetPassword = new PasswordResetToken();
        $check_token = $passwordResetPassword->asObject()->where('token', $token)->first();

        if (!$check_token) {
            return redirect()->route('admin.forgot.form')->with('fail', 'Token tidak valid. Minta tautan setel ulang kata sandi lainnya');
        } else {
            // Check if token not expired (Not older than 15 minutes)
            $diffMins = Carbon::createFromDate('Y-m-d H:i:s', $check_token->created_at)->diffInMinutes(Carbon::now());

            if ($diffMins > 15) {
                // If token expired (older than 15 minutes)
                return redirect()->route('admin.forgot.form')->with('fail', 'Token sudah habis masa berlakunya. Minta tautan setel ulang kata sandi lainnya');
            } else {
                return view('App\Modules\Auth\Views\reset', [
                    'title' => 'Reset Password',
                    'validation' => \Config\Services::validation(),
                    'token' => $token
                ]);
            }
        }
    }

    public function resetPasswordHandler($token)
    {
        $isValid = $this->validate([
            'new_password' => [
                'rules' => 'required|min_length[5]|max_length[20]|is_password_strong[new_password]',
                'errors' => [
                    'required' => 'Masukkan password baru',
                    'min_length' => 'Kata sandi baru harus memiliki minimal 5 karakter.',
                    'max_length' => 'Kata sandi baru harus maksimal 20 karakter',
                    'is_password_stong' => 'Kata sandi baru minimal harus terdiri dari 1 huruf besar, 1 huruf kecil, 1 angka, dan 1 karakter khusus.',
                ]
            ],
            'confirm_new_password' => [
                'rules' => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Konfirmasi password baru',
                    'matches' => 'Kata sandi tidak cocok'
                ]
            ]
        ]);

        if (!$isValid) {
            return view('App\Modules\Auth\Views\reset', [
                'title' => 'Reset Password',
                'validation' => \Config\Services::validation(),
                'token' => $token
            ]);
        } else {
            // Get token details
            $passwordResetPassword = new PasswordResetToken();
            $get_token = $passwordResetPassword->asObject()->where('token', $token)->first();

            // Get user (admin) details
            $user_info = $this->user->asObject()->where('email', $get_token->email)->first();

            if (!$get_token) {
                return redirect()->back()->with('fail', 'Invalid token!')->withInput();
            } else {
                // Update admin password in DB
                $this->user->where('email', $user_info->email)
                    ->set(['password' => Hash::make($this->request->getVar('new_password'))])
                    ->update();

                // Send notification to user (admin) email address
                $mail_data = array(
                    'user' => $user_info,
                    'new_password' => $this->request->getVar('new_password')
                );

                $view = \Config\Services::renderer();
                $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates/password-changed-email-template');

                $mailConfig = array(
                    'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                    'mail_from_name' => env('EMAIL_FROM_NAME'),
                    'mail_recipient_email' => $user_info->email,
                    'mail_recipient_name' => $user_info->name,
                    'mail_subject' => 'Password Changed',
                    'mail_body' => $mail_body,
                );

                if (sendEmail($mailConfig)) {
                    // Delete token
                    $passwordResetPassword->where('email', $user_info->email)->delete();

                    // Redirect and display message on login page
                    return redirect()->route('login.form')->with('success', 'Selesai!, kata sandi Anda telah diubah. Gunakan kata sandi baru untuk masuk ke sistem.');
                } else {
                    return redirect()->back()->with('fail', 'Something went wrong')->withInput();
                }
            }
        }
    }

    public function logoutHandler()
    {
        CIAuth::forget();

        return redirect()->route('login.form')->with('fail', 'Anda Keluar');
    }
}
