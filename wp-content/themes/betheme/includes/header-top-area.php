<?php $translate['wpml-no'] = mfn_opts_get('translate') ? mfn_opts_get('translate-wpml-no','No translations available for this page') : __('No translations available for this page','betheme'); ?>

<?php if( mfn_opts_get('action-bar')): ?>
	<div id="Action_bar">
		<div class="container">
			<div class="column one">

				<ul class="contact_details">
					<?php
						if( $header_slogan = mfn_opts_get( 'header-slogan' ) ){
							echo '<li class="slogan">'. $header_slogan .'</li>';
						}
						if( $header_phone = mfn_opts_get( 'header-phone' ) ){
							echo '<li class="phone"><i class="icon-phone"></i><a href="tel:'. str_replace(' ', '', $header_phone) .'">'. $header_phone .'</a></li>';
						}
						if( $header_phone_2 = mfn_opts_get( 'header-phone-2' ) ){
							echo '<li class="phone"><i class="icon-phone"></i><a href="tel:'. str_replace(' ', '', $header_phone_2) .'">'. $header_phone_2 .'</a></li>';
						}
						if( $header_email = mfn_opts_get( 'header-email' ) ){
							echo '<li class="mail"><i class="icon-mail-line"></i><a href="mailto:'. $header_email .'">'. $header_email .'</a></li>';
						}
					?>
				</ul>

				<?php
					if( has_nav_menu( 'social-menu' ) ){
						mfn_wp_social_menu();
					} else {
						get_template_part( 'includes/include', 'social' );
					}
				?>

			</div>
		</div>
	</div>
<?php endif; ?>

<?php
	if( mfn_header_style( true ) == 'header-overlay' ){

		// Overlay ----------
		echo '<div id="Overlay">';
			mfn_wp_overlay_menu();
		echo '</div>';

		// Button ----------
		echo '<a class="overlay-menu-toggle" href="#">';
			echo '<i class="open icon-menu-fine"></i>';
			echo '<i class="close icon-cancel-fine"></i>';
		echo '</a>';

	}
?>

<?php
function printMegaMenu(){
echo '
<div class="menu-container">
  <div class="menu">
    <ul>
      <li>
      	<a href="#">Sobre la RTRS</a>
        <ul>
        	<li>
	        	<div class="col-md-4">
					<a href="#"><h2 class="h-mm">Qué es la RTRS</h2></a>
					<a href="#" class="mm-link">Misión y objetivos</a>
					<a href="#" class="mm-link">Nuestra historia</a>
					<div class="mm-sep"></div>
					<a href="#"><h2 class="h-mm">Nuestra gestión</h2></a>
					<a href="#" class="mm-link">Informes de gestión</a>
					<a href="#" class="mm-link">Reportes financieros</a>
				</div>
				<div class="col-md-4">
					<a href="#"><h2 class="h-mm">Quiénes somos</h2></a>
					<a class="mm-link" href="#">Estructura de Gobierno</a>
					<a class="mm-link" href="#">Comité Ejecutivo</a>
					<a class="mm-link" href="#">Secretariado Ejecutivo</a>
					<a class="mm-link" href="#">Task Forces</a>
				</div>
				<div class="col-md-4">
					<img class="img-responsive" src="/wp-content/uploads/2019/01/foto-mega-menu.jpg">
				</div>
			</li>
		</ul>
      </li>
      <li>
      	<a href="#">Miembros</a>
      </li>
      <li>
      	<a href="#">Productores</a>
      </li>
      <li>
      	<a href="#">Cómo comprar</a>
      </li>
      <li>
      	<a href="#">Certificación</a>
      </li>
      <li>
      	<a href="#">Conferencia</a>
      </li>
      <li>
      	<a href="#">Contacto</a>
      </li>
    </ul>
  </div>
</div>';
}

?>

<div id="rs-encuentra" class="hidden">
	<div id="cross-encuentra">
		<a href="#"><img src="/wp-content/uploads/2019/01/X.png"></a>
	</div>
	<div id="rs-menu-encuentra">
		<h1 class="blanco">Encuentre</h1>
		<nav class="navbar navbar-default">
		  <div class="container-fluid">
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav">
		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Cómo certificarse <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="#">Acción Uno</a></li>
		            <li><a href="#">Acción Dos</a></li>
		            <li><a href="#">Acción Tres</a></li>
		          </ul>
		        </li>
		        <li><a href="#">Productores</a></li>
		        <li><a href="#">Miembros</a></li>
		        <li><a href="#">Material certificado</a></li>
		        <li><a href="#">Información del mercado</a></li>
		      </ul>
		    </div>
		  </div>
	</div>
</div>
<?php
$tags=get_tags(array('hide_empty'=>false));
?>
<div id="rs-buscador" class="hidden">
	<div id="cross-buscador">
		<a href="#"><img src="/wp-content/uploads/2019/01/X.png"></a>
	</div>
	<div id="rs-form-buscador">
		<form id="form-buscador" action="/" method="GET">
			<input type="text" name="s" id="rs-input-search" placeholder="Buscar">
		</form>
		<div id="rs-explore-tags">
			<?php
			foreach($tags as $tag){
				echo '<a href="'.get_tag_link($tag->term_id).'">#'.$tag->name.', </a>';
			}
			?>
		</div>
	</div>
</div>

<!-- .header_placeholder 4sticky  -->
<div class="header_placeholder"></div>

<div id="Top_bar" class="loading">

	<div class="container">

		<div class="column one">

			<div class="top_bar_left clearfix">

				<!-- Logo -->
				<?php get_template_part( 'includes/include', 'logo' ); ?>

				<div class="menu_wrapper">

					<?php
						if( ( mfn_header_style( true ) != 'header-overlay' ) && ( mfn_opts_get( 'menu-style' ) != 'hide' ) ){

							// #menu --------------------------
							if( in_array( mfn_header_style(), array( 'header-split', 'header-split header-semi', 'header-below header-split' ) ) ){
								mfn_wp_split_menu();
							} else {
								echo '
								<div id="rs-top-menu">
									<div id="rs-top-menu-left">
										<a>Biblioteca</a> <span class="sep">|</span>
										<a>Acceda a la plataforma</a> <span class="sep">|</span>
										<a>Información del mercado</a>
									</div>
									<div id="rs-top-menu-right">
										<a id="rs-btn-encuentre">ENCUENTRE</a>
										<a id="rs-lang-menu">ES</a>
										<div id="rs-search"></div>
									</div>
								</div>';
								printMegaMenu();
								#mfn_wp_nav_menu();
							}

							// responsive menu button ---------
							$mb_class = '';
							if( mfn_opts_get('header-menu-mobile-sticky') ) $mb_class .= ' is-sticky';

							echo '<a class="responsive-menu-toggle '. $mb_class .'" href="#">';
								if( $menu_text = trim( mfn_opts_get( 'header-menu-text' ) ) ){
									echo '<span>'. $menu_text .'</span>';
								} else {
									echo '<i class="icon-menu-fine"></i>';
								}
							echo '</a>';

						}
					?>
				</div>

				<div class="secondary_menu_wrapper">
					<!-- #secondary-menu -->
					<?php mfn_wp_secondary_menu(); ?>
				</div>

				<div class="banner_wrapper">
					<?php mfn_opts_show( 'header-banner' ); ?>
				</div>

				<div class="search_wrapper">
					<!-- #searchform -->
					<?php get_search_form( true ); ?>
				</div>

			</div>

			<?php
				if( ! mfn_opts_get( 'top-bar-right-hide' ) ){
					get_template_part( 'includes/header', 'top-bar-right' );
				}
			?>

		</div>

	</div>

</div>
