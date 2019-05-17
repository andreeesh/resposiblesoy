<?php
$doc_img=get_post_custom_values('wpcf-imagen', $post->ID)[0];
$lang=ICL_LANGUAGE_CODE;
$title_ultima_version="Última versión aprobada";
$title_proxima_version="Próxima versión";
$title_traducciones="Traducciones disponibles";
$title_versiones="Versiones anteriores";
$title_descripcion="Descripción";
$title_descargar="Descargar";

$val_ultima_version=get_post_custom_values('wpcf-ultima-version', $post->ID)[0];
$val_proxima_version=get_post_custom_values('wpcf-proxima-version', $post->ID)[0];
$val_traducciones=get_post_custom_values('wpcf-traducciones', $post->ID)[0];
$val_versiones=get_post_custom_values('wpcf-versiones-anteriores', $post->ID)[0];
$val_descripcion=get_post_custom_values('wpcf-descripcion', $post->ID)[0];
$val_descargar=get_post_custom_values('wpcf-archivo-descargable', $post->ID)[0];

#Traducciones
if($lang="en")
{
    $title_ultima_version="Última versión aprobada";
    $title_proxima_version="Próxima versión";
    $title_traducciones="Traducciones disponibles";
    $title_versiones="Versiones anteriores";
    $title_descripcion="Descripción";
    $title_descargar="Descargar";
}

if($lang="po")
{
    $title_ultima_version="Última versión aprobada";
    $title_proxima_version="Próxima versión";
    $title_traducciones="Traducciones disponibles";
    $title_versiones="Versiones anteriores";
    $title_descripcion="Descripción";
    $title_descargar="Descargar";
}

echo '
<div class="content_wrapper clearfix">
    <div class="sections_group">
        <div class="entry-content" itemprop="mainContentOfPage">
            <div class="section the_content has_content">
                <div class="section_wrapper">
                    <div class="the_content_wrapper">
                        <div class="vc_row wpb_row vc_row-fluid header-seccion pt-lg pb-xs">
                            <div class="wpb_column vc_column_container vc_col-sm-12">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper">
                                        <h6 style="color: #56871f;text-align: left" class="vc_custom_heading font-medium link-volver-biblioteca"><a href="/biblioteca">Volver a Biblioteca</a></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="vc_row wpb_row vc_row-fluid">
                        <div class="wpb_column vc_column_container vc_col-sm-6">
                            <div class="vc_column-inner">
                                <div class="wpb_wrapper">
                                    <h4 class="extra-bold">'.$post->post_title.'</h4>
                                    <div class="wpb_text_column wpb_content_element">
                                        <div class="wpb_wrapper">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="vc_row wpb_row vc_row-fluid pt-xs">
                    <div class="wpb_column vc_column_container vc_col-sm-4">
                        <div class="vc_column-inner">
                            <div class="wpb_wrapper">
                                <div class="wpb_single_image wpb_content_element vc_align_left">
                                    <figure class="wpb_wrapper vc_figure">
                                        <a href="'.$doc_img.'" target="_self" class="vc_single_image-wrapper vc_box_border_grey" rel="lightbox[rel-'.$post->ID.']" data-type="gallery">
                                            <img src="'.$doc_img.'" class="vc_single_image-img attachment-thumbnail" alt=""></a>
		                            </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpb_column vc_column_container vc_col-sm-8">
                        <div class="vc_column-inner">
                            <div class="wpb_wrapper">
                            <table id="table-doc-ampliado">
                                <tbody>
                                    <tr><td>'.$title_ultima_version.'</td><td>'.$val_ultima_version.'</td></tr>
                                    <tr><td>'.$title_proxima_version.'</td><td>'.$val_proxima_version.'</td></tr>
                                    <tr><td>'.$title_traducciones.'</td><td>'.$val_traducciones.'</td></tr>
                                    <tr><td>'.$title_versiones.'</td><td>'.$val_versiones.'</td></tr>
                                    <tr class="tr-desc"><td>'.$title_descripcion.'</td><td>'.$val_descripcion.'</td></tr>
                                </tbody>
                            </table>
                            <a class="link-down-doc" href="'.$val_descargar.'" target="_blank">
                                <div class="down-documento text-right pt-xs font-medium">
                                    <img class="icon-down-documento" src="/wp-content/uploads/2019/05/down-documento.png">
                                    '.$title_descargar.'
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div></div></div>				
        <div class="section section-page-footer">
            <div class="section_wrapper clearfix">
                <div class="column one page-pager"></div>
            </div>
        </div>
    </div>  
</div>
</div>';