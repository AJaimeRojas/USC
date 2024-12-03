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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'ultraschall_blog' );

/** MySQL database username */
define( 'DB_USER', 'ultraschall_blog' );

/** MySQL database password */
define( 'DB_PASSWORD', 'PuP$3_WbPlhcPUEy' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'YDUBBAe{-@Ea6rKr$Du%p:2GX_@RU8NwQi`is3TEoZ%y7WyvhYk1?p1)as95$*bg' );
define( 'SECURE_AUTH_KEY',  '#IiiH5Y^%yiUvgdE-SADmYTEUxS[]YtR`c8$9W_9]4O2m)Bf&%k3dL<*TV!1T#jV' );
define( 'LOGGED_IN_KEY',    ',HnSwZU[U!al3/b[ennaDM*49*rS5KsMLP<a>u9v@q`rI2S>gt?>W+UHtF2(4hsA' );
define( 'NONCE_KEY',        ' WGtLoe_We(i@;3LG#NGXdMD<<z(+5lHJK]<__!m|=l,H}fQv;V>4!+nzH9}|im$' );
define( 'AUTH_SALT',        '#6saHbP7;a{|?J$4S)5SFlT_W%?dRZAPY:j69ofQbu#7J^a5[] JqDBlJP,D(},@' );
define( 'SECURE_AUTH_SALT', 'JNB!N!+@hj?kYe:=]6J,3,N0CBdZfSq`EVv +.P~_X=*]cZcXO&eC6IsV:OfJktG' );
define( 'LOGGED_IN_SALT',   '>fyne9&X ~:<NlQqiS?]bG<?NZ::w59oH2S%PI}`l0G~T>+:p_0/S<*Sg It{y-X' );
define( 'NONCE_SALT',       ']TTF;)UuANzH;`ZSiXXclw`4j4*0K,-t6^d6$2mgmiPf8HwH|)=&,`-vf?ED<HjU' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
