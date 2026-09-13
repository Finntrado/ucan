<?php
/**
 * Newsletter subscribers, stored in this WordPress database.
 *
 * - Table {prefix}ucan_subscribers, created on theme activation (and on the
 *   first request after an update, via the version check below).
 * - POST /wp-json/ucan/v1/subscribe - the site's newsletter forms send the
 *   validated email + consent here (see _scripts/newsletter_submit.py).
 *   Each row is the DPDP consent record: email, purpose, notice version,
 *   the page it was given on, and when (UTC).
 * - wp-admin > Subscribers: list, search, CSV export, delete (erasure
 *   requests under the DPDP Act).
 */

defined( 'ABSPATH' ) || exit;

const UCAN_SUBSCRIBERS_DB_VERSION = '1';
const UCAN_CONSENT_PURPOSE        = 'U-CAN newsletter, event invitations and programme details';

function ucan_subscribers_table() {
	global $wpdb;
	return $wpdb->prefix . 'ucan_subscribers';
}

function ucan_subscribers_install() {
	global $wpdb;
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	$table   = ucan_subscribers_table();
	$charset = $wpdb->get_charset_collate();
	dbDelta(
		"CREATE TABLE $table (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			email varchar(191) NOT NULL,
			consent_purpose varchar(255) NOT NULL,
			notice_version varchar(64) NOT NULL,
			source_page varchar(255) NOT NULL DEFAULT '',
			consented_at datetime NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY email (email)
		) $charset;"
	);
	update_option( 'ucan_subscribers_db_version', UCAN_SUBSCRIBERS_DB_VERSION );
}
add_action( 'after_switch_theme', 'ucan_subscribers_install' );
add_action(
	'init',
	function () {
		if ( get_option( 'ucan_subscribers_db_version' ) !== UCAN_SUBSCRIBERS_DB_VERSION ) {
			ucan_subscribers_install();
		}
	}
);

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'ucan/v1',
			'/subscribe',
			array(
				'methods'             => 'POST',
				'callback'            => 'ucan_subscribe',
				'permission_callback' => '__return_true',
			)
		);
	}
);

function ucan_subscribe( WP_REST_Request $req ) {
	$wants_json = false !== strpos( (string) $req->get_header( 'accept' ), 'application/json' );

	$email   = strtolower( sanitize_email( (string) $req->get_param( 'email' ) ) );
	$consent = strtolower( (string) $req->get_param( 'consent' ) );
	if ( ! is_email( $email ) || ! in_array( $consent, array( 'yes', 'on', '1', 'true' ), true ) ) {
		return ucan_subscribe_respond( $wants_json, false, 400 );
	}

	global $wpdb;
	$table    = ucan_subscribers_table();
	$existing = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $table WHERE email = %s", $email ) );

	// light abuse limit: 20 NEW addresses an hour per visitor IP (only a hash
	// is kept, briefly). Re-submitting an address already on the list only
	// refreshes its consent record, so it never counts - an office or event
	// sharing one IP can't lock people out by trying twice.
	if ( ! $existing ) {
		$ip   = isset( $_SERVER['REMOTE_ADDR'] ) ? (string) $_SERVER['REMOTE_ADDR'] : '';
		$key  = 'ucan_sub_' . md5( $ip . wp_salt( 'nonce' ) );
		$hits = (int) get_transient( $key );
		if ( $hits >= 20 ) {
			return ucan_subscribe_respond( $wants_json, false, 429 );
		}
		set_transient( $key, $hits + 1, HOUR_IN_SECONDS );
	}

	$notice = substr( sanitize_text_field( (string) $req->get_param( 'notice_version' ) ), 0, 64 );
	$page   = substr( sanitize_text_field( (string) $req->get_param( 'page' ) ), 0, 255 );
	$row    = array(
		'email'           => $email,
		'consent_purpose' => UCAN_CONSENT_PURPOSE,
		'notice_version'  => '' !== $notice ? $notice : 'ucan-newsletter-notice-2026-01',
		'source_page'     => $page,
		'consented_at'    => current_time( 'mysql', true ),
	);

	// re-subscribing refreshes the consent record rather than duplicating it
	$saved = $existing
		? $wpdb->update( $table, $row, array( 'id' => (int) $existing ) )
		: $wpdb->insert( $table, $row );

	if ( false === $saved ) {
		return ucan_subscribe_respond( $wants_json, false, 500 );
	}
	return ucan_subscribe_respond( $wants_json, true, 200 );
}

function ucan_subscribe_respond( $wants_json, $ok, $status ) {
	if ( $wants_json ) {
		return new WP_REST_Response( array( 'ok' => $ok ), $status );
	}
	// JS switched off: a plain form post - send the visitor back to the page
	$back = wp_get_referer();
	wp_safe_redirect( ( $back ? $back : home_url( '/' ) ) . ( $ok ? '#subscribed' : '#subscribe' ), 303 );
	exit;
}

