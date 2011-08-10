<?php

class PHB_Mysql extends PDO {
	private static $_instance;
	private static $_config;

	private $_fetchMode = PDO::FETCH_OBJ;
	
	/**
	 *
	 * Constructor
	 *
	 */
	public function __construct ($config = '') {
		if ($config != '') self::config($config);
		
		$dsn = self::$_config['dsn'];
		$user = !empty(self::$_config['user']) ? self::$_config['user'] : '';
		$password = !empty(self::$_config['password']) ? self::$_config['password'] : '';

		try {
			parent::__construct($dsn, $user, $password);

			parent::setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $this->_fetchMode);
			parent::setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);		// Just to be sure that cache is enabled

//			parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		} catch (PDOException $e) {
			if ('production' != _ENV_) {
				echo 'Could not connect to database: ' . $e->getMessage();
			}
		}
	}


	/**
	 *
	 * Singleton
	 *
	 */
	public static function getInstance ($config = '') {
		if (!isset(self::$_instance)) {
			$c = __CLASS__;
			self::$_instance = new $c($config);
		}

		return self::$_instance;
	}
	

	/**
	 *
	 * Configure the database
	 *
	 */
	public static function config (array $config) {
		self::$_config = $config;
	}


	/**
	 *
	 * Query
	 *
	 */
	public function query ($query, $parms = array(), $fetchMode = '') {
		$stmt = parent::prepare($query);
	
		$result = $stmt->execute((array)$parms);

		if ($fetchMode) $stmt->setFetchMode($fetchMode);

        return $result ? $stmt : false;
	}


	/**
	 *
	 * Fetch all
	 *
	 */
	public function fetchAll ($qry, $parms = array(), $fetchMode = '') {
		$stmt = $this->query($qry, (array)$parms, $fetchMode);
		$result = $stmt->fetchAll();

		return $result;
	}
	

	/**
	 *
	 * Fetch row
	 *
	 */
	public function fetchRow ($qry, $parms = array(), $fetchMode = '') {
		if ( stristr($qry, 'LIMIT 1')===false ) $qry.= ' LIMIT 1';

		$stmt = $this->query($qry, (array)$parms, $fetchMode);
		$result = $stmt->fetch();

		return $result;
	}
	

	/**
	 *
	 * Fetch pair (useful for SELECT fields)
	 *
	 */
	public function fetchPairs ($qry, $parms = array()) {
		$stmt = $this->query($qry, (array)$parms);
		$result = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$result[$row[0]] = $row[1];
		}

		return $result;
	}
	

	/**
	 *
	 * Update
	 *
	 */
	public function update ($table, array $rows, $rule = '') {
		foreach ($rows as $col => $val) {
			$qry[] = "`" . $col . "` = :" . $col;
		}

		$qry = "UPDATE `" . $table . "` SET " . implode(', ', $qry);
		if ($rule != '') $qry .= " WHERE " . $rule;

		$result = $this->query($qry, $rows);

		return $result ? $result->rowCount() : false;
	}


	/**
	 *
	 * Insert
	 *
	 */
	public function insert ($table, array $rows, $lastId = false) {
		$cols = array();
		$vals = array();
		
		foreach ($rows as $col => $val) {
			$cols[] = "`" . $col . "`";
			$vals[] = ":" . $col;
		}
		
        $qry = "INSERT INTO `" . $table . "` " . "(" . implode(', ', $cols) . ") " . "VALUES (" . implode(', ', $vals) . ")";

		$result = $this->query($qry, $rows);
		
		return $result ? $lastId ? parent::lastInsertId() : $result->rowCount() : false;
	}


	/**
	 *
	 * Delete
	 *
	 */
	public function delete ($table, $rule = '') {
		$qry = "DELETE FROM `" . $table . "`";
		if ($rule != '') $qry .= " WHERE " . $rule;

		$result = $this->query($qry);

		return $result;
	}
}
