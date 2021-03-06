<?php

namespace App\Models;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Model;

class Transaksi extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'transaksi';
	protected $primaryKey           = 'id_trx';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'no_antrian',
		'tanggal',
		'nm_driver',
		'id_gdc',
		'id_tipe',
		'id_jenis',
		'id_layanan',
		'wkt_upload',
		'wkt_mulai',
		'wkt_selesai',
		'id_cs',
		'sts_trx',
		'id_csat',
		'link',
		'sessionid',
		'recordingid',
		'tampil'
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
