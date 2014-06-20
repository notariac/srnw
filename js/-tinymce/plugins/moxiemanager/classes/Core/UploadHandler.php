<?php
/**
 * UploadHandler.php
 *
 * Copyright 2003-2013, Moxiecode Systems AB, All rights reserved.
 */

/**
 * Http hander that takes the passed in file blob and stores that as a file object in the file system.
 *
 * @package MOXMAN_Core
 */
class MOXMAN_Core_UploadHandler implements MOXMAN_Http_IHandler {
	/**
	 * Process a request using the specified context.
	 *
	 * @param MOXMAN_Http_Context $httpContext Context instance to pass to use for the handler.
	 */
	public function processRequest(MOXMAN_Http_Context $httpContext) {
		$tempFilePath = null;

		$request = $httpContext->getRequest();
		$response = $httpContext->getResponse();

		try {
			// Check if the user is authenticated or not
			if (!MOXMAN::getAuthManager()->isAuthenticated()) {
				if (!isset($json->method) || !preg_match('/^(login|logout)$/', $json->method)) {
					$exception = new MOXMAN_Exception("Access denied by authenticator(s).", 10);

					$exception->setData(array(
						"login_url" => MOXMAN::getConfig()->get("authenticator.login_page")
					));

					throw $exception;
				}
			}

			$file = MOXMAN::getFile($request->get("path"));
			$config = $file->getConfig();

			if ($config->get('general.demo')) {
				throw new MOXMAN_Exception(
					"This action is restricted in demo mode.",
					MOXMAN_Exception::DEMO_MODE
				);
			}

			$maxSizeBytes = preg_replace("/[^0-9.]/", "", $config->get("upload.maxsize"));

			if (strpos((strtolower($config->get("upload.maxsize"))), "k") > 0) {
				$maxSizeBytes = round(floatval($maxSizeBytes) * 1024);
			}

			if (strpos((strtolower($config->get("upload.maxsize"))), "m") > 0) {
				$maxSizeBytes = round(floatval($maxSizeBytes) * 1024 * 1024);
			}

			$filename = $request->get("name");
			$id = $request->get("id");
			$loaded = intval($request->get("loaded", "0"));
			$total = intval($request->get("total", "-1"));
			$file = MOXMAN::getFile($file->getPath(), $filename);

			// Generate unique id for first chunk
			// TODO: We should cleanup orphan ID:s if upload fails etc
			if ($loaded == 0) {
				$id = uniqid();
			}

			// Setup path to temp file based on id
			$tempFilePath = MOXMAN_Util_PathUtils::combine(
				MOXMAN_Util_PathUtils::getTempDir(),
				"mcupload_" . $id . "." . MOXMAN_Util_PathUtils::getExtension($file->getName())
			);

			if (!$file->canWrite()) {
				throw new MOXMAN_Exception("No write access to path: " . $file->getPublicPath(), MOXMAN_Exception::NO_WRITE_ACCESS);
			}

			if ($total > $maxSizeBytes) {
				throw new MOXMAN_Exception("File size to large: " . $file->getPublicPath(), MOXMAN_Exception::FILE_SIZE_TO_LARGE);
			}

			// Operations on first chunk
			if ($loaded == 0) {
				if ($file->exists()) {
					if (!$config->get("upload.overwrite") && !$request->get("overwrite")) {
						throw new MOXMAN_Exception("Target file exists: " . $file->getPublicPath(), MOXMAN_Exception::FILE_EXISTS);
					} else {
						$file->delete();
					}
				}

				$filter = MOXMAN_Vfs_CombinedFileFilter::createFromConfig($config, "upload");
				if ($filter->accept($file) !== MOXMAN_Vfs_CombinedFileFilter::ACCEPTED) {
					throw new MOXMAN_Exception(
						"Invalid file name for: " . $file->getPublicPath(),
						MOXMAN_Exception::INVALID_FILE_NAME
					);
				}
			}

			$blobSize = 0;
			$inputFile = $request->getFile("file");
			if (!$inputFile) {
				throw new MOXMAN_Exception("No input file specified.");
			}

			if ($loaded === 0) {
				$blobSize = filesize($inputFile['tmp_name']);

				// Check if we should mock or not
				if (defined('PHPUNIT')) {
					if (!copy($inputFile['tmp_name'], $tempFilePath)) {
						throw new MOXMAN_Exception("Could not move the uploaded temp file.");
					}
				} else {
					if (!move_uploaded_file($inputFile['tmp_name'], $tempFilePath)) {
						throw new MOXMAN_Exception("Could not move the uploaded temp file.");
					}
				}
			} else {
				$in = fopen($inputFile['tmp_name'], 'r');
				if ($in) {
					$out = fopen($tempFilePath, 'a');
					if ($out) {
						while ($buff = fread($in, 8192)) {
							$blobSize += strlen($buff);
							fwrite($out, $buff);
						}

						fclose($out);
					}

					fclose($in);
				}
			}

			// Import file when all chunks are complete
			if ($total == -1 || $loaded + $blobSize == $total) {
				clearstatcache();

				// Check if file is valid on last chunk we also check on first chunk but not in the onces in between
				$filter = MOXMAN_Vfs_CombinedFileFilter::createFromConfig($config, "upload");
				if ($filter->accept($file) !== MOXMAN_Vfs_CombinedFileFilter::ACCEPTED) {
					throw new MOXMAN_Exception(
						"Invalid file name for: " . $file->getPublicPath(),
						MOXMAN_Exception::INVALID_FILE_NAME
					);
				}

				// Resize the temporary blob
				if ($config->get("upload.autoresize") && preg_match('/gif|jpe?g|png/i', MOXMAN_Util_PathUtils::getExtension($tempFilePath)) === 1) {
					$size = getimagesize($tempFilePath);
					$maxWidth = $config->get('upload.max_width');
					$maxHeight = $config->get('upload.max_height');

					if ($size[0] > $maxWidth || $size[1] > $maxHeight) {
						$imageAlter = new MOXMAN_Media_ImageAlter();
						$imageAlter->load($tempFilePath);
						$imageAlter->resize($maxWidth, $maxHeight, true);
						$imageAlter->save($tempFilePath, $config->get("upload.autoresize_jpeg_quality"));
					}
				}

				// Create thumbnail and upload then import local blob
				MOXMAN::getPluginManager()->get("core")->createThumbnail($file, $tempFilePath);
				$file->importFrom($tempFilePath);
				unlink($tempFilePath);

				$args = new MOXMAN_Core_FileActionEventArgs("add", $file);
				MOXMAN::getPluginManager()->get("core")->fire("FileAction", $args);

				$result = MOXMAN_Core_Plugin::fileToJson($file, true);
			} else {
				$result = $id;
			}

			$response->sendJson(array(
				"jsonrpc" => "2.0",
				"result" => $result,
				"id" => null
			));
		} catch (Exception $e) {
			if ($tempFilePath && file_exists($tempFilePath)) {
				unlink($tempFilePath);
			}

			MOXMAN::dispose(); // Closes any open file systems/connections

			$message = $e->getMessage();
			$data = null;

			// Add file and line number when running in debug mode
			// @codeCoverageIgnoreStart
			if (MOXMAN::getConfig()->get("general.debug")) {
				$message .= " " . $e->getFile() . " (" . $e->getLine() . ")";
			}
			// @codeCoverageIgnoreEnd

			// Grab the data from the exception
			if ($e instanceof MOXMAN_Exception && !$data) {
				$data = $e->getData();
			}

			// Json encode error response
			$response->sendJson((object) array(
				"jsonrpc" => "2.0",
				"error" => array(
					"code" => $e->getCode(),
					"message" => $message,
					"data" => $data
				),
				"id" => null
			));
		}
	}
}

?>