<?php

namespace App\Models;

use CodeIgniter\Model;

class GdcTemp extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'gdc_temp';
	protected $primaryKey           = 'id_temp';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_gdc',
		'nm_gdc',
		'lokasi',
		'email_gdc',
		'password',
		'id_device',
		'apikey',
		'ip_gdc',
		'ipserver',
		'pwr',
		'status',
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
	protected $updatedField         = 'modified_at';
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
