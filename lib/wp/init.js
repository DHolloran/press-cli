var sn = require( './../sn/init.js' );
var wp = {
	cli   : 'wp',
	check : {
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
 * Initializes the WP object.
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}              FALSE
 */
wp.init = function( callback ) {
	wp.check.cliInstalled();
	wp.exec();
	sn.exec.callback( callback );

	return false;
}; // wp.init();

// Export WP Object
module.exports = wp;