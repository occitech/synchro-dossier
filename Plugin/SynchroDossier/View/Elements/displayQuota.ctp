<?php if ($toPrint): ?>
	<div class="progress-bar">
		<?= $usedPercent . '% - ' . $currentQuota . '/' . $quota; ?>
	</div>
<?php endif ?>