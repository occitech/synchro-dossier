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
			<?= $currentQuota . 'Mb'; ?>
		</div>
	</div>
<?php endif ?>