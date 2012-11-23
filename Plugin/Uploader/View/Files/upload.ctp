<?= $this->Plupload->loadAsset('jquery'); ?>

<script type="text/javascript">
$(function() {
	$("#uploader").pluploadQueue(
		<?php echo $this->Plupload->getOptions();?>
	);
});
</script>

<form ..>
	<div id="uploader">
		<p><?= __('You browser doesn\'t have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.'); ?></p>
	</div>
</form>

<?= $this->Html->script('Uploader.app'); ?>