<?php

/**
 * 
 * General purpose utilities
 * Utils is the only system class that is not PHB_ prefixed
 * 
 */
class Utils {
	/**
	 * 
	 * Get cookie
	 * 
	 */
	public static function getCookie ($name) {
		return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
	}


	/**
	 * 
	 * Set cookie
	 * 
	 */
	public static function setCookie ($name, $value, $expire = '') {
		$reg = PHB_Registry::getInstance();
		$expire = $expire != '' ? $expire : _NOW_ + $reg->cookie['expire'];
		setcookie($name, $value, $expire, $reg->cookie['path'], $reg->cookie['domain'], $reg->cookie['secure']);
	}


	/**
	 * 
	 * Remove cookie
	 * 
	 */
	public static function removeCookie ($name) {
		$reg = PHB_Registry::getInstance();
		setcookie($name, '', 0, $reg->cookie['path'], $reg->cookie['domain'], $reg->cookie['secure']);
		unset($_COOKIE[$name]);
	}

}
