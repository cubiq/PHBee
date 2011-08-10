<?php

class PHBEE {
	public static $version = '0.1';
	
	public $reg;
	public $view;

	/**
	 * 
	 * Constructor
	 * 
	 */
	public function __construct () {
		$this->reg = PHB_Registry::getInstance();
		$this->view = PHB_View::getInstance();
		
		$this->view->phbee_version = self::$version;
	}
	

	/**
	 * 
	 * Prevent function call error if controller does not have a custom init()
	 * 
	 */
	public function init () { }
	
}