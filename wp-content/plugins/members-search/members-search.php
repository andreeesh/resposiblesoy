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
    
add_action('wp_enqueue_scripts', 'members_search_init');

function members_search_init(){
    wp_enqueue_script('members-search-js', plugins_url('app.js', __FILE__));
}

function miembros_shortcode($atts) 
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

add_shortcode( 'buscador_miembros', 'miembros_shortcode' );
?>