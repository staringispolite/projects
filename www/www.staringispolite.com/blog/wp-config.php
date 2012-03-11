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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'wpuser');

/** MySQL database password */
define('DB_PASSWORD', 'passWPword1.');

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
define('AUTH_KEY',         'ri6lU$%b?/PV{U-6>HUv=W|h/9dIIN&kB%5lZ3E#x: Bb-BU4-qDGegeYSAbL%1u');
define('SECURE_AUTH_KEY',  '@/|@-oIv8u`h2E.Y:4eXZgT7-(;6XEf|h-c|F->B3@MiL_i<GQb*~f2xOu0*64Oh');
define('LOGGED_IN_KEY',    'boY8=g9dlg<zuL2X&87~mUm YB79p9q5|A:ib4/A+r|0bPjZWJBK--IN6x/HaIIg');
define('NONCE_KEY',        'F;=~n2#? ~,2):+d0~IB$sbz+B(3G#>J9lmkB//,a;_js$vH_jdLEv* .+:(h!JM');
define('AUTH_SALT',        '`.-,V-SrX`)DA(&Uf1^n-_2d@<d.xMa01bp>dMi,AF:-C?@^$Y|9} R20eiWNOQg');
define('SECURE_AUTH_SALT', 'HG|RB+`-F8NV/S(Zz9;(=DY1yL5sK@YEr>[[:/Shi9(J2~I:XEC25nc3(lP4X/6s');
define('LOGGED_IN_SALT',   'V}Om*lhofoo-1z-|l+JCtP c_p(!-fzcl{Up1_HT=eR+j+ PP&Zzz+dNP}H  *r*');
define('NONCE_SALT',       'hnA&Y$sh)Ch1T6trqMbU8M_b!1<pJNPnDdIq`S|^tCw>n,B[~ N4-_@Rv) +9?ya');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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

/**
 * W3 Total Cache
 */
// Enable caching
define('WP_CACHE', true);
// Initialization hits memory limit of 34M
ini_set('memory_limit', '64M');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
