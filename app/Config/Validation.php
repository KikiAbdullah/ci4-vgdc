<?php

namespace Config;

use App\Validation\CustomRules;
use CodeIgniter\Validation\CreditCardRules;
use CodeIgniter\Validation\FileRules;
use CodeIgniter\Validation\FormatRules;
use CodeIgniter\Validation\Rules;

class Validation
{
	//--------------------------------------------------------------------
	// Setup
	//--------------------------------------------------------------------

	/**
	 * Stores the classes that contain the
	 * rules that are available.
	 *
	 * @var string[]
	 */
	public $ruleSets = [
		Rules::class,
		FormatRules::class,
		FileRules::class,
		CreditCardRules::class,
		CustomRules::class,
	];

	/**
	 * Specifies the views that are used to display the
	 * errors.
	 *
	 * @var array<string, string>
	 */
	public $templates = [
		'list'   => 'CodeIgniter\Validation\Views\list',
		'single' => 'CodeIgniter\Validation\Views\single',
	];

	//--------------------------------------------------------------------
	// Rules
	//--------------------------------------------------------------------

	public $register = [
		'username' => 'trim|required|valid_data',
		'nama' => 'trim|required|valid_data',
		'email' => 'trim|required|valid_data',
		'password' => 'trim|required|min_length[8]|max_length[14]|valid_password',
		'confirm_password' => 'required|matches[password]',
	];

	public $register_errors = [
		'username' => [
			'required'      => 'Username wajib diisi',
			'alpha_numeric' => 'Username hanya boleh diisi dengan huruf dan angka',
			'min_length'    => 'Username minimal terdiri dari 5 karakter',
			'max_length'    => 'Username maksimal terdiri dari 20 karakter'
		],
		'email' => [
			'required'          => 'Email wajib diisi',
			'email.valid_email' => 'Email tidak valid'
		],
		'password' => [
			'required'      => 'Password wajib diisi',
			'min_length'    => 'Password minimal terdiri dari 8 karakter',
			'max_length'    => 'Password maksimal terdiri dari 14 karakter'
		],
		'confirm_password' => [
			'required'      => 'Password wajib diisi',
			'min_length'    => 'Password minimal terdiri dari 8 karakter',
			'max_length'    => 'Password maksimal terdiri dari 14 karakter'
		]
	];

	public $update = [
		'username' => 'trim|required|valid_data',
		'nama' => 'trim|required|valid_data',
		'email' => 'trim|required|valid_data'
	];

	public $store_user_role = [
		'user_role' => 'trim|required'
	];

	public $transaction = [
		'trx_file'         => 'uploaded[trx_file]|ext_in[trx_file,xls,xlsx]|max_size[trx_file,1000]',
	];

	public $transaction_errors = [
		'trx_file' => [
			'ext_in'    => 'File Excel hanya boleh diisi dengan xls atau xlsx.',
			'max_size'  => 'File Excel product maksimal 1mb',
			'uploaded'  => 'File Excel product wajib diisi'
		]
	];

	public $store_kios = [
		'nm_gdc' => 'trim|required|valid_data',
		'email_gdc' => 'trim|required',
		'lokasi' => 'trim|required|valid_data',
		//'status' =>'trim|required|valid_data',
		'ip_gdc' => 'trim|required|valid_data',
		'ipserver' => 'trim|required|valid_data',
		'apikey' => 'trim|required',
		'id_device' => 'trim|required',
		'password' => 'trim|required|min_length[8]|max_length[14]|valid_password',
		'confirm_password' => 'required|matches[password]',
	];

	public $update_kios = [
		'nm_gdc' => 'trim|required|valid_data',
		'email_gdc' => 'trim|required',
		'lokasi' => 'trim|required|valid_data',
		'ip_gdc' => 'trim|required|valid_data',
		'ipserver' => 'trim|required|valid_data',
		'apikey' => 'trim|required',
		'id_device' => 'trim|required'
	];
}
