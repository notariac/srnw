<?php
/**
 * LocalFileConfigProvider.php
 *
 * Copyright 2003-2013, Moxiecode Systems AB, All rights reserved.
 */

/**
 * This class provides config instances for files.
 *
 * @package MOXMAN_Vfs_Local
 */
class MOXMAN_Vfs_Local_FileConfigProvider implements MOXMAN_Vfs_IFileConfigProvider {
	/** @ignore */
	private $fileSystem, $config;

	/** @ignore */
	public function __construct($filesystem, $config) {
		$this->fileSystem = $filesystem;
		$this->config = $config;
	}

	/**
	 * Returns a config based on the specified file.
	 *
	 * @param MOXMAN_Vfs_IFile $file File to get the config for.
	 * @return MOXMAN_Util_Config Config for the specified file.
	 */
	public function getConfig(MOXMAN_Vfs_IFile $file) {
		$config = clone $this->config;
		$path = $file->isFile() ? $file->getParent() : $file->getPath();
		$root = $this->fileSystem->getRootPath();
		$mcAccessFile = $this->config->get("filesystem.local.access_file_name", "mc_access");
		$user = MOXMAN::getUser();
		$configFiles = array();

		// Collect config files
		while ($path && strlen($path) >= strlen($root)) {
			if (file_exists($path . '/' . $mcAccessFile)) {
				$configFiles[] = $path . '/' . $mcAccessFile;
			}

			$path = MOXMAN_Util_PathUtils::getParent($path);
		}

		// Extend current config with the config files
		for ($i = count($configFiles) - 1; $i >= 0; $i--) {
			// Parse mc_access file
			$iniParser = new MOXMAN_Util_IniParser();
			$iniParser->load($configFiles[$i]);

			// Loop and extend it
			$items = $iniParser->getItems();
			foreach ($items as $key => $value) {
				// Group specific config
				if (is_array($value)) {
					$targetGroups = explode(',', $key);
					foreach ($targetGroups as $targetGroup) {
						if ($user->isMemberOf($targetGroup)) {
							foreach ($value as $key2 => $value2) {
								$config->put($key2, $value2);
							}
						}
					}
				} else {
					$config->put($key, $value);
				}
			}
		}

		return $config;
	}
}

?>