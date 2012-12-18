<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $title_for_layout; ?> - <?php echo __('Synchro Dossier'); ?></title>
		
		<?php
		echo $this->Html->css(array(
			'SynchroDossier./bootstrap/bootstrap/css/bootstrap',
			'SynchroDossier.smoothness/jquery-ui-1.9.2.custom.min',
			'SynchroDossier.style',
			'style'
		));
		echo $this->Html->script(array(
			'SynchroDossier.jquery.1.8.3.min',
			'SynchroDossier.jquery-ui-1.9.2.custom.min',
			'SynchroDossier./bootstrap/bootstrap/js/bootstrap',
			'SynchroDossier._lib/jquery.cookie',
			'SynchroDossier.jquery.jstree',
		));

		echo $this->Layout->js();
		echo $this->Html->script(array());

		echo $this->fetch('css');

		?>
	</head>
	<body>
		<header>
			<?php if ($this->elementExists('sdHeader')): ?>
				<?= $this->element('sdHeader'); ?>
			<?php endif ?>
		</header>
		<?= $this->element('SynchroDossier.navbar'); ?>
		<div id="content-container" class="container-fluid">
			<div class="row-fluid">
				<div class="sidebar well">
					<?= $this->element('SynchroDossier.sidebar', array('can' => $can)); ?>
				</div>
				<div id="content" class="clearfix">
					<div id="inner-content" class="span12">
						<?= $this->Layout->sessionFlash(); ?>
						<?= $this->fetch('content'); ?>
					</div>
				</div>
				&nbsp;
			</div>
		</div>
		<div>
			<?= $this->element('SynchroDossier.addSharingModal'); ?>
			<?= $this->element('SynchroDossier.addNewVersionModal'); ?>
			<?= $this->element('SynchroDossier.renameFolderModal'); ?>
			<?= $this->element('SynchroDossier.createFolderModal'); ?>
			<?= $this->element('SynchroDossier.formCommentModal'); ?>
		</div>
		<?=	$this->fetch('script'); ?>
		<?= $this->Html->script('Uploader.app'); ?>
		<footer>
			<?= Configure::read('SynchroDossier.footer') ?>
			<p>
				Propulsé par <?= $this->Html->link('Synchro Dossier', '#'); ?>
			</p>
		</footer>
	</body>
</html>