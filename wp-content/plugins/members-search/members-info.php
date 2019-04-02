<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
$members=new WP_Query();
$reports=new WP_Query();
$meta_query_array=array();

# Todos los miembros
$m_args = array('post_type' => 'members', 'orderby' => 'title', 'order' => 'asc');

if(!empty($_GET['members'])):
    $m_args = array('post_type' => 'members', 'orderby' => 'title', 'order' => 'asc', 'p' => $_GET['members']);
else:   
    if(!empty($_GET['states']))
        $meta_query_array[]=array(array('key' => '_wpcf_belongs_states_id', 'value' => $_GET['states']));
    
    if(!empty($_GET['countries']))
        $meta_query_array[]=array(array('key' => '_wpcf_belongs_countries_id', 'value' => $_GET['countries']));

    $m_args = array('post_type' => 'members', 'order' => 'asc', 'meta_query' => $meta_query_array);
endif;
$members->query($m_args);
$x=1;
if($members->have_posts()):
    if($x==1):?>
    <div class="vc_row wpb_row vc_inner vc_row-fluid">
    <?php
    endif;
    while($members->have_posts()):
        $members->the_post();
        $member=get_post();
        $member_logo=get_post_custom_values('wpcf-logo', $member->ID);
        $member_logo=$member_logo[0];

        $r_args = array('post_type' => 'reportes', 'meta_query' => array(array('key' => '_wpcf_belongs_members_id', 'value' => $member->ID)));

        if(!empty($_GET['reports'])):
            $r_args = array('post_type' => 'reportes', 'meta_query' => array(array('key' => '_wpcf_belongs_members_id', 'value' => $member->ID)));
        endif;

        $reports->query($r_args);
        ?>
        <div class="wpb_column vc_column_container vc_col-lg-3">
            <div class="vc_column-inner">
                <div class="wpb_wrapper text-center">
                    <img class="logo-members-white" src="<?=$member_logo?>" onclick="showMemberInfo(this);">
                </div>
                <div class="wpb_wrapper text-center">
                    <p>
                    <?php
                    if($reports->have_posts()):
                        while($reports->have_posts()):
                            $reports->the_post();
                            $report=get_post();
                            echo $report->ID;
                        endwhile;
                    endif;
                    ?>
                    </p>
                </div>
            </div>
        </div>
        <?php
    endwhile;
    $x++;
    if($x==4):?>
    </div>
    <?php
    endif;
endif;