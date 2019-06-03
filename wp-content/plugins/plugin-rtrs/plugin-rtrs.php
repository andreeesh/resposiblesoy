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

if (!function_exists('sc_buscador_miembros'))
{
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
}

if (!function_exists('sc_tipos_biblioteca'))
{
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
}

if (!function_exists('sc_buscador_biblioteca'))
{
    function sc_buscador_biblioteca($atts)
    {
        echo '
        <div id="cont_buscador_biblioteca">
            <form id="form-biblioteca">
                <input name="s" type="text" id="buscador_biblioteca" class="font-medium" placeholder="Introduzca el término de búsqueda aquí...">
            </form>
        </div>';
    }
}

if (!function_exists('sc_tabla_biblioteca'))
{
    function sc_tabla_biblioteca($atts)
    {
        echo '<div id="loader-tabla-documentos"></div>';
    }
}

if (!function_exists('sc_news')) 
{
	function sc_news($attr, $content = null)
	{
		$output="";
		$args_news=array(
			'post_type'   => 'post',
			'post_status' => 'publish',
			'orderby'     => 'date',
			'order'       => 'DESC',
			'cat'		  => 4,
			'posts_per_page' => 2);

		$news_query = new WP_Query();
		$news_query->query($args_news);

		$args_agenda=array(
			'post_type'   => 'post',
			'post_status' => 'publish',
			'orderby'     => 'date',
			'order'       => 'DESC',
			'cat'		  => 5,
			'posts_per_page' => 4);

		$agenda_query = new WP_Query();
		$agenda_query->query($args_agenda);

		$output.='
		<div class="wrapper">
		    <div class="container hidden-xs hidden-sm">
		        <div class="row">
		            <div>
		                <div class="col-sm-7 left">
		                    <a href="/novedades" class="link-head-news"><h1 class="celeste header-na pb-sm">Novedades</h1></a>';
		                    if ($news_query->have_posts()) {
		                    	while ($news_query->have_posts()){
		                    		$news_query->the_post();
		                    		$news=get_post();
		                    		$output.='<div class="row row-novedades">';
		                    		
		                    		if(has_post_thumbnail($news->ID)){
  										$image=wp_get_attachment_image_src(get_post_thumbnail_id($news->ID), 'single-post-thumbnail');
  										$output.='<div class="col-sm-6 news-img" style="background-image:url('.$image[0].')"></div>';
									}

									$output.='<div class="col-sm-6">';
									$output.='<div class="news-date">'.get_the_date('', $news->ID).'</div>';	
									$output.='<a class="link-title-news" href="'.get_the_permalink($news->ID).'"><h5 class="news-title">'.wp_trim_words(get_the_title($news->ID), 10, "...").'</h5></a>';
									#$output.='<p class="news-excerpt">'.get_the_excerpt($news->ID).'</p>';		
									$output.='</div>';
									$output.='</div>';
								}
								$output.='<a href="/novedades" class="button-info-home amarillo py-xxs px-xs">Ver más</a>';
		                    }
		      $output.='</div> <!-- end col-sm-7 -->
		                <div class="col-sm-5 right">
		                    <h1 class="blanco header-na pb-sm">Agenda</h1>
		                    <div id="agenda-container">';
			                    if ($agenda_query->have_posts()) {
			                    	while ($agenda_query->have_posts()) {
			                    		$agenda_query->the_post();
			                    		$agenda=get_post();
			                    		$output.='<div class="row-agenda">';		                    		
		  									if(has_post_thumbnail($agenda->ID)){
		  										$image=wp_get_attachment_image_src(get_post_thumbnail_id($agenda->ID), 'single-post-thumbnail');
		  										$output.='<div class="agenda-img" style="background-image:url('.$image[0].')"></div>';
											}
											$output.='<div class="agenda-date">'.get_the_date('', $agenda->ID).'</div>';	
											$output.='<p class="agenda-excerpt">'.get_the_excerpt($agenda->ID).'</p>';		
											$output.='<div class="agenda-sep"></div>';
										$output.='</div>';	                    	}
			                    }
		      	  $output.='</div> 
		      	  			<div id="scrolles-agenda">
			      	  			<div id="scroll-up-agenda">
			      	  				<img src="/wp-content/uploads/2019/01/arrow-up-agenda.png" width="28">
			      	  			</div>
			      	  			<div id="scroll-down-agenda">
			      	  				<img src="/wp-content/uploads/2019/01/arrow-down-agenda.png" width="28">
			      	  			</div>
		      	  			</div>
		      			</div> 
		            </div> 
		        </div> 
		    </div> 
		    <div class="container-fluid hidden-lg hidden-md">
		        <div class="row">
		            <div>
		                <div class="col-sm-7 col-xs-12 left container">
		                    <a href="/novedades" class="link-head-news"><h1 class="celeste header-na py-xs">Novedades</h1></a>';
		                    if ($news_query->have_posts()) {
		                    	while ($news_query->have_posts()){
		                    		$news_query->the_post();
		                    		$news=get_post();
		                    		$output.='<div class="row row-novedades">';
		                    		$output.='<div class="col-sm-6 col-xs-12">';
		                    		
		                    		if(has_post_thumbnail($news->ID)){
  										$image=wp_get_attachment_image_src(get_post_thumbnail_id($news->ID), 'single-post-thumbnail');
  										$output.='<div class="news-img" style="background-image:url('.$image[0].')"></div>';
									}

									$output.='</div>';
									$output.='<div class="col-sm-6 col-xs-12">';
									$output.='<div class="news-date">'.get_the_date('', $news->ID).'</div>';	
									$output.='<a class="link-title-news" href="'.get_the_permalink($news->ID).'"><h5 class="news-title">'.wp_trim_words(get_the_title($news->ID), 10, "...").'</h5></a>';
									#$output.='<p class="news-excerpt">'.get_the_excerpt($news->ID).'</p>';		
									$output.='</div>';
									$output.='</div>';
								}
								$output.='<a href="/novedades" class="button-info-home amarillo py-xxs px-xs">Ver más</a>';
		                    }
		      $output.='</div> 
		                <div class="col-sm-5 col-xs-12 right right-col-agenda container">
		                    <h1 class="blanco header-na pt-xs pb-sm">Agenda</h1>
		                    <div id="agenda-container">';
			                    if ($agenda_query->have_posts()) {
			                    	while ($agenda_query->have_posts()) {
			                    		$agenda_query->the_post();
			                    		$agenda=get_post();
			                    		$output.='<div class="row-agenda">';		                    		
		  									if(has_post_thumbnail($agenda->ID)){
		  										$image=wp_get_attachment_image_src(get_post_thumbnail_id($agenda->ID), 'single-post-thumbnail');
		  										$output.='<div class="agenda-img" style="background-image:url('.$image[0].')"></div>';
											}
											$output.='<div class="agenda-date">'.get_the_date('', $agenda->ID).'</div>';	
											$output.='<p class="agenda-excerpt">'.get_the_excerpt($agenda->ID).'</p>';		
											$output.='<div class="agenda-sep"></div>';
										$output.='</div>';	                    	}
			                    }
		       $output.='</div> 
		      	  			<div id="scrolles-agenda">
			      	  			<div id="scroll-up-agenda">
			      	  				<img src="/wp-content/uploads/2019/01/arrow-up-agenda.png" width="28">
			      	  			</div>
			      	  			<div id="scroll-down-agenda">
			      	  				<img src="/wp-content/uploads/2019/01/arrow-down-agenda.png" width="28">
			      	  			</div>
		      	  			</div>
		      			</div> 
		            </div> 
		        </div> 
		    </div> 
		</div>' ;
		return $output;
	}
}

