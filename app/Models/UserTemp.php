<?php

namespace App\Models;

use CodeIgniter\Model;

class UserTemp extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'user_temp';
    protected $primaryKey           = 'id_temp';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = [
        'username',
        'password',
        'nama',
        'no_telp',
        'id_user_role',
        'email',
        'status',
        'jml_login',
        'pwd_created',
        'pwd_exp',
        'created_at',
        'created_by',
        'approved_at',
        'approved_by',
        'is_approved',
        'approval_tipe',
        'is_done'
    ];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = [];
    protected $afterInsert          = [];
    protected $beforeUpdate         = [];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];
}
