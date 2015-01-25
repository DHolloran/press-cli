var sn = {
	path    : '',
	binCLI  : 'bin/wp-cli.phar',
	exec    : {},
	utils   : {},
	options : {
		config : {},
		set    : {},
		cli    : {
			install : {
				get : {},
			},
			theme : {
				get : {},
			},
		},
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
 * @since   1.0.0
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
 * @since   1.0.0
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
 * @since   1.0.0
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
 * @since   1.0.0
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
 * @since   1.0.0
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
 * @since   1.0.0
 *
 * @return  {String} The converted string.
 */
String.prototype.stripNonAlphaNumeric = function() {
	return this.replace(/\W/g, ' ');
}; // String.prototype.stripNonAlphaNumeric();

/**
 * Converts non alpha-numeric characters to dashes.
 *
 * @since   1.0.0
 *
 * @return  {String}  The converted string.
 */
String.prototype.convertToDashes = function() {
	return this.stripNonAlphaNumeric().replace(/[_\s]/g, '-').replace('--', '-');
}; // String.prototype.convertToDashes()

/**
 * Converts non alpha-numeric characters to underscores.
 *
 * @since   1.0.0
 *
 * @return  {String}  The converted string.
 */
String.prototype.convertToUnderscores = function() {
	return this.stripNonAlphaNumeric().replace(/[-\s]/g, '_').replace('__', '_');
}; // String.prototype.convertToUnderscores()

/**
 * Converts a string to a slug
 *
 * @since   1.0.0
 *
 * @return  {String}  The converted string.
 */
String.prototype.convertToSlug = function() {
	return this.convertToDashes().toLowerCase();
}; // String.prototype.convertToSlug()

/**
 * Converts a string to an ID
 *
 * @since   1.0.0
 *
 * @return  {String}  The converted string.
 */
String.prototype.convertToID = function() {
	return this.convertToUnderscores().toLowerCase();
}; // String.prototype.convertToID()

/**
 * Trims any URL protocols off of a string.
 *
 * @since   1.0.0
 *
 * @return  {String}  The trimmed string.
 */
String.prototype.trimProtocol = function() {
	return this.replace('https://', '').replace('http://', '');
}; // String.prototype.trimProtocol()


/**
 * Picks characters from a string.
 *
 * @since   1.0.0
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
 * @since   1.0.0
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
}; // String.prototype.shuffle()

/**
 * Generates a random strong password.
 *
 * @since   1.0.0
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
 * @since   1.0.0
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
 * @since   1.0.0
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

/**
 * Gets the install administrator password.
 *
 * @since   1.0.0
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}             FALSE
 */
sn.options.cli.install.get.adminPassword = function( callback ) {
	sn.options.cli.ask( 'Administrator Password', sn.options.generatePassword(), function( data ) {
		sn.options.config.install.adminPassword = data;

		// Remind user to save their password
		sn.utils.warn( 'Remember to save your password ' + data + ' in a safe place' );

		sn.exec.callback( callback );
	});

	return false;
}; // sn.options.cli.install.get.adminPassword

/**
 * Gets the install site title.
 *
 * @since   1.0.0
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}             FALSE
 */
sn.options.cli.install.get.title = function( callback ) {
	var defaultOption = ( typeof sn.options.config.install.title === 'undefined' ) ? '' : sn.options.config.install.title;
	sn.options.cli.ask( 'Site Title', defaultOption, function( data ) {
		sn.options.config.install.title = data;

		sn.exec.callback( callback );
	});

	return false;
}; // sn.options.cli.install.get.title

/**
 * Gets the install site URL.
 *
 * @since   1.0.0
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}             FALSE
 */
sn.options.cli.install.get.url = function( callback ) {
	var defaultOption = ( typeof sn.options.config.install.url === 'undefined' ) ? '' : sn.options.config.install.url;
	sn.options.cli.ask( 'Site URL (ex:myawesomesite.dev)', defaultOption, function( data ) {
		sn.options.config.install.url = data;

		sn.exec.callback( callback );
	});

	return false;
}; // sn.options.cli.install.get.url

/**
 * Gets all of the install options.
 *
 * @since   1.0.0
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}             FALSE
 */
sn.options.cli.install.get.all = function( callback ) {
	sn.options.cli.install.get.title(function() {
		sn.options.cli.install.get.url(function() {
			sn.options.cli.install.get.adminPassword(function() {
				callback();
			});
		});
	});
}; // sn.options.cli.install.get.all()

/**
 * Gets the existing theme name.
 *
 * @since   1.0.0
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}             FALSE
 */
sn.options.cli.theme.get.themeDirectoryName = function( callback ) {
	if ( sn.options.config.theme.isNewTheme ) {
		sn.exec.callback( callback );

		return false;
	} // if()

	var defaultOption = ( typeof sn.options.config.theme.existing.themeDirectoryName === 'undefined' ) ? '' : sn.options.config.theme.existing.themeDirectoryName;
	sn.options.cli.ask( 'Existing Theme Name', defaultOption, function( data ) {
		sn.options.config.theme.existing.themeDirectoryName = data;

		sn.exec.callback( callback );
	});

	return false;
}; // sn.options.cli.theme.get.themeDirectoryName()

/**
 * Gets the existing theme git repository.
 *
 * @since   1.0.0
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}             FALSE
 */
sn.options.cli.theme.get.gitRepository = function( callback ) {
	if ( sn.options.config.theme.isNewTheme ) {
		sn.exec.callback( callback );

		return false;
	} // if()

	var defaultOption = ( typeof sn.options.config.theme.existing.gitRepository === 'undefined' ) ? '' : sn.options.config.theme.existing.gitRepository;
	sn.options.cli.ask( 'Existing Theme Repository Slug', defaultOption, function( data ) {
		sn.options.config.theme.existing.gitRepository = data;

		sn.exec.callback( callback );
	});

	return false;
}; // sn.options.cli.theme.get.gitRepository()

/**
 * Gets the theme setup option.
 *
 * @since   1.0.0
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}             FALSE
 */
sn.options.cli.theme.get.isNewTheme = function( callback ) {
	sn.options.cli.ask( 'Setup new theme (Y/N)', 'Y', function( data ) {
		sn.options.config.theme.isNewTheme = data;

		sn.exec.callback( callback );
	});

	return false;
}; // sn.options.cli.theme.get.isNewTheme()

/**
 * Gets all of the theme options.
 *
 * @since   1.0.0
 *
 * @todo    Break out existing and new theme options into sn.options.cli.theme.get.allNew/Existing() om a check against sn.options.config.theme.isNewTheme.
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}             FALSE
 */
sn.options.cli.theme.get.all = function( callback ) {
	sn.options.cli.theme.get.isNewTheme(function() {
		sn.options.cli.theme.get.gitRepository(function() {
			sn.options.cli.theme.get.themeDirectoryName(function() {
				sn.exec.callback( callback );
			});
		});
	});
}; // sn.options.cli.theme.get.all()

/**
 * Handles reading the configuration options.
 *
 * @since   1.0.0
 *
 * @return  {Object}  The configuration options.
 */
sn.options.getConfig = function() {
	var fs = require('fs');

	return JSON.parse( fs.readFileSync( sn.path + '/config.json', 'utf8') );
}; // sn.options.getConfig()

/**
 * Sets config.install.directoryName option from site URL.
 *
 * @since   1.0.0
 *
 * @return  {Boolean}  FALSE
 */
sn.options.set.directoryName = function() {
	var url = ( typeof sn.options.config.install.url === 'undefined' ) ? '' : sn.options.config.install.url;

	if ( url === '' || sn.options.config.install.directoryName !== '' ) {
		return false;
	} // if()

	sn.options.config.install.directoryName = url.trimProtocol();

	return false;
}; // sn.options.set.directoryName();

/**
 * Sets config.install.dbname option from site URL.
 *
 * @since   1.0.0
 *
 * @return  {Boolean}  FALSE
 */
sn.options.set.dbname = function() {
	var url = ( typeof sn.options.config.install.url === 'undefined' ) ? '' : sn.options.config.install.url;

	if ( url === '' || sn.options.config.install.dbname !== '' ) {
		return false;
	} // if()

	url = url.trimProtocol();
	var id  = url.convertToID();
	var dbname = 'wp_' + id;

	sn.options.config.install.dbname = dbname;

	return false;
}; // sn.options.set.dbname();

/**
 * Sets config.install.dbprefix option from site title.
 *
 * @since   1.0.0
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
 * Sets config.theme.create.themeName option from site title.
 *
 * @since   1.0.0
 *
 * @return  {Boolean}  FALSE
 */
sn.options.set.themeName = function() {
	if ( ! sn.options.config.theme.isNewTheme ) {
		return false;
	} // if()

	var title = ( typeof sn.options.config.install.title === 'undefined' ) ? '' : sn.options.config.install.title;

	if ( title === '' || sn.options.config.theme.create.themeName !== '' ) {
		return false;
	} // if()

	sn.options.config.theme.create.themeName = title;

	return false;
}; // sn.options.set.themeName()

/**
 * Retrieves the zip URL from the latest tag
 *
 * @since   1.0.0
 *
 * @todo Either and event or promise or something...
 * @todo Better error handling.
 * @todo Abstract GitHub get release tag functionality.
 *
 * @return  {Boolean}  FALSE
 */
sn.options.set.themeURL = function() {
	if ( sn.options.config.theme.create.themeURL !== '' || ! sn.options.config.theme.isNewTheme ) {
		return false;
	} // if()

	var _          = require('underscore');
	var https      = require('https');
	var user       = ( typeof sn.options.config.theme.create.user === 'undefined' ) ? '' : sn.options.config.theme.create.user;
	var repo       = ( typeof sn.options.config.theme.create.repo === 'undefined' ) ? '' : sn.options.config.theme.create.repo;
	var releaseTag = ( typeof sn.options.config.theme.create.releaseTag === 'undefined' ) ? '' : sn.options.config.theme.create.releaseTag;

	// We need at least the user and repository
	if ( user === '' || repo === '' ) {
		return false;
	} // if()

	// If we have a release tag no need to go get it.
	if ( releaseTag !== '' ) {
		var url = 'https://api.github.com/repos/' + user + '/' + repo + '/zipball/' + releaseTag;

		sn.options.config.theme.create.themeURL = url;

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
			sn.utils.error( err );
			return false;
		} // if()

		// Check if we got a tag back
		json = JSON.parse(JSON.stringify(res));
		if ( json.length === 0 ) {
			sn.utils.warn( 'No releases found for https://github.com/' + user + '/' + repo + '/'  );

			return false;
		} // if()

		var url = ( typeof json[0].zipball_url === 'undefined' ) ? '' : json[0].zipball_url;
		sn.options.config.theme.create.themeURL = url;
	});

	return false;
}; // sn.options.set.themeURL()

