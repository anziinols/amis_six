<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    protected $userModel;
    protected $workplanModel;
    protected $workplanActivityModel;
    protected $proposalModel;
    protected $meetingsModel;
    protected $documentModel;

    public function __construct()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            header('Location: ' . base_url('login'));
            exit;
        }

        // Load the models
        $this->userModel = new \App\Models\UserModel();
        $this->workplanModel = new \App\Models\WorkplanModel();
        $this->workplanActivityModel = new \App\Models\WorkplanActivityModel();
        $this->proposalModel = new \App\Models\ProposalModel();
        $this->meetingsModel = new \App\Models\MeetingsModel();
        $this->documentModel = new \App\Models\DocumentModel();
    }

    public function index()
    {
        // Get user information from session
        $userId = session()->get('user_id');
        $userRole = session()->get('role');

        // Get user data to find branch_id
        $userData = $this->userModel->find($userId);
        $branchId = $userData['branch_id'] ?? 0;

        // Get workplans related to the user
        $myWorkplans = [];
        if ($userRole == 'admin' || $userRole == 'supervisor') {
            // For admin/supervisor: get workplans they supervise
            $myWorkplans = $this->workplanModel
                ->select('workplans.*, branches.name as branch_name')
                ->join('branches', 'branches.id = workplans.branch_id', 'left')
                ->where('workplans.supervisor_id', $userId)
                ->where('workplans.deleted_at IS NULL')
                ->orderBy('workplans.start_date', 'DESC')
                ->findAll(5);
        } else {
            // For regular users: get workplans from their branch
            $myWorkplans = $this->workplanModel
                ->select('workplans.*, branches.name as branch_name')
                ->join('branches', 'branches.id = workplans.branch_id', 'left')
                ->where('workplans.branch_id', $branchId)
                ->where('workplans.deleted_at IS NULL')
                ->orderBy('workplans.start_date', 'DESC')
                ->findAll(5);
        }

        // Get pending proposals/tasks for the user
        $pendingTasks = 0;
        $completedTasks = 0;

        if ($userRole == 'admin' || $userRole == 'supervisor') {
            // For admin/supervisor: count proposals they need to approve
            $pendingTasks = $this->proposalModel
                ->where('supervisor_id', $userId)
                ->where('status', 'submitted')
                ->where('deleted_at IS NULL')
                ->countAllResults();

            $completedTasks = $this->proposalModel
                ->where('supervisor_id', $userId)
                ->whereIn('status', ['approved', 'rated'])
                ->where('deleted_at IS NULL')
                ->countAllResults();
        } else {
            // For regular users: count their pending and completed proposals
            $pendingTasks = $this->proposalModel
                ->where('action_officer_id', $userId)
                ->whereIn('status', ['pending', 'submitted'])
                ->where('deleted_at IS NULL')
                ->countAllResults();

            $completedTasks = $this->proposalModel
                ->where('action_officer_id', $userId)
                ->whereIn('status', ['approved', 'rated'])
                ->where('deleted_at IS NULL')
                ->countAllResults();
        }

        // Get upcoming meetings for the user's branch
        $upcomingMeetings = $this->meetingsModel->getUpcomingMeetings(3);

        // Get recent documents for the user's branch
        $recentDocuments = $this->documentModel
            ->where('branch_id', $branchId)
            ->where('deleted_at IS NULL')
            ->orderBy('created_at', 'DESC')
            ->findAll(3);

        $data = [
            'title' => 'Dashboard',
            'user' => [
                'name' => session()->get('fname') . ' ' . session()->get('lname'),
                'role' => session()->get('role'),
                'email' => session()->get('email')
            ],
            'userData' => $userData,
            'myWorkplans' => $myWorkplans,
            'pendingTasks' => $pendingTasks,
            'completedTasks' => $completedTasks,
            'upcomingMeetings' => $upcomingMeetings,
            'recentDocuments' => $recentDocuments
        ];

        return view('dashboard/dashboard_landing', $data);
    }

    public function profile()
    {
        // Get the user ID from session
        $userId = session()->get('user_id');

        // Fetch full user data from the model
        $userData = $this->userModel->find($userId);

        if (!$userData) {
            // Handle case where user is not found in database
            return redirect()->to(base_url('logout'))->with('error', 'User profile not found');
        }

        $data = [
            'title' => 'My Profile',
            'user' => [
                'name' => session()->get('name') ?? ($userData['fname'] . ' ' . $userData['lname']),
                'role' => session()->get('role') ?? $userData['role'],
                'email' => session()->get('email') ?? $userData['email']
            ],
            'userData' => $userData
        ];

        return view('dashboard/dashboard_profile', $data);
    }



    public function updateProfile()
    {
        // Get the user ID from session
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->back()->with('profile_error', 'User not logged in');
        }

        // Get the form data
        $fname = trim($this->request->getPost('fname'));
        $lname = trim($this->request->getPost('lname'));
        $phone = trim($this->request->getPost('phone'));
        $address = trim($this->request->getPost('address'));
        $dob = trim($this->request->getPost('dob'));
        $gender = trim($this->request->getPost('gender'));

        // Validate required fields
        if (empty($fname) || empty($lname)) {
            return redirect()->back()->with('profile_error', 'First name and last name are required');
        }

        $data = [
            'fname' => $fname,
            'lname' => $lname,
            'phone' => $phone,
            'address' => $address,
            'dob' => $dob,
            'gender' => $gender
        ];

        // Update the user data
        $updated = $this->userModel->update($userId, $data);

        if ($updated) {
            // Update session data
            session()->set([
                'fname' => $fname,
                'lname' => $lname,
                'user_name' => $fname . ' ' . $lname
            ]);

            return redirect()->back()->with('profile_success', 'Profile updated successfully');
        }

        return redirect()->back()->with('profile_error', 'Error updating profile');
    }

    public function updateProfilePhoto()
    {
        // Get the user ID from session
        $userId = session()->get('user_id');

        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Check if a file was uploaded
        $file = $this->request->getFile('profile_photo');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No valid file uploaded'
            ]);
        }

        // Validate file type
        $validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file->getClientMimeType(), $validTypes)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid file type. Only JPG, PNG, and GIF are allowed.'
            ]);
        }

        // Validate file size (max 2MB)
        if ($file->getSize() > 2097152) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'File size exceeds the maximum limit of 2MB'
            ]);
        }

        // Create uploads/profile directory if it doesn't exist
        $uploadPath = ROOTPATH . 'public/uploads/profile';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Generate a unique filename
        $newFileName = $userId . '_' . time() . '.' . $file->getExtension();

        // Move the file to the uploads directory
        if ($file->move($uploadPath, $newFileName)) {
            // Update the user's profile image in the database
            $updated = $this->userModel->update($userId, [
                'id_photo_filepath' => 'public/uploads/profile/' . $newFileName
            ]);

            if ($updated) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Profile photo updated successfully',
                    'file_url' => base_url('public/uploads/profile/' . $newFileName)
                ]);
            } else {
                // Clean up uploaded file if database update failed
                unlink($uploadPath . '/' . $newFileName);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error updating profile photo in database'
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error uploading file'
        ]);
    }

    public function updatePassword()
    {
        // Get the user ID from session
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->back()->with('error', 'User not logged in');
        }

        // Get the form data
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Validate required fields
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            return redirect()->back()->with('error', 'All password fields are required');
        }

        // Check if new passwords match
        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'New passwords do not match');
        }

        // Validate password strength (minimum 4 characters)
        if (strlen($newPassword) < 4) {
            return redirect()->back()->with('error', 'Password must be at least 4 characters long');
        }

        // Get the user data
        $userData = $this->userModel->find($userId);

        if (!$userData) {
            return redirect()->back()->with('error', 'User not found');
        }

        // Verify current password
        if (!password_verify($currentPassword, $userData['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect');
        }

        // Update the user's password in the database
        // Hash the password manually to ensure it's properly hashed
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $updated = $this->userModel->update($userId, [
            'password' => $hashedPassword, // Pre-hashed password
            'updated_by' => $userId
        ]);

        if ($updated) {
            log_message('info', 'Password changed successfully for user ID: ' . $userId);
            return redirect()->back()->with('success', 'Password changed successfully');
        }

        log_message('error', 'Failed to update password for user ID: ' . $userId);
        return redirect()->back()->with('error', 'Error changing password');
    }

}