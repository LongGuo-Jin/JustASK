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
define( 'DB_NAME', 'shepher7_wp180' );

/** MySQL database username */
define( 'DB_USER', 'shepher7_wp180' );

/** MySQL database password */
define( 'DB_PASSWORD', 'pp59!8S8@f' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'rc8nrj2bk3nla3pamqq0qqq3exyynfutxlobjktrmjwyd5qem3rq2lytf3ct3fym' );
define( 'SECURE_AUTH_KEY',  'qufr7e2ztpso9yxh74zmnrkhn91avqozcwmoq706bbtgob1tgfvsiox1rte2r1ef' );
define( 'LOGGED_IN_KEY',    'dxaqbbrphbgnzteojfcuw7gg2qxfj5hk6a08irnkgq45ckqyaiuktmc0irlgq24p' );
define( 'NONCE_KEY',        'ydg1iinywcrmosnjle71rbvf1h1qts7rykvbxkyegzbxtmwr7thb5pj4u1wa16jj' );
define( 'AUTH_SALT',        'joxlt6kkepoygny25ztjeoap1krsltlvol7embqoli8woqjah3flabrs1nnvbbua' );
define( 'SECURE_AUTH_SALT', 'zhfhgc4qnl8x4jfv3kso7c2e1do1chnclizpwryvkcpc1xycxcjxgfxib6agpx22' );
define( 'LOGGED_IN_SALT',   'zoyichsexothlfy5ub7xsslq8ho0fc1qr1vz3ocfpwipdhc4jovf6bjkwef90vg5' );
define( 'NONCE_SALT',       'effrusogyla4k12592g18ml4tnxoq2fob61aq4i3weuvzfspr4cke0cdvrdthhnj' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpo6_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system

