<h3><?= __('Ajouter un commentaire sur le ficher : ') . $file['UploadedFile']['filename']; ?></h3>
<?= $this->Form->create('Comment'); ?>

<?= $this->Form->input('Comment.body', array('label' => 'Commentaire :')); ?>

<?= $this->Form->end(__('Post comment')); ?>

<?php foreach ($file['Comment'] as $comment): ?>
	<h4>Par <?= $comment['name']; ?> le <?= $this->Time->format('j/m/Y H:i', $comment['created']); ?> </h4>
	<p><?= $comment['body']; ?></p>
<?php endforeach ?>