/* ---------------------------------------------------------------- admin */

add_action(
	'admin_menu',
	function () {
		add_menu_page( 'Newsletter subscribers', 'Subscribers', 'manage_options', 'ucan-subscribers', 'ucan_subscribers_admin', 'dashicons-email-alt', 26 );
	}
);

function ucan_subscribers_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	global $wpdb;
	$table  = ucan_subscribers_table();
	$search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
	$paged  = max( 1, isset( $_GET['paged'] ) ? (int) $_GET['paged'] : 1 );
	$per    = 50;
	$where  = $search ? $wpdb->prepare( 'WHERE email LIKE %s', '%' . $wpdb->esc_like( $search ) . '%' ) : '';
	$total  = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table $where" );
	$rows   = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table $where ORDER BY consented_at DESC LIMIT %d OFFSET %d", $per, ( $paged - 1 ) * $per ) );
	$export = wp_nonce_url( admin_url( 'admin-post.php?action=ucan_subscribers_export' ), 'ucan_subscribers_export' );
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline">Newsletter subscribers</h1>
		<a href="<?php echo esc_url( $export ); ?>" class="page-title-action">Export CSV</a>
		<?php if ( isset( $_GET['deleted'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>Subscriber deleted.</p></div>
		<?php endif; ?>
		<p><?php echo esc_html( number_format_i18n( $total ) ); ?> subscriber(s). Deleting a row erases that person's email and consent record (use this for DPDP erasure requests).</p>
		<form method="get">
			<input type="hidden" name="page" value="ucan-subscribers">
			<p class="search-box">
				<input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="Search email">
				<input type="submit" class="button" value="Search">
			</p>
		</form>
		<table class="widefat striped">
			<thead><tr><th>Email</th><th>Consented (UTC)</th><th>Notice version</th><th>Signed up on</th><th></th></tr></thead>
			<tbody>
			<?php if ( ! $rows ) : ?>
				<tr><td colspan="5">No subscribers yet.</td></tr>
			<?php endif; ?>
			<?php foreach ( $rows as $r ) : ?>
				<?php $del = wp_nonce_url( admin_url( 'admin-post.php?action=ucan_subscribers_delete&id=' . (int) $r->id ), 'ucan_subscribers_delete_' . (int) $r->id ); ?>
				<tr>
					<td><?php echo esc_html( $r->email ); ?></td>
					<td><?php echo esc_html( $r->consented_at ); ?></td>
					<td><?php echo esc_html( $r->notice_version ); ?></td>
					<td><?php echo esc_html( $r->source_page ); ?></td>
					<td><a href="<?php echo esc_url( $del ); ?>" onclick="return confirm('Permanently delete this subscriber?');">Delete</a></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php
		$pages = (int) ceil( $total / $per );
		if ( $pages > 1 ) {
			echo '<p>' . paginate_links(
				array(
					'base'    => add_query_arg( 'paged', '%#%' ),
					'format'  => '',
					'current' => $paged,
					'total'   => $pages,
				)
			) . '</p>';
		}
		?>
	</div>
	<?php
}

add_action(
	'admin_post_ucan_subscribers_export',
	function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Not allowed.' );
		}
		check_admin_referer( 'ucan_subscribers_export' );
		global $wpdb;
		$rows = $wpdb->get_results( 'SELECT email, consented_at, notice_version, consent_purpose, source_page FROM ' . ucan_subscribers_table() . ' ORDER BY consented_at DESC', ARRAY_A );
		nocache_headers();
		header( 'Content-Type: text/csv; charset=UTF-8' );
		header( 'Content-Disposition: attachment; filename="ucan-subscribers-' . gmdate( 'Y-m-d' ) . '.csv"' );
		$out = fopen( 'php://output', 'w' );
		fputcsv( $out, array( 'email', 'consented_at_utc', 'notice_version', 'consent_purpose', 'source_page' ) );
		foreach ( $rows as $row ) {
			// stop spreadsheet apps treating a value as a formula
			$row = array_map(
				function ( $v ) {
					return preg_match( '/^[=+\-@]/', (string) $v ) ? "'" . $v : $v;
				},
				$row
			);
			fputcsv( $out, $row );
		}
		fclose( $out );
		exit;
	}
);

add_action(
	'admin_post_ucan_subscribers_delete',
	function () {
		$id = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Not allowed.' );
		}
		check_admin_referer( 'ucan_subscribers_delete_' . $id );
		global $wpdb;
		$wpdb->delete( ucan_subscribers_table(), array( 'id' => $id ) );
		wp_safe_redirect( admin_url( 'admin.php?page=ucan-subscribers&deleted=1' ) );
		exit;
	}
);
