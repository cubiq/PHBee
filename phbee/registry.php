<?php

class PHB_Registry {
	private static $_instance;

	private $_reg = array();
	
	/**
	 *
	 * Constructor
	 *
	 */
	public function __construct () {}


	/**
	 *
	 * Singleton
	 *
	 */
	public static function getInstance () {
		if (!isset(self::$_instance)) {
			$c = __CLASS__;
			self::$_instance = new $c;
		}

		return self::$_instance;
	}
	

	/**
	 *
	 * Setter
	 *
	 */
	public function __set ($name, $value) {
		$this->_reg[$name] = $value;
	}


	/**
	 *
	 * Getter
	 *
	 */
	public function __get ($name) {
		return isset($this->_reg[$name]) ? $this->_reg[$name] : null;
	}
	

	/**
	 *
	 * Unset-ter
	 *
	 */
	public function __unset ($name) {
		unset($this->_reg[$name]);
	}


	/**
	 *
	 * Isset-ter
	 *
	 */
	public function __isset ($name) {
		return isset($this->_reg[$name]);
	}
	

	/**
	 *
	 * Merge current registry with an array
	 *
	 */
	public function merge ($arr) {
		$this->_reg = array_replace_recursive($this->_reg, (array)$arr);
	}
	
}
