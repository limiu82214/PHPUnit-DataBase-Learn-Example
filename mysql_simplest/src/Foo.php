<?php

class Foo {
	private $pdo;

	public function __construct(PDO $pdo) {
		$this->pdo = $pdo;
	}

	public function insert(array $data) {
        $query = $this->pdo->prepare('INSERT INTO test_table (name, age) VALUES (?, ?)');
        return $query->execute(array($data['name'], $data['age']));
	}
}
