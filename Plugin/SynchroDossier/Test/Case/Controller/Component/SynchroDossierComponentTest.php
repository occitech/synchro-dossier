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
		$this->Controller = new Controller();
		$this->Controller->Auth = $this->getMock('AuthComponent');
	}

	public function tearDown() {
		unset($this->SynchroDossier);

		parent::tearDown();
	}

	public function testSetCanViewQuota_HasRight() {
		$this->Controller->Auth->expects($this->any())
			->method('user')
			->will($this->returnValue('4'));

		$result = $this->SynchroDossier->setCanViewQuota($this->Controller);
		$this->assertTrue($result);
	}

	public function testSetCanViewQuota_HasNotRight() {
		$this->Controller->Auth->expects($this->once())
			->method('user')
			->will($this->returnValue('6'));


		$result = $this->SynchroDossier->setCanViewQuota($this->Controller);
		$this->assertFalse($result);
	}
}
