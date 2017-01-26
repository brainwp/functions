
function SLUG_init() {
	register_post_type( 'SLUG', array(
		'labels'            => array(
			'name'                => __( 'PLURAL', 'TEMA' ),
			'singular_name'       => __( 'SINGULAR', 'TEMA' ),
			'all_items'           => __( 'TOD PLURAL', 'TEMA' ),
			'new_item'            => __( 'NOV SINGULAR', 'TEMA' ),
			'add_new'             => __( 'Adicionar NOV', 'TEMA' ),
			'add_new_item'        => __( 'Adicionar NOV SINGULAR', 'TEMA' ),
			'edit_item'           => __( 'Editar SINGULAR', 'TEMA' ),
			'view_item'           => __( 'Ver SINGULAR', 'TEMA' ),
			'search_items'        => __( 'Buscar PLURAL', 'TEMA' ),
			'not_found'           => __( 'NENH SINGULAR encontrado', 'TEMA' ),
			'not_found_in_trash'  => __( 'NENH SINGULAR encontrado', 'TEMA' ),
			'parent_item_colon'   => __( 'SINGULAR pai', 'TEMA' ),
			'menu_name'           => __( 'PLURAL', 'TEMA' ),
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'supports'          => array( 'title', 'editor', 'excerpt' ),
		'has_archive'       => true,
		'rewrite'           => true,
		'query_var'         => true,
		'menu_icon'         => 'dashicons-admin-post',
		'show_in_rest'      => true,
		'rest_base'         => 'SLUG',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'SLUG_init' );

function SLUG_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['SLUG'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('SINGULAR updated. <a target="_blank" href="%s">Ver SINGULAR</a>', 'TEMA'), esc_url( $permalink ) ),
		2 => __('Campo personalizado atualizado.', 'TEMA'),
		3 => __('Campo personalizado deletado.', 'TEMA'),
		4 => __('SINGULAR atualizado.', 'TEMA'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('SINGULAR restaurado para a revisão %s', 'TEMA'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Movie published. <a href="%s">View Movie</a>', 'TEMA'), esc_url( $permalink ) ),
		7 => __('SINGULAR salvo.', 'TEMA'),
		8 => sprintf( __('SINGULAR enviado. <a target="_blank" href="%s">Pré-visualização</a>', 'TEMA'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('SINGULAR agendado para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Pré-visualização</a>', 'TEMA'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Rascunho de SINGULAR atualizado. <a target="_blank" href="%s">Pré-visualização</a>', 'TEMA'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'SLUG_updated_messages' );
