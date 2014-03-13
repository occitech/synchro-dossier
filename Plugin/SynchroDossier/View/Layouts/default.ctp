<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo __(Configure::read('Site.title')); ?></title>

		<?php
		echo $this->Html->css(array(
			'/croogo/css/croogo-bootstrap',
			'/croogo/css/croogo-bootstrap-responsive',
			'//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css',
			'SynchroDossier.smoothness/jquery-ui-1.9.2.custom.min',
			'SynchroDossier.style',
			'style'
		));
		echo $this->Html->script(array(
			'SynchroDossier.jquery.1.8.3.min',
			'/croogo/js/croogo-bootstrap.js',
			'SynchroDossier.jquery-ui-1.9.2.custom.min',
			'//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js',
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
			<?= $this->element('SynchroDossier.renameFolderModal'); ?>
			<?= $this->element('SynchroDossier.createFolderModal'); ?>
			<?= $this->element('SynchroDossier.formCommentModal'); ?>
			<?= $this->element('SynchroDossier.formFileTagsModal'); ?>
		</div>
		<?=	$this->fetch('script'); ?>
		<?= $this->Html->script('Uploader.app'); ?>
		<footer>
			<?= Configure::read('SynchroDossier.footer') ?>
			<p>
				Propulsé par <a href="http://www.synchro-dossier.fr/" title="Synchro Dossier">Synchro&nbsp;Dossier</a>
			</p>
		</footer>
	</body>
</html>
