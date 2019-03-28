<?php
/*
Plugin Name: Buscador de miembros
Plugin URI: 
Description: Muestra el buscador de miembros
Version: 1.0
Author: Andrés Hernández
Author URI: http://www.colooor.com.ar
*/

if (! defined('ABSPATH')) 
	die('-1');

function miembros_shortcode($atts) 
{
    $members=new WP_Query();
    $countries=new WP_Query();
    $estaments=new WP_Query();

    $m_args = array('post_type' => 'members', 'orderby' => 'title', 'order' => 'asc');
    $c_args = array('post_type' => 'pais', 'orderby' => 'title', 'order' => 'asc');
    $e_args = array('post_type' => 'estamento', 'orderby' => 'title', 'order' => 'asc');
    
    $members->query($m_args);
    $countries->query($c_args);
    $estaments->query($e_args);
    $reports = range(2014, 2019, 1);
    

    echo '
    <div class="row">
        <div class="col-lg-3">';
            if($members->have_posts()):?>
                <select name="members">
                    <option value="" disabled selected>Nombre</option>
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
        <div class="col-lg-3">';
            if($estaments->have_posts()):?>
                <select name="estaments">
                    <option value="" disabled selected>Estamento</option>
                    <?php
                    while($estaments->have_posts()):
                        $estaments->the_post();
                        $estament=get_post();?>
                        <option value="<?=$estament->ID?>"><?=$estament->post_title?></option>
                    <?php                
                    endwhile;
                    ?>
                </select>
            <?php
            endif;
    echo 
       '</div>
        <div class="col-lg-3">';
            if($countries->have_posts()):?>
                <select name="countries">
                    <option value="" disabled selected>País</option>
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
        <div class="col-lg-3">';
            if(!empty($reports)):?>
                <select name="reports">
                    <option value="" disabled selected>Reportes anuales</option>
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
    </div>';
}

add_shortcode( 'buscador_miembros', 'miembros_shortcode' );
?>