/**
 * Sets config.theme.create.themeDescription option from site title.
 *
 * @since   1.0.0
 *
 * @return  {Boolean}  FALSE
 */
sn.options.set.themeDescription = function() {
	if ( ! sn.options.config.theme.isNewTheme ) {
		return false;
	} // if()

	var title = ( typeof sn.options.config.install.title === 'undefined' ) ? '' : sn.options.config.install.title;

	if ( title === '' || sn.options.config.theme.create.themeDescription !== '' ) {
		return false;
	} // if()

	var description = 'This custom theme has been created for '+title+' by <a href="http://www.matchboxdesigngroup.com/">Matchbox Design Group</a>';
	sn.options.config.theme.create.themeDescription = description;

	return false;
}; // sn.options.set.themeDescription()

/**
 * Sets default configuration options before CLI options.
 *
 * @since   1.0.0
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

	return sn.exec.callback( callback );
}; // sn.options.setDefaults()

/**
 * Initializes Snifter Utilities.
 *
 * @since   1.0.0
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}   FALSE
 */
sn.init = function( callback ) {
	var fs = require('fs');

	// Set module path
	sn.path = module.id.replace( '/lib/sn/init.js', '' );

	// Set wp-cli.phar bin path (for fall back)
	var wpBinCLI = sn.path + '/' + sn.binCLI;
	sn.binCLI    = ( fs.existsSync( wpBinCLI ) ) ? wpBinCLI : '';

	// Get configuration options
	sn.options.config = sn.options.getConfig();
	sn.options.setDefaults( callback );

	// Get CLI options
	sn.options.cli.install.get.all(function() {
		sn.options.cli.theme.get.all(function() {
			sn.options.setDefaults( callback );
		});
	});

	return false;
}; // sn.init()

// Export Options
module.exports = sn;