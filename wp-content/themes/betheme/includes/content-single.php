<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

// prev & next post -------------------
$single_post_nav = array(
	'hide-header'	=> false,	
	'hide-sticky'	=> false,	
	'in-same-term'	=> false,	
);

$opts_single_post_nav = mfn_opts_get( 'prev-next-nav' );
if( is_array( $opts_single_post_nav ) ){
	
	if( isset( $opts_single_post_nav['hide-header'] ) ){
		$single_post_nav['hide-header'] = true;
	}
	if( isset( $opts_single_post_nav['hide-sticky'] ) ){
		$single_post_nav['hide-sticky'] = true;
	}
	if( isset( $opts_single_post_nav['in-same-term'] ) ){
		$single_post_nav['in-same-term'] = true;
	}
	
}

$post_prev = get_adjacent_post( $single_post_nav['in-same-term'], '', true );
$post_next = get_adjacent_post( $single_post_nav['in-same-term'], '', false );
$blog_page_id = get_option('page_for_posts');

// post classes -----------------------
$classes = array();
if( ! mfn_post_thumbnail( get_the_ID() ) ) $classes[] = 'no-img';
if( get_post_meta(get_the_ID(), 'mfn-post-hide-image', true) ) $classes[] = 'no-img';
if( post_password_required() ) $classes[] = 'no-img';
if( ! mfn_opts_get( 'blog-title' ) ) $classes[] = 'no-title';

$post_next_link="";

if(is_object($post_next)){
	$post_next_link=get_site_url()."/".$post_next->post_name;
}	
?>

<div class="content_wrapper clearfix">
    <div class="sections_group">
        <div class="entry-content" itemprop="mainContentOfPage">
            <div class="section the_content has_content">
                <div class="section_wrapper">
                    <div class="the_content_wrapper">
                        <div class="vc_row wpb_row vc_row-fluid header-seccion pt-lg pb-xs">
                            <div class="wpb_column vc_column_container vc_col-sm-6">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper">
                                        <h6 style="color: #56871f;text-align: left" class="vc_custom_heading font-medium link-volver-biblioteca"><a href="/novedades">Volver a Novedades</a></h6>
                                    </div>
                                </div>
							</div>
							<div class="wpb_column vc_column_container vc_col-sm-6">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper">
										<?php
										if(is_object($post_next)){?>
										<h6 style="color: #56871f;text-align: right" class="vc_custom_heading font-medium link-next-post"><a href="<?=$post_next_link?>">Siguiente</a></h6>
										<?php } ?>
                                    </div>
                                </div>
                            </div>
						</div>
						<div class="vc_row wpb_row vc_row-fluid">
                            <div class="wpb_column vc_column_container vc_col-sm-12">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper shared">
										<a target="_blank" href="mailto:?subject=<?=$post->post_title?>&body=<?=$post->post_excerpt?>. Seguí leyendo la nota haciendo click en el siguiente link: <?=the_permalink()?>"><img class="share-news" src="/wp-content/uploads/2019/05/share-mail.png"></a>
										<a class="btn-twitter" target="_blank" href="https://twitter.com/intent/tweet?url=<?=get_the_permalink()?>"><img class="share-news" src="/wp-content/uploads/2019/05/share-twitter.png"></a>
										<a class="btn-linkedin" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url=<?=get_the_permalink()?>&title=<?=$post->post_title?>&summary=<?=$post->post_title?>&source=<?=get_site_url()?>"><img class="share-news" src="/wp-content/uploads/2019/05/share-linkedin.png"></a>
										<a class="btn-facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?=get_the_permalink()?>"><img class="share-news" src="/wp-content/uploads/2019/05/share-facebook.png"></a>
                                    </div>
                                </div>
							</div>
						</div>
						<div class="vc_row wpb_row vc_row-fluid pt-xs">
                            <div class="wpb_column vc_column_container vc_col-sm-12">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper">
										<h4 class="extra-bold" style="color:#454537"><?=$post->post_title?></h4>
                                    </div>
                                </div>
							</div>
						</div>
						<div class="vc_row wpb_row vc_row-fluid pt-xxs">
                            <div class="wpb_column vc_column_container vc_col-sm-12">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper">
										<p style="color:#979785"><?=$post->post_excerpt?></p>
                                    </div>
                                </div>
							</div>
						</div>
						<?php the_content() ?>
						<div class="vc_row wpb_row vc_row-fluid py-xs">
                            <div class="wpb_column vc_column_container vc_col-sm-12">
                                <div class="vc_column-inner">
									<div class="wpb_wrapper shared">
										<a target="_blank" href="mailto:?subject=<?=$post->post_title?>&body=<?=$post->post_excerpt?>. Seguí leyendo la nota haciendo click en el siguiente link: <?=the_permalink()?>"><img class="share-news" src="/wp-content/uploads/2019/05/share-mail.png"></a>
										<a class="btn-twitter" target="_blank" href="https://twitter.com/intent/tweet?url=<?=get_the_permalink()?>"><img class="share-news" src="/wp-content/uploads/2019/05/share-twitter.png"></a>
										<a class="btn-linkedin" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url=<?=get_the_permalink()?>&title=<?=$post->post_title?>&summary=<?=$post->post_title?>&source=<?=get_site_url()?>"><img class="share-news" src="/wp-content/uploads/2019/05/share-linkedin.png"></a>
										<a class="btn-facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?=get_the_permalink()?>"><img class="share-news" src="/wp-content/uploads/2019/05/share-facebook.png"></a>
                                    </div>
                                </div>
							</div>
						</div>
						<div class="vc_row wpb_row vc_row-fluid">
                            <div class="wpb_column vc_column_container vc_col-sm-6">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper">
                                        <h6 style="color: #56871f;text-align: left" class="vc_custom_heading font-medium link-volver-biblioteca"><a href="/novedades">Volver a Novedades</a></h6>
                                    </div>
                                </div>
							</div>
							<div class="wpb_column vc_column_container vc_col-sm-6">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper">
										<?php
										if(is_object($post_next)){?>
										<h6 style="color: #56871f;text-align: right" class="vc_custom_heading font-medium link-next-post"><a href="<?=$post_next_link?>">Siguiente</a></h6>
										<?php } ?>
                                    </div>
                                </div>
                            </div>
						</div>
						<div class="vc_row wpb_row vc_row-fluid py-xs">
                            <?php echo do_shortcode('[rel_post_news]')?>
						</div>
					</div>	
				</div>	
			</div>	
		</div>
	</div>
</div>
						