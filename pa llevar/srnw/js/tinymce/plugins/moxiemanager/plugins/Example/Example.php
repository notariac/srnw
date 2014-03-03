<?php
/**
 * Example.php
 *
 * Copyright 2003-2013, Moxiecode Systems AB, All rights reserved.
 */

/**
 * ...
 */
class MOXMAN_ExamplePlugin implements MOXMAN_Plugin {
	public function init() {
		$this->bind("Authenticate", "onAuthenticate");
		$this->bind("FileAction", "onFileAction");
	}

	public function onAuthenticate(MOXMAN_Auth_AuthEventArgs $args) {
	}

	public function onFileAction(MOXMAN_Vfs_FileActionEventArgs $args) {
		
	}
}

// Add plugin
MOXMAN::getPluginManager()->add("example", new MOXMAN_ExamplePlugin());

?>