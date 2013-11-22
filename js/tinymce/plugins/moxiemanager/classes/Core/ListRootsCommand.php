<?php
/**
 * GetRoots.php
 *
 * Copyright 2003-2013, Moxiecode Systems AB, All rights reserved.
 */

/**
 * Command that lists the roots for all registred file systems.
 *
 * @package MOXMAN_Core
 */
class MOXMAN_Core_ListRootsCommand extends MOXMAN_Core_BaseCommand {
	/**
	 * Executes the command logic with the specified RPC parameters.
	 *
	 * @param Object $params Command parameters sent from client.
	 * @return Object Result object to be passed back to client.
	 */
	public function execute($params) {
		$rootPaths = array();

		foreach (MOXMAN::getFileSystemManager()->getFileSystems() as $filesystem) {
			$rootFile = $filesystem->getRootFile();

			if (!$rootFile->isHidden()) {
				// Get root file meta data
				$meta = $rootFile->getMetaData();
				$meta = $meta->getAll("ui");

				// Add show/hide login to meta
				$meta["standalone"] = MOXMAN::getAuthManager()->hasStandalone();

				// Return name, path and optional meta data
				$rootPaths[] = (object) array(
					"name" => $filesystem->getRootName(),
					"path" => $rootFile->getPublicPath(),
					"meta" => $meta,
					"config" => $this->getPublicConfig($rootFile)
				);
			}
		}

		return $rootPaths;
	}
}

?>