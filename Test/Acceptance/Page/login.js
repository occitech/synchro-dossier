var require = patchRequire(require);
var occitest = require('../Helper/occitest');

var Page = {
	name: 'la page de connexion',
	url: occitest.url('/users/login/'),
	urlRegExp: new RegExp(/\/users\/login/)
//	form: '#UserLoginForm',
//
//	login: function(username, password) {
//		casper.test.info('Je soumet le formulaire de connexion (' + username + ' / ' + password + ')');
//		casper.fill(this.form, {
//			'data[User][username]': username,
//			'data[User][password]': password
//		}, true);
//	}
};

module.exports = Page;
