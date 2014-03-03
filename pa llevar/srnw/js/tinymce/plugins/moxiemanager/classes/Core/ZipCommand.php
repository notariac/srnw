<?php
/**
 * UnZip.php
 *
 * Copyright 2003-2013, Moxiecode Systems AB, All rights reserved.
 */

/**
 * Command for zipping files on the remote file system.
 *
 * @package MOXMAN_Core
 */
class MOXMAN_Core_ZipCommand extends MOXMAN_Core_BaseCommand {
	/**
	 * Executes the command logic with the specified RPC parameters.
	 *
	 * @param Object $params Command parameters sent from client.
	 * @return Object Result object to be passed back to client.
	 */
	public function execute($params) {
		$toPath = $params->to;
		$ext = MOXMAN_Util_PathUtils::getExtension($toPath);
		if ($ext !== 'zip') {
			$toPath .= '.zip';
		}

		$toFile = MOXMAN::getFile($toPath);
		$config = $toFile->getConfig();

		if ($config->get('general.demo')) {
			throw new MOXMAN_Exception(
				"This action is restricted in demo mode.",
				MOXMAN_Exception::DEMO_MODE
			);
		}

		if (!$toFile->canWrite()) {
			throw new MOXMAN_Exception(
				"No write access to file: " . $toFile->getPublicPath(),
				MOXMAN_Exception::NO_WRITE_ACCESS
			);
		}

		$zipWriter = new MOXMAN_Zip_ZipWriter(array(
			"compressionLevel" => 5
		));

		$filter = MOXMAN_Vfs_BasicFileFilter::createFromConfig($config);

		$path = $params->path;
		foreach ($params->names as $name) {
			$fromFile = MOXMAN::getFile(MOXMAN_Util_PathUtils::combine($path, $name));
			$this->addZipFiles($fromFile, $fromFile->getParent(), $filter, $zipWriter);
		}

		$stream = $toFile->open(MOXMAN_Vfs_IFileStream::WRITE);
		if ($stream) {
			$stream->write($zipWriter->toString());
			$stream->close();
		}

		$this->fireFileAction(MOXMAN_Core_FileActionEventArgs::ADD, $toFile);

		return $this->fileToJson($toFile);
	}

	/** @ignore */
	private function addZipFiles($file, $rootPath, $filter, $zipWriter) {
		if ($filter->accept($file) === MOXMAN_Vfs_IFileFilter::ACCEPTED) {
			$zipPath = substr($file->getPath(), strlen($rootPath));

			if ($file->isFile()) {
				if ($file instanceof MOXMAN_Vfs_Local_File) {
					$zipWriter->addFile($zipPath, $file->getPath());
				} else {
					$stream = $file->open(MOXMAN_Vfs_IFileStream::READ);
					if ($stream) {
						$zipWriter->addFileData($zipPath, $stream->readToEnd());
						$stream->close();
					}
				}
			} else {
				$zipWriter->addDirectory($zipPath);

				$files = $file->listFilesFiltered($filter);
				foreach ($files as $file) {
					$this->addZipFiles($file, $rootPath, $filter, $zipWriter);
				}
			}
		}
	}
}

?>