<?php

namespace App\Controllers\suara24;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\KecamatanModel;
use App\Models\DesaModel;
use App\Models\PaslonModel;
use CodeIgniter\Config\Config;
use CodeIgniter\Database\Query;
use CodeIgniter\API\ResponseTrait;

class Login extends BaseController
{
    var $model, $validation;
    use ResponseTrait;
    function __construct()
    {
        $this->model = new AdminModel();
        $this->validation = \Config\Services::validation();
        helper("cookie");
        helper("global_fungsi_helper");
    }
    public function login()
    {
        if (get_cookie('admin_cookie_username') && get_cookie('admin_cookie_password') && (get_cookie('admin_cookie_password') != '1')) {
            $username = get_cookie('admin_cookie_username');
            $password = get_cookie('admin_cookie_password');
            $dataAkun = $this->model->getData($username);
            if (!password_verify($password, $dataAkun['password'])) {
                set_cookie("admin_cookie_failed", '1', 3600);
                $err[] = "Ops! Terjadi kesalahan";
                return redirect()->to('suara24/login');
            }
            $akun = [
                'admin_username' => $username,
                'admin_name' => $dataAkun['name'],
                'admin_email' => $dataAkun['email'],
                'admin_role' => $dataAkun['role'],
                'admin_id' => $dataAkun['id'],
                'admin_picture' => $dataAkun['picture'],
            ];
            session()->set($akun);
            return redirect()->to('suara24/suara');
        }
        $data = [];
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'username' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Username harus diisi'
                    ]
                ],
                'password' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Password harus diisi'
                    ]
                ]
            ];
            if (!$this->validate($rules)) {
                session()->setFlashdata("warning", $this->validation->getErrors());
                return redirect()->to("suara24/login");
            }

            $username = $this->request->getVar('username');
            $password = $this->request->getVar('password');
            $remember_me = $this->request->getVar('remember_me');

            $dataAkun = $this->model->getData($username);
            if (!isset($dataAkun['username'])) {
                $err[] = "Username tidak ditemukan.";
                session()->setFlashdata('username', $username);
                session()->setFlashdata('warning', $err);
                return redirect()->to("suara24/login");
            }
            if (!password_verify($password, $dataAkun['password'])) {
                $err[] = "Password yang di masukkan salah.";
                session()->setFlashdata('username', $username);
                session()->setFlashdata('warning', $err);
                return redirect()->to("suara24/login");
            }

            if ($remember_me == '1') {
                set_cookie("admin_cookie_username", $username, 3600 * 24 * 30);
                set_cookie("admin_cookie_password", $password, 3600 * 24 * 30);
            }

            $akun = [
                'admin_username' => $dataAkun['username'],
                'admin_name' => $dataAkun['name'],
                'admin_email' => $dataAkun['email'],
                'admin_role' => $dataAkun['role'],
                'admin_id' => $dataAkun['id'],
                'admin_picture' => $dataAkun['picture'],
            ];
            session()->set($akun);
            return redirect()->to("suara24/suara")->withCookies();
        }
        echo view("web/auth/login", $data);
    }
    function logout()
    {
        delete_cookie("admin_cookie_username");
        delete_cookie("admin_cookie_password");
        session()->destroy();
        if (session()->get('admin_username') != '') {
            session()->setFlashdata("success", "Anda berhasil logout");
        }
        echo view("web/chart");
    }

    function lupapassword()
    {
        $err = [];
        if ($this->request->getMethod() == 'post') {
            $username = $this->request->getVar('username');
            if ($username == '') {
                $err[] = "Silakan masukkan username atau email yang anda punya";
            }
            if (empty($err)) {
                $data = $this->model->getData($username);
                if (empty($data)) {
                    $err[] = "Akun yang kamu masukkan tidak terdata";
                }
            }
            if (empty($err)) {
                $email = $data['email'];
                $token = md5(date('ymdhis'));

                $link = site_url("admin2011/resetpassword/?email=$email&token=$token");
                $attachment = "";
                $to = $email;
                $title = "Reset Password";
                $message = "Berikut ini adalah link untuk melakukan reset password Anda.";
                $message .= "Silakan klik link berikut ini $link";

                // kirim_email($attachment, $to, $title, $message);

                $dataUpdate = [
                    'email' => $email,
                    'token' => $token
                ];
                $this->model->updateData($dataUpdate);
                session()->setFlashdata("success", "Email untuk recovery sudah kami kirimkan ke email anda");
            }
            if ($err) {
                session()->setFlashdata("username", $username);
                session()->setFlashdata("warning", $err);
            }
            return redirect()->to("admin2011/lupapassword");
        }
        echo view("admin/auth/forgot_password");
    }
    function resetpassword()
    {
        $err = [];
        $email = $this->request->getVar('email');
        $token = $this->request->getVar('token');
        if ($email != '' and $token != '') {
            $dataAkun = $this->model->getData($email); //<-- cek di tabel admin
            if ($dataAkun['token'] != $token) {
                $err[] = "Token tidak valid";
            }
        } else {
            $err[] = "Parameter yang dikirimkan tidak valid";
        }

        if ($err) {
            session()->setFlashdata("warning", $err);
        }

        if ($this->request->getMethod() == 'post') {
            $aturan = [
                'password' => [
                    'rules' => 'required|min_length[5]',
                    'errors' => [
                        'required' => 'Password harus diisi',
                        'min_length' => 'Panjang karakter minimum untuk password adalah 5 karakter'
                    ]
                ],
                'konfirmasi_password' => [
                    'rules' => 'required|min_length[5]|matches[password]',
                    'errors' => [
                        'required' => 'Konfirmasi password harus diisi',
                        'min_length' => 'Panjang karakter minimum untuk konfirmasi password adalah 5 karakter',
                        'matches' => 'Konfirmasi password tidak sesuai dengan password yang diisikan'
                    ]
                ]
            ];

            if (!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $dataUpdate = [
                    'email' => $email,
                    'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
                    'token' => null
                ];
                $this->model->updateData($dataUpdate);
                session()->setFlashdata('success', 'Password berhasil direset, silakan login');

                delete_cookie('admin_cookie_username');
                delete_cookie('admin_cookie_password');

                return redirect()->to('admin2011/login')->withCookies();
            }
        }

        echo view("admin/auth/reset_password");
    }
}
