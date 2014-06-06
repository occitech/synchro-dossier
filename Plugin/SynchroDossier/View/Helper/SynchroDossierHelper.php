<?php

App::uses('SdUser', 'SdUsers.Model');
App::uses('AppHelper', 'View/Helper');

class SynchroDossierHelper extends AppHelper {

	public $helpers = array(
		'Html',
		'Session',
		'Form',
		'Uploader.UploaderAcl'
	);

	public function hasUserRole($roleId) {
		return $roleId == SdUser::ROLE_UTILISATEUR_ID;
	}

	public function hasAdminRole($roleId) {
		return $roleId == SdUser::ROLE_ADMIN_ID;
	}

	public function getQuotaData() {
		return $this->_View->getVar('quota');
	}

	public function displayQuota($element = 'SynchroDossier.displayQuota') {
		$quota = $this->getQuotaData();

		$vars = array(
			'toPrint' => false
		);

		if (!empty($quota)) {
			$vars = array(
				'toPrint' => true,
				'quota' => round($quota['quota_mb'] / 1024, 2),
				'currentQuota' => round($quota['current_quota_mb'] / 1024, 2),
				'usedPercent' => $quota['current_quota_mb'] / $quota['quota_mb'] * 100
			);
		}
		$element = $this->_View->element($element, $vars);

		return $element;
	}

	public function displayTreeFolders($folders) {
		$output = '';
		foreach ($folders as $folder) {
			if ($this->UploaderAcl->userCan($folder['Aco'], 'read')) {
				$subfolderOutput = '';
				if (isset($folder['children']) && count($folder['children']) > 0) {
					$subfolderOutput .= $this->displayTreeFolders($folder['children']);
					$subfolderOutput = $this->Html->tag('ul', $subfolderOutput, array());
				}
				$link = $this->Html->link(
					$folder['UploadedFile']['filename'],
					array('plugin' => 'uploader', 'controller' => 'files', 'action' => 'browse', $folder['UploadedFile']['id']),
					array('title' => $folder['UploadedFile']['filename'])
				);
				$output .= $this->Html->tag('li', $link . "\n" . $subfolderOutput, array(
					'id' => 'folder' . $folder['UploadedFile']['id'],
					'class' => 'jstree-closed'
				));
			}

		}
		return $output;
	}

	public function displayRights($aroData){
		$labelsRights = '';
		$_rights = array(
			'_read' => __d('synchro_dossier', 'Read'),
			'_write' => __d('synchro_dossier', 'Write'),
			'_delete' => __d('synchro_dossier', 'Delete'),
			'_update' => __d('synchro_dossier', 'Rename'),
			'_create' => __d('synchro_dossier', 'Create'),
			'_change_right' => __d('synchro_dossier', 'Change Right')
		);

		foreach ($aroData as $aro) {
			if ($aro['foreign_key'] == $this->Session->read('Auth.User.id')) {
				$permissions = $aro['Permission'];

				foreach ($_rights as $key => $label) {
					if (!empty($permissions[$key])) {
						$labelsRights .= '<li>' . $label . '</li>' . "\n";
					}
				}
			}
		}

		return $this->Html->tag('ul', $labelsRights, array('class' => 'user-rights'));
	}

	public function displaySubscriptionInput($folderData, $userId, $alertEmails = array()) {
		$folderId = $folderData['UploadedFile']['id'];

		$options = array(
			'label' => __d('synchro_dossier', 'Subscribe to email alert'),
			'value' => 1,
			'type' => 'checkbox',
			'data-folder-id' => $folderId,
			'data-subscribed-text' =>  __d('synchro_dossier', 'Unsubscribe to email alert'),
		);

		if (!empty($alertEmails)) {
			if (in_array($folderId, Hash::extract($alertEmails, '{n}.SdAlertEmail.uploaded_file_id'))) {
				$temp = $options['label'];
				$options['label'] = $options['data-subscribed-text'];
				$options['data-subscribed-text'] = $temp;
				$options['value'] = 0;
			}
		}

		return $this->Html->link(
			$options['label'],
			array(
				'plugin' => 'sd_users',
				'controller' => 'sd_users',
				'action' => 'manageAlertEmail',
				$userId,
				$folderId,
				$options['value']
			),
			$options,
			__d(
				'synchro_dossier',
				'You\'re about to %s to email alert for folder %s. Are you sure ?',
				$options['value'] ? __d('synchro_dossier', 'subscribe') : __d('synchro_dossier', 'unsubscribe'),
				$folderData['UploadedFile']['filename']
			)
		);
	}
	public function routeIsActive($route) {
		$isActive = true;
		$_keysToCheck = array('plugin', 'controller', 'action');
		$currentRoute = $this->request->params;
		$routeArr = $route;

		if (!is_array($route)) {
			$routeArr = Router::parse($route);
		}

		foreach ($_keysToCheck as $key) {
			$isActive = $isActive && $route[$key] == $currentRoute[$key];
		}

		return $isActive;
	}

}
