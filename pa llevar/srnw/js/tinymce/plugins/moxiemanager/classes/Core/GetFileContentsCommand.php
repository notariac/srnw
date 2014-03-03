<?php
/**
 * GetFileContentsCommand.php
 *
 * Copyright 2003-2013, Moxiecode Systems AB, All rights reserved.
 */

/**
 * Command that returns meta data for the specified file.
 *
 * @package MOXMAN_Core
 */
class MOXMAN_Core_GetFileContentsCommand extends MOXMAN_Core_BaseCommand {
	/**
	 * Executes the command logic with the specified RPC parameters.
	 *
	 * @param Object $params Command parameters sent from client.
	 * @return Object Result object to be passed back to client.
	 */
	public function execute($params) {
		$file = MOXMAN::getFile($params->path);
		$config = $file->getConfig();

		$filter = MOXMAN_Vfs_CombinedFileFilter::createFromConfig($config, "edit");
		if ($filter->accept($file) !== MOXMAN_Vfs_CombinedFileFilter::ACCEPTED) {
			throw new MOXMAN_Exception(
				"Invalid file name for: " . $file->getPublicPath(),
				MOXMAN_Exception::INVALID_FILE_NAME
			);
		}

		$content = "";
		$stream = $file->open(MOXMAN_Vfs_IFileStream::READ);
		if ($stream) {
			$content = $stream->readToEnd();
			$stream->close();
		}

		return (object) array(
			"content" => $content
		);
	}
}

?>