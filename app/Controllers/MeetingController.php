<?php
// app/Controllers/MeetingController.php

namespace App\Controllers;

use App\Models\MeetingsModel;
use CodeIgniter\RESTful\ResourceController;

class MeetingController extends ResourceController
{
    protected $meetingsModel;
    protected $helpers = ['form', 'url', 'file', 'text'];

    public function __construct()
    {
        $this->meetingsModel = new MeetingsModel();
    }

    /**
     * Display a listing of meetings
     */
    public function index()
    {
        $data = [
            'title' => 'Meetings',
            'meetings' => $this->meetingsModel->getMeetingsWithBranch()
        ];

        return view('meeting/meeting_index', $data);
    }

    /**
     * Display the form to create a new meeting
     */
    public function new()
    {
        // Load branch model to get branches for dropdown
        $branchModel = new \App\Models\BranchesModel();

        $data = [
            'title' => 'Create New Meeting',
            'branches' => $branchModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('meeting/meeting_create', $data);
    }

    /**
     * Process the form submission to create a meeting
     */
    public function create()
    {
        // Validate input
        $rules = [
            'branch_id' => 'required|integer',
            'title' => 'required|max_length[255]',
            'meeting_date' => 'required|valid_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'permit_empty|max_length[255]',
            'access_type' => 'required|in_list[private,internal,public]'
        ];

        if (!$this->validate($rules)) {
            $branchModel = new \App\Models\BranchesModel();

            return view('meeting/meeting_create', [
                'title' => 'Create New Meeting',
                'branches' => $branchModel->findAll(),
                'validation' => $this->validator
            ]);
        }

        // Process form data
        $meetingDate = $this->request->getPost('meeting_date');
        $startTime = $this->request->getPost('start_time');
        $endTime = $this->request->getPost('end_time');

        // Format the time values properly
        $formattedStartTime = date('Y-m-d H:i:s', strtotime("$meetingDate $startTime"));
        $formattedEndTime = date('Y-m-d H:i:s', strtotime("$meetingDate $endTime"));

        // Process participants data
        $participantNames = $this->request->getPost('participant_name') ?? [];
        $participantPositions = $this->request->getPost('participant_position') ?? [];
        $participantContacts = $this->request->getPost('participant_contacts') ?? [];
        $participantRemarks = $this->request->getPost('participant_remarks') ?? [];

        $participants = [];
        foreach ($participantNames as $index => $name) {
            if (trim($name) !== '') {
                $participants[] = [
                    'name' => trim($name),
                    'position' => isset($participantPositions[$index]) ? trim($participantPositions[$index]) : '',
                    'contacts' => isset($participantContacts[$index]) ? trim($participantContacts[$index]) : '',
                    'remarks' => isset($participantRemarks[$index]) ? trim($participantRemarks[$index]) : ''
                ];
            }
        }

        $meetingData = [
            'branch_id' => $this->request->getPost('branch_id'),
            'title' => $this->request->getPost('title'),
            'agenda' => $this->request->getPost('agenda'),
            'meeting_date' => $meetingDate,
            'start_time' => $formattedStartTime,
            'end_time' => $formattedEndTime,
            'location' => $this->request->getPost('location'),
            'access_type' => $this->request->getPost('access_type'),
            'status' => 'scheduled',
            'participants' => json_encode($participants),
            'created_by' => session()->get('user_id') ?? 1,
            'remarks' => $this->request->getPost('remarks')
        ];

        // Handle file uploads
        $attachments = [];
        $files = $this->request->getFiles();

        if(isset($files['attachments'])) {
            foreach($files['attachments'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    // Create directory if it doesn't exist
                    $uploadPath = 'public/uploads/meeting_attachments';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    // Generate unique filename
                    $newName = $file->getRandomName();

                    // Move file to upload directory
                    $file->move($uploadPath, $newName);

                    // Add file info to attachments array
                    $attachments[] = [
                        'filename' => $file->getClientName(),
                        'path' => "{$uploadPath}/{$newName}"
                    ];
                }
            }
        }

        // Add attachments to meeting data if any
        if (!empty($attachments)) {
            $meetingData['attachments'] = json_encode($attachments);
        }

        // Save meeting to database
        if ($this->meetingsModel->save($meetingData)) {
            return redirect()
                ->to(base_url('meetings'))
                ->with('success', 'Meeting created successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Failed to create meeting')
                ->withInput();
        }
    }

    /**
     * Display a specific meeting
     */
    public function show($id = null)
    {
        if ($id === null) {
            return redirect()->to(base_url('meetings'));
        }

        $meeting = $this->meetingsModel->find($id);

        if (empty($meeting)) {
            return redirect()
                ->to(base_url('meetings'))
                ->with('error', 'Meeting not found');
        }

        // Get branch information
        $branchModel = new \App\Models\BranchesModel();
        $branch = $branchModel->find($meeting['branch_id']);

        $data = [
            'title' => 'Meeting Details',
            'meeting' => $meeting,
            'branch' => $branch
        ];

        return view('meeting/meeting_show', $data);
    }

    /**
     * Display the form to edit a meeting
     */
    public function edit($id = null)
    {
        if ($id === null) {
            return redirect()->to(base_url('meetings'));
        }

        $meeting = $this->meetingsModel->find($id);

        if (empty($meeting)) {
            return redirect()
                ->to(base_url('meetings'))
                ->with('error', 'Meeting not found');
        }

        // Load branch model to get branches for dropdown
        $branchModel = new \App\Models\BranchesModel();

        $data = [
            'title' => 'Edit Meeting',
            'meeting' => $meeting,
            'branches' => $branchModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('meeting/meeting_edit', $data);
    }

    /**
     * Process the form submission to update a meeting
     */
    public function update($id = null)
    {
        if ($id === null) {
            return redirect()->to(base_url('meetings'));
        }

        // Validate input
        $rules = [
            'branch_id' => 'required|integer',
            'title' => 'required|max_length[255]',
            'meeting_date' => 'required|valid_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'permit_empty|max_length[255]',
            'access_type' => 'required|in_list[private,internal,public]'
        ];

        if (!$this->validate($rules)) {
            return $this->edit($id);
        }

        // Fetch existing meeting
        $meeting = $this->meetingsModel->find($id);

        if (empty($meeting)) {
            return redirect()
                ->to(base_url('meetings'))
                ->with('error', 'Meeting not found');
        }

        // Process form data
        $meetingDate = $this->request->getPost('meeting_date');
        $startTime = $this->request->getPost('start_time');
        $endTime = $this->request->getPost('end_time');

        // Format the time values properly
        $formattedStartTime = date('Y-m-d H:i:s', strtotime("$meetingDate $startTime"));
        $formattedEndTime = date('Y-m-d H:i:s', strtotime("$meetingDate $endTime"));

        // Process participants data
        $participantNames = $this->request->getPost('participant_name') ?? [];
        $participantPositions = $this->request->getPost('participant_position') ?? [];
        $participantContacts = $this->request->getPost('participant_contacts') ?? [];
        $participantRemarks = $this->request->getPost('participant_remarks') ?? [];

        $participants = [];
        foreach ($participantNames as $index => $name) {
            if (trim($name) !== '') {
                $participants[] = [
                    'name' => trim($name),
                    'position' => isset($participantPositions[$index]) ? trim($participantPositions[$index]) : '',
                    'contacts' => isset($participantContacts[$index]) ? trim($participantContacts[$index]) : '',
                    'remarks' => isset($participantRemarks[$index]) ? trim($participantRemarks[$index]) : ''
                ];
            }
        }

        $meetingData = [
            'id' => $id,
            'branch_id' => $this->request->getPost('branch_id'),
            'title' => $this->request->getPost('title'),
            'agenda' => $this->request->getPost('agenda'),
            'meeting_date' => $meetingDate,
            'start_time' => $formattedStartTime,
            'end_time' => $formattedEndTime,
            'location' => $this->request->getPost('location'),
            'access_type' => $this->request->getPost('access_type'),
            'participants' => json_encode($participants),
            'updated_by' => session()->get('user_id') ?? 1,
            'remarks' => $this->request->getPost('remarks')
        ];

        // Handle file uploads
        $attachments = isset($meeting['attachments']) ? $meeting['attachments'] : [];
        $files = $this->request->getFiles();

        if(isset($files['attachments'])) {
            foreach($files['attachments'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    // Create directory if it doesn't exist
                    $uploadPath = 'public/uploads/meeting_attachments';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    // Generate unique filename
                    $newName = $file->getRandomName();

                    // Move file to upload directory
                    $file->move($uploadPath, $newName);

                    // Add file info to attachments array
                    $attachments[] = [
                        'filename' => $file->getClientName(),
                        'path' => "{$uploadPath}/{$newName}"
                    ];
                }
            }
        }

        // Add attachments to meeting data if any
        if (!empty($attachments)) {
            $meetingData['attachments'] = json_encode($attachments);
        }

        // Save meeting to database
        if ($this->meetingsModel->save($meetingData)) {
            return redirect()
                ->to(base_url('meetings'))
                ->with('success', 'Meeting updated successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Failed to update meeting')
                ->withInput();
        }
    }

    /**
     * Delete a meeting
     */
    public function delete($id = null)
    {
        if ($id === null) {
            return redirect()->to(base_url('meetings'));
        }

        if ($this->meetingsModel->delete($id)) {
            return redirect()
                ->to(base_url('meetings'))
                ->with('success', 'Meeting deleted successfully');
        } else {
            return redirect()
                ->to(base_url('meetings'))
                ->with('error', 'Failed to delete meeting');
        }
    }

    /**
     * Download meeting attachment
     */
    public function download($id = null, $attachmentIndex = 0)
    {
        if ($id === null) {
            return redirect()->to(base_url('meetings'));
        }

        $meeting = $this->meetingsModel->find($id);

        if (empty($meeting) || empty($meeting['attachments'])) {
            return redirect()
                ->to(base_url('meetings'))
                ->with('error', 'Attachment not found');
        }

        if (!isset($meeting['attachments'][$attachmentIndex])) {
            return redirect()
                ->to(base_url('meetings'))
                ->with('error', 'Attachment not found');
        }

        $attachment = $meeting['attachments'][$attachmentIndex];
        $filePath = $attachment['path'];

        if (!file_exists($filePath)) {
            return redirect()
                ->to(base_url('meetings'))
                ->with('error', 'File not found');
        }

        return $this->response->download($filePath, null);
    }

    /**
     * Delete meeting attachment
     */
    public function deleteAttachment($id = null, $attachmentIndex = 0)
    {
        if ($id === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid meeting ID']);
        }

        $meeting = $this->meetingsModel->find($id);

        if (empty($meeting) || empty($meeting['attachments'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Attachment not found']);
        }

        if (!isset($meeting['attachments'][$attachmentIndex])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Attachment not found']);
        }

        $attachments = $meeting['attachments'];
        $attachment = $attachments[$attachmentIndex];

        // Delete file from server
        if (file_exists($attachment['path'])) {
            unlink($attachment['path']);
        }

        // Remove attachment from array
        array_splice($attachments, $attachmentIndex, 1);

        // Update meeting record
        $this->meetingsModel->update($id, ['attachments' => json_encode($attachments)]);

        return $this->response->setJSON(['success' => true, 'message' => 'Attachment deleted successfully']);
    }

    /**
     * Update meeting status
     */
    public function updateStatus($id = null)
    {
        if ($id === null) {
            return redirect()->to(base_url('meetings'));
        }

        $status = $this->request->getPost('status');

        // Validate status
        $validStatuses = ['scheduled', 'in_progress', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            return redirect()
                ->to(base_url('meetings/' . $id))
                ->with('error', 'Invalid status value');
        }

        // Update status
        if ($this->meetingsModel->updateStatus($id, $status, session()->get('user_id') ?? 1)) {
            return redirect()
                ->to(base_url('meetings/' . $id))
                ->with('success', 'Meeting status updated successfully');
        } else {
            return redirect()
                ->to(base_url('meetings/' . $id))
                ->with('error', 'Failed to update meeting status');
        }
    }
}