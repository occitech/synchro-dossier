<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo __(Configure::read('Site.title')); ?></title>

		<?php
		echo $this->Html->css(array(
			'/croogo/css/croogo-bootstrap',
			'/croogo/css/croogo-bootstrap-responsive',
			'SynchroDossier.smoothness/jquery-ui-1.9.2.custom.min',
			'SynchroDossier.style',
			'style'
		));
		echo $this->Html->script(array(
			'SynchroDossier.jquery.1.8.3.min',
			'SynchroDossier.jquery-ui-1.9.2.custom.min',
			'/croogo/js/croogo-bootstrap.js',
			'SynchroDossier._lib/jquery.cookie',
			'SynchroDossier.jquery.jstree',
			'SynchroDossier.jquery.ga-event',
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
		<?= $this->fetch('navbar') ?>
		<div id="content-container" class="container-fluid">
			<div class="row-fluid">
			<?php
				$sidebar = $this->fetch('sidebar');
				if (trim($sidebar) != ''):
			?>
				<div class="sidebar well">
					<?= $sidebar; ?>
				</div>
			<?php endif; ?>
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
			<?= $this->element('SynchroDossier.renameFileModal'); ?>
			<?= $this->element('SynchroDossier.formCommentModal'); ?>
			<?= $this->element('SynchroDossier.formFileTagsModal'); ?>
			<?= $this->element('SdUsers.deleteAdminModal'); ?>

			<?php
				if (!empty($folderId)) :
					echo $this->element('SynchroDossier.createFolderModal');
					echo $this->element('SynchroDossier.renameFolderModal');
				endif;
			?>
		</div>
		<?=	$this->fetch('script'); ?>
		<?= $this->Html->script('Uploader.app'); ?>
		<footer>
			<?= Configure::read('SynchroDossier.footer') ?>
			<p>
				<?= __d('synchro_dossier', 'Propulsé par') ?> <a href="http://www.synchro-dossier.fr/" title="Synchro Dossier">Synchro&nbsp;Dossier</a>
				<?= $this->element('SynchroDossier.version'); ?>
			</p>
		</footer>
	<?= $this->element('google_analytics'); ?>
	</body>
</html>
