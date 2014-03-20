<?php

App::uses('Component', 'Controller');

class SynchroDossierComponent extends Component {

	public $canViewQuota = null;

	public $helperName = 'SynchroDossier.SynchroDossier';

	public function initialize(Controller $controller) {
		$this->__checkSsl($controller);
		$this->setCanViewQuota($controller);
		$controller->Auth->loginRedirect = array('plugin' => 'uploader', 'controller' => 'files', 'action' => 'browse');
		$controller->Auth->authError = __d('synchro_dossier', 'Pour accéder à cette page vous devez vous connecter');
	}

	public function beforeRender(Controller $controller) {
		$params = array_intersect_key($controller->request->params, $controller->Auth->loginAction);
		if (!empty($controller->layout)) {
			if ($params == $controller->Auth->loginAction) {
				$controller->layout = 'SynchroDossier.login';
			} elseif (!isset($controller->request->params['prefix'])) {
				$controller->layout = 'SynchroDossier.default';
				$controller->helpers[] = 'Uploader.UploaderAcl';
			}
		}

		$controller->helpers[] = $this->helperName;
		$this->__setQuotaInformation($controller);
		$this->__setListUserCanAccessCurrentFolder($controller);
		$this->__setRootFolders($controller);

		$controller->helpers[] = 'Chosen.Chosen';
	}

	private function __setListUserCanAccessCurrentFolder(Controller $controller) {
		if (!is_null($controller->Auth->user())) {
			$UploaderAclAcoModel = ClassRegistry::init('Uploader.UploaderAclAco');

			$idFolder = isset($controller->request->params['pass'][0]) ? $controller->request->params['pass'][0] : null;
			$usersCanAccess = $UploaderAclAcoModel->getArosOfFolder('UploadedFile', $idFolder);
			$usersCanAccess['Aro'] = isset($usersCanAccess['Aro']) ? $usersCanAccess['Aro'] : array();
			$controller->set('SynchroDossier_aroAccessFolder', $usersCanAccess['Aro']);
		}
	}

	private function __setRootFolders(Controller $controller) {
		if (!is_null($controller->Auth->user())) {
			$UploadedFileModel = ClassRegistry::init('Uploader.UploadedFile');
			$UploadedFileModel->recursive = 1;
			$allFolders = $UploadedFileModel->getThreadedAllFolders();
			$controller->set('SynchroDossier_allFolders', $allFolders);
		}
	}

	private function __setQuotaInformation(Controller $controller) {
		if ($this->canViewQuota) {
			$SdInformationModel = ClassRegistry::init('SynchroDossier.SdInformation');
			$quota = $SdInformationModel->find('first');
			if (array_key_exists('SdInformation', $quota)) {
				$quota = $quota['SdInformation'];
			}
		} else {
			$quota = array();
		}
		$controller->set(compact('quota'));
	}

	private function __checkSsl(Controller $controller) {
		$url = env('SERVER_NAME') . $controller->here;
		if ($controller->request->is('ssl') && !Configure::read('sd.config.useSsl')) {
			$controller->redirect('http://' . $url);
		} elseif (!$controller->request->is('ssl') && Configure::read('sd.config.useSsl')) {
			$controller->redirect('https://' . $url);
		}
	}

	public function setCanViewQuota(Controller $controller) {
		if (is_null($this->canViewQuota)) {
			$roleId = $controller->Auth->user('role_id');
			$supervisersRole = array(
				Configure::read('sd.Occitech.roleId'),
				Configure::read('sd.SuperAdmin.roleId'),
				Configure::read('sd.Admin.roleId')
			);
			$this->canViewQuota = in_array($roleId, $supervisersRole);
		}
		return $this->canViewQuota;
	}
}
