<?php

/**
* This class is responsible for loading requested classes
* dynamically at runtime.
*/
class AutoLoader {
	public function __construct() {
		spl_autoload_register(array($this, 'loader'));
	}

	private function loader($class_name) {
		// convert a given namespace to a path include the file
		// and don't throw warnings if the file doesn't exists
		@include dirname(__FILE__) . '/' . $class_name . '.php';
	}
}