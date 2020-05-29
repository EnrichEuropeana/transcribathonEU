<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp_transcribathon');

/** MySQL database username */
define('DB_USER', 'enrichingeuropeana');

/** MySQL database password */
define('DB_PASSWORD', 'ishoR#R7k%faQz');


/** MySQL hostname */
define('DB_HOST', 'mysql-db1.man.poznan.pl:3307');

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
define('AUTH_KEY',         '/DNiOYF/8FZ/HU< |?b7VQjnJNoF3lB!kBPRcXCK~|uYgZvL4;1#mxo2E?2xTHh');
define('SECURE_AUTH_KEY',  'uDnJE,MD32GLpMo+8byP.%RgJ-DC$h&o[|+S^6vFm{S.X.9_EUkPTtX(9ei#;BL!');
define('LOGGED_IN_KEY',    'bcbM}$911vxY@*SELzaRz?]l=]n<J$y2k)Y7JP@b{^Acxd{lmlc{5$(=Zq-,?hx+');
define('NONCE_KEY',        'N_k||D+,|C({iQ1:4Q{vQa[zYWJP|^G+H+J+h@HZP|:8C,w/Zk{1+lHB6igU4+jV');
define('AUTH_SALT',        '~vy:qR*:|0Jl-y-G}d&b=S6U37n ci%B7w8r/ut$Y&Z}+H1}kJ^lTZZ4]=NRU?P|');
define('SECURE_AUTH_SALT', '0y[w+uR^Lwt{9G|##zbaqe5EK:GuHu:x4KNo+?>7T;:@E`S<jrM$aWHV/P`w$rLn');
define('LOGGED_IN_SALT',   '[A,11my|Vxnk;{{}LVv24w[/Hm_g9,e #nH`vV:}!yxZi+|N+e>1mB(+w|:$-o2<');
define('NONCE_SALT',       'V+jAXo#~|{ M[h=Q/Q^MEXLt+Sex5mDO>}5}=6I>fY4*8jY.1]9%b2()f6=!ep@p');

/**#@-*/

/* Multisite */
define('WP_ALLOW_MULTISITE', true);

define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', 'transcribathon.eu');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
define('RELOCATE', true);
define('COOKIE_DOMAIN', '.transcribathon.eu');
define('COOKIEPATH', '/');
define('COOKIEHASH', md5('transcribathon.eu'));
define('FS_METHOD', 'direct');



/**
 * WordPress Database Table prefix. 
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);


putenv( 'PANTHEON_INDEX_HOST=transcribathon.eu' );
putenv( 'PANTHEON_INDEX_PORT=8983' );


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

