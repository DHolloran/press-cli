#!/usr/bin/env node

(function(){
	var sn = require('./lib/sn/init.js');
	sn.init(function() {
		var wp = require('./lib/wp/init.js');
		wp.init(function(){});
	});
}());