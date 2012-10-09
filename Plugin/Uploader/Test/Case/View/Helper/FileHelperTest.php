<?php

App::uses('Controller', 'Controller');
App::uses('CroogoTestCase', 'TestSuite');
App::uses('View', 'View');
App::uses('FileHelper', 'Uploader.View/Helper');

class FileHelperTest extends CroogoTestCase {

	public function setUp() {
		$controller = null;
		$this->View = new View($controller);
		$this->File = new FileHelper($this->View);
	}

	public function tearDown() {
		unset($this->View);
		unset($this->File);
	}

	public function testSizeLittle() {
		$result = $this->File->size(841, 'o');
		debug($result);
		$this->assertEqual('1 Ko', $result);
	}
	public function testSizeOctectToKo() {
		$result = $this->File->size(8000, 'o');
		debug($result);
		$this->assertEqual('8 Ko', $result);
	}
	public function testSizeOctectToMo() {
		$result = $this->File->size(8000000, 'o');
		debug($result);
		$this->assertEqual('7.6 Mo', $result);
	}
	public function testSizeOctectToGo() {
		$result = $this->File->size(8000000000, 'o');
		debug($result);
		$this->assertEqual('7.45 Go', $result);
	}
}