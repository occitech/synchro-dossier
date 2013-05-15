<?= $this->Form->create('Comment'); ?>

<?= $this->Form->input('Comment.body', array('label' => false, 'class' => 'span5')); ?>

<?= $this->Form->submit(__d('uploader', 'Poster votre commentaire'), array('class' => 'btn')); ?>


<?= $this->Form->end(); ?>

<?php foreach ($file['Comment'] as $comment): ?>
	<h4>Par <?= $comment['name']; ?> le <?= $this->Time->format('j/m/Y H:i', $comment['created']); ?> </h4>
	<p><?= $comment['body']; ?></p>
<?php endforeach ?>