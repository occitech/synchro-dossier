<?php

class AllSdTest extends CakeTestSuite {

	public $label = 'Synchro Dossier - All tests';

	public static function suite() {
		$suite = new CakeTestSuite('Synchro Dossier - All tests');

		$suite->addTestDirectoryRecursive(TESTS . 'Case' . DS . 'Lib');
		$_pluginTests = array('SdLogs', 'SdUsers', 'Uploader', 'SynchroDossier');
		foreach ($_pluginTests as $name) {
			$suite->addTestDirectoryRecursive(App::pluginPath($name) . 'Test' . DS . 'Case');
		}

		return $suite;
	}

}
