<?php
/**
 * Reached only for front-end URLs that no page, redirect or route claimed
 * (functions.php answers everything else), so this is the 404. It serves the
 * branded error page with a real 404 status; the inline fallback below is only
 * for a broken install where inc/redirects.php is missing.
 */
if ( function_exists( 'ucan_serve_error_page' ) ) {
	ucan_serve_error_page( 404 );
}
status_header( 404 );
nocache_headers();
?><!doctype html>
<html lang="en-IN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex">
<title>Page not found | U-CAN</title>
</head>
<body>
<h1>Page not found</h1>
<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Go to the U-CAN homepage</a>.</p>
</body>
</html>
