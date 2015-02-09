var theme = {
	dir    : '',
	config : {},
	create : {},
	clone  : {},
	tools  : {},
};
var sn    = require( './../sn/init.js' );
var wp    = require( './init.js' );

/**
 * Handles initializing theme tools.
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}             `false`
 */
theme.tools.init = function( callback ) {
	var slug = theme.config.create.themeSlug;
	var sh = require( 'execSync' );

	sn.chdir( theme.dir+'/'+slug, function() {
		// NPM Install
		if ( theme.config.npmInstall ) {
			sn.utils.info( 'Running npm install...' );
			sh.run( 'npm update' );
		} // if()

		// Bower Install
		if ( theme.config.npmInstall ) {
			sn.utils.info( 'Running bower install...' );
			sh.run( 'bower update' );
		} // if()

		return sn.exec.callback( callback );
	});

	return false;
}; // theme.tools.init()

/**
 * Handles installing a current theme from Beanstalk.
 *
 * @todo    Error handling.
 * @todo    Add options other than GitHub.
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}             `false`
 */
theme.clone.init = function( callback ) {
	return sn.exec.callback( callback );
}; // theme.clone.init()

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

/**
 * Initializes the theme object.
 *
 * @param   {Function}  callback  The function to be executed on completion.
 *
 * @return  {Boolean}              `false`
 */
theme.init = function( callback ) {
	var _ = require('underscore');

	// Merges the options into the objects configuration
	theme.config = _.extend( theme.config, sn.options.config.theme );

	// If the theme install has been disabled lets keep moving...
	if ( theme.config.disable ) {
		return sn.exec.callback( callback );
	} // if()

	if ( theme.config.isNewTheme ) {
		return theme.create.init( callback );
	} else {
		return theme.clone.init( callback );
	} // if/else()
}; // theme.init()

module.exports = theme;