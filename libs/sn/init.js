var sn = {
	options : {
		config : {},
		set    : {},
	},
};

// Terminal colors
var colors = require('colors');
colors.setTheme({
	silly: 'rainbow',
	prompt: 'grey',
	info: 'green',
	data: 'grey',
	help: 'cyan',
	warn: 'yellow',
	debug: 'blue',
	error: 'red',
});

/**
 * Outputs an error message to stdout.
 *
 * @param   {String}  msg  The message to output to stdout.
 *
 * @return  {Boolean}       FALSE
 */
sn.utils.error = function( msg ) {
	console.error( msg.error );

	return false;
}; // sn.utils.error()

/**
 * Outputs a warning message to stdout.
 *
 * @param   {String}  msg  The message to output to stdout.
 *
 * @return  {Boolean}       FALSE
 */
sn.utils.warn = function( msg ) {
	console.warn( msg.warn );

	return false;
}; // sn.utils.warn()

/**
 * Outputs a message to stdout.
 *
 * @param   {String}  msg  The message to output to stdout.
 *
 * @return  {Boolean}       FALSE
 */
sn.utils.info = function( msg ) {
	console.log( msg );

	return false;
}; // sn.utils.info()

/**
 * Outputs a debug message to stdout.
 *
 * @param   {String}  msg  The message to output to stdout.
 *
 * @return  {Boolean}       FALSE
 */
sn.utils.debug = function( msg ) {
	console.log( msg.debug );

	return false;
}; // sn.utils.debug()

/**
 * Outputs a help message to stdout.
 *
 * @param   {String}  msg  The message to output to stdout.
 *
 * @return  {Boolean}       FALSE
 */
sn.utils.help = function( msg ) {
	console.log( msg.help );

	return false;
}; // sn.utils.help()

/**
 * Strips non alpha-numeric characters from a string.
 *
 * @return  {String} The converted string.
 */
String.prototype.stripNonAlphaNumeric = function() {
	return this.replace(/\W/g, ' ');
}; // String.prototype.stripNonAlphaNumeric();

/**
 * Converts non alpha-numeric characters to dashes.
 *
 * @return  {String}  The converted string.
 */
String.prototype.convertToDashes = function() {
	return this.stripNonAlphaNumeric().replace(/[_\s]/g, '-').replace('--', '-');
}; // String.prototype.convertToDashes()

/**
 * Converts non alpha-numeric characters to underscores.
 *
 * @return  {String}  The converted string.
 */
String.prototype.convertToUnderscores = function() {
	return this.stripNonAlphaNumeric().replace(/[-\s]/g, '_').replace('__', '_');
}; // String.prototype.convertToUnderscores()

/**
 * Converts a string to a slug
 *
 * @return  {String}  The converted string.
 */
String.prototype.convertToSlug = function() {
	return this.convertToDashes().toLowerCase();
}; // String.prototype.convertToSlug()

/**
 * Converts a string to an ID
 *
 * @return  {String}  The converted string.
 */
String.prototype.convertToID = function() {
	return this.convertToUnderscores().toLowerCase();
}; // String.prototype.convertToID()

/**
 * Trims any URL protocols off of a string.
 *
 * @return  {String}  The trimmed string.
 */
String.prototype.trimProtocol = function() {
	return this.replace('https://', '').replace('http://', '');
}; // String.prototype.trimProtocol()


/**
 * Picks characters from a string.
 *
 * @param   {Integer}  min  The minimum amount of characters to choose.
 * @param   {Integer}  max  The maximum amount of characters to choose.
 *
 * @return  {String}        The selected characters.
 */
String.prototype.pick = function(min, max) {
	var n, chars = '';

	if (typeof max === 'undefined') {
		n = min;
	} else {
		n = min + Math.floor(Math.random() * (max - min));
	} // if/else()

	for (var i = 0; i < n; i++) {
		chars += this.charAt(Math.floor(Math.random() * this.length));
	} // for()

	return chars;
}; // String.prototype.pick()

/**
 * Shuffles a string.
 *
 * @return  {String}  The shuffled string.
 */
String.prototype.shuffle = function() {
	var array = this.split('');
	var tmp, current, top = array.length;

	if (top) while (--top) {
		current = Math.floor(Math.random() * (top + 1));
		tmp = array[current];
		array[current] = array[top];
		array[top] = tmp;
	}

	return array.join('');
};

