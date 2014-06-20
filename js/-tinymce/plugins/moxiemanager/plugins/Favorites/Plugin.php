<?php
/**
 * Plugin.php
 *
 * Copyright 2003-2013, Moxiecode Systems AB, All rights reserved.
 */

/**
 * ...
 */
class MOXMAN_Favorites_Plugin implements MOXMAN_IPlugin, MOXMAN_ICommandHandler {
	public function init() {
		MOXMAN::getFileSystemManager()->registerFileSystem("favorite", "MOXMAN_Favorites_FileSystem");
		MOXMAN::getFileSystemManager()->addRoot("Favorites=favorite:///");
	}

	public function execute($name, $params) {
		switch ($name) {
			case "favorites.add":
				return $this->add($params);

			case "favorites.remove":
				return $this->remove($params);
		}
	}

	public function add($params) {
		if (MOXMAN::getConfig()->get('general.demo')) {
			throw new MOXMAN_Exception(
				"This action is restricted in demo mode.",
				MOXMAN_Exception::DEMO_MODE
			);
		}

		if (isset($params->paths) && is_array($params->paths)) {
			$paths = $params->paths;
			$files = MOXMAN_Util_Json::decode(MOXMAN::getUserStorage()->get("favorites.files", "[]"));

			// If files is larger then max size then crop it
			$max = intval(MOXMAN::getConfig()->get("favorites.max"));
			if (count($files) >= $max) {
				$files = array_slice($files, count($files) - $max);
			}

			// Remove existing paths
			for ($i = 0; $i < count($paths); $i++) {
				foreach ($files as $file) {
					if ($file->path == $paths[$i]) {
						array_splice($paths, $i, 1);
					}
				}
			}

			// Add new files
			foreach ($paths as $path) {
				$file = MOXMAN::getFile($path);

				$files[] = array(
					"path" => $file->getPublicPath(),
					"size" => $file->getSize(),
					"isdir" => $file->isDirectory(),
					"mdate" => $file->getLastModified()
				);
			}

			MOXMAN::getUserStorage()->put("favorites.files", MOXMAN_Util_Json::encode($files));
		}

		return true;
	}

	public function remove($params) {
		if (MOXMAN::getConfig()->get('general.demo')) {
			throw new MOXMAN_Exception(
				"This action is restricted in demo mode.",
				MOXMAN_Exception::DEMO_MODE
			);
		}

		if (isset($params->paths) && is_array($params->paths)) {
			$paths = $params->paths;
			$files = MOXMAN_Util_Json::decode(MOXMAN::getUserStorage()->get("favorites.files", "[]"));

			// Remove existing paths
			for ($i = 0; $i < count($files); $i++) {
				foreach ($paths as $path) {
					if ($files[$i]->path == $path) {
						array_splice($files, $i, 1);
					}
				}
			}

			MOXMAN::getUserStorage()->put("favorites.files", MOXMAN_Util_Json::encode($files));
		}

		return true;
	}
}

// Add plugin
MOXMAN::getPluginManager()->add("favorites", new MOXMAN_Favorites_Plugin());

?>