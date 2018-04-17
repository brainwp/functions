<?php

/**
 * Nas funções abaixo, substitua todos "brasa" pelo nome do tema em desenvolvimento
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
	 * O valor true faz o corte absoluto da imagem, sem redimensionar, sem esse valor o corte é centralizado veja mais em:
	 * http://codex.wordpress.org/Function_Reference/add_image_size#Crop_Mode
	 */
	
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

/**
 * Pega o ID do post pelo nome.
 */
function get_id_by_post_name($post_name)
{
    global $wpdb;
    $id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$post_name."'");
    return $id;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Remove a versao do WordPress do HTML gerado no front-end.
 */
function wp_remove_version() {
return '';
}
add_filter('the_generator', 'wp_remove_version');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Redireciona non-admin's users para a home do site.
 */

add_action( 'admin_init', 'redireciona_non_admin_users' );
function redireciona_non_admin_users() {
	if ( ! current_user_can( 'manage_options' ) && '/wp-admin/admin-ajax.php' != $_SERVER['PHP_SELF'] ) {
		wp_redirect( home_url() );
		exit;
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Filtro para adicionar CSS a todas as aparições de uma palavra ou frase
 */
function filter_nomedofiltro( $content ) {
	$string = array(' Solid ');
	$string2 = array(' Solid.');
	$string3 = array(' Solid,');
	$content = str_ireplace( $string, '<span style=color:#244c78;font-weight:bold;"> Solid </span>', $content );
	$content = str_ireplace( $string2, '<span style=color:#244c78;font-weight:bold;"> Solid.</span>', $content );
	$content = str_ireplace( $string3, '<span style=color:#244c78;font-weight:bold;"> Solid,</span>', $content );
	return $content;
}

add_filter( 'the_content', 'filter_nomedofiltro' );
add_filter( 'the_excerpt', 'filter_nomedofiltro' );


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// FILTRO PARA TROCAR CERTAS PALAVRAS POR LINKS

function replace_text_wps($text){
	$replace = array(
	// 'WORD TO REPLACE' => 'REPLACE WORD WITH THIS'
	' wordpress.org ' => ' <a href="http://br.wordpress.org">Wordpress</a> '
	);
	$text = str_replace(array_keys($replace), $replace, $text);
	return $text;
}

add_filter('the_content', 'replace_text_wps');
add_filter('the_excerpt', 'replace_text_wps');


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// FILTRO PARA TROCAR O TAMAMNHO DO EXCERPT
function brasa_custom_excerpt_length( $length ) {
	return 25;
}
add_filter( 'excerpt_length', 'brasa_custom_excerpt_length', 999 );
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// Mostrar Mensagem de urgência no Painel de Controle

function showMessage($message, $errormsg = false)
{
	if ($errormsg) {
		echo '<div id="message" class="updated fade">';
	}
	else {
		echo '<div id="message" class="error">';
	}
	echo "<p>$message</p></div>";
} 

function showAdminMessages()
{
    showMessage("Esta &eacute; a nova vers&atilde;o (0.9) do Portal Maracatu.org.br, a principal instabilidade nesse momento &eacute; o plugin Slickr Flickr. Escreva para suporte@maracatu.org.br para ajuda.
	<!-- Mensagem de alerta no Painel Administrativo de todos os sites do Maracatu.org.br -->", true);
}
add_action('admin_notices', 'showAdminMessages');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Cria página automaticamente ao ativer o tema.
 * Testado em WP 3.8.1
 */
if (isset($_GET['activated']) && is_admin()){
	$page_title = 'Página Automática';
	$page_content = 'Adicione aqui, caso deseje que a página seja criada com algo no content';
	$page_template = 'page-automatica.php'; // Caso não queira um page template, apague essa linha.
	$page_check = get_page_by_title( $page_title );
	$page = array(
			'post_type' => 'page',
			'post_title' => $page_title,
			'post_content' => $page_content,
			'post_status' => 'publish',
			'post_author' => 1,
	);
	if(!isset( $page_check->ID )){
		$page_id = wp_insert_post( $page );
		if( !empty( $page_template ) ){
				update_post_meta( $page_id, '_wp_page_template', $page_template );
		}
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Imprime na listagem de posts do admin o ID de cada post e página.
 * Testado em WP 3.7.3
 */

// Adiciona a função nos Posts
add_filter('manage_posts_columns', 'posts_columns_id', 5);
add_action('manage_posts_custom_column', 'posts_custom_id_columns', 5, 2);

// Adiciona a função nas Pages
add_filter('manage_pages_columns', 'posts_columns_id', 5);
add_action('manage_pages_custom_column', 'posts_custom_id_columns', 5, 2);

function posts_columns_id( $defaults ) {
    $defaults['wps_post_id'] = __( 'ID' );
    return $defaults;
}

function posts_custom_id_columns( $column_name, $id ) {
	if( $column_name === 'wps_post_id' ){
                echo $id;
    }
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/** Remove menus do admin **/
function remove_menus () {
global $menu;
	$restricted = array(__('Links'), __('Pages'), __('Tools'), __('Users'), __('Settings'), __('Comments'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}

/** Adiciona contagem de anexos nos posts **/
add_action('admin_menu', 'remove_menus');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Verifica se um plugin está instalado, caso contrário exibe uma mensagem no admin.
 * Testado em WP 3.9
 */

function show_message_admin( $message ) {
	echo '<div id="message" class="error">';
	echo "<p>$message</p></div>";
}

function check_plugins() {
	$m = 'É necessário instalar, ativar e configurar o(s) plugin(s)';
	if ( !is_plugin_active( 'jetpack/jetpack.php' ) ) {
		$m .= ' <a target= \"_blank\" href=\"http://wordpress.org/plugins/jetpack/\">JetPack</a>';
	}
	if ( !is_plugin_active( 'login-lockdown/loginlockdown.php' ) ) {
		$m .= ', <a target= \"_blank\" href=\"https://wordpress.org/plugins/login-lockdown/\">Login LockDown</a>';
	}
	$m .= '.';
	if ( strpos( $m,'target' ) == true ) {
		show_message_admin( $m );
	}
}
add_action( 'admin_notices', 'check_plugins' );

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Configura a estrutura de links permanentes ao ativer o tema.
 * Aqui o exemplo aplicado é /%postname%/
 * Testado em WP 3.8.1
 */

if (isset($_GET['activated']) && is_admin()){
		update_option( 'permalink_structure', '/%postname%/' );
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Desabilita o script HeartBeat no Admin, exceto em post.php e post-new.php.
 * Esse script faz requisições ajax a cada 15 segundos quando seu admin está aberto.
 */
add_action( 'init', 'wp_deregister_heartbeat', 1 );
function wp_deregister_heartbeat() {
	global $pagenow;

	if ( 'post.php' != $pagenow && 'post-new.php' != $pagenow )
		wp_deregister_script('heartbeat');
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Função para adicionar favicon no WordPress.
 */
function brasa_favicon() {
	$src = get_stylesheet_directory_uri() . '/assets/images/favicon.png';
 	echo '<link rel="shortcut icon" href="'. $src . '" />';
}

add_action('wp_head', 'brasa_favicon');
add_action('admin_head', 'brasa_favicon');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Imprime a URL para Home. Apenas como teste.
 */
function brasa_home() {
	return home_url();
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Função para remover de exibição a versão do WordPress.
 */

function remove_version() {
return '';
}
add_filter('the_generator', 'remove_version');


/**
* Função mostra no rodapé qual pagina vc está.
*/
add_action('admin_bar_menu', 'show_template');
function show_template() { 
global $template;
print_r($template);
}

/**
 * Set image size when theme is activated
 * Testado em WP 4.7.*
 */
if ( ! function_exists( 'brasa_theme_activated' ) ) {
    function brasa_theme_activated() {
        // set thumbnail size in settings > media
        update_option( 'thumbnail_size_w', 300 );
        update_option( 'thumbnail_size_h', 300 );
        // set medium size in settings > media
        update_option( 'medium_size_w', 600 );
        update_option( 'medium_size_h', 600 );
        // set large size in settings > media
        update_option( 'large_size_w', 1280 );
        update_option( 'large_size_h', 1280 );
    }
    add_action( 'after_switch_theme', 'brasa_theme_activated' );
}

/**
 * Set image size when theme is activated
 * Testado em WP 4.7.*
 */
function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/imagem.png)!important;
            background-size: contain;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////
////////////////////////   adiciona o nome de uma taxonomia nos campos de busca ////////
//////////////////////////

function busca_tax( $where, &$wp_query )
// busca nos nomes dos termos o valor inserido no formulario de busca padrao do wordpress
// trocar 'fonte pelo CPT e 'tema_fonte' pelo nome da sua taxonomia

{
   		global $wpdb;
    	global $wp_query;
   	if (isset($wp_query->query['post_type'])){
		if ($wp_query->is_main_query() AND $wp_query->is_search AND $wp_query->query['post_type'] == 'fonte' ) {
			$busca = $_GET['s']; 
			$where .= " 
				OR $wpdb->posts.ID 
				IN (SELECT tr.object_id 
					FROM $wpdb->term_relationships 
					AS tr 
					INNER JOIN $wpdb->term_taxonomy 
					AS tt 
					ON tr.term_taxonomy_id = tt.term_taxonomy_id 
					WHERE tt.taxonomy = 'tema_fonte'  
					AND tt.term_id 
					IN (SELECT t.term_id 
						FROM $wpdb->terms 
						AS t 
						WHERE name 
						LIKE '%$busca%'
					)
				)";  
		return $where;

		}
		
	}  

	return $where; 
}

add_filter( 'posts_where', 'busca_tax', 10, 2 );

/**
 * Pega o titulo do site na url do mesmo (subdomain or path)
 * Exemplo: 2016.diadograffiti.org - retorna: 2016.
 * Exemplo 2: diadograffiti.org/2017 - retorn: 2017
 * @param string $url
 * @return string
 */
function brasa_get_site_title_by_url( $url ) {
	$title = '';
	if ( defined( 'SUBDOMAIN_INSTALL' ) ) {
		if( SUBDOMAIN_INSTALL ) {
        	$title = explode( '.', $url );
        	$title = str_replace( array( 'https://', 'http://' ), '', $title[0] );
		} else {
			$url = substr_replace( $url, '', -1 );
			$url = explode( '/', $url );
			$title = end( $url );
    	}
    } else {
		$url = substr_replace( $url, '', -1 );
		$url = explode( '/', $url );
		$title = end( $url );
    }
    return $title;
}

/**
 * 
 * Função para criar usuário administrador no WordPress via FTP
 * 
 * @author 		Everaldo Matias <http://everaldomatias.github.io>
 * @version 	0.1
 * @since 		17/04/2018
 * @link    	https://gist.github.com/everaldomatias/99103dc6852f689d5ba88098e39c614b
 * @see 		https://brasa.art.br/como-adicionar-um-usuario-no-wordpress-pelo-ftp/
 * @return 		void
 * 
 */

function user_by_ftp(){
	$user 	= 'ketchup';
	$pass 	= 'S9XJQjc2*u!D';
	$email 	= 'email@ketchup.com.br';
	if ( ! username_exists( $user ) && ! email_exists( $email ) ) {
		$user_id 	= wp_create_user( $user, $pass, $email );
		$user 		= new WP_User( $user_id );
		$user->set_role( 'administrator' );
	}
}
add_action( 'init','user_by_ftp' );