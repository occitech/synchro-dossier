<?php
App::uses('AclLoader', 'Lib');
App::uses('AclExtras', 'Acl.Lib');
App::uses('AclPermission', 'Acl.Model');
App::uses('ConfigReaderInterface', 'Configure');
App::uses('CroogoTestCase', 'Croogo.TestSuite');

class AclLoaderTest extends CroogoTestCase {

	public $ReaderMock;
	public $Loader;
	public $AclExtrasMock;
	public $PermissionMock;

	public $fixtures = array(
		'plugin.users.role'
	);

	public function setUp() {
		$this->ReaderMock = $this->getMock('ConfigReaderInterface');
		$this->AclExtrasMock = $this->getMock('AclExtras');
		$this->PermissionMock = $this->getMock('AclPermission');
		ClassRegistry::addObject('AclPermission', $this->PermissionMock);

		$this->Loader = new AclLoader('Plugin.acls', $this->ReaderMock, $this->AclExtrasMock);
	}

	public function tearDown() {
		unset($this->Loader, $this->ReaderMock);
		ClassRegistry::flush();
	}

/**
 * Test addAuthorizations
 */
	public function testAddAuthorizationsShouldSyncAcos() {
		$this->AclExtrasMock->expects($this->once())->method('startup');
		$this->AclExtrasMock->expects($this->once())->method('aco_sync');

		$this->Loader->addAuthorizations();
	}

	public function testAddAuthorizationsShouldReturnTrueOnSuccess() {
		$this->PermissionMock->expects($this->any())
			->method('allow')
			->will($this->returnValue(true));

		$this->assertTrue($this->Loader->addAuthorizations());
	}

	public function testAddAuthorizationsShouldReturnFalseOnError() {
		$acls = array('Foo/Bar/name' => array('Registered'));
		$this->ReaderMock->expects($this->any())
			->method('read')
			->will($this->returnValue($acls));

		$this->PermissionMock->expects($this->any())
			->method('allow')
			->will($this->returnValue(false));

		$this->assertFalse($this->Loader->addAuthorizations());
	}

	public function testAddAuthorizationsShouldAllowPermissionToActionForRoleWhenSingleRole() {
		$acls = array('Foo/Bar/name' => array('Registered'));
		$this->ReaderMock->expects($this->any())
			->method('read')
			->will($this->returnValue($acls));

		$this->PermissionMock->expects($this->once())
			->method('allow')
			->will($this->returnValue(true))
			->with(
				$this->equalTo(array('model' => 'Role', 'foreign_key' => 2)),
				$this->equalTo('Foo/Bar/name'),
				$this->equalTo('*'),
				$this->equalTo(1)
			);

		$this->Loader->addAuthorizations();
	}

	public function testAddAuthorizationsShouldAllowPermissionToActionForAllRolesWhenSeveralRoles() {
		$acls = array('Foo/Bar/name' => array('Registered', 'Public'));
		$this->ReaderMock->expects($this->any())
			->method('read')
			->will($this->returnValue($acls));

		$this->PermissionMock->expects($this->exactly(2))
			->method('allow')
			->will($this->returnValue(true));
		$this->PermissionMock->expects($this->at(0))
			->method('allow')
			->with(
				$this->equalTo(array('model' => 'Role', 'foreign_key' => 3))
			);
		$this->PermissionMock->expects($this->at(1))
			->method('allow')
			->with(
				$this->equalTo(array('model' => 'Role', 'foreign_key' => 2))
			);

		$this->Loader->addAuthorizations();
	}

/**
 * Test removeAuthorizations
 */
	public function testRemoveAuthorizationsShouldSyncAcos() {
		$this->AclExtrasMock->expects($this->once())->method('startup');
		$this->AclExtrasMock->expects($this->once())->method('aco_sync');

		$this->Loader->removeAuthorizations();
	}

	public function testRemoveAuthorizationsShouldDenyPermissionToActionForRoleWhenSingleRole() {
		$acls = array('Foo/Bar/name' => array('Registered'));
		$this->ReaderMock->expects($this->any())
			->method('read')
			->will($this->returnValue($acls));

		$this->PermissionMock->expects($this->once())
			->method('allow')
			->with(
				$this->equalTo(array('model' => 'Role', 'foreign_key' => 2)),
				$this->equalTo('Foo/Bar/name'),
				$this->equalTo('*'),
				$this->equalTo(-1)
			);

		$this->Loader->removeAuthorizations();
	}

}
