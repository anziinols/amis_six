<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AdminFilter
 * 
 * Ensures only users with admin capability can access admin routes
 */
class AdminFilter implements FilterInterface
{
    /**
     * Check if user has admin capability before allowing access
     *
     * @param RequestInterface $request
     * @param array|null $arguments
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in first
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'))->with('error', 'Please login to access this page');
        }

        // Check if user has admin capability
        if (session()->get('is_admin') != 1) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Access denied. Administrator privileges required.');
        }

        return;
    }

    /**
     * After filter - not needed for admin check
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array|null $arguments
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
        return;
    }
}