if (!function_exists('sc_numbers')) 
{
	function sc_numbers($attr, $content = null)
	{
		$output="";
		$args=array(
			'post_type'   => 'numero',
			'post_status' => 'publish',
			'orderby'     => 'order',
			'order'       => 'ASC');

		$numbers_query = new WP_Query();
		$numbers_query->query($args);

		if ($numbers_query->have_posts()) {
			while ($numbers_query->have_posts()) {
				$numbers_query->the_post();
				$numbers=get_post();
				$title=$numbers->post_title;
				$valor_uno=get_post_custom_values('wpcf-item-uno-valor', $numbers->ID);
				$bajada_uno=get_post_custom_values('wpcf-item-uno-bajada', $numbers->ID);
				$valor_dos=get_post_custom_values('wpcf-item-dos-valor', $numbers->ID);
				$bajada_dos=get_post_custom_values('wpcf-item-dos-bajada', $numbers->ID);
				$valor_tres=get_post_custom_values('wpcf-item-tres-valor', $numbers->ID);
				$bajada_tres=get_post_custom_values('wpcf-item-tres-bajada', $numbers->ID);
			}
		}

		$output.='<div class="vc_column-inner">
			<div class="wpb_wrapper">
				<h1 class="vc_custom_heading pb-sm" id="title-numbers">'.$title.'</h1>
				<div class="vc_row wpb_row vc_inner vc_row-fluid">
					<div class="wpb_column vc_column_container vc_col-sm-4">
						<div class="vc_column-inner">
							<div class="wpb_wrapper">
								<h1 class="vc_custom_heading valor-numbers pb-xxs">'.$valor_uno[0].'</h1>
								<h4 class="vc_custom_heading bajada-numbers pb-xxs">'.$bajada_uno[0].'</h4>
							</div>
						</div>
					</div>
					<div class="wpb_column vc_column_container vc_col-sm-4">
						<div class="vc_column-inner">
							<div class="wpb_wrapper">
								<h1 class="vc_custom_heading valor-numbers pb-xxs">'.$valor_dos[0].'</h1>
								<h4 class="vc_custom_heading bajada-numbers pb-xxs">'.$bajada_dos[0].'</h4>
							</div>
						</div>
					</div>
					<div class="wpb_column vc_column_container vc_col-sm-4">
						<div class="vc_column-inner">
							<div class="wpb_wrapper">
								<h1 class="vc_custom_heading valor-numbers pb-xxs">'.$valor_tres[0].'</h1>
								<h4 class="vc_custom_heading bajada-numbers pb-xxs">'.$bajada_tres[0].'</h4>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
		return $output;
	}
}

