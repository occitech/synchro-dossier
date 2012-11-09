<?php
/**
 * Test Librairy.
 * Extends core librairy to add convenience methods.
 */
class OccitechCakeTestCase extends CakeTestCase {
/**
 * First record from fixtures
 *
 * @var array
 */
	protected $_record = array();

/**
 * Asserts that data are valid given Model validation rules
 * Calls the Model::validate() method and asserts the result
 *
 * @param Model $Model Model being tested
 * @param array $data Data to validate
 * @return void
 */
	public function assertValid(Model $Model, $data) {
		$this->assertTrue($this->_validData($Model, $data));
	}

/**
 * Asserts that data are validation errors match an expected value when
 * validation given data for the Model
 * Calls the Model::validate() method and asserts validationErrors
 *
 * @param Model $Model Model being tested
 * @param array $data Data to validate
 * @param array $expectedErrors Expected errors keys
 * @return void
 */
	public function assertValidationErrors($Model, $data, $expectedErrors) {
		$this->_validData($Model, $data, $validationErrors);
		sort($expectedErrors);
		$this->assertEqual(array_keys($validationErrors), $expectedErrors);
	}

/**
 * Convenience method allowing to validate data and return the result
 *
 * @param Model $Model Model being tested
 * @param array $data Profile data
 * @param array $validationErrors Validation errors: this variable will be updated with validationErrors (sorted by key) in case of validation fail
 * @return boolean Return value of Model::validate()
 */
	protected function _validData(Model $Model, $data, &$validationErrors = array()) {
		$valid = true;
		$Model->create($data);
		if (!$Model->validates()) {
			$validationErrors = $Model->validationErrors;
			ksort($validationErrors);
			$valid = false;
		} else {
			$validationErrors = array();
		}
		return $valid;
	}

/**
 * Execute a search, return the result ids and generated conditions
 *
 * @param Model $Model Model being tested
 * @param array $criteria Search criteria
 * @param array $conditions (Out) search conditions used
 * @return array List of result ids in the returned order
 */
	protected function _searchResults(Model $Model, $criteria, &$conditions = array()) {
		$conditions = $Model->parseCriteria($criteria);
		$results = $Model->find('all', compact('conditions'));
		return Hash::extract($results, '{n}.' . $Model->alias . '.id');
	}

/**
 * Assert results of a search with criteria
 *
 * @param Model $Model Model being tested
 * @param array $criteria Search criteria
 * @param array $expected Expected id
 */
	protected function _assertSearchResults(Model $Model, $criteria, $expected) {
		$results = $this->_searchResults($Model, $criteria, $conditions);
		sort($expected);
		sort($results);
		$this->assertEqual($results, $expected);
	}

/*
  Expects that an event is dispatched once with the correct params passed
 * The event propagation will be stopped by default (see $stopEvent param)
 
  @param string $eventName			Name of the expected event
 * @param Object $expectedSubject	PHPUnit constraint for the event subject (like used for a with)
 * @param Object $expectedParams	PHPUnit constraint for the additional params
 * @param boolean $stopEvent		Set to false to keep event propagation
 * @param Object $nbCalls			Number of expected calls, optional [default: $this->once()]
 * @param Object $eventManager		Event manager waiting the event
 * @return callable					Attached callback so you can detach it afterwards
 *									$callback = $this->expectDispatchedEvent([...]);
 *									[ trigger the event ]
 *									CakeEventManager::instance()->detach($callback);
 */
	public function expectEventDispatched($eventName, $expectedSubject = null, $expectedParams = null, $stopEvent = true, $nbCalls = null, $eventManager = null) {
		if (is_null($expectedSubject)) {
			$expectedSubject = $this->anything();
		}
		if (is_null($expectedParams)) {
			$expectedParams = $this->anything();
		}
		if (is_null($nbCalls)) {
			$nbCalls = $this->once();
		}
		if (is_null($eventManager)) {
			$eventManager = CakeEventManager::instance();
		}

		$Listener = $this->getMock('StdObject', array('callbackMethod'));
		$callback = function($event) use ($Listener, $stopEvent) {
			$Listener->callbackMethod($event->subject(), $event->data);
			if ($stopEvent) {
				$event->stopPropagation();
			}
		};

		$eventManager->attach($callback, $eventName, array('priority' => 1));
		if ($eventManager !== CakeEventManager::instance()) {
			$this->detachEvent($eventName);
		}

		$Listener->expects($nbCalls)->method('callbackMethod')
			->with($expectedSubject, $expectedParams);

		return $callback;
	}

	public function detachEvent($eventName) {
		CakeEventManager::instance()->detach(null, $eventName);
		foreach(CakeEventManager::instance()->listeners($eventName) as $listner) {
			CakeEventManager::instance()->detach($listner['callable'], $eventName);
		}
	}

//Function avoiding error when using in a group
	public function test() {

	}
}