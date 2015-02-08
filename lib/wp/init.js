var sn = require( './../sn/init.js' );
var wp = {
	cli        : 'wp',
	installDir : '',
	check      : {
		clisIsInstalled : false,
		wpIsInstalled   : undefined
	},
};

/**
 * Builds WordPress CLI parameters.
 *
 * @param   {Object}  params  The parameters for wp-cli.
 *
 * @return  {String}          The built WordPress CLI parameters.
 */
wp.params = function( params ) {
	params        = ( typeof params === 'object' ) ? params : {};

	if ( Object.keys(params).length ) {
		return '';
	} //if()

	var cliParams = [];
	var _         = require('underscore');

	// Build the parameters --param1=param --param="Param 2"
	_.each( params, function(value, key, list) {
		value = ( value.indexOf( ' ' ) === -1 ) ? value : '"' + value + '"';
		cliParams.push( '--' + key + '==' + value );
	});
	cliParams = cliParams.join( ' ' );

	return cliParams.trim();
}; // wp.params()

/**
 * Executes WordPress CLI.
 *
 * @param   {String}  command  The command to execute.
 * @param   {Object}  params   The parameters for wp-cli.
 *
 * @return  {Mixed}            The built WordPress CLI parameters.
 */
wp.exec = function( command, params ) {
	var cliParams   = wp.params( params );
	var sh          = require('execSync');
	var execCommand = wp.cli + ' ' + command + ' ' + cliParams;

	// Execute
	var exec = sh.exec( execCommand );

	return exec;
}; // wp.exec();

/**
 * Executes WordPress CLI.
 *
 * @param   {String}   command  The command to execute.
 * @param   {Object}   params   The parameters for wp-cli.
 *
 * @return  {Boolean}            `false`
 */
wp.run = function( command, params ) {
	var cliParams   = wp.params( params );
	var sh          = require('execSync');
	var execCommand = wp.cli + ' ' + command + ' ' + cliParams;

	// Execute
	sh.run( execCommand );

	return false;
}; // wp.run();

/**
 * Checks to see if WP CLI tools exist
 *
 * @todo Offer to install WP CLI and set them up for the user
 *
 * @return {Boolean} false
 */
wp.check.cliInstalled = function() {
	var fs              = require('fs');
	var sh              = require('execSync');
	var exec            = sh.exec( 'wp --info' );
	var isWin           = !!process.platform.match(/^win/);
	var clisIsInstalled = ( exec.code === 0 );

	if ( isWin ) {
		errorMsg = "Sorry, this will not work on a Windows box since a UNIX\n environment is required for WP CLI. \n";
		sn.utils.error( errorMsg );
	} else {
		wp.clisIsInstalled = clisIsInstalled;
	} // if/else()

	// Make sure we have WP-CLI to interact with
	wp.cli = ( wp.clisIsInstalled ) ? wp.cli : sn.binCLI;
	if ( wp.cli === '' ) {
		return false;
	} // if()

	// Make sure the wp-cli.phar is executable if we need it
	if ( wp.cli !== 'wp' ) {
		fs.chmodSync( wp.cli, '755' );
	} // if()

	return false;
}; // wp.check.cliInstalled()

/**
 * Changes to current working directory.
 *
 * @param   {String}    dir       The directory to change to.
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}              `false`
 */
wp.chdir = function( dir, callback ) {
	try {
		process.chdir( dir );
		sn.utils.debug( 'Current Directory: ' + process.cwd() );

		return sn.exec.callback( callback );
	} catch (err) {
		sn.exitError( 'Error: ' + err );
	} // try/catch

	return false;
}; // wp.chdir()

/**
 * Gets the current installed version of WordPress.
 *
 * @return  {String}  The current installed version of WordPress.
 */
wp.getCoreVersion = function() {
	if ( ! wp.isCoreInstalled() ) {
		return '';
	} // if()

	var version = wp.exec( 'core version' );

	return version.stdout;
}; // wp.getCoreVersion()

