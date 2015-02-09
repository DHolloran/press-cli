var theme = {};
var theme = {
	dir    : '',
	config : {},
	create : {},
};
var sn    = require( './../sn/init.js' );
var wp    = require( './init.js' );
/**
 * Handles installing a new theme from GitHub.
 *
 * @todo    Error handling.
 * @todo    Add options other than GitHub.
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}             `false`
 */
theme.create.init = function( callback ) {
	theme.dir = wp.installDir + '/' + theme.config.create.root;
	theme.dir = theme.dir.replace('//', '/').replace('//', '/');

	sn.chdir( theme.dir, function() {
		var fs = require( 'fs' );
		var sh   = require( 'execSync' );
		var tag  = theme.config.create.releaseTag;
		var repo = theme.config.create.repo;
		var user = theme.config.create.user;
		var slug = theme.config.create.themeSlug;

		// Download theme
		// @todo Handle errors
		sh.run( 'wget https://github.com/'+user+'/'+repo+'/archive/'+tag+'.zip' );

		// Unzip theme
		// @todo check for existence of zip
		sn.utils.info( 'Unzipping theme...' );
		var unzip = sh.exec( 'unzip '+tag+'.zip' );

		// Remove Zip
		fs.unlinkSync( theme.dir+'/'+tag+'.zip' );

		// Rename theme
		// @todo check for existence of theme directory
		fs.renameSync( theme.dir+'/' + repo+'-'+tag, theme.dir+'/'+slug );

		// Initialize the tools
		theme.tools.init( callback );
	});

	return false;
}; // theme.create.init()
module.exports = theme;