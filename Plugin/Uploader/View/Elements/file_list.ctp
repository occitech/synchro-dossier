<?php if (!empty($folderTree)):?>
<ul class="breadcrumb">
	<?php
		$i = 0;
		$folderCount = count($folderTree);
	?>
	<?php foreach ($folderTree as $id => $folderName): ?>
		<?php $isLastElement = ++$i == $folderCount; ?>
		<li <?php if ($isLastElement) echo 'class="active"'; ?>>
			<?php
				if ($id === 0) {
					$url = '/';
				} else {
					$url = $this->Html->url(array('action' => 'browse', $id));
				}
			?>
			<?php if ($isLastElement): ?>
				<?= $folderName ?>
			<?php else: ?>
				<a href="<?= $url ?>"><?= $folderName; ?></a>
				<span class="divider">/</span>
			<?php endif ?>
		</li>
	<?php endforeach; ?>
</ul>
<?php endif ?>

<?= $this->fetch('uploader'); ?>


<h2><?= $this->fetch('browse_title'); ?></h2>


<div class="uploader">
	<?php if ($isRootFolder): ?>
		<?= $this->element('Uploader.folder_table'); ?>
	<?php else: ?>
		<?= $this->element('Uploader.file_table'); ?>
	<?php endif; ?>

	<?php if ($this->elementExists('SynchroDossier.paging')): ?>
		<?= $this->element('SynchroDossier.paging', array('displayCounter' => false)) ?>
	<?php endif ?>
</div>
