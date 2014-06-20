<?php
/**
 * MoveTo.php
 *
 * Copyright 2003-2013, Moxiecode Systems AB, All rights reserved.
 */

/**
 * Command for moving multiple files from one path to another.
 *
 * @package MOXMAN_Core
 */
class MOXMAN_Core_MoveToCommand extends MOXMAN_Core_BaseCommand {
	/**
	 * Executes the command logic with the specified RPC parameters.
	 *
	 * @param Object $params Command parameters sent from client.
	 * @return Object Result object to be passed back to client.
	 */
	public function execute($params) {
		$from = $params->from;
		$to = $params->to;

		// Move multiple files
		if (is_array($from)) {
			$result = array();
			foreach ($from as $path) {
				$fromFile = MOXMAN::getFile($path);
				$toFile = MOXMAN::getFile($to, $fromFile->getName());
				$this->moveFile($fromFile, $toFile);

				$result[] = parent::fileToJson($toFile);
			}

			return $result;
		}

		// Move single file
		$fromFile = MOXMAN::getFile($from);
		$toFile = MOXMAN::getFile($params->to);
		$this->moveFile($fromFile, $toFile);

		return parent::fileToJson($toFile);
	}

	/** @ignore */
	private function moveFile($fromFile, $toFile) {
		$config = $toFile->getConfig();

		if ($config->get('general.demo')) {
			throw new MOXMAN_Exception(
				"This action is restricted in demo mode.",
				MOXMAN_Exception::DEMO_MODE
			);
		}

		if (!$fromFile->exists()) {
			throw new MOXMAN_Exception(
				"From file doesn't exist: " . $fromFile->getPublicPath(),
				MOXMAN_Exception::FILE_DOESNT_EXIST
			);
		}

		if ($toFile->exists()) {
			throw new MOXMAN_Exception(
				"To file already exist: " . $toFile->getPublicPath(),
				MOXMAN_Exception::FILE_EXISTS
			);
		}

		if (!$toFile->canWrite()) {
			throw new MOXMAN_Exception(
				"No write access to file: " . $toFile->getPublicPath(),
				MOXMAN_Exception::NO_WRITE_ACCESS
			);
		}

		$filter = MOXMAN_Vfs_CombinedFileFilter::createFromConfig($config, "rename");
		if ($filter->accept($toFile, $fromFile->isFile()) !== MOXMAN_Vfs_CombinedFileFilter::ACCEPTED) {
			throw new MOXMAN_Exception(
				"Invalid file name for: " . $toFile->getPublicPath(),
				MOXMAN_Exception::INVALID_FILE_NAME
			);
		}

		$fromFile->moveTo($toFile);

		$this->fireTargetFileAction(MOXMAN_Core_FileActionEventArgs::MOVE, $fromFile, $toFile);
	}
}

?>