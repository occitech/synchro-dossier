<?php
App::uses('UploadedFile', 'Uploader.Model');

/**
 * UploadedFile Test Case
 *
 */
class UploadedFileTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.uploader.uploaded_files_user',
		'plugin.uploader.uploaded_file',
		'plugin.uploader.user',
		'plugin.uploader.file_storage'
	);

/**
 * Remove content of the folder and all subfolder !
 */
	protected function _rmContentDir($dir) { 
		if (is_dir($dir)) { 
			$contents = scandir($dir); 
			foreach ($contents as $content) { 
				if ($content != "." && $content != "..") { 
					if (filetype($dir."/".$content) == "dir") {
						$this->_rmContentDir($dir."/".$content);
					} else {
						unlink($dir."/".$content);
					}
				} 
			} 
			reset($contents); 
		} 
	}


	public function setUp() {
		parent::setUp();
		$this->UploadedFile = ClassRegistry::init('Uploader.UploadedFile');
		$this->data = array(
			'FileStorage' => array(
				'file' => array(
					'name' => 'monfichier.jpg',
					'type' => 'text/x-gettext-translation',
					'tmp_name' => '/tmp/phpTmnlQd',
					'error' => (int) 0,
					'size' => (int) 71881
		)));
		Configure::write('FileStorage.adapter', 'Test');
		file_put_contents('/tmp/phpTmnlQd', 'Hi !');
	}

	public function tearDown() {
		unset($this->UploadedFile);
		$folder = Configure::read('FileStorage.testFolder');
		$this->_rmContentDir($folder);
		parent::tearDown();
	}

	public function testGetPathFile() {
		Configure::write('FileStorage.filePattern', '{user_id}/{file_id}-{version}-{filename}');
		$result = $this->_runProtectedMethod('_getPathFile', array(5, 2, 3, 'MygreatFile'));
		$this->assertEqual($result, '5/2-3-MygreatFile');
	}


	public function testUploadAlreadyExist() {
		$user_id = 1;
		$this->UploadedFile->upload($this->data, $user_id, 1);
		$result = $this->UploadedFile->find('first', array('conditions' => array('UploadedFile.filename' => 'monfichier.jpg')));
		$this->assertEqual($result['UploadedFile']['id'], 2);
		$this->assertEqual($result['UploadedFile']['current_version'], 2);
		$this->assertEqual($result['UploadedFile']['user_id'], 2);		
	}

	public function testUploadFileNotExist() {
		$user_id = 1;
		$this->data['FileStorage']['file']['name'] = 'Newfile.jpg';
		$this->UploadedFile->upload($this->data, $user_id, 1);
		
		$result = $this->UploadedFile->find('first', array('order' => 'UploadedFile.id desc'));
		$this->assertEqual($result['UploadedFile']['id'], 14);
		$this->assertEqual($result['UploadedFile']['filename'], 'Newfile.jpg');
		$this->assertEqual($result['UploadedFile']['current_version'], 1);

		$result = $this->UploadedFile->FileStorage->find('first', array('order' => 'id desc'));
		$this->assertEqual($result['FileStorage']['foreign_key'], 14);
		$this->assertEqual($result['FileStorage']['path'], '1/14-1-Newfile.jpg');
		$this->assertEqual($result['FileStorage']['user_id'], $user_id);
	}

	public function testUploadFileNotExistButAnotherFileHasTheSameName() {
		$user_id = 1;
		$folder_id = 2;
		$this->UploadedFile->upload($this->data, $user_id, $folder_id);
		$result = $this->UploadedFile->find('first', array('order' => 'UploadedFile.id desc'));
		$this->assertEqual($result['UploadedFile']['id'], 14);
		$this->assertEqual($result['UploadedFile']['filename'], 'monfichier.jpg');
		$this->assertEqual($result['UploadedFile']['current_version'], 1);
	}

	/**
	 * Vérifie que le fichier est corrextement copier dans le dossier du
	 * propriétaire du dossier parent
	 */
	public function testUploadAlreadyExistVerifyFileIsCreated() {
		$uploader_id = 1;
		$owner_id = 2;
		$parent_id = 1;
		$this->UploadedFile->upload($this->data, $uploader_id, $parent_id);
		$result = file_exists(Configure::read('FileStorage.testFolder') . DS . $owner_id . DS . '2-2-monfichier.jpg');
		$this->assertEqual($result, true);
	}

	/**
	 * 
	 */
	public function testUserHasRightNoRulesFound() {
		$user_id = 5845;
		$file_id = 155144;
		$result = $this->UploadedFile->userHasRight($user_id, $file_id, 'notImportant');
		$this->assertEqual($result, false);
		
		
	}

	protected function _runProtectedMethod($name, $args = array()) {
		$method = new ReflectionMethod(get_class($this->UploadedFile), $name);
		$method->setAccessible(true);
		return $method->invokeArgs($this->UploadedFile, $args);
	}

}
