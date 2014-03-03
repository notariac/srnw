<?php
/**
 * Delete.php
 *
 * Copyright 2003-2013, Moxiecode Systems AB, All rights reserved.
 */

/**
 * Command that deletes multiple files.
 *
 * @package MOXMAN_Core
 */
class MOXMAN_Core_DeleteCommand extends MOXMAN_Core_BaseCommand {
	/**
	 * Executes the command logic with the specified RPC parameters.
	 *
	 * @param Object $params Command parameters sent from client.
	 * @return Object Result object to be passed back to client.
	 */
	public function execute($params) {
		$paths = $params->paths;
		$result = array();

		foreach ($paths as $path) {
			$file = MOXMAN::getFile($path);
			$config = $file->getConfig();

			if ($config->get('general.demo')) {
				throw new MOXMAN_Exception(
					"This action is restricted in demo mode.",
					MOXMAN_Exception::DEMO_MODE
				);
			}

			if (!$file->exists()) {
				throw new MOXMAN_Exception(
					"Path doesn't exist: " . $file->getPublicPath(),
					MOXMAN_Exception::FILE_DOESNT_EXIST
				);
			}

			if (!$file->canWrite()) {
				throw new MOXMAN_Exception(
					"No write access to file: " . $file->getPublicPath(),
					MOXMAN_Exception::NO_WRITE_ACCESS
				);
			}

			$result[] = $this->fileToJson($file);

			if ($file->exists()) {
				$file->delete(true);
				$this->fireFileAction(MOXMAN_Core_FileActionEventArgs::DELETE, $file);
			}
		}

		return $result;
	}
}

?>