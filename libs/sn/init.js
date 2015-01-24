var sn = {
	options : {
		config : {},
		set    : {},
	},
};

var colors = require('colors');

colors.setTheme({
	silly: 'rainbow',
	prompt: 'grey',
	info: 'green',
	data: 'grey',
	help: 'cyan',
	warn: 'yellow',
	debug: 'blue',
	error: 'red'
});

/**
 * Handles reading the configuration options.
 * Outputs an error message to stdout.
 *
 * @return  {Object}  The configuration options.
 * @param   {String}  msg  The message to output to stdout.
 *
 * @return  {Boolean}       FALSE
 */
sn.options.getConfig = function() {
	var fs = require('fs');
sn.utils.error = function( msg ) {
	console.error( msg.error );

	return JSON.parse( fs.readFileSync('./config.json', 'utf8') );
}; // sn.options.getConfig()
	return false;
}; // sn.utils.error()

/**
 * Strips non alpha-numeric characters from a string.
 * Outputs a warning message to stdout.
 *
 * @param   {String}  string  The string.
 * @param   {String}  msg  The message to output to stdout.
 *
 * @return  {String}          The converted string.
 * @return  {Boolean}       FALSE
 */
sn.stripNonAlphaNumeric = function( string ) {
	return string.replace(/\W/g, ' ');
}; // sn.stripNonAlphaNumeric();
sn.utils.warn = function( msg ) {
	console.warn( msg.warn );

	return false;
}; // sn.utils.warn()

/**
 * Converts non alpha-numeric characters to dashes.
 * Outputs a message to stdout.
 *
 * @param   {String}  string  The string.
 * @param   {String}  msg  The message to output to stdout.
 *
 * @return  {String}          The converted string.
 * @return  {Boolean}       FALSE
 */
sn.convertToDashes = function( string ) {
	string = sn.stripNonAlphaNumeric( string );
	return string.replace(/ /g, '-').replace('_', '-').replace('--', '-');
}; // sn.convertToDashes()
sn.utils.info = function( msg ) {
	console.log( msg );

	return false;
}; // sn.utils.info()

/**
 * Converts non alpha-numeric characters to underscores.
 * Outputs a debug message to stdout.
 *
 * @param   {String}  string  The string.
 * @param   {String}  msg  The message to output to stdout.
 *
 * @return  {String}          The converted string.
 * @return  {Boolean}       FALSE
 */
sn.convertToUnderscores = function( string ) {
	string = sn.stripNonAlphaNumeric( string );
	return string.replace(/ /g, '_').replace('-', '_').replace('__', '_');
}; // sn.convertToDashes()
sn.utils.debug = function( msg ) {
	console.log( msg.debug );

	return false;
}; // sn.utils.debug()

/**
 * Converts a string to a slug
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
 *
 * @param   {String}  string  The string.
 *
 * @return  {String}          The converted string.
 */
sn.convertToSlug = function( string ) {
	string = sn.convertToDashes( string );

	return string.toLowerCase();
}; // sn.convertToSlug()

/**
 * Converts a string to an ID
 *
 * @param   {String}  string  The string.
 *
 * @return  {String}          The converted string.
 */
sn.convertToID = function( string ) {
	string = sn.convertToUnderscores( string );

	return string.toLowerCase();
}; // sn.convertToID()

/**
 * Trims the protocols off of an URL.
 *
 * @param   {String}  string  The URL.
 *
 * @return  {String}          The trimmed URL.
 */
sn.trimProtocol = function( url ) {
	url = url.replace('http://', '');
	url = url.replace('https://', '');

	return url;
}; // sn.trimProtocol()

/**
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