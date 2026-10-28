<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = service('session');

        if (!$session->has('is_logged_in') || !$session->get('is_logged_in')) {
            $session->set('redirect_url', current_url());
            return redirect()->to('/login')->with('error', 'Please login to access the system.');
        }

        // Role check if arguments passed
        if (!empty($arguments)) {
            $user = $session->get('user');
            if ($user && $user['role'] !== 'admin' && !in_array($user['role'], $arguments)) {
                return redirect()->to('/dashboard')->with('error', 'Access denied. You do not have permission to view that module.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action required after execution
    }
}
