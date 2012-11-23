<?= $this->Plupload->loadAsset('jquery'); ?>

<script type="text/javascript">
$(function() {
	$("#uploader").pluploadQueue(
		<?php echo $this->Plupload->getOptions();?>
	);
	var uploader = $('#uploader').pluploadQueue();
	uploader.bind('FileUploaded', function(uploader, file, response) {
		response = $.parseJSON(response.response);
		if (response.error) {
			alert(response.error.message);
		};
	});

	return false;
});
</script>

<form ..>
	<div id="uploader">
		<p><?= __('You browser doesn\'t have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.'); ?></p>
	</div>
</form>