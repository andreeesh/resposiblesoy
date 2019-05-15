<?php
/*
Plugin Name: Plugin RTRS
Plugin URI: 
Description: Contenidos personalizados para RTRS
Version: 1.0
Author: Andrés Hernández
Author URI: http://www.colooor.com.ar
*/

if (! defined('ABSPATH')) 
    die('-1');
    
add_action('wp_enqueue_scripts', 'plugin_rtrs_init');

function plugin_rtrs_init()
{
    wp_enqueue_script('js', plugins_url('app.js', __FILE__));
}

function sc_buscador_miembros($atts) 
{
    $members=new WP_Query();
    $countries=new WP_Query();
    $states=new WP_Query();

    $m_args = array('post_type' => 'members', 'orderby' => 'title', 'order' => 'asc');
    $c_args = array('post_type' => 'countries', 'orderby' => 'title', 'order' => 'asc');
    $s_args = array('post_type' => 'states', 'orderby' => 'title', 'order' => 'asc');
    
    $members->query($m_args);
    $countries->query($c_args);
    $states->query($s_args);
    $reports = range(2014, 2019, 1);
    
    echo '
    <div class="row members-filters pb-xxs">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">';
            if($members->have_posts()):?>
                <select name="members" id="members" class="select-members">
                    <option value="" selected>Nombre</option>
                    <?php
                    while($members->have_posts()):
                        $members->the_post();
                        $member=get_post();?>
                        <option value="<?=$member->ID?>"><?=$member->post_title?></option>
                    <?php                
                    endwhile;
                    ?>
                </select>
            <?php
            endif;
    echo 
       '</div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">';
            if($states->have_posts()):?>
                <select name="states" id="states" class="select-members">
                    <option value="" selected>Estamento</option>
                    <?php
                    while($states->have_posts()):
                        $states->the_post();
                        $state=get_post();?>
                        <option value="<?=$state->ID?>"><?=$state->post_title?></option>
                    <?php                
                    endwhile;
                    ?>
                </select>
            <?php
            endif;
    echo 
       '</div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">';
            if($countries->have_posts()):?>
                <select name="countries" id="countries" class="select-members">
                    <option value="" selected>País</option>
                    <?php
                    while($countries->have_posts()):
                        $countries->the_post();
                        $country=get_post();?>
                        <option value="<?=$country->ID?>"><?=$country->post_title?></option>
                    <?php                
                    endwhile;
                    ?>
                </select>
            <?php
            endif;
    echo 
       '</div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">';
            if(!empty($reports)):?>
                <select name="reports" id="reports" class="select-members">
                    <option value="" selected>Reportes anuales</option>
                    <?php
                    foreach($reports as $year):?>
                        <option value="<?=$year?>"><?=$year?></option>
                    <?php                
                    endforeach;
                    ?>
                </select>
            <?php
            endif;
    echo 
        '</div>
    </div>
    <div id="ajax-loader"></div>';
}

function sc_tipos_biblioteca($atts)
{
    $types=new WP_Query();
    $t_args=array('post_type'=>'tipos','orderby'=>'title','order'=>'asc');
    $types->query($t_args);
    echo '<div id="cont_tipo_cerrado"><div id="tipo_cerrado" class="font-medium">Tipo</div></div>';
    echo '
    <div id="cont_tipo_abierto" class="hidden">
        <div id="tipo_abierto" class="font-medium">Tipo</div>';
        echo '<a href="#" class="link-tipos font-medium" id="tipotodos">Todos los documentos</a>';
        if($types->have_posts()):
            while($types->have_posts()):
                $types->the_post();
                $type=get_post();
                echo '<a href="#" class="link-tipos font-medium" id="tipo'.$type->ID.'">'.$type->post_title.'</a>';
            endwhile;
        endif;
    echo '
    </div>';
}

function sc_buscador_biblioteca($atts)
{
    echo '
    <div id="cont_buscador_biblioteca">
        <form id="form-biblioteca">
            <input name="s" type="text" id="buscador_biblioteca" class="font-medium" placeholder="Introduzca el término de búsqueda aquí...">
        </form>
    </div>';
}

function sc_tabla_biblioteca($atts)
{
    echo '<div id="loader-tabla-documentos"></div>';
}

function sc_titulo_documento($atts)
{
    $docs=new WP_Query();
    $d_args=array('post_type'=>'documentos','orderby'=>'title','order'=>'asc','p'=>$_GET['d']);
    $docs->query($d_args);
    while($docs->have_posts()):
        $docs->the_post();
        $doc=get_post();
        echo '<h4 class="extra-bold">'.$doc->post_title.'</h4>';
    endwhile;
}

function sc_imagen_documento($atts){
    echo "imagen documento";
}

function sc_info_documento($atts){
    echo "info documento";
}


add_shortcode('buscador_miembros', 'sc_buscador_miembros');
add_shortcode('tabla_biblioteca', 'sc_tabla_biblioteca');
add_shortcode('tipos_biblioteca', 'sc_tipos_biblioteca');
add_shortcode('buscador_biblioteca', 'sc_buscador_biblioteca');
add_shortcode('titulo_documento', 'sc_titulo_documento');
add_shortcode('imagen_documento', 'sc_imagen_documento');
add_shortcode('info_documento', 'sc_info_documento');
?>