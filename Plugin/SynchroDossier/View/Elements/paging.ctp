<?php
	$pagingOptions = array('tag' => 'li',
		'escape' => false,
		'disabledTag' => 'a',
		'currentClass' => 'active',
		'currentTag' => 'a'
	);
	$paginationEnabled = $this->Paginator->hasPrev() || $this->Paginator->hasNext();
?>
<?php if ($paginationEnabled): ?>
<div class="pagination">
	<ul class="pagination pagination-large">
	<?php
		echo $this->Paginator->prev(
			'&larr;',
			$pagingOptions + array('class' => 'previous'),
			null,
			$pagingOptions + array('class' => 'previous disabled')
		);
		echo $this->Paginator->numbers($pagingOptions + array('separator' => ''));
		echo $this->Paginator->next(
			'&rarr;',
			$pagingOptions + array('class' => 'next'),
			null,
			$pagingOptions + array('class' => 'next disabled')
		);
	?>
	</ul>
</div>
<?php endif; ?>
<?php
	$paging = $this->Paginator->request->params['paging'];
	$pagingKeys = array_keys($paging);
	$count = $paging[array_shift($pagingKeys)]['count'];
?>
<?php if (!empty($displayPaging)): ?>
<div class="pager-count">
	<?= __dn('synchro_dossier', '%s matching result', '%s matching results', $count, $count) ?>
</div>
<?php endif ?>
