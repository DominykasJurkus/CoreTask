<?php

class DB {
	private $host = 'db';
	private $user = 'root';
	private $pass = 'test';
	private $dbname = 'Products';

	public function connect() {
		$conn_str = "mysql:host=$this->host;dbname=$this->dbname";
		$conn = new PDO($conn_str, $this->user, $this->pass);
		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $conn;
	}
}