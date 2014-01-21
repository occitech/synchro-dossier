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
		<p><?= __d('uploader', 'Glissez/Déposez vos fichiers ici'); ?></p>
		<span>ou</span>
		<a href="#" id="browse" class="btn"><?= __d('uploader', 'Parcourir'); ?></a>

		<div class="progress progress-striped active">
			<div class="bar plupload-bar" style="width: 0%;"></div>
		</div>
	</div>
</div>
<div class="file-list">
	<div class="popover fade bottom in popover-upload">
		<div class="arrow"></div>
		<div class="popover-inner">
			<h3 class="popover-title"><?= __d('uploader', 'Fichiers dans la file d\'attente'); ?></h3>
			<div class="popover-content">
				<ul class="list unstyled">
				</ul>
				<div class="actions">
					<p class="left upload-send"><a href="#" class="btn send"><?= __d('uploader', 'Envoyer'); ?></a></p>
					<p class="right upload-cancel"><a href="#" class="btn btn-danger cancel"><?= __d('uploader', 'Annuler'); ?></a></p>
				</div>
			</div>
		</div>
	</div>
</div>

<div style="width: 100%; text-align: center;">

</div>
