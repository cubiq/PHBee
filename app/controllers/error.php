<?php

class ErrorController extends PHBEE
{
	public function index () {
		header('HTTP/1.1 404 Not Found', true);
	}
}
