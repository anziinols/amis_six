<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ActivitiesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'workplan_period_id' => 1,
                'performance_output_id' => 1,
                'supervisor_id' => 3,
                'action_officer_id' => 2,
                'activity_title' => 'Farmer Training on Modern Agriculture Techniques',
                'activity_description' => 'Comprehensive training program for farmers on modern agriculture techniques including crop rotation, pest management, and sustainable farming practices.',
                'province_id' => 36,
                'district_id' => 48,
                'date_start' => '2025-08-20',
                'date_end' => '2025-08-22',
                'total_cost' => 15000.00,
                'location' => 'Mandi Village Community Center',
                'type' => 'trainings',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 3,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 3,
            ],
            [
                'workplan_period_id' => 1,
                'performance_output_id' => 1,
                'supervisor_id' => 3,
                'action_officer_id' => 2,
                'activity_title' => 'Youth Entrepreneurship Training',
                'activity_description' => 'Training program focused on developing entrepreneurship skills among rural youth, covering business planning, financial management, and market analysis.',
                'province_id' => 36,
                'district_id' => 48,
                'date_start' => '2025-08-25',
                'date_end' => '2025-08-27',
                'total_cost' => 12000.00,
                'location' => 'District Youth Center',
                'type' => 'trainings',
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 3,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 3,
            ],
            [
                'workplan_period_id' => 1,
                'performance_output_id' => 1,
                'supervisor_id' => 3,
                'action_officer_id' => 2,
                'activity_title' => 'Women Empowerment Workshop',
                'activity_description' => 'Workshop designed to empower women through skills development, leadership training, and awareness about women rights and opportunities.',
                'province_id' => 36,
                'district_id' => 48,
                'date_start' => '2025-09-01',
                'date_end' => '2025-09-03',
                'total_cost' => 8000.00,
                'location' => 'Women Community Hall',
                'type' => 'trainings',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 3,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 3,
            ],
            [
                'workplan_period_id' => 1,
                'performance_output_id' => 1,
                'supervisor_id' => 3,
                'action_officer_id' => 2,
                'activity_title' => 'Document Management System Implementation',
                'activity_description' => 'Implementation of a comprehensive document management system for better record keeping and information management.',
                'province_id' => 36,
                'district_id' => 48,
                'date_start' => '2025-08-15',
                'date_end' => '2025-08-18',
                'total_cost' => 5000.00,
                'location' => 'District Office',
                'type' => 'documents',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 3,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 3,
            ],
        ];

        // Insert data
        $this->db->table('activities')->insertBatch($data);
    }
}
