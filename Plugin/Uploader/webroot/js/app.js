jQuery(document).ready(function($) {
	$('.show-versions').click(function(event) {
		event.preventDefault();
		id = $(this).attr('id');
		$('.versions-' + id).each(function() {
			$(this).toggle();
		});
		if ($('.versions-' + id).first().is(':visible')) {
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
	$('[rel="tooltip"]').tooltip({
		delay: 200
	});

	/**
	 * New version
	 */

	$('.add-new-version').on('click', function() {
		action = $(this).attr('action');
		$('#addNewVersion').find('form').attr('action', action);
		$('#addNewVersion').find('.filename').text($(this).attr('data-filename'));
	});

	/**
	 * Comments
	 */
	$('.comments').on('click', function(){
		getModalContent($(this), $(this).attr('href'));
	});

	function getModalContent(popOverLink, modalElt) {
		$.ajax({
			url: popOverLink.attr('ajax-url'),
		}).done(function(data) {
			$(modalElt).find('.modal-body').html(data);
		});		
	}

	/**
	 * File preview
	 */
	$('.file-preview').on('mouseenter', function(event) {
		$(this).tooltip('show');
		$.ajax({
			url: $(this).attr('data-preview-url'),
		}).done(function(data) {
			$('.tooltip-inner').html(data);
			$('.tooltip-arrow').hide();
		});

		event.preventDefault();
	});
	$('.file-preview').on('mouseleave', function(event) {
		$(this).popover('hide');
		event.preventDefault();
	});
});
