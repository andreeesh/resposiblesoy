<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
$members=new WP_Query();
$countries=new WP_Query();
$states=new WP_Query();
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

$members->query($m_args);?>

<?php
$x=0;
if($members->have_posts()):?>
    <div class="vc_tta-container" data-vc-action="collapse">
        <div class="vc_general vc_tta vc_tta-tabs vc_tta-color-white vc_tta-style-flat vc_tta-shape-square vc_tta-spacing-1 vc_tta-tabs-position-top vc_tta-controls-align-left">
            <div class="vc_tta-tabs-container">
                <?php
                while($members->have_posts()):
                    $members->the_post();
                    $member=get_post();
                    $member_logo=get_post_custom_values('wpcf-logo', $member->ID);?>
                    <div class="vc_col-lg-3 vc_col-md-3 vc_col-sm-3 vc_col-xs-3 link-member-tab" data-vc-tab="">
                        <a href="#panel-member-<?=$member->post_name?>" data-vc-tabs="" data-vc-container=".vc_tta">
                            <img src="<?=$member_logo[0]?>">
                        </a>
                    </div>
                    <?php
                endwhile;?>
            </div>
            <div class="vc_tta-panels-container">
                <div class="vc_tta-panels">
                <?php
                    while($members->have_posts()):
                        $members->the_post();
                        $member=get_post();
                        $member_website=get_post_custom_values('wpcf-sitio-web', $member->ID);
                        $member_about=get_post_custom_values('wpcf-sobre-la-empresa', $member->ID);
                        # Get the member country
                        $country_id = get_post_meta(get_the_ID(), '_wpcf_belongs_countries_id', true);
                        $country_name = get_the_title($country_id);
                        # Get the member state
                        $state_id = get_post_meta( get_the_ID(), '_wpcf_belongs_states_id', true);
                        $state_name = get_the_title($state_id);
                        # Get member's reports
                        $r_args = array('post_type' => 'reports', 'meta_query' => array(array('key' => '_wpcf_belongs_members_id', 'value' => $member->ID)));
                        $reports->query($r_args);
                        ?>
                        <div class="vc_tta-panel" id="panel-member-<?=$member->post_name?>" data-vc-content=".vc_tta-panel-body">
                            <div class="vc_tta-panel-heading">
                                <h4 class="vc_tta-panel-title">
                                    <a href="#panel-member-<?=$member->post_name?>" data-vc-accordion="" data-vc-container=".vc_tta-container"></a>
                                </h4>
                            </div>
                            <div class="vc_tta-panel-body members-info-body">
                                <div class="wpb_text_column wpb_content_element ">
                                    <div class="wpb_wrapper">
                                        <div class="item-members-info">Sitio web: <?=$member_website[0]?></div>
                                        <div class="item-members-info">País: <?=$country_name?></div>
                                        <div class="item-members-info">Estamento: <?=$state_name?></div>
                                        <div class="item-members-info members-about"><?=$member_about[0]?></div>
                                        <?php
                                        if($reports->have_posts()):
                                            while($reports->have_posts()):
                                                $reports->the_post();
                                                $report=get_post();
                                                $report_title=get_post_custom_values('wpcf-titulo-reporte', $report->ID);
                                                $report_file=get_post_custom_values('wpcf-archivo', $report->ID);?>
                                                <div class="item-members-info">Archivos: 
                                                    <a target="_blank" href="<?=$report_file[0]?>"><?=$report_title[0]?></a>
                                                </div>
                                            <?php
                                            endwhile;
                                        endif;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;?>
                </div>
            </div>
        </div>
    </div>
    <?php
endif;