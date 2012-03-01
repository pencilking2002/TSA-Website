<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'textile_society');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ')cdBx_b^LR CMlNyra<S|Xa=FTQ%6u_&s4^/?`1Nuy_1X[0z9P2E^P}bdAYzW9M;');
define('SECURE_AUTH_KEY',  ':L||NU~s?S%`_*_COjB->4 a;!,uuCL8ger4P||%^t|d8}R5U#LkHKP(cvMo2N9/');
define('LOGGED_IN_KEY',    'CT`/;~EfgXFff#>(tbf]UZ3-D{6:8f*-<:h6>tbJq!P]Ku:{yN:D|uR@i(2T}u[j');
define('NONCE_KEY',        '{|PEM5h}Ul[Iq%,nXv&yfx17siXu?xI#ve-HgFno4v0$}XY|@)oV6i,js`GG9#M^');
define('AUTH_SALT',        'M&xB<||I05.+&M<<16pi#KRsgBy3=oCf0U}^r#FHAWv|,qbO:}b+;6,<@@MK^cxr');
define('SECURE_AUTH_SALT', 'u-FVjD~w:^r3;<MJoVgT-lL)&?AbU.g6uQ%1=U7!g5:j35PF.q#3&%|=?yw i3pq');
define('LOGGED_IN_SALT',   'D-2+pC.uJ75PTmAp-;q8r)>)/3msgs+U5~{sf{4z]Wag8J<1[Y:#|!=J1OB=|rqb');
define('NONCE_SALT',       'rmQh4F#:3~;bz><om9-|60Zi?x{:(l/z}O<daB22e}U(Bq!P?=6kb*@2)IoHz#Mt');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp3_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
