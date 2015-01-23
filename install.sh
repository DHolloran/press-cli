#!/bin/sh

# 1. Check for wp-cli wp --info?
# 2. Install or Offer to install wp-cli curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar| or http://wp-cli.org/
# 3. --save/--init option to make/fill out a configuration file
# 4. Add update option
#
# Requires WP-CLI/Node/Bower

cd /Applications/MAMP/htdocs/;
mkdir wp-test;
cd wp-test;
if ! $(wp core is-installed); then
	wp core download --force;
	wp core config --dbname=wp_test --dbuser=root --dbpass=root --dbhost=127.0.0.1 --dbprefix=wpt_; # --extra-php Be nice to set development wp-config constants
	wp db create;
	wp core install --url=http://wp-test.dev --title="WP Test" --admin_user=wp_test_user --admin_password=1234 --admin_email=dtholloran@gmail.com;
	wp core version;
	# Update or open vHost/host/htdocs/index.php
fi

# If some sort of starter flag is flipped???
cd wp-content/themes;
wget https://github.com/matchboxdesigngroup/mdg-base/archive/0.2.5.zip;
unzip 0.2.5.zip;
mv mdg-base-0.2.5 wp-test;
rm -rvf 0.2.5.zip;

# Else grab the theme from beanstalk
# (Option to set root path)
mv wp-content wp-content2;  # use root path and maybe an option to remove this??
rm wp-content2;
git clone https://dholloran@matchboxdesigngroup.git.beanstalkapp.com/mfkhaiti-org.git
mv mfkhaiti-org wp-content; #use root path
cd wp-content; # use root path
git checkout master; # Add option for master branch name
git checkout develop; # Add option for develop branch name
cd themes/mfk; # Option for theme name
# Check for NPM offer to install/install
npm install; # Check for node_modules folder and use update
# Check for Bower offer to install/install
bower install -F; # Check for bower_components folder and use update -F/prune

# Remove Hello World
if $(wp plugin is-installed hello); then
	wp plugin deactivate hello;
	wp plugin uninstall hello;
fi

# Remove Akismet
if $(wp plugin is-installed akismet); then
	wp plugin deactivate akismet;
	wp plugin uninstall akismet;
fi

# Use for all plugin installs
# if ! $(wp plugin is-installed <plugin_name>); then
# fi

# install plugins
wp plugin install regenerate-thumbnails --activate;
wp plugin install duplicate-post --activate;
wp plugin install log-deprecated-notices --activate;
wp plugin install simply-show-ids --activate;
wp plugin install query-monitor --activate;
wp plugin install jetpack;
wp plugin install launch-check;
wp plugin install wordpress-seo;

# Look into for Gravity Forms/WP DB Migrate Pro
# Install from a local zip file
# wp plugin install ../my-plugin.zip

# Install from a remote zip file
# wp plugin install http://s3.amazonaws.com/bucketname/my-plugin.zip?AWSAccessKeyId=123&amp;Expires=456&amp;Signature=abcdef

# Look into deleting default page/post