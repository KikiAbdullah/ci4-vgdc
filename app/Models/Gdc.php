<?php

namespace App\Models;

use CodeIgniter\Model;

class Gdc extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'gdc';
	protected $primaryKey           = 'id_gdc';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
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
		'created_by',
		'modified_by',
		'approved_at',
		'approved_by'
	];

	// Dates
	protected $useTimestamps        = true;
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
