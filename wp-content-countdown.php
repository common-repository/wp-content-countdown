<?php
/*
Plugin Name: Content countdown
Plugin URI: https://danielesparza.studio/wp-content-countdown/
Description: Content countdown es un plugin para WordPress que permite mostrar un contenido después de cierta fecha a través del uso de un shortcode. Este plugin hace utiliza el componente alert de la librería 4.3.0 de bootstrap.
Version: 1.0
Author: Daniel Esparza
Author URI: https://danielesparza.studio/
License: GPL v3

Content countdown
©2020 Daniel Esparza, inspirado por #openliveit #dannydshore | Consultoría en servicios y soluciones de entorno web - https://danielesparza.studio/

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(function_exists('admin_menu_desparza')) { 
    //menu exist
} else {
	add_action('admin_menu', 'admin_menu_desparza');
	function admin_menu_desparza(){
		add_menu_page('DE Plugins', 'DE Plugins', 'manage_options', 'desparza-menu', 'wp_desparza_function', 'dashicons-editor-code', 90 );
		add_submenu_page('desparza-menu', 'Sobre Daniel Esparza', 'Sobre Daniel Esparza', 'manage_options', 'desparza-menu' );
	
    function wp_desparza_function(){  	
	?>
		<div class="wrap">
            <h2>Daniel Esparza</h2>
            <p>Consultoría en servicios y soluciones de entorno web.<br>¿Qué tipo de servicio o solución necesita tu negocio?</p>
            <h4>Contact info:</h4>
            <p>
                Sitio web: <a href="https://danielesparza.studio/" target="_blank">https://danielesparza.studio/</a><br>
                Contacto: <a href="mailto:hi@danielesparza.studio" target="_blank">hi@danielesparza.studio</a><br>
                Messenger: <a href="https://www.messenger.com/t/danielesparza.studio" target="_blank">enviar mensaje</a><br>
                Información acerca del plugin: <a href="https://danielesparza.studio/wp-content-countdown/" target="_blank">sitio web del plugin</a><br>
                Daniel Esparza | Consultoría en servicios y soluciones de entorno web.<br>
                ©2020 Daniel Esparza, inspirado por #openliveit #dannydshore
            </p>
		</div>
	<?php }
        
    }	
    
    add_action( 'admin_enqueue_scripts', 'wpcdt_register_adminstyle' );
    function wpcdt_register_adminstyle() {
        wp_register_style( 'wpcdt_register_adminstyle_css', plugin_dir_url( __FILE__ ) . 'css/wpcdt_style_admin.css', array(), '1.0' );
        wp_enqueue_style( 'wpcdt_register_adminstyle_css' );
    }
    
}


if ( ! function_exists( 'wp_content_countdown_add' ) ) {

add_action( 'admin_menu', 'wp_content_countdown_add' );
function wp_content_countdown_add() {
    add_submenu_page('desparza-menu', 'Content countdown', 'Content countdown', 'manage_options', 'wp-content-countdown-settings', 'wpcdt_how_to_use' );
}

function wpcdt_how_to_use(){ 
    echo '
    <div class="wrap">
        <h2>Content countdown, ¿Como usar el shortcode?</h2>
        <ul>
            <li>[wpcdt day="00" month="00" year="0000"] contenido [/wpcdt] // Configruación por defecto.</li>
            <li>[wpcdt day="00" month="00" year="0000" before="texto"] contenido [/wpcdt] // Cambiando el texto antes de los dias.</li>
            <li>[wpcdt day="00" month="00" year="0000" after="texto"] contenido [/wpcdt] // Cambiando el texto después de los dias.</li>
            <li>[wpcdt day="00" month="00" year="0000" class="nombre de la clase"] contenido [/wpcdt] // Agrgando una clase para cambiar los estilos CSS.</li>
            <li>[wpcdt day="00" month="00" year="0000" class="nombre de la clase" color="alert-secondary"] contenido [/wpcdt]  // Cambiando el color de la alerta. <br> Lista completa de colores: <a href="https://getbootstrap.com/docs/4.3/components/alerts/" target="_blank">Bootstrap Alerts</a></li>
        </ul>
    </div>';
}

// Add Style
add_action('wp_enqueue_scripts', 'wpcdt_style');
function wpcdt_style() {
    wp_register_style('wpcdt_css', plugin_dir_url( __FILE__ ) . 'css/wpcdt_style.css', array(), '4.3.0');
    wp_enqueue_style('wpcdt_css');
    //scripts
    wp_register_script('wpcdt_bootstrap_script', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js' , array(), '4.3.0', true);
    wp_enqueue_script('wpcdt_bootstrap_script');
}

// Add Shortcode
add_shortcode('wpcdt', 'wp_content_countdown_function');
function wp_content_countdown_function($atts, $content = null){
ob_start();	
		
    extract(shortcode_atts(array(
		'day' => '',
        'month' => '',
        'year' => '',
        'class' => 'wpcdt',
        'color' => 'alert-secondary',
        'before' => 'Solo faltan',
        'after' => 'días para mostrar el contenido...'
    ), $atts));
	$remain = ((mktime( 0,0,0,(int)$month,(int)$day,(int)$year) - time())/86400);
    $days = ceil((mktime( 0,0,0,(int)$month,(int)$day,(int)$year) - time())/86400);
    
    if( $remain >= 1 ){
        return $daysremain = 
           '<div class="'. $class .' alert '. $color .' alert-dismissible fade show" role="alert">
              <span>'. $before .' <strong>('. $days .')</strong> '. $after .'</span>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
     }elseif($remain <= 1 && $remain >= 0.1 ){
        return $daysremain = 
            '<div class="'. $class .' alert alert-success alert-dismissible fade show" role="alert">
              <span>'. $before .' <strong>('. $days .')</strong> '. $after .'</span>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
    }else{
        return $content;
    }

$output_string = ob_get_contents();
ob_end_clean();
return $output_string;
}
    
}