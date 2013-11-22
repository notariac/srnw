<?php
/**
 * MOXMAN.php
 *
 * Copyright 2003-2013, Moxiecode Systems AB, All rights reserved.
 */

@date_default_timezone_set('Europe/Paris');
@set_time_limit(5 * 60); // 5 minutes execution time

if (!defined('MOXMAN_ROOT')) {
	/**
	  * Path to the root of the moxiemanager.
	  *
	  * @package MOXMAN
	  */
	define('MOXMAN_ROOT', preg_replace('/[\\/\\\][^\\/\\\]+$/', '', dirname(__FILE__)));
}

if (!defined('MOXMAN_CLASSES')) {
	/**
	  * Path to the classes directory.
	  *
	  * @package MOXMAN
	  */
	define('MOXMAN_CLASSES', MOXMAN_ROOT . '/classes');
}

if (!defined('MOXMAN_PLUGINS')) {
	/**
	  * Path to the plugins directory.
	  *
	  * @package MOXMAN
	  */
	define('MOXMAN_PLUGINS', MOXMAN_ROOT . '/plugins');
}

// Load default config
if (!isset($moxieManagerConfig)) {
	$moxieManagerConfig = array();
	require_once(MOXMAN_ROOT . '/config.php');
}

require_once(MOXMAN_CLASSES . '/AutoLoader.php');
MOXMAN_AutoLoader::register();

/**
 * MoxieManager factory instance.
 *
 * @package MOXMAN
 */
class MOXMAN {
	/** @ignore */
	private static $logger, $session, $clientResourceManager, $pdo;

	/** @ignore */
	private static $fileSystemManager, $config, $pluginManager, $authManager, $user, $storageManager;

	// @codeCoverageIgnoreStart

	/**
	 * Returns the file system manager instance.
	 *
	 * @return MOXMAN_Vfs_FileSystemManager File system manager instance.
	 */
	public static function getFileSystemManager() {
		if (!self::$fileSystemManager) {
			$config = self::getConfig();
			self::$fileSystemManager = new MOXMAN_Vfs_FileSystemManager($config);
			self::$fileSystemManager->registerFileSystem("local", "MOXMAN_Vfs_Local_FileSystem");
			self::$fileSystemManager->registerFileSystem("zip", "MOXMAN_Vfs_Zip_FileSystem");
			self::$fileSystemManager->addRoot(self::getConfig()->get("filesystem.rootpath"));
		}

		return self::$fileSystemManager;
	}

	/**
	 * Returns the current user instance.
	 *
	 * @return MOXMAN_Auth_User Current user instance.
	 */
	public static function getUser() {
		return self::getAuthManager()->getUser();
	}

	/**
	 * Returns a file for the specified path.
	 *
	 * @param string $path Path to file to retrive.
	 * @param string $childPath Optional child path to combine with path.
	 * @return MOXMAN_Vfs_IFile File instance for the specified path.
	 */
	public static function getFile($path, $childPath = "") {
		return self::getFileSystemManager()->getFile($path, $childPath);
	}

	/**
	 * Returns the auth manager instance.
	 *
	 * @return MOXMAN_Auth_AuthManager Authentication manager instance.
	 */
	public static function getAuthManager() {
		if (!self::$authManager) {
			self::$authManager = new MOXMAN_Auth_AuthManager(self::getConfig()->get("authenticator"));
		}

		return self::$authManager;
	}

	/**
	 * Returns the global config instance.
	 *
	 * @return MOXMAN_Util_Config Global config instance.
	 */
	public static function getConfig() {
		if (!self::$config) {
			// Verify license
			if (!empty($GLOBALS['moxieManagerConfig'])) {
				if (!preg_match('/^([0-9A-Z]{4}\-){7}[0-9A-Z]{4}$/', trim($GLOBALS['moxieManagerConfig']["general.license"]))) {
					throw new MOXMAN_Exception("Invalid license: " . $GLOBALS['moxieManagerConfig']["general.license"]);
				}
			}

			self::$config = new MOXMAN_Util_Config($GLOBALS['moxieManagerConfig']);
			unset($GLOBALS['moxieManagerConfig']);
		}

		return self::$config;
	}

	/**
	 * Returns the logger instance.
	 *
	 * @return MOXMAN_Util_Logger Logger instance.
	 */
	public static function getLogger() {
		if (!self::$logger) {
			$config = self::getConfig();

			if ($config->get("log.enabled", false) === false) {
				return null;
			}

			self::$logger = new MOXMAN_Util_Logger(array(
				"path" => $config->get("log.path"),
				"filename" => $config->get("log.filename"),
				"format" => $config->get("log.format"),
				"max_size" => $config->get("log.max_size"),
				"max_files" => $config->get("log.max_files"),
				"level" => $config->get("log.level"),
				"date_format" => $config->get("log.date_format", "Y-m-d H:i:s")
			));
		}

		return self::$logger;
	}

	/**
	 * Returns plugin manager instance.
	 *
	 * @return MOXMAN_PluginManager Plugin manager instance.
	 */
	public static function getPluginManager() {
		if (!self::$pluginManager) {
			$user = self::getUser();
			self::$pluginManager = new MOXMAN_PluginManager($user);
		}

		return self::$pluginManager;
	}

