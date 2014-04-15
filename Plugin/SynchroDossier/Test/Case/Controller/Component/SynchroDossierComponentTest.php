<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Controller', 'Controller');
App::uses('Component', 'Controller');
App::uses('SynchroDossierComponent', 'SynchroDossier.Controller/Component');

class SynchroDossierComponentTest extends ControllerTestCase {

	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->SynchroDossier = new SynchroDossierComponent($Collection);
		$this->Controller = new Controller();
		$this->Controller->Auth = $this->getMock('AuthComponent', array('user'), array($Collection));
	}

	public function tearDown() {
		unset($this->SynchroDossier);

		parent::tearDown();
	}

	public function testSetCanViewQuota_HasRight() {
		$this->Controller->Auth->staticExpects($this->any())
			->method('user')
			->will($this->returnValue('4'));

		$result = $this->SynchroDossier->setCanViewQuota($this->Controller);
		$this->assertTrue($result);
	}

	public function testSetCanViewQuota_HasNotRight() {
		$this->Controller->Auth->staticExpects($this->once())
			->method('user')
			->will($this->returnValue('6'));


		$result = $this->SynchroDossier->setCanViewQuota($this->Controller);
		$this->assertFalse($result);
	}
}
