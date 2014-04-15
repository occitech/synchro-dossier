<?php
App::uses('CroogoTestCase', 'Croogo.TestSuite');

class AllUploaderTestsTest extends PHPUnit_Framework_TestSuite {

/**
 * suite
 *
 * @return CakeTestSuite
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Synchro Dossier Uploader tests');
		$suite->addTestDirectoryRecursive(CakePlugin::path('Uploader') . 'Test' . DS . 'Case' . DS);
		return $suite;
	}

}
