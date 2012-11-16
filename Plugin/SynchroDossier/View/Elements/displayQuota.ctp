<?php if ($toPrint): ?>
	<div class="progress-bar">
		<?= $percent . '% - ' . $currentQuota . '/' . $quota; ?>
	</div>
<?php endif ?>