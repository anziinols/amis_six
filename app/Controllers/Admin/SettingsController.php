<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrgSettingsModel;

class SettingsController extends BaseController
{
    protected $settingsModel;
    protected $session;

    public function __construct()
    {
        $this->settingsModel = new OrgSettingsModel();
        $this->session = session();
    }

    public function index()
    {
        $data = [
            'title' => 'Organization Settings',
            'settings' => $this->settingsModel->where('deleted_at IS NULL')->findAll()
        ];

        return view('admin/settings/settings_index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Create New Setting'
        ];

        return view('admin/settings/settings_create', $data);
    }

    public function create()
    {
        $code = $this->request->getPost('settings_code');
        $name = $this->request->getPost('settings_name');
        $settings = $this->request->getPost('settings');
        $userId = $this->session->get('user_id');

        $result = $this->settingsModel->saveSettingsByCode($code, $name, $settings, $userId);

        if ($result) {
            $this->session->setFlashdata('success', 'Setting created successfully');
            return redirect()->to(base_url('admin/org-settings'));
        } else {
            $this->session->setFlashdata('error', 'Failed to create setting');
            return redirect()->back()->withInput();
        }
    }

    public function show($id)
    {
        $setting = $this->settingsModel->find($id);
        
        if (!$setting) {
            $this->session->setFlashdata('error', 'Setting not found');
            return redirect()->to(base_url('admin/org-settings'));
        }

        // Decode JSON settings for display
        if (!empty($setting['settings']) && is_string($setting['settings'])) {
            $setting['settings_formatted'] = json_decode($setting['settings'], true);
        } else {
            $setting['settings_formatted'] = [];
        }

        $data = [
            'title' => 'View Setting',
            'setting' => $setting
        ];

        return view('admin/settings/settings_show', $data);
    }

    public function edit($id)
    {
        $setting = $this->settingsModel->find($id);
        
        if (!$setting) {
            $this->session->setFlashdata('error', 'Setting not found');
            return redirect()->to(base_url('admin/org-settings'));
        }

        $data = [
            'title' => 'Edit Setting',
            'setting' => $setting
        ];

        return view('admin/settings/settings_edit', $data);
    }

    public function update($id)
    {
        $setting = $this->settingsModel->find($id);
        
        if (!$setting) {
            $this->session->setFlashdata('error', 'Setting not found');
            return redirect()->to(base_url('admin/org-settings'));
        }

        $code = $setting['settings_code']; // Use existing code
        $name = $this->request->getPost('settings_name');
        $settings = $this->request->getPost('settings');
        $userId = $this->session->get('user_id');

        $result = $this->settingsModel->saveSettingsByCode($code, $name, $settings, $userId);

        if ($result) {
            $this->session->setFlashdata('success', 'Setting updated successfully');
            return redirect()->to(base_url('admin/org-settings'));
        } else {
            $this->session->setFlashdata('error', 'Failed to update setting');
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        $setting = $this->settingsModel->find($id);
        
        if (!$setting) {
            $this->session->setFlashdata('error', 'Setting not found');
            return redirect()->to(base_url('admin/org-settings'));
        }

        $code = $setting['settings_code'];
        $userId = $this->session->get('user_id');

        $result = $this->settingsModel->deleteSettingsByCode($code, $userId);

        if ($result) {
            $this->session->setFlashdata('success', 'Setting deleted successfully');
        } else {
            $this->session->setFlashdata('error', 'Failed to delete setting');
        }

        return redirect()->to(base_url('admin/org-settings'));
    }
} 