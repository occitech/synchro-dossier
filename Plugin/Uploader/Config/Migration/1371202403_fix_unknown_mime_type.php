<?php
App::uses('File', 'Utility');
App::uses('Hash', 'Utility');
class FixUnknownMimeType extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'This migration is irreversible. Down direction will have no effect';
	private $__unknownMimeType = '';
/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
		),
		'down' => array(
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		$success = true;
		if ($direction == 'up') {
			$UploadedFile = $this->generateModel('UploadedFile');
			$FileStorage = $this->generateModel('FileStorage');
			$FileStorage->useTable = 'file_storage';

			$filesWithoutMimeTypes = $this->__getUploadedFileWithoutMimeType($UploadedFile);

			$filesId = Hash::extract($filesWithoutMimeTypes, '{n}.UploadedFile.id');
			$paths = $this->__getFilePath($FileStorage, $filesId);

			$uploadedFilesWithMimeType = $this->__getUpdatedUploadedFilesData($paths);
			if (!empty($uploadedFilesWithMimeType)) {
				$success = $success && $UploadedFile->saveMany($uploadedFilesWithMimeType);
			}
		}
		return $success;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}

	private function __getUpdatedUploadedFilesData($paths){
		$uploadedFiles = array();
		foreach ($paths as $path) {
			$uploadedFiles[]= array(
				'id' => $path['FileStorage']['foreign_key'],
				'mime_type' => $this->__getMimeTypeAccordingToFile($path)
			);
		}
		return $uploadedFiles;
	}

	private function __getMimeTypeAccordingToFile($path){
		$basePath = APP . DS . 'uploads';
		$file = new File($basePath . DS . $path['FileStorage']['path']);
		$mimeType = $file->mime();
		$mimeType = !$mimeType ? $this->__unknownMimeType : $file->mime();
		unset($file);
		return $mimeType;
	}

	private function __getUploadedFileWithoutMimeType(Model $UploadedFile){
		return  $UploadedFile->find('all', array(
			'conditions' => array(
				'mime_type' => '',
				'is_folder' => 0
			),
			'fields' => array('UploadedFile.id')
		));
	}

	private function __getFilePath(Model $FileStorage, $filesId){
		return  $FileStorage->find('all', array(
			'conditions' => array(
				'model' => 'UploadedFile',
				'foreign_key' => $filesId,
			),
			'fields' => array('FileStorage.path', 'FileStorage.foreign_key')
		));
	}
}
