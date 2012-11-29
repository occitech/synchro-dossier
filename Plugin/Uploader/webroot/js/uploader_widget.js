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
		__nameLength = 25;

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

		uploader.bind('FilesAdded', function(up, files) {
			$(__filesListElt).css('display', 'block');
			for (var i in files) {
				__totalSize += files[i].size;
				__nbFiles++;
				__nbFilesToSend++;
				var content = $(__filesListElt).find('.list');
				content.prepend('<p>' + 
					__truncate(files[i].name, __nameLength) + ' (' + 
					plupload.formatSize(files[i].size) + ')</p>'
				);
			}
		});

		uploader.bind('UploadProgress', function(up, file, response) {
			$(__progressBarElt).css('width', up.total.percent + '%');
		});

		uploader.bind('FileUploaded', function(up, files) {
			__nbFilesToSend--;
			$(__nbFilesToSendElt).text(__nbFilesToSend);
		});

		uploader.bind('UploadComplete', function(up, files) {
			__totalSize = 0;
			__nbFiles = 0;
			__nbFilesToSend = 0;
			$(__filesListElt).css('display', 'none');
			$(__filesListElt).find('.list').text('');
			location.assign(location.href);
			location.reload();
		});
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