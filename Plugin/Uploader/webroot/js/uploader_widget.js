uploaderWidget = function() {
	var __options, __nbFilesElt, __nbFilesToSendElt, __filesListElt,
		__totalSizeElt, __sendButtonElt, __progressBarElt;

	var __nbFiles = 0, __nbFilesToSend = 0, __totalSize = 0;

	function init(options, nbFiles, nbFilesToSend, totalSize, sendButton, progressBar, filesList) {
		__options = options;
		__nbFilesElt = nbFiles;
		__nbFilesToSendElt = nbFilesToSend;
		__totalSizeElt = totalSize;
		__sendButtonElt = sendButton;
		__progressBarElt = progressBar;
		__filesListElt = filesList;
		__nameLength = 20;

		uploader = new plupload.Uploader(options);

		uploader.init();

		$(__sendButtonElt).click(function() {
			$(__progressBarElt).css('width', '0%');
			$(__filesListElt).css('display', 'none');
			$(__progressBarElt).parent().css('display', 'block');
			if( $('.progressbar-overlay').length == 0 ){
				$('body').append('<div class="progressbar-overlay"></div>');
				$('.progressbar-overlay').css('display', 'block');
			}
			uploader.start();
			uploader.refresh();
		});
		
		$('p.upload-cancel > a.btn.cancel').on('click', function(event) {
			event.preventDefault();
			uploader.splice(0, uploader.files.length);
			__resetFilesPopover();
		});

		uploader.bind('FilesAdded', function(up, files) {
			$(__filesListElt).css('display', 'block');
			for (var i in files) {
				__totalSize += files[i].size;
				__nbFiles++;
				__nbFilesToSend++;
				var content = $(__filesListElt).find('.list');
				content.prepend('<li>' +
					__truncate(files[i].name, __nameLength) + ' (' +
					plupload.formatSize(files[i].size) + ') <a id="file-' + files[i].id + '" href="#" data-remove-file=' + i + '>&times;</a></li>'
				);

				$('#file-' + files[i].id).on('click', function(event) {
					event.preventDefault();
					$(event.currentTarget).parent().remove();
					up.removeFile(uploader.files[i]);
					if (uploader.files.length == 0) {
						__resetFilesPopover();
					}
				});
			}
		});


		uploader.bind('UploadProgress', function(up, file, response) {
			$(__progressBarElt).css('width', up.total.percent + '%');
		});

		uploader.bind('FileUploaded', function(up, files, response) {
			__nbFilesToSend--;
			$(__nbFilesToSendElt).text(__nbFilesToSend);
			responseJson = $.parseJSON(response.response);
			if (responseJson.error != undefined) {
				alert(responseJson.error['message']);
				up.stop();
				location.assign(location.href);
				location.reload();
			};
		});

		uploader.bind('UploadComplete', function(up, files) {
			__resetFilesPopover();
			location.assign(location.href);
			location.reload();
			window.location.href = __options['callback_url'];
		});
	}

	function __resetFilesPopover() {
			__totalSize = 0;
			__nbFiles = 0;
			__nbFilesToSend = 0;
			$(__filesListElt).css('display', 'none');
			$(__filesListElt).find('.list').text('');
	}

	function __truncate (str, length) {
		if (str.length > length) {
			str = str.substring(0, length) + '...';
		}
		return str;
	}

	return {
		init: init
	}
}();