/**
 * Checks for the existence of a string in stdout.
 *
 * @param   {String}   stdout  The stdout to check in.
 * @param   {String}   string  The string to check for.
 *
 * @return  {Boolean}          `true` if the string exists and `false` if not.
 */
wp.stdoutStringExists = function( stdout, string ) {
	if ( typeof stdout === 'undefined' ) {
		return false;
	} // if()

	return ( stdout.toLowerCase().indexOf( string.toLowerCase() ) !== -1 );
}; // wp.stdoutStringExists()

/**
 * Checks if core is installed.
 *
 * @return  {Boolean}  `true` if core is installed and `false` if not.
 */
wp.isCoreInstalled = function() {
	var exec = wp.exec( 'wp core is-installed' );

	if ( exec.code !== 1 ) {
		sn.utils.warn( exec.stdout );

		return false;
	} // if()

	sn.utils.debug( exec.stdout );

	return wp.stdoutStringExists( exec.stdout, 'error' );
}; // wp.isCoreInstalled()

/**
 * Makes the install directory.
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}              `false`
 */
wp.makeInstallDirectory = function( callback ) {
	var sh        = require( 'execSync' );
	var fs        = require( 'fs' );
	var directory = sn.options.config.install.directory;
	var url       = sn.options.config.install.url;

	// Build install directory
	wp.installDir = directory + '/' + url + '/';
	wp.installDir = wp.installDir.replace( '//', '/' );

	// Make the install directory if it doesnt exist
	if ( ! fs.existsSync( wp.installDir ) ) {
		fs.mkdirSync( url, 0755 );
		sn.utils.info( 'Site directory created at ' +  wp.installDir );
	} else {
		sn.utils.warn( 'Site directory already exists at ' +  wp.installDir );
	} // if/else()

	wp.chdir( url, function() {
		return sn.exec.callback( callback );
	});

	return false;
}; // wp.makeInstallDirectory()

/**
 * Handles installing core.
 *
 * @todo    Handle errors.
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}             `false`
 */
wp.installCore = function( callback ) {
	// No need for anything here lets keep going.
	if ( wp.isCoreInstalled() ) {
		var alreadyInstalledMsg = 'WordPress ' + wp.getCoreVersion().trim() + ' is already installed in '+wp.installDir;
		sn.utils.info( alreadyInstalledMsg );

		return sn.exec.callback( callback );
	} // if()

	// Setup the parameters.
	var install        = sn.options.config.install;
	var dbname         = install.dbname;
	var dbuser         = install.dbuser;
	var dbpass         = install.dbpass;
	var dbhost         = install.dbhost;
	var dbprefix       = install.dbprefix;
	var url            = install.url;
	var title          = '"'+install.title+'"';
	var adminUser      = install.adminUser;
	var adminPassword  = '"'+install.adminPassword+'"';
	var adminEmail     = install.adminEmail;

	// Download WordPress Core
	wp.run( 'core download --force' );

	// Create Configuration (wp-confing.php)
	wp.run( 'core config --dbname='+dbname+' --dbuser='+dbuser+' --dbpass="'+dbpass+'" --dbhost='+dbhost+' --dbprefix='+dbprefix );

	// Create Database
	wp.run( 'db create' );

	// Install WordPress Core
	var coreInstall = 'core install --url='+url+' --title='+title+' --admin_user='+adminUser+' --admin_password='+adminPassword+' --admin_email='+adminEmail;
	wp.run( coreInstall.replace( '(', '' ).trim() );

	// Where we successful?
	if ( wp.isCoreInstalled() ) {
		var installMsg = 'WordPress ' + wp.getCoreVersion().trim() + ' has been installed successfully to '+wp.installDir;
		sn.utils.info( installMsg );
	} // if()

	return sn.exec.callback( callback );
}; // wp.installCore()
 * Initializes the WP object.
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}              FALSE
 */
wp.init = function( callback ) {
	wp.check.cliInstalled();
	sn.exec.callback( callback );

	return false;
}; // wp.init();

// Export WP Object
module.exports = wp;
