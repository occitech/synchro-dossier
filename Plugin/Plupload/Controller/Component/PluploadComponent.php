<?php

App::uses('Component', 'Controller');

class PluploadComponent extends Component {

	public function upload() {
		$uploadFinished = false;
		$directory = sys_get_temp_dir();

		$filename = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
		$currentChunk = isset($_REQUEST['chunk']) ? intval($_REQUEST['chunk']) : 0;
		$nbChunks = isset($_REQUEST['chunks']) ? intval($_REQUEST['chunks']) : 0;

		if (!is_null(env("CONTENT_TYPE"))) {
			$contentType = env("CONTENT_TYPE");
		} elseif (!is_null(env("HTTP_CONTENT_TYPE"))) {
			$contentType = env("HTTP_CONTENT_TYPE");
		}

		$filename = $this->_getUniqueFileName($filename, $directory, $nbChunks);
		$filePath = $directory . DIRECTORY_SEPARATOR . $filename;

		$error = $this->_writeChunkInTempFile($filePath, $currentChunk, $contentType);

		if (is_null($error)) {
			if (!$nbChunks || $currentChunk == $nbChunks - 1) {
				rename("{$filePath}.part", $filePath);
				$uploadFinished = true;
			}
			$response = '{"jsonrpc" : "2.0", "result" : null, "id" : "id"}';
		} else {
			$response = $error;
		}

		return array($uploadFinished, $response, $filePath);
	}

	public function generateJsonMessage($options) {
		$default = array('jsonrpc' => '2.0', 'result' => null);

		$options = array_merge($default, $options);

		return json_encode($options);
	}

	/**
	 * Return an unique filename if chunking is disabled, else we return given filename
	 */
	protected function _getUniqueFileName($filename, $directory, $nbChunks) {
		$newFilename = $filename;

		if ($nbChunks < 2 && file_exists($directory . DS . $filename)) {
			$extentionPosition = strrpos($filename, '.');
			$fileNameWithoutExtention = substr($filename, 0, $extentionPosition);
			$extention = substr($filename, $extentionPosition);

			$count = 0;
			do {
				$newFilename = $fileNameWithoutExtention . '_' . $count . $extention;
				$count++;
			} while (file_exists($directory . DS . $newFilename));
		}
		return $newFilename;
	}

	protected function _writeChunkInTempFile($filePath, $currentChunk, $contentType) {
		$error = null;
		$inPath = "php://input";
		$inPathExist = true;

		if (strpos($contentType, "multipart") !== false) {
			$inPath = $_FILES['file']['tmp_name'];
			$inPathExist = isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name']);
		}

		if ($inPathExist) {
			$out = fopen("{$filePath}.part", $currentChunk == 0 ? "wb" : "ab");
			if ($out) {
				$in = fopen($inPath, "rb");

				if ($in) {
					while ($buff = fread($in, 4096)){
						fwrite($out, $buff);
					}
				} else {
					$error = '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}';
				}
				fclose($in);
				fclose($out);
			} else {
				$error = '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}';
			}
		} else {
			$error = '{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}';
		}

		return $error;
	}
}