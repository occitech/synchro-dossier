jQuery(document).ready(function($) {
	$('.show-versions').click(function(event) {
		event.preventDefault();
		id = $(this).attr('id');
		$('.versions-' + id).each(function() {
			$(this).toggle();
		});
		if ($('.versions-' + id).first().css('display') != 'none') {
			$(this).html('<i class="icon-chevron-down"></i>');
		} else {
			$(this).html('<i class="icon-chevron-right"></i>');
		}
	})

	$(function () {
		$(".sidebar-folders").jstree({
			"themes" : {
				"theme" : "synchrodossier",
				"dots" : false,
				"icons" : false
			},
			"plugins" : [ "themes", "html_data" ]
		});
	});

	$('.rename-folder').on('click', function() {
		$('#renameFolderModal').find('#UploadedFileFilename').val($(this).attr('data-filename'));
		$('#renameFolderModal').find('#UploadedFileId').val($(this).attr('data-id'));
	})

	/**
	 * Activate tooltip
	 */
	$('*').tooltip({
		delay: 200
	});

	/**
	 * Plupload Error message
	 */
	var uploader = $('#uploader').pluploadQueue();
	if (uploader != undefined) {
		uploader.bind('FileUploaded', function(uploader, file, response) {
			response = $.parseJSON(response.response);
			if (response.error) {
				alert(response.error.message);
			};
		});
	}
});
