<?php
/*
Functions da Galáxia
Uma simples documentacao das funcoes, hacks e truques usados pela Brasa.
*/


/**
 * A funcao abaixo, substitua todos "brasa" pelo nome do tema em desenvolvimento
 */
function brasa_setup() {
	/**
	 * Prepara o tema para traducao. As mesmas devem estar em /languages/
	 */
	load_theme_textdomain( 'brasa', get_template_directory() . '/languages' );
	
	/**
	 * Adiciona posts e comentarios ao RSS feed.
	 */
	add_theme_support( 'automatic-feed-links' );
	
	/**
	 * Habilita os Post Thumbnails e adiciona tamanhos personalizados.
	 * Exemplo de uso: <?php the_post_thumbnail('thumb-exemplo'); ?>,
	 * isso dentro de um loop pega a imagem destacada de cada post.
	 * Em uma pagina exibe a imagem destacada da pagina corrente,
	 * podendo usar no header por exemplo.
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'thumb-exemplo', 350, 200, true );
	
	/**
	 * Para um tema que use wp_nav_menu(), adicione as localizacoes abaixo.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'brasa' ),
	) );

	/**
	 * Habilita no tema, suporte aos Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

}

endif; // brasa_setup()

add_action( 'after_setup_theme', 'brasa_setup' );

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Funcao abaixo, exclui do the_category(); as categorias definidas no array $exclude;
 */
function the_category_filter($thelist,$separator=' ') {  
    if(!defined('WP_ADMIN')) {  
        //Nome das categorias que deseja excluir do the_category();  
        $exclude = array('exemplo', 'Exemplo');  
          
        $cats = explode($separator,$thelist);  
        $newlist = array();  
        foreach($cats as $cat) {  
            $catname = trim(strip_tags($cat));  
            if(!in_array($catname,$exclude))  
                $newlist[] = $cat;  
        }  
        return implode($separator,$newlist);  
    } else {  
        return $thelist;  
    }  
}  
add_filter('the_category','the_category_filter', 10, 2); 

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Remove widgets desnecessarios do admin
 *
 * @link http://www.deluxeblogtips.com/2011/01/remove-dashboard-widgets-in-wordpress.html
 */
function brasa_remove_dashboard_widgets() {
  remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
  remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
  remove_meta_box('dashboard_primary', 'dashboard', 'normal');
  remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
}

add_action('admin_init', 'brasa_remove_dashboard_widgets');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Criando um site restrito, redireciona a pessoa caso nao esteja logada.
 */
add_action('init','to_login');
function to_login() {
    $isLoginPage = strpos($_SERVER['REQUEST_URI'], "wp-login.php") !== false;   
    if(!is_user_logged_in() && !is_admin() &&  !$isLoginPage) {
        header( 'Location: http://seusite.com/wp-admin' ) ;
        die();
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>