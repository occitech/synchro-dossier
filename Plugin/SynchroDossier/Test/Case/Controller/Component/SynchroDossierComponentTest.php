<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Controller', 'Controller');
App::uses('Component', 'Controller');
App::uses('SynchroDossierComponent', 'SynchroDossier.Controller/Component');

class AuthComponent extends Object {
	public function user($var) {
		return true;
	}
}

class SynchroDossierComponentTest extends ControllerTestCase {

	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->SynchroDossier = new SynchroDossierComponent($Collection);
	}

	public function tearDown() {
		unset($this->SynchroDossier);

		parent::tearDown();
	}

	public function testHasRightToViewQuota_HasRight() {
		$controller = new Controller();
		$controller->Auth = $this->getMock('AuthComponent');
		$controller->Auth->expects($this->any())
			->method('user')
			->will($this->returnValue('4'));

		$result = $this->SynchroDossier->hasRightToViewQuota($controller);
		$this->assertTrue($result);
	}

	public function testHasRightToViewQuota_HasNotRight() {
		$controller = new Controller();
		$controller->Auth = $this->getMock('AuthComponent');
		$controller->Auth->expects($this->once())
			->method('user')
			->will($this->returnValue('6'));


		$result = $this->SynchroDossier->hasRightToViewQuota($controller);
		$this->assertFalse($result);
	}
}
