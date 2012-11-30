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
	$('[rel="tooltip"]').tooltip({
		delay: 200
	});
	
	$('.comments').on('click', function(){
		getModalContent($(this), $(this).attr('href'));
	});

	function getModalContent(popOverLink, modalElt) {
		$.ajax({
			url: popOverLink.attr('ajax-url'),
		}).done(function(data) {
			console.log(data);
			$(modalElt).find('.modal-body').html(data);
		});		
	}

});
