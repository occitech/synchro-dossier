<div>
	<div>
		<?php echo $this->Html->link(__('..'), array('controller' => 'files', 'action' => 'browse', $file['UploadedFile']['parent_id'])) ?>
	</div>

	<div>
		<?php echo $this->Html->link(
			__('Ajouter une version'),
			array(
				'controller' => 'files',
				'action' => 'upload',
				$file['UploadedFile']['parent_id'],
				$file['UploadedFile']['filename']
			)
		); ?>
	</div>
	<br>
	<big>File : <?php echo $file['UploadedFile']['filename'] ?></big> <br>
	<table>
		<?php $i = 1; ?>
		<?php foreach ($file['FileStorage'] as $version): ?>
			<tr>
				<td style="width:25%;">Version : <small><?php echo $i; // Utiliser un helper qui va extraire la version ?></small></td>
				<td style="width:25%;">Par : <small><?php echo $version['user_id']; ?></small></td>
				<td style="width:50%;">Le : <?php echo $version['created']; ?></td>
			</tr>
			<?php $i++; ?>
		<?php endforeach ?>
	</table>
</div>