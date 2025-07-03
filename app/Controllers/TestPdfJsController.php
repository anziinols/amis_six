<?php

namespace App\Controllers;

use CodeIgniter\Controller;

/**
 * Test PDF JavaScript Controller
 * 
 * Simple controller to test JavaScript PDF functionality
 */
class TestPdfJsController extends Controller
{
    /**
     * Test JavaScript PDF generation page
     */
    public function index()
    {
        $data = [
            'title' => 'PDF Generation Test - AMIS System'
        ];

        return view('test_pdf', $data);
    }
}
