<?php
class Model {

	private static $static_db;
	protected $db;

	public function __construct() {
		$this->db = self::$static_db;
	}

	public static function init() {
		global $config;
		self::$static_db = new PDO('sqlite:models/database.sqlite');
		self::$static_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function inject_data($data) { return $data; }
}

Model::init();
