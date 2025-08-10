<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditTrail extends Model
{
    protected $table = 'audit_trail';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'event',
        'table_name',
        'row_pk',
        'changed_columns',
        'old_values',
        'new_values',
        'actor_id',
        'actor_type',
        'request_id',
        'ip_address',
        'user_agent',
        'http_method',
        'url',
        'notes',
        'created_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}