if (!function_exists('sc_line_years')) 
{
    function sc_line_years($attr, $content = null)
    {
		$output = '<ul class="list-unstyled">
		<li>
			<a href="#2006" class="ps2id line-history">
				<img src="http://responsiblesoy.loc/wp-content/uploads/2019/05/line-hor-history.png">
				<span class="hidden year-history">2006</span>
			</a>
		</li>
		<li>
			<a href="#2010" class="ps2id line-history">
				<img src="http://responsiblesoy.loc/wp-content/uploads/2019/05/line-hor-history.png">
				<span class="hidden year-history">2010</span>
			</a>
		</li>
		<li>
			<a href="#2011" class="ps2id line-history">
				<img src="http://responsiblesoy.loc/wp-content/uploads/2019/05/line-hor-history.png">
				<span class="hidden year-history">2011</span>
			</a>
		</li>
		<li>
			<a href="#2012" class="ps2id line-history">
				<img src="http://responsiblesoy.loc/wp-content/uploads/2019/05/line-hor-history.png">
				<span class="hidden year-history">2012</span>
			</a>
		</li>
		<li>
			<a href="#2013" class="ps2id line-history">
				<img src="http://responsiblesoy.loc/wp-content/uploads/2019/05/line-hor-history.png">
				<span class="hidden year-history">2013</span>
			</a>
		</li>
		<li>
			<a href="#2015" class="ps2id line-history">
				<img src="http://responsiblesoy.loc/wp-content/uploads/2019/05/line-hor-history.png">
				<span class="hidden year-history">2015</span>
			</a>
		</li>
		<li>
			<a href="#2017" class="ps2id line-history">
				<img src="http://responsiblesoy.loc/wp-content/uploads/2019/05/line-hor-history.png">
				<span class="hidden year-history">2017</span>
			</a>
		</li>
		<li>
			<a href="#2018" class="ps2id line-history">
				<img src="http://responsiblesoy.loc/wp-content/uploads/2019/05/line-hor-history.png">
				<span class="hidden year-history">2018</span>
			</a>
		</li>
		</ul>';
		return $output;
	}
}

