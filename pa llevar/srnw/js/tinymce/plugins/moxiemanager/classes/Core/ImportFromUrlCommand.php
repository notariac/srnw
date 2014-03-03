<?php
/**
 * GetFileContentsCommand.php
 *
 * Copyright 2003-2013, Moxiecode Systems AB, All rights reserved.
 */

/**
 * Imports a file from the specified URL.
 *
 * @package MOXMAN_Core
 */
class MOXMAN_Core_ImportFromUrlCommand extends MOXMAN_Core_BaseCommand {
	/**
	 * Executes the command logic with the specified RPC parameters.
	 *
	 * @param Object $params Command parameters sent from client.
	 * @return Object Result object to be passed back to client.
	 */
	public function execute($params) {
		$file = MOXMAN::getFile($params->path);
		$url = parse_url($params->url);
		$config = $file->getConfig();

		if ($config->get('general.demo')) {
			throw new MOXMAN_Exception(
				"This action is restricted in demo mode.",
				MOXMAN_Exception::DEMO_MODE
			);
		}

		if ($file->exists()) {
			throw new MOXMAN_Exception(
				"To file already exist: " . $file->getPublicPath(),
				MOXMAN_Exception::FILE_EXISTS
			);
		}

		if (!$file->canWrite()) {
			throw new MOXMAN_Exception(
				"No write access to file: " . $toFile->getPublicPath(),
				MOXMAN_Exception::NO_WRITE_ACCESS
			);
		}

		$filter = MOXMAN_Vfs_BasicFileFilter::createFromConfig($config);
		if ($filter->accept($file, false) !== MOXMAN_Vfs_IFileFilter::ACCEPTED) {
			throw new MOXMAN_Exception(
				"Invalid file name for: " . $file->getPublicPath(),
				MOXMAN_Exception::INVALID_FILE_NAME
			);
		}

		$port = "";
		if (isset($url["port"])) {
			$port = ":". $url["port"];
		}

		$query = "";
		if (isset($url["query"])) {
			$query = "?". $url["query"];
		}

		$path = $url["path"] . $query;
		$host = $url["scheme"] . "://" . $url["host"] . $port;

		$httpClient = new MOXMAN_Http_HttpClient($host);
		$request = $httpClient->createRequest($path);
		$response = $request->send();

		$stream = $file->open(MOXMAN_Vfs_IFileStream::WRITE);
		if ($stream) {
			// Stream file down to disk
			while (($chunk = $response->read()) != "") {
				$stream->write($chunk);
			}

			$stream->close();
		}

		$httpClient->close();

		$args = new MOXMAN_Core_FileActionEventArgs("add", $file);
		MOXMAN::getPluginManager()->get("core")->fire("FileAction", $args);

		return parent::fileToJson($file);
	}
}

?>
