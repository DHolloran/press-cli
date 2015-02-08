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