/**
 * Generates a random strong password.
 *
 * @return  {String}  The random strong password.
 */
sn.options.generatePassword = function() {
	var specials  = '!@#$%^&*()_+{}:"<>?|[];\',./`~';
	var lowercase = 'abcdefghijklmnopqrstuvwxyz';
	var uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	var numbers   = '0123456789';
	var all       = specials + lowercase + uppercase + numbers;
	return (specials.pick(1) + lowercase.pick(1) + uppercase.pick(1) + all.pick(3, 10)).shuffle();
}; // sn.options.generatePassword()

/**
 * Executes the callback function.
 *
 * @param   {Function}  callback  The function to be executed.
 *
 * @return  {Boolean}             FALSE
 */
sn.exec.callback = function( callback ) {
	if ( typeof callback === 'function' ) {
		callback();
	} // if()

	return false;
}; // sn.exec.callback()

/**
 * Handles asking for the options not set in configuration.
 *
 * @param   {String}    optionMsg      The option request text.
 * @param   {Mixed}     defaultOption  The default option.
 * @param   {Function}  callback       The function to be executed on completion.
 *
 * @return  {Boolean}                   FALSE
 */
sn.options.cli.ask = function( optionMsg, defaultOption, callback ) {
	var stdin = process.stdin,
			stdout = process.stdout
	;

	stdin.resume();
	var msg = '';
	if ( defaultOption === '' ) {
		stdout.write( optionMsg + "*".error + ": " );
	} else if ( defaultOption === null ) {
		stdout.write( optionMsg + ": " );
	} else {
		msg = " (" + defaultOption + ")";
		stdout.write( optionMsg + msg.info + ":" );
	} // if/else()

	stdin.once( 'data', function( data ) {
		data = data.toString('utf8').trim();

		if ( defaultOption === '' && data === '' ) {
			var errorMsg = optionMsg + ' is required!';
			sn.utils.error( errorMsg.error  );
			sn.options.cli.ask( optionMsg, defaultOption, callback );
		} else {
			data = ( data === '' ) ? defaultOption : data;
			data = ( data.toLowerCase() === 'y' ) ? true : data;
			data = ( typeof data === 'string' && data.toLowerCase() === 'n' ) ? false : data;
			if ( typeof callback !== 'undefined' ) {
				callback( data );

				return false;
			} // if()
		} // if/else()
	});
}; // sn.options.cli.ask()
 * Sets config.install.directoryName option from site URL.
 *
 * @return  {Boolean}  FALSE
 */
sn.options.set.directoryName = function() {
	var url = ( typeof sn.options.config.install.url === 'undefined' ) ? '' : sn.options.config.install.url;

	if ( url === '' || sn.options.config.install.directoryName !== '' ) {
		return false;
	} // if()

	sn.options.config.install.directoryName = sn.trimProtocol( url );

	return false;
}; // sn.options.set.directoryName();

/**
 * Sets config.install.dbname option from site URL.
 *
 * @return  {Boolean}  FALSE
 */
sn.options.set.dbname = function() {
	var url = ( typeof sn.options.config.install.url === 'undefined' ) ? '' : sn.options.config.install.url;

	if ( url === '' || sn.options.config.install.dbname !== '' ) {
		return false;
	} // if()

	url = sn.trimProtocol( url );
	var id  = sn.convertToID( url );
	var dbname = 'wp_' + id;

	sn.options.config.install.dbname = dbname;

	return false;
}; // sn.options.set.dbname();

/**
 * Sets config.install.dbprefix option from site title.
 *
 * @return  {Boolean}  FALSE
 */
sn.options.set.dbprefix = function() {
	if ( sn.options.config.install.dbprefix !== '' ) {
		return false;
	} // if()

	var _             = require('underscore');
	var defaultPrefix = 'wp_';
	var dbprefix      = '';
	var title         = ( typeof sn.options.config.install.title === 'undefined' ) ? '' : sn.options.config.install.title;

	// Make sure we have a title
	if ( title === '' ) {
		sn.options.config.install.dbprefix = defaultPrefix;

		return false;
	} // if()

	// Get the title parts
	var titleParts = title.split( ' ' );
	_.each( titleParts, function(value, key, list) {
		if ( value !== '' ) {
			dbprefix = dbprefix + value.charAt(0);
		} // if()
	});

	if ( dbprefix === '' ) {
		dbprefix = defaultPrefix;
	} else {
		dbprefix = dbprefix.toLowerCase() + '_';
	} // if/else()

	sn.options.config.install.dbprefix = dbprefix;

	return false;
}; // sn.options.set.dbprefix();

