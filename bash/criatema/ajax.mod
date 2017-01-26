<?php //Funcoes chamadas por Ajax

add_action( 'wp_enqueue_scripts', 'ajax_localize', 1 );
function ajax_localize(){
	wp_localize_script( 'odin-main', 'odin_main', array('ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}



add_action('wp_ajax_query', 'query_func');
add_action('wp_ajax_nopriv_query', 'query_func'); // Allow front-end submission 
function query_func(){
	$cat=$_POST['cat'];
	$args = array(
		'post_type' => 'noticia',
		'posts_per_page' =>8,
		'tax_query' => array(
			array(
				'taxonomy' => 'category',
				'field'    => 'ID',
				'terms'    => $cat,
			),
		),
	);
	$term=get_term($cat);
	$slug=get_term_link($term);
	$resposta= array('html'=>'', 'slug'=>$slug
		);
	$WP_query_cat = new WP_Query( $args );
	
	if( $WP_query_cat->have_posts()  )
	{
		while ( $WP_query_cat->have_posts() ) {
			$WP_query_cat->the_post();
			$quadrada=get_field('img_quadrada', get_the_id());
			if (isset($quadrada) AND get_field('img_quadrada', get_the_id()) != "") {
				$thumb= '<img 	class="img wp-post-image" src="'.get_field('img_quadrada', get_the_id())['sizes']['quadrada'].'" alt="">';
			}
			elseif (!has_post_thumbnail( get_the_id())) {
	 			$thumb= '<img class="img wp-post-image" src="'.get_template_directory_uri().'/assets/images/logo-quadrado.png" alt="">';

			} 
			else{
				$thumb=get_the_post_thumbnail( get_the_id(), 'quadrada');
			}
			$resposta['html'] .= '
			<div class="cada-noticia col-sm-3">
				<div class="noticia-titulo">
					<a href="'.get_permalink( ) .'">
						<h4>'.get_the_title( ).'</h4>
						<p>'.get_the_excerpt( ).'</p>
					</a>
				</div><!--box-titulo-->'.$thumb.'
			</div>';
		}
		wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly
	}
	
	
	echo json_encode($resposta);
	wp_die( );
}
