/**
 * Application representation
 */
var require = patchRequire(require);
var occitest = require('./occitest');
var utils = require('utils');

var Layout = {
	navbar: '.navbar'
};

//var sampleFile = function sampleFile(name) {
//	return 'Test/File/' + name;
//};
var Fixtures = {
	Users: {
//		invalid: {
//			email: 'invalid.user@example.com',
//			password: 'xxxx'
//		},
//		candidat: {
//			email: 'premier.candidat@example.com',
//			password: 'passwordSecret',
//			displayName: 'Premier Candidat'
//		},
//		entreprise: {
//			email: 'premiere.entreprise@example.com',
//			password: 'passwordSecret',
//			displayName: 'First company'
//		}
	}
};
//
//var Asserts = {
//	loggedAs: function loggedAs(userFixtureKey) {
//		var displayName = Fixtures.Users[userFixtureKey].displayName;
//		casper.test.assertSelectorHasText(
//			Layout.userSidebar,
//			displayName,
//			'je suis connecté ETQ ' + displayName
//		);
//	},
//	errorMessageDisplayed: function errorMessageDisplayed() {
//		casper.test.assertVisible(Layout.errorMessage, 'un message d\'erreur est affiché');
//	},
//	validationErrorDisplayedForField: function validationErrorDisplayedForField(fieldCssSelector, fieldDisplayName) {
//		casper.test.assertVisible(
//			fieldCssSelector + ' ~ ' + Layout.validationError,
//			'une erreur de validation est affichée pour le champ "' + fieldDisplayName + '"'
//		);
//	},
//	flashPageDisplayedThenRedirect: function flashPageDisplayedThenRedirect() {
//		casper.test.assertDoesntExist('#header', 'une page flash est affichée');
//		casper.click('body p:first-child a');
//	}
//};
//
var logoutUrl = occitest.url('/users/logout');

var startCasperAs = function startCasperAs(userFixtureKey, url, then) {
	var Login = require('../Page/login');
	if (userFixtureKey === 'visiteur') {
		casper.start(logoutUrl);
	} else {
		var userData = Fixtures.Users[userFixtureKey];
		casper.start(Login.url, function loginAs() {
			Login.login(userData.email, userData.password);
		});
	}
	casper.thenOpen(url, then);
};

exports.Fixtures = Fixtures;
exports.Layout = Layout;
//exports.Asserts = Asserts;
exports.ETQ = startCasperAs;
