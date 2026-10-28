<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if ($this->session->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function processLogin()
    {
        $login = trim((string)$this->request->getPost('login'));
        $password = (string)$this->request->getPost('password');

        if (empty($login) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Username/Email and Password are required.');
        }

        $userModel = new UserModel();
        $user = $userModel->findByLogin($login);

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Invalid login credentials.');
        }

        if (!$user['is_active']) {
            return redirect()->back()->with('error', 'Your account is deactivated. Contact administrator.');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid login credentials.');
        }

        // Login Success
        $userModel->update($user['id'], [
            'last_login' => date('Y-m-d H:i:s'),
        ]);

        $this->session->set([
            'is_logged_in' => true,
            'user'         => [
                'id'        => $user['id'],
                'username'  => $user['username'],
                'email'     => $user['email'],
                'full_name' => $user['full_name'],
                'role'      => $user['role'],
                'avatar'    => $user['avatar'],
            ],
        ]);

        log_activity('Auth', 'Login', $user['id'], "User {$user['username']} logged in successfully");

        $redirectUrl = $this->session->get('redirect_url') ?? '/dashboard';
        $this->session->remove('redirect_url');

        return redirect()->to($redirectUrl)->with('success', "Welcome back, {$user['full_name']}!");
    }

    public function logout()
    {
        if ($this->currentUser) {
            log_activity('Auth', 'Logout', $this->currentUser['id'], "User logged out");
        }
        $this->session->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out successfully.');
    }

    public function profile()
    {
        $userModel = new UserModel();
        $user = $userModel->find($this->currentUser['id']);

        return $this->render('auth/profile', [
            'pageTitle' => 'My Profile',
            'user'      => $user,
        ]);
    }

    public function updateProfile()
    {
        $userModel = new UserModel();
        $userId = $this->currentUser['id'];

        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
        ];

        $newPassword = (string)$this->request->getPost('new_password');
        if (!empty($newPassword)) {
            if (strlen($newPassword) < 6) {
                return redirect()->back()->with('error', 'New password must be at least 6 characters.');
            }
            $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $userModel->update($userId, $data);

        // Update session
        $this->session->set('user', array_merge($this->currentUser, [
            'full_name' => $data['full_name'],
            'email'     => $data['email'],
        ]));

        log_activity('Auth', 'Update Profile', $userId, "Updated profile information");

        return redirect()->to('/profile')->with('success', 'Profile updated successfully.');
    }
}
