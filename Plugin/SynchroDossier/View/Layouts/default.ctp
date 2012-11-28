<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $title_for_layout; ?> - <?php echo __('Croogo'); ?></title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		
		<?php
		echo $this->Html->css(array(
			'SynchroDossier./bootstrap/bootstrap/css/bootstrap',
		));
		echo $this->Html->script(array(
			'SynchroDossier./bootstrap/bootstrap/js/bootstrap',
			'SynchroDossier.jquery.jstree'
		));
		echo $this->Layout->js();
		echo $this->Html->script(array());

		echo $this->fetch('script');
		echo $this->fetch('css');

		?>
	</head>
	<body>
		<?= $this->element('SynchroDossier.navbar'); ?>
		<div id="content-container" class="container-fluid">
			<div class="row-fluid">
				<div class="sidebar" style="width: 200px; float: left;">
					<?= $this->element('SynchroDossier.sidebar'); ?>
				</div>
				<div id="content" class="clearfix" style="margin-left: 200px;">
					<div id="inner-content" class="span12">
						<?= $this->Layout->sessionFlash(); ?>
						<?= $this->fetch('content'); ?>
					</div>
				</div>
				&nbsp;
			</div>
		</div>
	<?= $this->Html->script('Uploader.app'); ?>
	</body>
</html>