if (!function_exists('sc_latest_news'))
{
    function sc_latest_news($atts)
    {
        $news = new WP_Query();
        $n_args=array('post_type'=>'post','post_status'=>'publish','orderby'=>'date','order'=>'DESC','cat'=>4,'posts_per_page'=>$atts['limit'],'offset'=>$atts['offset']);
        $news->query($n_args);
		if ($news->have_posts()):
			$x=0;
			$y=0;
            while ($news->have_posts()):
                $news->the_post();
				$new=get_post();
				if($atts['display']=='big')
				{
					echo get_the_post_thumbnail($new->ID, 'full');
					echo '<h5 class="extra-bold pt-xs"><a href="/'.$new->post_name.'">'.$new->post_title.'</a></h5>';
					echo '<div class="date-post font-semibold py-xxs">'.get_the_date().'</div>';
					echo '<div class="post-excerpt font-medium pt-xxs pb-sm">'.get_the_excerpt().'</div>';
					echo '<a href="/'.$new->post_name.'" class="ver-mas-post">Ver más +</a>';
				}
				if($atts['display']=='vertical')
				{
					if($x<2): 
						$separator="sep-bottom"; 
						$padding="pb-xxs";
					else:
						$separator="";
						$padding="";
					endif;

					if($x>0): 
						$margin="mt-xxs pt-na"; 
					else:
						$margin="";
					endif;

					echo '<div class="row-noticia-vertical '.$separator.' '.$margin.' '.$padding.'">';
					echo '<div class="img-noticia-vertical mr-xs">';
					echo get_the_post_thumbnail($new->ID, 'thumbnail');
					echo '</div>';
					echo '<div class="date-post font-semibold">'.get_the_date().'</div>';
					echo '<h6 class="extra-bold py-xxs"><a href="/'.$new->post_name.'">'.$new->post_title.'</a></h6>';
					echo '<a href="/'.$new->post_name.'" class="ver-mas-post">Ver más +</a>';
					echo '</div>';
					$x++;
				}
            endwhile;
        endif;
    }
}

if (!function_exists('sc_post_date'))
{
	function sc_post_date($atts)
    {
		echo '<div class="date-post font-semibold py-xxs">'.get_the_date().'</div>';
	}
}

if (!function_exists('sc_rel_post_news'))
{
	function sc_rel_post_news($atts)
	{
		$rel_news = new WP_Query();
        $rn_args=array('post_type'=>'post','post_status'=>'publish','orderby'=>'date','order'=>'DESC','cat'=>4,'posts_per_page'=>3);
		$rel_news->query($rn_args);
		if ($rel_news->have_posts()):
			$y=0;
            while ($rel_news->have_posts()):
                $rel_news->the_post();
				$rel_new=get_post();
				if($y==0): 
					$separator="sep-right"; 
					$padding="pr-xxs";
				endif;
				
				if($y==1): 
					$separator="sep-right"; 
					$padding="pr-xxs pl-xxs";
				endif;
				
				if($y==2): 
					$separator=""; 
					$padding="pl-xxs";
				endif;
				
				echo '
				<div class="wpb_column vc_column_container vc_col-xs-12 vc_col-sm-4 vc_col-md-4 col-news-horizontal '.$padding.' '.$separator.'">
				<div class="vc_column-inner">
				<div class="wpb_wrapper">';
				echo get_the_post_thumbnail($rel_new->ID, 'full');
				echo '<div class="date-post font-semibold">'.get_the_date().'</div>';
				echo '<h6 class="extra-bold py-xs"><a href="/'.$rel_new->post_name.'">'.$rel_new->post_title.'</a></h6>';
				echo '<span class="a-bottom"><a href="/'.$rel_new->post_name.'" class="ver-mas-post">Ver más +</a></span>';
				echo '</div></div></div>';
				$y++;
			endwhile;
		endif;
	}
}

if (!function_exists('sc_certified_producers'))
{
	function sc_certified_producers()
	{
		$url = "http://platform.responsiblesoy.org/reportsinfo/";
		$connectionInfo = array("Database"=>"SA_DWH_CHAINPOINT_RTRS", "UID"=>"public_website_user", "PWD"=>"P&34ha^TGH4er6DC", "ReturnDatesAsStrings" => true);
		$connection = sqlsrv_connect('CHAIN-SQL01-W2K8\mssqlreports', $connectionInfo);
		var_dump($connection);
	}
}

add_shortcode('buscador_miembros', 'sc_buscador_miembros');
add_shortcode('tabla_biblioteca', 'sc_tabla_biblioteca');
add_shortcode('tipos_biblioteca', 'sc_tipos_biblioteca');
add_shortcode('buscador_biblioteca', 'sc_buscador_biblioteca');
add_shortcode('numbers', 'sc_numbers');
add_shortcode('news', 'sc_news');
add_shortcode('line_years', 'sc_line_years');
add_shortcode('latest_news', 'sc_latest_news');
add_shortcode('post_date', 'sc_post_date');
add_shortcode('rel_post_news', 'sc_rel_post_news');
add_shortcode('certified_producers', 'sc_certified_producers');
?>