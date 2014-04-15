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
		<p><?= __d('uploader', 'You browser doesn\'t have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.'); ?></p>
	</div>
</form>
