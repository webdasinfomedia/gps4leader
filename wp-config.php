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
define('DB_NAME', 'wordpress4.5');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'c!B7 4rF6gA&eek<m/8GP|6*EQ`7,>1w}n/UlA.;y/$fuV{AW1@Aj~~fm?(tFrY`');
define('SECURE_AUTH_KEY',  'F%]y@u#e4h}XmohDx&oZYhx^cULoZ5KWsJ|w*+;J)J8_/[BFN`~Lhb`egaw7(yO~');
define('LOGGED_IN_KEY',    '%9-J4?tW)<AhU|bA+NAtn2opL7W-*gU]7u?}dq+C2W6A0K=_&_bU+KAnz6+&>~!=');
define('NONCE_KEY',        '9o$gvD(o.4gHLFx-r#JgI6$Z!zyd.:V{4>HX4}4fqiHuoh6Fv839e$| U#oz1Y]t');
define('AUTH_SALT',        'v]+P{{MHa)9oip^8_vQR>/CxBiX+{I}0?gp4~__SYN0|g)VOCtFC3gZ]zoz .IWi');
define('SECURE_AUTH_SALT', '4LARL3fj2Q^VsGLCIvUM__CRCe]dNw`vS797>!iPw>vL#nc7t{v`I_2=G D*HFrF');
define('LOGGED_IN_SALT',   'JJYEbaK8%juJ/OdGZg|<c1#mGPU$CMwKU+D)G7@qhT]pO1e6y8j=)j3T{7#.XqbR');
define('NONCE_SALT',       '<MWX4ijZ*b3w*1uYB BFuT8]@Yo8wK752{ 9t+>^4w#~7@&+w`Z#b)_`O<<._,~<');

/**#@-*/

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
