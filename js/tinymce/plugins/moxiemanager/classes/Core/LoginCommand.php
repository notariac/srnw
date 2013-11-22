<?php
/**
 * LoginCommand.php
 *
 * Copyright 2003-2013, Moxiecode Systems AB, All rights reserved.
 */

/**
 * Command that logs in the user.
 *
 * @package MOXMAN_Core
 */
class MOXMAN_Core_LoginCommand extends MOXMAN_Core_BaseCommand {
	/**
	 * Executes the command logic with the specified RPC parameters.
	 *
	 * @param Object $params Command parameters sent from client.
	 * @return Object Result object to be passed back to client.
	 */
	public function execute($params) {
		return MOXMAN::getAuthManager()->login($params->username, $params->password, $params->persistent);
	}
}

?>