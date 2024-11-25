<?php

namespace App\Controllers\Suara24;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use CodeIgniter\Config\Config;
use CodeIgniter\Database\Query;
use CodeIgniter\API\ResponseTrait;

class Profile extends BaseController
{
    use ResponseTrait;
    var $model, $validation;
    function __construct()
    {
        $this->model = new AdminModel();
        $this->validation = \Config\Services::validation();
        helper("cookie");
        helper("global_fungsi_helper");
    }
    function index()
    {
        echo view('web/auth/profile');
    }
    function change_profile()
    {
        $rules = [
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama harus diisi'
                ]
            ],
            'username' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Username harus diisi',
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'email' => 'Email tidak valid'
                ]
            ]
        ];
        $name = $this->request->getVar('name');
        $username = $this->request->getVar('username');
        $email = $this->request->getVar('email');
        $dataUpdate = array(
            'name' => $name,
            'username' => $username,
            'email' => $email
        );
        $detailAdmin = $this->model->find(session()->get('admin_id'));
        if ($detailAdmin['username'] != $username) {
            $rules = [
                'username' => [
                    'rules' => 'is_unique[cpadmin.username]',
                    'errors' => [
                        'is_unique' => 'Username sudah digunakan',
                    ]
                ]
            ];
        }
        if ($detailAdmin['email'] != $email) {
            $rules = [
                'email' => [
                    'rules' => 'is_unique[cpadmin.email]',
                    'errors' => [
                        'is_unique' => 'Email sudah digunakan',
                    ]
                ]
            ];
        }

        if (!$this->validate($rules)) {
            // If validation fails, return the validation errors as JSON
            $errors = $this->validator->getErrors();
            return $this->respond(['errors' => $errors], 400);
        }
        $this->model->update(session()->get('admin_id'), $dataUpdate);
        $akun = [
            'admin_username' => $dataUpdate['username'],
            'admin_name' => $dataUpdate['name'],
            'admin_email' => $dataUpdate['email'],
        ];
        session()->set($akun);
        return $this->respond(['message' => 'Profile berhasil diupdate'], 200);
    }
    function change_password()
    {
        $rules = [
            'oPassword' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password Lama tidak boleh kosong',
                ]
            ],
            'nPassword' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'New Password tidak boleh kosong',
                    'min_length' => 'Password minimal terdiri dari 8 karakter',
                ]
            ],
            'cPassword' => [
                'rules' => 'required|matches[nPassword]',
                'errors' => [
                    'required' => 'Konfirmasi Password tidak boleh kosong',
                    'matches' => 'Konfirmasi password tidak sama',
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            // If validation fails, return the validation errors as JSON
            $errors = $this->validator->getErrors();
            return $this->respond(['errors' => $errors], 400);
        }

        // Get the current user ID or user data from session
        $admin_id = session()->get('admin_id'); // Replace with your own logic
        // Get the current user data from the database
        $user = $this->model->find($admin_id);

        // Verify if the current password matches the one provided in the form
        if (!password_verify($this->request->getVar('oPassword'), $user['password'])) {
            return $this->respond(['errors' => ['Password' => "Pasword lama salah"]], 400);
        }

        // Update the user's password
        $newPassword = password_hash($this->request->getVar('cPassword'), PASSWORD_BCRYPT);
        $this->model->update($admin_id, ['password' => $newPassword]);
        return $this->respond([
            'status' => 'success',
            'message' => 'Password updated successfully',
        ], 200);
    }
    function change_picture()
    {
        $rules = [
            'picture' => [
                'rules' => 'max_size[picture,2048]|ext_in[picture,png,jpg,jpeg,gif]',
                'errors' => [
                    'max_size' => "Ukuran file harus 2 MB",
                    'ext_in' => 'Tipe file tidak diizinkan',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            // If validation fails, return the validation errors as JSON
            $errors = $this->validator->getErrors();
            return $this->respond(['errors' => $errors], 400);
        }

        $detail = $this->model->find(session()->get('admin_id'));
        // Validate and save the image file
        $image = $this->request->getFile('picture');
        if ($image->isValid() && in_array($image->getClientMimeType(), ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'])) {
            $newName = $image->getRandomName();
            $image->move(ROOTPATH . 'public/' . getenv('dir.upload.profile'), $newName);

            // Delete the previous image file if it exists
            if (!empty($detail['picture'])) {
                $imagePath = ROOTPATH . 'public/' . getenv('dir.upload.profile') . $detail['picture'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Update the picture in the database
            $this->model->update($detail['id'], ['picture' => $newName]);
        }
        session()->set(['admin_picture' => $newName]);
        return $this->respond([
            'status' => 'success',
            'message' => 'Photo profile updated successfully',
            'photo_url' => base_url() . getenv('dir.upload.profile') . $newName
        ], 200);
    }
}
