<?php
/**
 * Google Analytics 4 - loaded ONLY after the visitor accepts analytics in the cookie banner.
 *
 *  - Nothing is requested from Google until consent (localStorage "ucan_consent_v1" has analytics:true).
 *  - "Essential only" (or reopening the banner and rejecting) stops collection and removes the _ga cookies.
 *  - No advertising features: Google signals and ad personalisation are off, IP anonymisation requested.
 *  - Production hosts only, so LocalWP / previews / the noindexed Vercel copy never pollute the data.
 *  - The Content-Security-Policy is widened for Google's hosts only when this is active.
 *
 * Measurement ID: G-TT92QE00S9 (GA4 property 543884984).
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'UCAN_GA_ID' ) ) {
	define( 'UCAN_GA_ID', 'G-TT92QE00S9' );
}

function ucan_analytics_active() {
	return function_exists( 'ucan_is_prod_host' ) && ucan_is_prod_host();
}

/** Extra CSP sources for Google Analytics (script / connect / img). */
function ucan_analytics_csp() {
	if ( ! ucan_analytics_active() ) {
		return array( 'script' => '', 'connect' => '', 'img' => '' );
	}
	return array(
		'script'  => ' https://www.googletagmanager.com',
		'connect' => ' https://www.googletagmanager.com https://*.google-analytics.com https://*.analytics.google.com',
		'img'     => ' https://www.googletagmanager.com https://*.google-analytics.com',
	);
}

function ucan_analytics_snippet() {
	$id = UCAN_GA_ID;
	$js = <<<'JS'
(function(){
var ID='__ID__',KEY='ucan_consent_v1',loaded=false;
function ok(){try{var v=JSON.parse(localStorage.getItem(KEY));return !!(v&&v.analytics===true);}catch(e){return false;}}
function load(){
  if(loaded)return;loaded=true;window['ga-disable-'+ID]=false;
  window.dataLayer=window.dataLayer||[];window.gtag=function(){window.dataLayer.push(arguments);};
  window.gtag('js',new Date());
  window.gtag('config',ID,{anonymize_ip:true,allow_google_signals:false,allow_ad_personalization_signals:false});
  var s=document.createElement('script');s.async=true;s.src='https://www.googletagmanager.com/gtag/js?id='+ID;document.head.appendChild(s);
}
function stop(){
  window['ga-disable-'+ID]=true;
  var names=['_ga','_ga_'+ID.replace('G-',''),'_gid'],host=location.hostname.split('.'),doms=[''];
  for(var i=0;i<host.length-1;i++){doms.push('; domain=.'+host.slice(i).join('.'));}
  names.forEach(function(n){doms.forEach(function(d){document.cookie=n+'=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/'+d+'; SameSite=Lax';});});
}
function sync(){if(ok())load();else stop();}
document.addEventListener('click',function(e){
  var t=e.target&&e.target.closest?e.target.closest('#cc-accept,#cc-reject'):null;
  if(t)setTimeout(sync,0);
},true);
if(ok())load();
})();
JS;
	return '<script data-ucan="analytics">' . str_replace( '__ID__', esc_js( $id ), $js ) . '</script>';
}

/** Inject the consent-gated snippet before </body> (production hosts only). */
function ucan_inject_analytics( $html ) {
	if ( ! ucan_analytics_active() || false === strpos( $html, '</body>' ) ) {
		return $html;
	}
	return str_replace( '</body>', ucan_analytics_snippet() . '</body>', $html );
}
