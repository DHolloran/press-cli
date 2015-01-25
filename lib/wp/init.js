var sn = require( './../sn/init.js' );
var wp = {
	base  : 'wp',
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
	params        = ( typeof params === 'object' ) ? params : { param1 : 'test', param2 : 'Test String', };
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
// Export WP Object
module.exports = wp;