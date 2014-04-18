eventTracking = function () {
	var sendEvent = function() {
		var gaCat = $(this).data('category') ? $(this).data('category') : '',
		gaAct = $(this).data('action') ? $(this).data('action') : '',
		gaLab = $(this).data('label') ? $(this).data('label') : '';
		try {
			ga('send', 'event', gaCat, gaAct, gaLab);
			// _gaq.push(['_trackEvent', gaCat, gaAct, gaLab]);
		} catch (e) {
			console.log(e);
		}
	};

	$clicked = $('[data-event="ga"][data-action="click"]');
	$hovered = $('[data-event="ga"][data-action="hover"]');
	$clicked.on('click', sendEvent);
	$hovered.on('mouseenter', sendEvent);
}
