<?= $this->Plupload->css(); ?>
<?= $this->Plupload->plupload(array('url' => $this->here, 'max_file_size' => '100mb')); ?>

<script type="text/javascript">
$(function() {
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
			