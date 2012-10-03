<?php

class MetaComponent extends Component {

/**
 * @var Controller
 */
	protected $_controller;

/**
 * startup
 */
	public function startup(Controller $controller) {
		$this->_controller = $controller;
		if (isset($controller->request->params['admin'])) {
			$this->_adminTabs();

			if (empty($controller->request->data['Meta'])) {
				return;
			}
			$unlockedFields = array();
			foreach ($controller->request->data['Meta'] as $uuid => $fields) {
				foreach ($fields as $field => $vals) {
					$unlockedFields[] = 'Meta.' . $uuid . '.' . $field;
				}
			}
			$controller->Security->unlockedFields += $unlockedFields;
		}
	}

/**
 * Hook admin tabs for controllers whom its primary model has MetaBehavior attached.
 */
	protected function _adminTabs() {
		$Model = $this->_controller->{$this->_controller->modelClass};
		if ($Model && !$Model->Behaviors->attached('Meta')) {
			return;
		}
		$controller = $this->_controller->name;
		$title = __('Custom Fields');
		$element = 'Meta.admin/meta_tab';
		Croogo::hookAdminTab("$controller/admin_add", $title, $element);
		Croogo::hookAdminTab("$controller/admin_edit", $title, $element);
	}

}

