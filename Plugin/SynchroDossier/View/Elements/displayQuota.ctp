<?php if ($toPrint): ?>
	<?php
		$classColor = 'bar-success';
		if ($usedPercent > 50) :
			$classColor = 'bar-warning';
		endif;
		if ($usedPercent > 75):
			$classColor = 'bar-danger';
		endif;
	?>
	<div class="progress">
		<div class="bar <?= $classColor; ?>" style="width: <?= $usedPercent; ?>%">
			<?= round($usedPercent, 2) . '%'; ?>
		</div>
	</div>
	<div style="font-size: 11px; text-align: center; margin-top: -20px;">
		<?= $currentQuota . 'Go / ' . $quota . 'Go'; ?>	
	</div>
<?php endif ?>