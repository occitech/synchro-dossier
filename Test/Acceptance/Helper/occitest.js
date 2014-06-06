/**
 * Helper module allowing to write tests simpler
 */
var require = patchRequire(require);
var utils = require('utils');

casper.test.on('fail', function(failure) {
	if (casper.status().started === false) return;

	var filename = failure.file + '/' + 'L' + failure.line + ' - ' + failure.message;
	casper.capture(config.failure_screenshot_path + '/' + filename + '.png');
});

casper.on('page.error', function onError(msg, trace) {
	casper.test.error(msg);
});

exports.url = function url(path) {
	return config.url_base + path;
};

exports.debugElement = function debugElement(selector) {
	utils.dump(casper.getElementInfo(selector));
}
