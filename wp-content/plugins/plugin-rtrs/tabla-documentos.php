<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
$docs=new WP_Query();
$d_args=array('post_type'=>'documentos','orderby'=>'title','order'=>'asc');

if(!empty($_GET['t']) && $_GET['t'] != "todos"):
    $meta_query_array[]=array(array('key'=>'_wpcf_belongs_tipos_id','value' => $_GET['t']));
    $d_args = array('post_type'=>'documentos','order'=>'asc','meta_query'=>$meta_query_array);
endif;

if(!empty($_GET['s'])):
    $d_args = array('post_type'=>'documentos','order'=>'asc','s'=>$_GET['s']);
endif;

$docs->query($d_args);

echo '
<table id="tabla-documentos">
    <thead>
        <tr>
            <th class="font-medium">Tipo</th>
            <th class="font-medium">Documento</th>
            <th class="font-medium">Doc<br>Relacionado</th>
            <th class="font-medium">Referencia</th>
            <th class="font-medium">Para quién aplica</th>
            <th class="font-medium" width="150px">Más info</th>
        </tr>
    </thead>
    <tbody>
        <tr>';
        if($docs->have_posts()):
            while($docs->have_posts()):
                $docs->the_post();
                $doc=get_post();
                $type_name = get_the_title(get_post_meta(get_the_ID(), '_wpcf_belongs_tipos_id', true));
                $doc_rel=get_post_custom_values('wpcf-documento-relacionado', $doc->ID)[0];
                $referencia=get_post_custom_values('wpcf-referencia', $doc->ID)[0];
                $aplica=get_post_custom_values('wpcf-aplica', $doc->ID)[0];
                echo '<tr>';
                echo '<td class="font-medium">'.$type_name.'</td>';
                echo '<td class="font-medium">'.$doc->post_title.'</td>';
                echo '<td class="font-medium">'.$doc_rel.'</td>';
                echo '<td class="font-medium">'.$referencia.'</td>';
                echo '<td class="font-medium">'.$aplica.'</td>';
                echo '<td class="font-medium"><a href="/documento?p='.$doc->ID.'">Ver más</a></td>';
                echo '</tr>';
            endwhile;
        endif;
        echo '
        </tr>
    </tbody>
</table>';