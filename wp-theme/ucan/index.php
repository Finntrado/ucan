<?php
/**
 * Reached only for front-end URLs with no matching page in pages/
 * (functions.php answers everything else), so this is the 404.
 */
status_header( 404 );
nocache_headers();
?><!doctype html>
<html lang="en-IN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex">
<title>Page not found | U-CAN</title>
<style>
body{margin:0;min-height:100vh;display:grid;place-items:center;background:#0E5348;color:#FBFAF6;font:16px/1.6 "Public Sans",system-ui,sans-serif;text-align:center;padding:24px}
h1{font:800 clamp(32px,6vw,56px)/1.1 Archivo,system-ui,sans-serif;margin:0 0 12px}
a{color:#CDDE71}
</style>
</head>
<body>
<main>
<h1>Page not found</h1>
<p>The page you were looking for doesn't exist. <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Go to the U-CAN homepage</a>.</p>
</main>
</body>
</html>
