<?php $this->Html->css('Uploader.uploader_widget', null, array('inline' => false)); ?>
<?php $this->Html->script('Uploader.uploader_widget', array('inline' => false)); ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>
	uploaderWidget.init(
		<?php echo $this->Plupload->getOptions();?>,
		'#nbFiles', '#nbFilesToSend', '#totalSize', '.send', '.plupload-bar', '.popover-upload'
	);
<?php $this->Html->scriptEnd(); ?>

<div class="row-fluid" id="plupload">
	<div id="drop-area">
		<p><?= __('Glissez/Déposez vos fichiers ici'); ?></p>
		<span>ou</span>
		<a href="#" id="browse" class="btn"><?= __('Parcourir'); ?></a>
	
		<div class="progress progress-striped active">
			<div class="bar plupload-bar" style="width: 0%;"></div>
		</div>
	</div>
</div>
<div class="file-list">
	<div class="popover fade bottom in popover-upload">
		<div class="arrow"></div>
		<div class="popover-inner">
			<h3 class="popover-title"><?= __('Fichiers dans la file d\'attente'); ?></h3>
			<div class="popover-content">
				<div class="list">

				</div>
				<p class="upload-send"><a href="#" class="btn send"><?= __('Envoyer'); ?></a></p>
			</div>
		</div>
	</div>
</div>

<div style="width: 100%; text-align: center;">
	
</div>