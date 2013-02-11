<div>
	<div>
		<?php echo $this->Html->link(__d('uploader', '..'), array('controller' => 'files', 'action' => 'browse', $file['UploadedFile']['parent_id'])) ?>
	</div>

	<div>
		<?php echo $this->Html->link(
			__d('uploader', 'Ajouter une version'),
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
				<td style="width:25%;">Version :
					<small>
						<?php echo $i; // Utiliser un helper qui va extraire la version ?>
					</small>
				</td>
				<td style="width:25%;">
					Par : <small><?php echo $version['user_id']; ?></small>
				</td>
				<td style="width:40%;">
					Le : <?php echo $version['created']; ?>
				</td>
				<td>
					<?php echo $this->Html->link(
						__d('uploader', 'Download'),
						array(
							'controller' => 'files',
							'action' => 'download',
							$version['id']
						)
					); ?>
				</td>
			</tr>
			<?php $i++; ?>
		<?php endforeach ?>
	</table>
</div>