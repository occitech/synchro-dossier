eventTracking = function () {
	$t = $('[data-event="ga"]');
	$t.click(function () {
		var gaCat = $(this).data('category') ? $(this).data('category') : '',
		gaAct = $(this).data('action') ? $(this).data('action') : '',
		gaLab = $(this).data('label') ? $(this).data('label') : '';
		try {
			// _gaq.push(['_trackEvent', gaCat, gaAct, gaLab]);
		} catch (e) {
            		console.log(e);
		}
        });
}
