<?php

/**

 * The base configuration for WordPress

 *

 * The wp-config.php creation script uses this file during the installation.

 * You don't have to use the web site, you can copy this file to "wp-config.php"

 * and fill in the values.

 *

 * This file contains the following configurations:

 *

 * * Database settings

 * * Secret keys

 * * Database table prefix

 * * ABSPATH

 *

 * @link https://wordpress.org/support/article/editing-wp-config-php/

 *

 * @package WordPress

 */


// ** Database settings - You can get this info from your web host ** //

/** The name of the database for WordPress */

define( 'DB_NAME', 'bitnami_wordpress' );


/** Database username */

define( 'DB_USER', 'dbmasteruser' );


/** Database password */

define( 'DB_PASSWORD', '):q;WXi6ZOC#*0tAd$3s8qchw-;HKI;H' );


/** Database hostname */

define( 'DB_HOST', 'ls-f71e24399d306f2f71e231530a0032e3f3619dcf.cw2wirudkr5t.ap-southeast-2.rds.amazonaws.com:3306' );


/** Database charset to use in creating database tables. */

define( 'DB_CHARSET', 'utf8' );


/** The database collate type. Don't change this if in doubt. */

define( 'DB_COLLATE', '' );


/**#@+

 * Authentication unique keys and salts.

 *

 * Change these to different unique phrases! You can generate these using

 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.

 *

 * You can change these at any point in time to invalidate all existing cookies.

 * This will force all users to have to log in again.

 *

 * @since 2.6.0

 */

define( 'AUTH_KEY',         'WmbC#mUypz@,<Vm~DI>.zPJ{@B;L!e)+;Q;.qQF`Yhhg:%rm7c4l/(_w*kHdSFI,' );

define( 'SECURE_AUTH_KEY',  '`+fb6[ys>H)4sVqI4|^#2Ar6f#a/?Efu@V4}Hu4[QP:CR#A8[f!K3YIU&s YUJkr' );

define( 'LOGGED_IN_KEY',    'w++]zW7GEEPwIyUDE9(>cr`t{Oia:7f`8kCQ;A:71(k9w[*N2}8+& vGpGZf;KYd' );

define( 'NONCE_KEY',        '`dhp_z:Ol?64x`^)|pK-_(Nnsd+iJSc`fmi(Wq#J-][#G{nU(4X9k[<cngi0Pdn7' );

define( 'AUTH_SALT',        '>|plEa*rt{Z.c8>aH`&Y(SI2U;NRWT,+eg6~.0{Q6*}P}n(e}$mqnm}-V{uo7bpu' );

define( 'SECURE_AUTH_SALT', 'z[XD-rGf#*.,-}$EE%)SpAgxu7%x|SsA_Y}BLr<rriz#;}:!Zg_]5pk0]]^Lhd^>' );

define( 'LOGGED_IN_SALT',   '.=8+~dZt[-<ia=@=RtT[niQh@^Yizu/,z+3S$7O((N80Mwc)N#:fds6vw^wpSm!>' );

define( 'NONCE_SALT',       '!eq UYG8(p]#U5K_F]Ty>YQeq5KFGEiXZtR`M9Fy5;s^DC{:oP3%51G>67[jlTr7' );


/**#@-*/


/**

 * WordPress database table prefix.

 *

 * You can have multiple installations in one database if you give each

 * a unique prefix. Only numbers, letters, and underscores please!

 */

$table_prefix = 'wp_';


/**

 * For developers: WordPress debugging mode.

 *

 * Change this to true to enable the display of notices during development.

 * It is strongly recommended that plugin and theme developers use WP_DEBUG

 * in their development environments.

 *

 * For information on other constants that can be used for debugging,

 * visit the documentation.

 *

 * @link https://wordpress.org/support/article/debugging-in-wordpress/

 */

define( 'WP_DEBUG', false );


/* Add any custom values between this line and the "stop editing" line. */




define( 'FS_METHOD', 'direct' );
/**
 * The WP_SITEURL and WP_HOME options are configured to access from any hostname or IP address.
 * If you want to access only from an specific domain, you can modify them. For example:
 *  define('WP_HOME','http://example.com');
 *  define('WP_SITEURL','http://example.com');
 *
 */
if ( defined( 'WP_CLI' ) ) {
	$_SERVER['HTTP_HOST'] = '127.0.0.1';
}

define( 'WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] . '/' );
define( 'WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */


/** Absolute path to the WordPress directory. */

if ( ! defined( 'ABSPATH' ) ) {

	define( 'ABSPATH', __DIR__ . '/' );

}


/** Sets up WordPress vars and included files. */

require_once ABSPATH . 'wp-settings.php';

/**
 * Disable pingback.ping xmlrpc method to prevent WordPress from participating in DDoS attacks
 * More info at: https://docs.bitnami.com/general/apps/wordpress/troubleshooting/xmlrpc-and-pingback/
 */
if ( !defined( 'WP_CLI' ) ) {
	// remove x-pingback HTTP header
	add_filter("wp_headers", function($headers) {
		unset($headers["X-Pingback"]);
		return $headers;
	});
	// disable pingbacks
	add_filter( "xmlrpc_methods", function( $methods ) {
		unset( $methods["pingback.ping"] );
		return $methods;
	});
}
