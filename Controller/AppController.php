<?php

App::uses('CroogoAppController', 'Croogo.Controller');

/**
 * Base Application Controller
 *
 * @package  Croogo
 * @link     http://www.croogo.org
 */
class AppController extends CroogoAppController {

	protected $_mergeParent = 'CroogoAppController';

	public $components = array('DebugKit.Toolbar');

}
