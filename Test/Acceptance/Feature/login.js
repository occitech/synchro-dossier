var utils = require('utils');
var App = require('../Helper/app');
var Occitest = require('../Helper/occitest');
var Login = require('../Page/login');

casper.test.begin('La vue de connexion est user friendly', 1, function suite(test) {
	App.ETQ('visiteur', Login.url, function() {
		test.info('Lorsque je visite ' + Login.name);
		test.assertDoesntExist(App.Layout.navbar, 'La barre de navigation n\'est pas affichée');
	});

	casper.run(function() {
		test.done();
	});
});

//casper.test.begin('Connexion avec des identifiants invalides', 3, function suite(test) {
//	Sesame.ETQ('visiteur', Login.url, function fill_form() {
//		test.info('Lorsque je visite la ' + Login.name);
//		test.assertVisible(Login.form, 'le formulaire de connexion est affiché');
//		Login.login(
//			Sesame.Fixtures.Users.invalid.email,
//			Sesame.Fixtures.Users.invalid.password
//		);
//	});
//
//	casper.then(function() {
//		test.assertUrlMatch(Login.urlRegExp, 'je reste sur la même page');
//		Sesame.Asserts.errorMessageDisplayed();
//	});
//
//	casper.run(function() {
//		test.done();
//	});
//});
//
//casper.test.begin('Connexion avec des identifiants candidat valides', 2, function suite(test) {
//	Sesame.ETQ('visiteur', Login.url, function fill_form() {
//		test.info('Lorsque je visite ' + Login.name);
//		Login.login(
//			Sesame.Fixtures.Users.candidat.email,
//			Sesame.Fixtures.Users.candidat.password
//		);
//	});
//
//	casper.then(function() {
//		var Dashboard = require('../Page/Candidat/dashboard');
//		test.assertUrlMatch(Dashboard.urlRegExp, 'je suis redirigé sur ' + Dashboard.name);
//		Sesame.Asserts.loggedAs('candidat');
//	});
//
//	casper.run(function() {
//		test.done();
//	});
//});
//
//casper.test.begin('Connexion avec des identifiants entreprise valides', 2, function suite(test) {
//	Sesame.ETQ('visiteur', Login.url, function fill_form() {
//		test.info('Lorsque je visite ' + Login.name);
//		Login.login(
//			Sesame.Fixtures.Users.entreprise.email,
//			Sesame.Fixtures.Users.entreprise.password
//		);
//	});
//
//	casper.then(function() {
//		var Dashboard = require('../Page/Entreprise/dashboard');
//		test.assertUrlMatch(Dashboard.urlRegExp, 'je suis redirigé sur ' + Dashboard.name);
//		Sesame.Asserts.loggedAs('entreprise');
//	});
//
//	casper.run(function() {
//		test.done();
//	});
//});
