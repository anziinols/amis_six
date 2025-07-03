<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $uri = $request->getPath();
        
        // Get filter configuration
        $filterConfig = config('Filters');
        $exceptedRoutes = $filterConfig->filters['auth']['except'] ?? [];
        
        // Check if current route is excepted
        if (in_array($uri, $exceptedRoutes)) {
            return;
        }
        
        // Check if this is a dakoii route
        if (str_starts_with($uri, 'dakoii/')) {
            if (!session()->get('dakoii_logged_in')) {
                return redirect()->to(base_url('dakoii'))->with('error', 'Please login to access this page');
            }
        } else {
            // Regular authentication
            if (!session()->get('logged_in')) {
                return redirect()->to(base_url('login'));
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}