/**
 * Sets config.newTheme.themeName option from site title.
 *
 * @return  {Boolean}  FALSE
 */
sn.options.set.themeName = function() {
	var title = ( typeof sn.options.config.install.title === 'undefined' ) ? '' : sn.options.config.install.title;

	if ( title === '' || sn.options.config.newTheme.themeName !== '' ) {
		return false;
	} // if()

	sn.options.config.newTheme.themeName = title;

	return false;
}; // sn.options.set.themeName()

/**
 * Retrieves the zip URL from the latest tag
 *
 * @todo Either and event or promise or something...
 * @todo Better error handling.
 * @todo Abstract GitHub get release tag functionality.
 *
 * @return  {Boolean}  FALSE
 */
sn.options.set.themeURL = function() {
	if ( sn.options.config.newTheme.themeURL !== '' ) {
		return false;
	} // if()

	var _          = require('underscore');
	var https      = require('https');
	var user       = ( typeof sn.options.config.newTheme.user === 'undefined' ) ? '' : sn.options.config.newTheme.user;
	var repo       = ( typeof sn.options.config.newTheme.repo === 'undefined' ) ? '' : sn.options.config.newTheme.repo;
	var releaseTag = ( typeof sn.options.config.newTheme.releaseTag === 'undefined' ) ? '' : sn.options.config.newTheme.releaseTag;

	// We need at least the user and repository
	if ( user === '' || repo === '' ) {
		return false;
	} // if()

	// If we have a release tag no need to go get it.
	if ( releaseTag !== '' ) {
		var url = 'https://api.github.com/repos/' + user + '/' + repo + '/zipball/' + releaseTag;

		sn.options.config.newTheme.themeURL = url;

		return url;
	} // if()

	var GitHubApi = require("github");
	var github = new GitHubApi({
		version: "3.0.0",
		protocol: "https",
		headers: {
			"user-agent": user,
		},
	});
	var msg = {
		user     : user,
		repo     : repo,
		page     : 1,
		per_page : 1,
	};

	github.repos.getTags(msg, function(err, res) {
		if ( err !== null ) {
			console.error( err );
			return false;
		} // if()

		// Check if we got a tag back
		json = JSON.parse(JSON.stringify(res));
		if ( json.length === 0 ) {
			console.warn( 'No releases found for https://github.com/' + user + '/' + repo + '/'  );

			return false;
		} // if()

		var url = ( typeof json[0].zipball_url === 'undefined' ) ? '' : json[0].zipball_url;
		sn.options.config.newTheme.themeURL = url;
	});

	return false;
}; // sn.options.set.themeURL()

/**
 * Sets config.newTheme.themeDescription option from site title.
 *
 * @return  {Boolean}  FALSE
 */
sn.options.set.themeDescription = function() {
	var title = ( typeof sn.options.config.install.title === 'undefined' ) ? '' : sn.options.config.install.title;

	if ( title === '' || sn.options.config.newTheme.themeDescription !== '' ) {
		return false;
	} // if()

	var description = 'This custom theme has been created for '+title+' by <a href="http://www.matchboxdesigngroup.com/">Matchbox Design Group</a>';
	sn.options.config.newTheme.themeDescription = description;

	return false;
}; // sn.options.set.themeDescription()

/**
 * Sets default configuration options before CLI options.
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}             FALSE
 */
sn.options.setDefaults = function( callback ) {
	sn.options.set.directoryName();
	sn.options.set.dbname();
	sn.options.set.dbprefix();
	sn.options.set.themeName();
	sn.options.set.themeURL();
	sn.options.set.themeDescription();

	if ( typeof callback === 'function' ) {
		callback();
	} // if()

	return false;
}; // sn.options.setDefaults()

/**
 * Initializes Snifter Utilities.
 *
 * @return  {Boolean}  FALSE
 */
sn.init = function() {
	// Get configuration options
	sn.options.config = sn.options.getConfig();

	// Set default options (If not set in configuration)
	sn.options.setDefaults(function() {
		sn.options.cli();
	});



	return false;
}; // sn.init()

// Export Options
module.exports = sn;