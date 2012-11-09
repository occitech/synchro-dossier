<?php

App::uses('RecordsGenerator', 'Lib');
App::uses('CroogoTestCase', 'TestSuite');

class RecordsGeneratorTest extends CroogoTestCase {

	public function testGenerate() {
		$acosTree = array(
			array(
				'model' => 'UploadedFile',
				'foreign_key' => 1,
				'alias' => null,
				'children' => array(
					array(
						'parent_id' => 1,
						'model' => 'UploadedFile',
						'foreign_key' => 3,
						'alias' => null,
						'children' => array(
							array(
								'model' => 'UploadedFile',
								'foreign_key' => 4,
								'alias' => null,
							),
							array(
								'model' => 'UploadedFile',
								'foreign_key' => 5,
								'alias' => null,
							),
						)
					),
				)
			),
			array(
				'model' => 'UploadedFile',
				'foreign_key' => 2,
				'alias' => null,
				'children' => array(
					array(
						'model' => 'UploadedFile',
						'foreign_key' => 6,
						'alias' => null,
					),
				),
			),
		);

		$expected = array(
			0 => array(
				'model' => 'UploadedFile',
				'foreign_key' => 1,
				'alias' => null,
				'parent_id' => null,
				'lft' => 1,
				'rght' => 8
			),
			1 => array(
				'parent_id' => 1,
				'model' => 'UploadedFile',
				'foreign_key' => 3,
				'alias' => null,
				'lft' => 2,
				'rght' => 7
			),
			2 => array(
				'model' => 'UploadedFile',
				'foreign_key' => 4,
				'alias' => null,
				'parent_id' => 2,
				'lft' => 3,
				'rght' => 4
			),
			3 => array(
				'model' => 'UploadedFile',
				'foreign_key' => 5,
				'alias' => null,
				'parent_id' => 2,
				'lft' => 5,
				'rght' => 6
			),
			4 => array(
				'model' => 'UploadedFile',
				'foreign_key' => 2,
				'alias' => null,
				'parent_id' => null,
				'lft' => 9,
				'rght' => 12
			),
			5 => array(
				'parent_id' => 1,
				'model' => 'UploadedFile',
				'foreign_key' => 6,
				'alias' => null,
				'lft' => 10,
				'rght' => 11
			)
		);

		$result = RecordsGenerator::generate($acosTree);
		debug($result);
		$this->assertEqual($result, $expected);
	}

}