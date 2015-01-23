var sn = require('./libs/sn/init.js');
sn.init();

console.log( 'Directory Name: ' + sn.options.config.install.directoryName );
console.log( 'dbname: ' + sn.options.config.install.dbname );
console.log( 'dbprefix: ' + sn.options.config.install.dbprefix );
console.log( 'Theme Name: ' + sn.options.config.newTheme.themeName );
console.log( 'Theme URL: ' + sn.options.config.newTheme.themeURL );
console.log( 'Theme Description: ' + sn.options.config.newTheme.themeDescription );