	/**
	 * Returns an instance of the client resource manager.
	 *
	 * @return MOXMAN_Http_ClientResourceManager Instance of client resource manager.
	 */
	public static function getClientResourceManager() {
		if (!self::$clientResourceManager) {
			if (self::getConfig()->get("general.debug")) {
				self::$clientResourceManager = new MOXMAN_Http_ClientResourceManager(array(
					"no_cache" => true
				));
			} else {
				self::$clientResourceManager = new MOXMAN_Http_ClientResourceManager(array(
					"disk_cache" => true,
					"cache_dir" => MOXMAN_ROOT . '/cache'
				));
			}

			self::$clientResourceManager->load(MOXMAN_ROOT . "/themes/classic/resources.xml");

			$plugins = explode(',', self::getConfig()->get("general.plugins"));
			foreach ($plugins as $plugin) {
				if ($plugin) {
					$resources = MOXMAN_ROOT . '/plugins/' . $plugin . "/resources.xml";

					if (file_exists($resources)) {
						self::$clientResourceManager->load($resources);
					}
				}
			}
		}

		return self::$clientResourceManager;
	}

	/**
	 * Returns an instance of the PDO wrapper class.
	 *
	 * @return MOXMAN_Util_Pdo PDO wrapper instance.
	 */
	public static function getPdo() {
		if (!self::$pdo) {
			$config = self::getConfig();

			self::$pdo = new MOXMAN_Util_Pdo(
				$config->get("sql.connection"),
				$config->get("sql.username"),
				$config->get("sql.password"),
				array(),
				$config->get("sql.table_prefix")
			);

			self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		return self::$pdo;
	}

	/**
	 * Returns the storage manager instance.
	 *
	 * @return MOXMAN_Storage_StorageManager Storage manager instance.
	 */
	public static function getStorageManager() {
		if (!self::$storageManager) {
			$config = self::getConfig();
			$user = self::getUser();

			self::$storageManager = new MOXMAN_Storage_StorageManager($config, $user);
		}

		return self::$storageManager;
	}

	/**
	 * Returns a storage instance for the global scope. Global data is shared between all users.
	 *
	 * @return MOXMAN_Storage_IStorage Storage instance for global data.
	 */
	public static function getGlobalStorage() {
		$storage = self::getStorageManager()->getGlobalStorage();

		return $storage;
	}

	/**
	 * Returns a storage instance for the user scope. Items stored in this instance will only be available for the specific user.
	 *
	 * @return MOXMAN_Storage_IStorage Storage instance for global data.
	 */
	public static function getUserStorage() {
		$storage = self::getStorageManager()->getUserStorage();

		return $storage;
	}

	/**
	 * Returns a storage instance for the group scope. Items stored in this instance will only be available
	 * for the specific group and if the user has access to that group.
	 *
	 * @param string $name Name of the group to get the storage for.
	 * @return MOXMAN_Storage_IStorage Storage instance for global data.
	 */
	public static function getGroupStorage($name) {
		$storage = self::getStorageManager()->getGroupStorage($name);

		return $storage;
	}

	/**
	 * Disposes the file systems. This might flush resources used by the file systems.
	 */
	public static function dispose() {
		if (self::getFileSystemManager()) {
			self::getFileSystemManager()->close();
		}
	}

	// @codeCoverageIgnoreEnd
}

// Load authenticators, needs to be loaded at page level since they might contain globals
$authenticators = preg_split('/[+|]/', MOXMAN::getConfig()->get("authenticator"));
foreach ($authenticators as $authenticator) {
	if ($authenticator) {
		$authenticator = MOXMAN_ROOT . '/plugins/' . $authenticator . "/Plugin.php";

		if (file_exists($authenticator)) {
			require_once($authenticator);
		}
	}
}

// Load plugins, needs to be loaded at page level since they might contain globals
$plugins = explode(',', MOXMAN::getConfig()->get("general.plugins"));
foreach ($plugins as $plugin) {
	if ($plugin) {
		$pluginPath = MOXMAN_ROOT . '/plugins/' . $plugin;

		MOXMAN_AutoLoader::addPrefixPath("MOXMAN_" . $plugin, $pluginPath);

		$plugin = $pluginPath . "/Plugin.php";
		if (file_exists($plugin)) {
			require_once($plugin);
		}
	}
}

// Load core plugin last
require_once(MOXMAN_CLASSES . '/Core/Plugin.php');

// Trigger authenticate on all plugins so it can override any config options
try {
	MOXMAN::getAuthManager()->isAuthenticated();
} catch (Exception $e) {
	// Handle exceptions in authenticators
	$httpContext = MOXMAN_Http_Context::getCurrent();
	$request = $httpContext->getRequest();
	$response = $httpContext->getResponse();

	if ($request->get("json")) {
		$response->sendJson((object) array(
			"jsonrpc" => "2.0",
			"error" => array(
				"code" => $e->getCode(),
				"message" => $e->getMessage()
			),
			"id" => "r0"
		));
	} else {
		die($e->getMessage());
	}

	die();
}

// Initialize all plugins
MOXMAN::getPluginManager()->initAll();

?>