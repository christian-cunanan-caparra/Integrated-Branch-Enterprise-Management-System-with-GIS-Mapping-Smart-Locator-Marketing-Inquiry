<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }
        
        return view('admin/login');
    }

    public function login()
    {
        $model = new UserModel();
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        if (empty($username) || empty($password)) {
            $this->session->setFlashdata('msg', 'Please enter username and password');
            return redirect()->to('/admin/login');
        }
        
        $user = $model->where('username', $username)->first();
        
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $sessionData = [
                    'id'         => $user['id'],
                    'username'   => $user['username'],
                    'email'      => $user['email'],
                    'role'       => $user['role'],
                    'isLoggedIn' => true
                ];
                $this->session->set($sessionData);
                return redirect()->to('/admin/dashboard');
            } else {
                $this->session->setFlashdata('msg', 'Wrong password');
                return redirect()->to('/admin/login');
            }
        } else {
            $this->session->setFlashdata('msg', 'Username not found');
            return redirect()->to('/admin/login');
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/admin/login')->with('msg', 'You have been logged out');
    }
}