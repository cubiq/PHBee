<?php

class PHB_View {
	private static $_instance;

	private $_reg;
	private $_layout = 'default';
	private $_page = '';
	private $_render = true;


	/**
	 *
	 * Constructor
	 *
	 */
	public function __construct () {
		$this->_reg = PHB_Registry::getInstance();
		$this->_page = $this->_reg->action;
	}


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
	 * Set active layout
	 * 
	 */
	public function setLayout ($layout) {
		$this->_layout = $layout;
	}


	/**
	 * 
	 * Set active page
	 * 
	 */
	public function setPage ($page) {
		$this->_page = $page;
	}


	/**
	 *
	 * Disable rendering
	 *
	 */
	public function disableRendering () {
		$this->_render = false;
	}
	

	/**
	 *
	 * Load main page view
	 *
	 */
	public function page () {
		include _APP_ . '/views/pages/' . $this->_reg->controller . '/' . $this->_page . '.phtml';
	}


	/**
	 *
	 * Render main layout
	 *
	 */
	public function render () {
		if ($this->_render) include _APP_ . '/views/layouts/' . $this->_layout . '.phtml';
	}


	/**
	 *
	 * Load block
	 *
	 */
	public function block ($block) {
		include _APP_ . '/views/blocks/' . $block . '.phtml';
	}


	public function isController ($controller) {
		return $this->_reg->controller == $controller;
	}
	
	public function isPage ($page) {
		return $this->_page == $page;
	}
}
