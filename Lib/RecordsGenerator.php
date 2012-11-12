<?php

/**
 * Cette classe permet de créer des records (qui ont le comportement Tree) à partir
 * de tableau imbriqué de la forme suivante :
 *  
 *  $acosTree = array(
 *		array(
 *			'name' => 'parent',
 *			'children' => array(
 *				array(
 *					'name' => 'firstChild',
 *					'children' => array(
 *						array(
 *							'name' => 'subSubChildren-1',
 *						),
 *						array(
 *							'name' => 'subSubChildren-2',
 *						),
 *					)
 *				),
 *			)
 *		),
 *	);
 */

class RecordsGenerator {

	protected function _getMaxFieldArrays($array, $field = 'rght') {
		$max = null;

		foreach ($array as $a) {
			if (is_null($max) || $max < $a[$field]) {
				$max = $a[$field];
			}
		}

		return $max;
	}

	public function generate($acosTree = null, $parentId = null, $leftRightCount = 1) {
		$acosTree = (is_null($acosTree)) ? $this->acosTree : $acosTree;
		$result = array();

		foreach ($acosTree as $aco) {
			$children = null;
			$childrenResult = array();

			$aco['parent_id'] = $parentId;
			$aco['lft'] = $leftRightCount;
			$aco['rght'] = null;

			if (isset($aco['children'])) {
				$children = $aco['children'];
				unset($aco['children']);
			}

			$leftRightCount += 1;

			if (!is_null($children)) {
				$childrenResult = self::generate($children, $parentId + 1, $leftRightCount);
				$leftRightCount = self::_getMaxFieldArrays($childrenResult) + 1;
			}

			$aco['rght'] = $leftRightCount;
			$result[] = $aco;
			$result = array_merge(array_values($result), array_values($childrenResult));
			
			$leftRightCount += 1;
		}

		return $result;
	}
}