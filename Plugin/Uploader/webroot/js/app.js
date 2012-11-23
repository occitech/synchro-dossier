jQuery(document).ready(function($) {
	$('.show-versions').click(function(event) {
		event.preventDefault();
		id = $(this).attr('id');
		$('.versions-' + id).each(function() {
			$(this).toggle();
		})
	})

	/**
	 * Plupload Error message
	 */
	var uploader = $('#uploader').pluploadQueue();
	console.log(uploader);
	if (uploader != undefined) {
		uploader.bind('FileUploaded', function(uploader, file, response) {
			response = $.parseJSON(response.response);
			if (response.error) {
				alert(response.error.message);
			};
		});
	}
});
