<?php
/**
 * ClientResourceManager.php
 *
 * Copyright 2003-2013, Moxiecode Systems AB, All rights reserved.
 */

 /**
  * This class will combine javascripts and stylesheets and compress them using gzip.
 *
 * @package MOXMAN_Http
 */
class MOXMAN_Http_ClientResourceManager {
	/** @ignore */
	private $packages;

	/**
	 * Constructs a client resources instance.
	 *
	 * @param array settings Optional settings for the class.
	 */
	public function __construct(array $settings = array()) {
		$this->settings = array_merge(array(
			"compress" => true,
			"cache_dir" => "",
			"expires"    => "30d",
			"disk_cache" => false,
			"no_cache" => false
		), $settings);

		$this->packages = array();
	}

	/**
	 * Loads the specified XML file and adds the packages inside it. The paths in the XML file will be resolved
	 * absolute to where the XML file was loaded from.
	 *
	 * @param string $path Path to XML file to load and add into the internal package collection.
	 */
	public function load($path) {
		$xml = simplexml_load_file($path);
		$base = MOXMAN_Util_PathUtils::getParent($path);

		// Load XML and add it to packages
		foreach ($xml->xpath('//package') as $package) {
			$attrs = $package->attributes();
			$packageName = strtolower($attrs["name"]);

			if (!isset($this->packages[$packageName])) {
				$this->packages[$packageName] = array();
			}

			foreach ($package->xpath('file') as $file) {
				$attrs = $file->attributes();
				$this->packages[$packageName][] = MOXMAN_Util_PathUtils::toAbsolute($base, $attrs["path"]);
			}
		}
	}

	/**
	 * Streams a specific package by name to the browser. This will combine the files in the package
	 * and gzip them together.
	 *
	 * @param string $name Name of the package to stream.
	 */
	public function streamPackage($name) {
		if (isset($this->packages[$name]) && count($this->packages[$name]) > 0) {
			$package = $this->packages[$name];
			$supportsGzip = false;
			$expiresOffset = $this->parseTime($this->settings["expires"]);

			// Generate md5 checksum of all the paths
			$hash = md5(implode('', $package));

			// Check if it supports gzip
			$zlibOn = ini_get('zlib.output_compression') || (ini_set('zlib.output_compression', 0) === false);
			$encodings = (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) ? strtolower($_SERVER['HTTP_ACCEPT_ENCODING']) : "";
			$encoding = preg_match( '/\b(x-gzip|gzip)\b/', $encodings, $match) ? $match[1] : "";

			if (!$this->settings["no_cache"]) {
				// Is northon antivirus header
				if (isset($_SERVER['---------------'])) {
					$encoding = "x-gzip";
				}

				$supportsGzip = $this->settings['compress'] && !empty($encoding) && !$zlibOn && function_exists('gzencode');

				// Check the extension of the first file
				$ext = MOXMAN_Util_PathUtils::getExtension($package[0]);

				// Set cache file name
				$cacheFile = $this->settings["cache_dir"] . "/" . $hash . ($supportsGzip ? ".gz" : "." . $ext);

		 		// Set headers
				header("Content-type: " . ($ext == "js" ? "text/javascript" : "text/css"));
				header("Vary: Accept-Encoding");  // Handle proxies
				header("Expires: " . gmdate("D, d M Y H:i:s", time() + $expiresOffset) . " GMT");
				header("Cache-Control: public, max-age=" . $expiresOffset);

				if ($supportsGzip) {
					header("Content-Encoding: " . $encoding);
				}

				// Use cached file
				if ($this->settings['disk_cache'] && file_exists($cacheFile)) {
					readfile($cacheFile);
					return;
				}
			} else {
				$ext = MOXMAN_Util_PathUtils::getExtension($package[0]);
				header("Content-type: " . ($ext == "js" ? "text/javascript" : "text/css"));

				// No cache headers
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: private", false);
			}

			// Combine all files
			$buffer = "";
			foreach ($package as $path) {
				$buffer .= file_get_contents($path);
			}

			// Compress data
			if ($supportsGzip) {
				$buffer = gzencode($buffer, 9, FORCE_GZIP);
			}

			// Write cached file
			if ($this->settings["disk_cache"]) {
				@file_put_contents($cacheFile, $buffer);
			}

			// Stream contents to client
			echo $buffer;
		}
	}

	/**
	 * Parses the specified time format into seconds. Supports formats like 10h, 10d, 10m.
	 *
	 * @param string $time Time format to convert into seconds.
	 * @return int Number of seconds for the specified format.
	 */
	private function parseTime($time) {
		$multipel = 1;

		// Hours
		if (strpos($time, "h") > 0) {
			$multipel = 3600;
		}

		// Days
		if (strpos($time, "d") > 0) {
			$multipel = 86400;
		}

		// Months
		if (strpos($time, "m") > 0) {
			$multipel = 2592000;
		}

		// Trim string
		return intval($time) * $multipel;
	}
}

?>