<?php
/**
 * Header functions
 *
 * @package Betheme
 * @author Muffin group
 * @link https://muffingroup.com
 */

/**
 * Page title
 */

if (! function_exists('mfn_title')) {
	function mfn_title($title)
	{
		if (mfn_opts_get('mfn-seo') && mfn_ID()) {
			if ($seo_title = trim(get_post_meta(mfn_ID(), 'mfn-meta-seo-title', true))) {
				$title = esc_html($seo_title);
			}
		}

		return $title;
	}
}
add_filter('pre_get_document_title', 'mfn_title');

/**
 * Built-in SEO Fields
 */

if (! function_exists('mfn_seo')) {
	function mfn_seo()
	{
		if (mfn_opts_get('mfn-seo')) {

			// description
			if (mfn_ID() && get_post_meta(mfn_ID(), 'mfn-meta-seo-description', true)) {
				echo '<meta name="description" content="'. esc_attr(get_post_meta(mfn_ID(), 'mfn-meta-seo-description', true)) .'"/>'."\n";
			} elseif (mfn_opts_get('meta-description')) {
				echo '<meta name="description" content="'. esc_attr(mfn_opts_get('meta-description')) .'"/>'."\n";
			}

			// keywords
			if (mfn_ID() &&  get_post_meta(mfn_ID(), 'mfn-meta-seo-keywords', true)) {
				echo '<meta name="keywords" content="'. esc_attr(get_post_meta(mfn_ID(), 'mfn-meta-seo-keywords', true)) .'"/>'."\n";
			} elseif (mfn_opts_get('meta-keywords')) {
				echo '<meta name="keywords" content="'. esc_attr(mfn_opts_get('meta-keywords')) .'"/>'."\n";
			}

			// og:image
			if (mfn_ID() &&  get_post_meta(mfn_ID(), 'mfn-meta-seo-og-image', true)) {
				echo '<meta property="og:image" content="'. esc_attr(get_post_meta(mfn_ID(), 'mfn-meta-seo-og-image', true)) .'"/>'."\n";
			} elseif (mfn_opts_get('mfn-seo-og-image')) {
				echo '<meta property="og:image" content="'. esc_attr(mfn_opts_get('mfn-seo-og-image')) .'"/>'."\n";
			}

			// hreflang | only if WMPL is not active
			if (! function_exists('icl_object_id')) {
				$format_locale = str_replace('_', '-', get_locale());
				echo '<link rel="alternate" hreflang="'. esc_attr($format_locale) .'" href="'. esc_url(get_permalink(mfn_ID())) .'"/>'."\n";
			}
		}

		// google analytics
		if (mfn_opts_get('google-analytics')) {
			mfn_opts_show('google-analytics');
		}

		// facebook pixel
		if (mfn_opts_get('facebook-pixel')) {
			echo "\n";
			mfn_opts_show('facebook-pixel');
		}
	}
}
add_action('wp_seo', 'mfn_seo');

/**
 * Google Remarketing Code
 */

if (! function_exists('mfn_google_remarketing')) {
	function mfn_google_remarketing()
	{
		// google remarketing
		if (mfn_opts_get('google-remarketing')) {
			mfn_opts_show('google-remarketing');
		}
	}
}
add_action('wp_footer', 'mfn_google_remarketing', 100);

/**
 * Fonts selected in Theme Options
 */

if (! function_exists('mfn_fonts_selected')) {
	function mfn_fonts_selected()
	{
		$fonts = array(
			'content' => mfn_opts_get('font-content', 'Roboto'),
			'menu' => mfn_opts_get('font-menu', 'Roboto'),
			'title' => mfn_opts_get('font-title', 'Patua One'),
			'headings' => mfn_opts_get('font-headings', 'Patua One'),
			'headingsSmall' => mfn_opts_get('font-headings-small', 'Roboto'),
			'blockquote' => mfn_opts_get('font-blockquote', 'Patua One'),
			'decorative' => mfn_opts_get('font-decorative', 'Patua One'),
		);

		return $fonts;
	}
}

/**
 * Styles
 */

if (! function_exists('mfn_styles')) {
	function mfn_styles()
	{
		$theme_disable = mfn_opts_get('theme-disable');

		// wp_enqueue_style

		wp_enqueue_style('style', get_stylesheet_uri(), false, THEME_VERSION);

		wp_enqueue_style('mfn-base', THEME_URI .'/css/base.css', false, THEME_VERSION);
		wp_enqueue_style('mfn-layout', THEME_URI .'/css/layout.css', false, THEME_VERSION);
		wp_enqueue_style('mfn-shortcodes', THEME_URI .'/css/shortcodes.css', false, THEME_VERSION);

		// plugins

		if (! isset($theme_disable[ 'entrance-animations' ])) {
			wp_enqueue_style('mfn-animations', THEME_URI .'/assets/animations/animations.min.css', false, THEME_VERSION);
		}

		wp_enqueue_style('mfn-jquery-ui', THEME_URI .'/assets/ui/jquery.ui.all.css', false, THEME_VERSION);
		wp_enqueue_style('mfn-jplayer', THEME_URI .'/assets/jplayer/css/jplayer.blue.monday.css', false, THEME_VERSION);

		// rtl | demo

		if ($_GET && key_exists('mfn-rtl', $_GET)) {
			wp_enqueue_style('mfn-rtl', THEME_URI .'/rtl.css', false, THEME_VERSION);
		}

		// responsive

		if (mfn_opts_get('responsive')) {
			wp_enqueue_style('mfn-responsive', THEME_URI .'/css/responsive.css', false, THEME_VERSION);
		} else {
			wp_enqueue_style('mfn-responsive-off', THEME_URI .'/css/responsive-off.css', false, THEME_VERSION);
		}

		// custom Theme Options styles

		if (mfn_opts_get('static-css') && (! ($_GET && key_exists('mfn-c', $_GET)))) {

			// static | style-static.css

			if (defined('STATIC_IN_CHILD') && STATIC_IN_CHILD) {
				wp_enqueue_style('mfn-style-static', CHILD_THEME_URI .'/style-static.css', false, THEME_VERSION);
			} else {
				wp_enqueue_style('mfn-style-static', THEME_URI .'/style-static.css', false, THEME_VERSION);
			}

		} else {

			// predefined skins

			if ($_GET && key_exists('mfn-c', $_GET)) {
				$skin = esc_html($_GET['mfn-c']); // demo
			} elseif ($layoutID = mfn_layout_ID()) {
				$skin = get_post_meta($layoutID, 'mfn-post-skin', true);
			} else {
				$skin = mfn_opts_get('skin', 'custom');
			}

			if (($skin != 'custom') && ($skin != 'one')) {
				wp_enqueue_style('mfn-skin-'. $skin, THEME_URI .'/css/skins/'. $skin .'/style.css', false, THEME_VERSION);
			}

		}

		// Google Fonts

		$google_fonts = mfn_fonts('all');

		// subset

		$subset = mfn_opts_get('font-subset');
		if ($subset) {
			$subset = '&amp;subset='. str_replace(' ', '', $subset);
		}

		// style & weight

		if ($weight = mfn_opts_get('font-weight')) {
			$weight = ':'. implode(',', $weight);
		}

		$fonts = mfn_fonts_selected();
		foreach ($fonts as $font) {
			if (in_array($font, $google_fonts)) {
				$font_slug = str_replace(' ', '+', $font);
				wp_enqueue_style($font_slug, 'https://fonts.googleapis.com/css?family='. $font_slug . $weight . $subset);
			}
		}

	}
}
add_action('wp_enqueue_scripts', 'mfn_styles');

/**
 * Styles | Custom Font
 */

if (! function_exists('mfn_styles_custom_font')) {
	function mfn_styles_custom_font()
	{
		if ($font_custom = mfn_opts_get('font-custom')) {

			$font_custom_woff = mfn_opts_get('font-custom-woff');
			$font_custom_ttf = mfn_opts_get('font-custom-ttf');

			echo '<!-- style | custom font -->'."\n";
			echo '<style id="mfn-dnmc-font-css">'."\n";
				echo '@font-face{';
					echo 'font-family:"'. $font_custom .'";';
						echo 'src:';
						if ($font_custom_woff) {
							echo 'url("'. $font_custom_woff .'") format("woff")';
						}
						if ($font_custom_woff && $font_custom_ttf) {
							echo ',';
						}
						if ($font_custom_ttf) {
							echo 'url("'. $font_custom_ttf .'") format("truetype")';
						}
						echo ';';
					echo 'font-weight:normal;';
					echo 'font-style:normal';
				echo '}'."\n";
			echo '</style>'."\n";

		}

		if ($font_custom2 = mfn_opts_get('font-custom2')) {

			$font_custom2_woff = mfn_opts_get('font-custom2-woff');
			$font_custom2_ttf = mfn_opts_get('font-custom2-ttf');

			echo '<!-- style | custom font 2 -->'."\n";
			echo '<style id="mfn-dnmc-font2-css">'."\n";
				echo '@font-face{';
					echo 'font-family:"'. $font_custom2 .'";';
						echo 'src:';
						if ($font_custom2_woff) {
							echo 'url("'. $font_custom2_woff .'") format("woff")';
						}
						if ($font_custom2_woff && $font_custom2_ttf) {
							echo ',';
						}
						if ($font_custom2_ttf) {
							echo 'url("'. $font_custom2_ttf .'") format("truetype")';
						}
					echo ';';
					echo 'font-weight:normal;';
					echo 'font-style:normal';
				echo '}'."\n";
			echo '</style>'."\n";

		}
	}
}
add_action('wp_head', 'mfn_styles_custom_font');

/**
 * Styles | Background
 */

if (! function_exists('mfn_styles_background')) {
	function mfn_styles_background()
	{
		$output = '';
		$output_ultrawide = '';

		// HTML

		if ($layoutID = mfn_layout_ID()) {
			$htmlB = get_post_meta($layoutID, 'mfn-post-bg', true);
			$htmlP = get_post_meta($layoutID, 'mfn-post-bg-pos', true);
		} else {
			$htmlB = mfn_opts_get('img-page-bg');
			$htmlP = mfn_opts_get('position-page-bg');
		}

		if ($htmlB) {
			$aBg 	= array();
			$aBg[] 	= 'background-image:url('. $htmlB .')';

			if ($htmlP) {
				$background_attr = explode(';', $htmlP);
				if ($background_attr[0]) {
					$aBg[] = 'background-repeat:'. $background_attr[0];
				}
				if ($background_attr[1]) {
					$aBg[] = 'background-position:'. $background_attr[1];
				}
				if ($background_attr[2]) {
					$aBg[] = 'background-attachment:'. $background_attr[2];
				}
				if ($background_attr[3]) {
					$aBg[] = 'background-size:'. $background_attr[3];
				} elseif (mfn_opts_get('size-page-bg')) {
					if (in_array(mfn_opts_get('size-page-bg'), array( 'cover', 'contain' ))) {
						$aBg[] = 'background-size:'. mfn_opts_get('size-page-bg');
					} elseif (mfn_opts_get('size-page-bg') == 'cover-ultrawide') {
						$output_ultrawide .= 'html{background-size:cover}';
					}
				}
			}
			$background = implode(';', $aBg);

			$output .= 'html{'. $background. '}'."\n";
		}

		// Header wrapper

		$headerB = false;

		if (mfn_opts_get('img-subheader-bg')) {
			$headerB = mfn_opts_get('img-subheader-bg');
		}

		if (mfn_ID() && ! is_search()) {
			if (((mfn_ID() == get_option('page_for_posts')) || (get_post_type(mfn_ID()) == 'page')) && has_post_thumbnail(mfn_ID())) {

				// Pages & Blog Page ---
				$headerB = wp_get_attachment_image_src(get_post_thumbnail_id(mfn_ID()), 'full');
				$headerB = $headerB[0];
			} elseif (get_post_meta(mfn_ID(), 'mfn-post-header-bg', true)) {

				// Single Post ---
				$headerB = get_post_meta(mfn_ID(), 'mfn-post-header-bg', true);
			}
		}

		$headerP = mfn_opts_get('img-subheader-attachment');

		if ($headerB) {
			$aBg 	= array();
			$aBg[] 	= 'background-image:url('. $headerB .')';

			if ($headerP == "fixed") {
				$aBg[] = 'background-attachment:fixed';
			} elseif ($headerP == "parallax") {
				// do nothing
			} elseif ($headerP) {
				$background_attr = explode(';', $headerP);
				if ($background_attr[0]) {
					$aBg[] = 'background-repeat:'. $background_attr[0];
				}
				if ($background_attr[1]) {
					$aBg[] = 'background-position:'. $background_attr[1];
				}
				if ($background_attr[2]) {
					$aBg[] = 'background-attachment:'. $background_attr[2];
				}
				if ($background_attr[3]) {
					$aBg[] = 'background-size:'. $background_attr[3];
				} elseif (mfn_opts_get('size-subheader-bg')) {
					if (in_array(mfn_opts_get('size-subheader-bg'), array( 'cover', 'contain' ))) {
						$aBg[] = 'background-size:'. mfn_opts_get('size-subheader-bg');
					} elseif (mfn_opts_get('size-subheader-bg') == 'cover-ultrawide') {
						$output_ultrawide .= 'body:not(.template-slider) #Header_wrapper{background-size:cover}';
					}
				}
			}

			$background = implode(';', $aBg);

			$output .= 'body:not(.template-slider) #Header_wrapper{'. $background. '}'."\n";
		}

		// Top Bar

		$topbarB = mfn_opts_get('top-bar-bg-img');
		$topbarP = mfn_opts_get('top-bar-bg-position');

		if ($topbarB) {
			$aBg 	= array();
			$aBg[] 	= 'background-image:url('. $topbarB .')';

			if ($topbarP) {
				$background_attr = explode(';', $topbarP);
				if ($background_attr[0]) {
					$aBg[] = 'background-repeat:'. $background_attr[0];
				}
				if ($background_attr[1]) {
					$aBg[] = 'background-position:'. $background_attr[1];
				}
				if ($background_attr[2]) {
					$aBg[] = 'background-attachment:'. $background_attr[2];
				}
				if ($background_attr[3]) {
					$aBg[] = 'background-size:'. $background_attr[3];
				} elseif (mfn_opts_get('topbar-bg-img-size')) {
					if (in_array(mfn_opts_get('topbar-bg-img-size'), array( 'cover', 'contain' ))) {
						$aBg[] = 'background-size:'. mfn_opts_get('topbar-bg-img-size');
					}
				}
			}

			$background = implode(';', $aBg);

			$output .= '#Top_bar,#Header_creative{'. $background. '}'."\n";
		}

		// Subheader

		if (get_post_meta(mfn_ID(), 'mfn-post-subheader-image', true)) {
			$subheaderB = get_post_meta(mfn_ID(), 'mfn-post-subheader-image', true);
		} else {
			$subheaderB = mfn_opts_get('subheader-image');
		}

		$subheaderP = mfn_opts_get('subheader-position');

		if ($subheaderB) {
			$aBg 	= array();
			$aBg[] 	= 'background-image:url('. $subheaderB .')';

			if ($subheaderP) {
				$background_attr = explode(';', $subheaderP);
				if ($background_attr[0]) {
					$aBg[] = 'background-repeat:'. $background_attr[0];
				}
				if ($background_attr[1]) {
					$aBg[] = 'background-position:'. $background_attr[1];
				}
				if ($background_attr[2]) {
					$aBg[] = 'background-attachment:'. $background_attr[2];
				}
				if ($background_attr[3]) {
					$aBg[] = 'background-size:'. $background_attr[3];
				} elseif (mfn_opts_get('subheader-size')) {
					if (in_array(mfn_opts_get('subheader-size'), array( 'cover', 'contain' ))) {
						$aBg[] = 'background-size:'. mfn_opts_get('subheader-size');
					} elseif (mfn_opts_get('subheader-size') == 'cover-ultrawide') {
						$output_ultrawide .= '#Subheader{background-size:cover}';
					}
				}
			}

			$background = implode(';', $aBg);

			$output .= '#Subheader{'. $background. '}'."\n";
		}

		// Footer

		$footerB = mfn_opts_get('footer-bg-img');
		$footerP = mfn_opts_get('footer-bg-img-position');

		if ($footerB) {
			$aBg 	= array();
			$aBg[] 	= 'background-image:url('. $footerB .')';

			if ($footerP) {
				$background_attr = explode(';', $footerP);
				if ($background_attr[0]) {
					$aBg[] = 'background-repeat:'. $background_attr[0];
				}
				if ($background_attr[1]) {
					$aBg[] = 'background-position:'. $background_attr[1];
				}
				if ($background_attr[2]) {
					$aBg[] = 'background-attachment:'. $background_attr[2];
				}
				if ($background_attr[3]) {
					$aBg[] = 'background-size:'. $background_attr[3];
				} elseif (mfn_opts_get('footer-bg-img-size')) {
					if (in_array(mfn_opts_get('footer-bg-img-size'), array( 'cover', 'contain' ))) {
						$aBg[] = 'background-size:'. mfn_opts_get('footer-bg-img-size');
					} elseif (mfn_opts_get('footer-bg-img-size') == 'cover-ultrawide') {
						$output_ultrawide .= '#Footer{background-size:cover}';
					}
				}
			}

			$background = implode(';', $aBg);

			$output .= '#Footer{'. $background. '}'."\n";
		}

		// output -----

		if ($output) {
			echo '<!-- style | background -->'."\n";
			echo '<style id="mfn-dnmc-bg-css">'."\n";
				echo $output;
				if ($output_ultrawide) {
					echo '@media only screen and (min-width: 1921px){'. $output_ultrawide. '}'."\n";
				}
			echo '</style>'."\n";
		}
	}
}
add_action('wp_head', 'mfn_styles_background');

/**
 * Styles | Minify
 */

if (! function_exists('mfn_styles_minify')) {
	function mfn_styles_minify($css)
	{
		// remove comments
		$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);

		// remove whitespace
		$css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);

		return $css;
	}
}

/**
 * Styles | Dynamic
 */

if (! function_exists('mfn_styles_dynamic')) {
	function mfn_styles_dynamic()
	{
		echo '<!-- style | dynamic -->'."\n";
		echo '<style id="mfn-dnmc-style-css">'."\n";

			ob_start();

			if (! mfn_opts_get('static-css')) {

				// responsive

				if (mfn_opts_get('responsive')) {
					include_once THEME_DIR .'/style-responsive.php';
				}

				// colors

				if ($_GET && key_exists('mfn-c', $_GET)) {
					$skin = esc_html($_GET['mfn-c']); // demo
				} elseif ($layoutID = mfn_layout_ID()) {
					$skin = get_post_meta($layoutID, 'mfn-post-skin', true);
				} else {
					$skin = mfn_opts_get('skin', 'custom');
				}

				if ($skin == 'custom') {
					include_once THEME_DIR . '/style-colors.php';
				} elseif ($skin == 'one') {
					include_once THEME_DIR . '/style-one.php';
				}

				// style PHP

				include_once THEME_DIR . '/style.php';
			}

			$css = ob_get_contents();

			ob_get_clean();

			echo mfn_styles_minify($css) ."\n";

		echo '</style>'."\n";
	}
}
add_action('wp_head', 'mfn_styles_dynamic');

/**
 * Styles | Custom Styles
 */

if (! function_exists('mfn_styles_custom')) {
	function mfn_styles_custom()
	{
		// Theme Options > Custom CSS

		if ($custom_css = mfn_opts_get('custom-css')) {
			echo '<!-- style | custom css | theme options -->'."\n";
			echo '<style id="mfn-dnmc-theme-css">'."\n";
				echo $custom_css ."\n";
			echo '</style>'."\n";
		}

		// Page Options > Custom CSS

		if ($custom_css = get_post_meta(mfn_ID(), 'mfn-post-css', true)) {
			echo '<!-- style | custom css | page options -->'."\n";
			echo '<style id="mfn-dnmc-page-css">'."\n";
				echo $custom_css."\n";
			echo '</style>'."\n";
		}

		// Layouts > Custom Colors

		if ($layoutID = mfn_layout_ID()) {
			$layout_styles = '';

			if (get_post_meta($layoutID, 'mfn-post-background-subheader', true)) {
				$layout_styles .= '#Subheader {background-color: '. get_post_meta($layoutID, 'mfn-post-background-subheader', true) .';} ';
			}
			if (get_post_meta($layoutID, 'mfn-post-color-subheader', true)) {
				$layout_styles .= '#Subheader .title {color: '. get_post_meta($layoutID, 'mfn-post-color-subheader', true) .';} ';
				$layout_styles .= '#Subheader ul.breadcrumbs li, #Subheader ul.breadcrumbs li a {color: '. hex2rgba(get_post_meta($layoutID, 'mfn-post-color-subheader', true), .6) .';} ';
			}

			if ($layout_styles) {
				echo '<!-- style | custom layout -->'."\n";
				echo '<style id="mfn-dnmc-layout-css">'."\n";
					echo $layout_styles."\n";
				echo '</style>'."\n";
			}
		}

	}
}
add_action('wp_head', 'mfn_styles_custom');

/**
 * Scripts
 */

if (! function_exists('mfn_scripts')) {
	function mfn_scripts()
	{
		wp_enqueue_script('jquery-ui-core', THEME_URI .'/assets/ui/jquery.ui.core.js', array( 'jquery' ), THEME_VERSION, true);
		wp_enqueue_script('jquery-ui-widget', THEME_URI .'/assets/ui/jquery.ui.widget.js', array( 'jquery' ), THEME_VERSION, true);
		wp_enqueue_script('jquery-ui-tabs', THEME_URI .'/assets/ui/jquery.ui.tabs.js', array( 'jquery' ), THEME_VERSION, true);
		wp_enqueue_script('jquery-ui-accordion', THEME_URI .'/assets/ui/jquery.ui.accordion.js', array( 'jquery' ), THEME_VERSION, true);

		wp_enqueue_script('mfn-plugins', THEME_URI .'/js/plugins.js', array( 'jquery' ), THEME_VERSION, true);
		wp_enqueue_script('mfn-menu', THEME_URI .'/js/menu.js', array( 'jquery' ), THEME_VERSION, true);

		wp_enqueue_script('mfn-animations', THEME_URI .'/assets/animations/animations.min.js', array( 'jquery' ), THEME_VERSION, true);
		wp_enqueue_script('mfn-jplayer', THEME_URI .'/assets/jplayer/jplayer.min.js', array( 'jquery' ), THEME_VERSION, true);

		$parallax = mfn_parallax_plugin();
		if ($parallax == 'translate3d') {
			wp_enqueue_script('mfn-parallax', THEME_URI .'/js/parallax/translate3d.js', array( 'jquery' ), THEME_VERSION, true);
		} elseif ($parallax == 'stellar') {
			wp_enqueue_script('mfn-stellar', THEME_URI .'/js/parallax/stellar.js', array( 'jquery' ), THEME_VERSION, true);
		}

		wp_enqueue_script('mfn-scripts', THEME_URI .'/js/scripts.js', array( 'jquery' ), THEME_VERSION, true);

		// single post | reply comment

		if (is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}

		// scripts config

		$lightboxOptions = mfn_opts_get('prettyphoto-options');

		$config = array(
			'mobileInit' => mfn_opts_get('mobile-menu-initial', 1240),
			'parallax' => mfn_parallax_plugin(),
			'responsive' => intval(mfn_opts_get('responsive', 0)),
			'retinaJS' => intval(mfn_opts_get('retina-js')),
			'lightbox' => array(
				'disable' => isset($lightboxOptions['disable']) ? true : false,
				'disableMobile' => isset($lightboxOptions['disable-mobile']) ? true : false,
				'title' => isset($lightboxOptions['title']) ? true : false,
			),
			'slider' => array(
				'blog' => intval(mfn_opts_get('slider-blog-timeout', 0)),
				'clients' => intval(mfn_opts_get('slider-clients-timeout', 0)),
				'offer' => intval(mfn_opts_get('slider-offer-timeout', 0)),
				'portfolio' => intval(mfn_opts_get('slider-portfolio-timeout', 0)),
				'shop' => intval(mfn_opts_get('slider-shop-timeout', 0)),
				'slider' => intval(mfn_opts_get('slider-slider-timeout', 0)),
				'testimonials' => intval(mfn_opts_get('slider-testimonials-timeout', 0)),
			),
		);

		if (mfn_opts_get('love')) {
			$config['ajax'] = admin_url('admin-ajax.php');
		}

		wp_localize_script('mfn-plugins', 'mfn', $config);
	}
}
add_action('wp_enqueue_scripts', 'mfn_scripts');

/**
 * Scripts | Custom JS
 */

function mfn_scripts_custom() {
	if ($custom_js = mfn_opts_get('custom-js')) {
		wp_add_inline_script('mfn-scripts', $custom_js);
	}
}
add_action('wp_enqueue_scripts', 'mfn_scripts_custom');

/**
 * Body classes | Header
 * Adds classes to the array of body classes.
 */

if (! function_exists('mfn_header_style')) {
	function mfn_header_style($firstPartOnly = false)
	{
		$header_layout = false;

		// plugin: Muffin Header Builder

		if (class_exists('Mfn_HB_Front') && get_site_option('mfn_header_builder')) {
			return 'mhb';
		}

		// header styles

		if ($_GET && key_exists('mfn-h', $_GET)) {
			$header_layout = esc_html($_GET['mfn-h']); // demo
		} elseif ($layoutID = mfn_layout_ID()) {
			$header_layout = get_post_meta($layoutID, 'mfn-post-header-style', true);
		} elseif (mfn_opts_get('header-style')) {
			$header_layout =  mfn_opts_get('header-style');
		}

		if (strpos($header_layout, ',')) {

			// multiple header parameters

			$a_header_layout = explode(',', $header_layout);

			// return ONLY first parameter

			if ($firstPartOnly) {
				return 'header-'.$a_header_layout[0];
			}

			foreach ((array)$a_header_layout as $key => $val) {
				$a_header_layout[$key] = 'header-'. $val;
			}
			$header = implode(' ', $a_header_layout);

		} else {

			// one parameter
			$header = 'header-'. $header_layout;
		}

		return $header;
	}
}

/**
 * Body classes | Sidebar
 * Adds classes to the array of body classes.
 */

if (! function_exists('mfn_sidebar_classes')) {
	function mfn_sidebar_classes($has_both = false)
	{
		$classes = $both = false;

		if (mfn_ID()) {
			if (get_post_type() == 'page' && mfn_opts_get('single-page-layout')) {

				// Theme Options | Single - Page
				$layout = mfn_opts_get('single-page-layout');

			} elseif (get_post_type() == 'post' && is_single() && mfn_opts_get('single-layout')) {

				// Theme Options | Single - Post
				$layout = mfn_opts_get('single-layout');

			} elseif (get_post_type() == 'portfolio' && is_single() && mfn_opts_get('single-portfolio-layout')) {

				// Theme Options | Single - Portfolio
				$layout = mfn_opts_get('single-portfolio-layout');

			} else {

				// Post Meta
				$layout = get_post_meta(mfn_ID(), 'mfn-post-layout', true);
			}

			switch ($layout) {

				case 'left-sidebar':
					$classes = ' with_aside aside_left';
					break;

				case 'right-sidebar':
					$classes = ' with_aside aside_right';
					break;

				case 'both-sidebars':
					$classes = ' with_aside aside_both';
					$both = true;
					break;

			}

			// demo

			if ($_GET && key_exists('mfn-s', $_GET)) {
				if ($_GET['mfn-s']) {
					$classes = ' with_aside aside_right';
				} else {
					$classes = false;
				}
			}
		}

		// WooCommerce

		if (function_exists('is_woocommerce')) {
			if (is_woocommerce()) {
				if (! isset($layout) || ! $layout) {

					// BeTheme version < 6.4 | DO NOT DELETE
					if (is_active_sidebar('shop')) {
						$classes = ' with_aside aside_right';
					}

				} elseif ($layout == 'both-sidebars') {

					// Only one sidebar for shop
					$classes = ' with_aside aside_right';

				}
			}

			if (function_exists('is_product') && is_product() && mfn_opts_get('shop-sidebar') == 'shop') {
				$classes = false;
			}
		}

		// bbPress

		if (function_exists('is_bbpress') && is_bbpress() && is_active_sidebar('forum')) {
			$classes = ' with_aside aside_right';
		}

		// BuddyPress

		if (function_exists('is_buddypress') && is_buddypress() && is_active_sidebar('buddy')) {
			$classes = ' with_aside aside_right';
		}

		// Easy Digital Downloads

		if ((get_post_type() == 'download')  && is_active_sidebar('edd')) {
			$classes = ' with_aside aside_right';
		}

		// Events Calendar

		if (function_exists('tribe_is_month') && is_active_sidebar('events')) {
			if (tribe_is_month() || tribe_is_day() || tribe_is_event() || tribe_is_event_query() || tribe_is_venue()) {
				$classes = ' with_aside aside_right';
			}
		}

		// Page | Search

		if (is_search()) {
			if (is_active_sidebar('mfn-search')) {
				$classes = ' with_aside aside_right';
			} else {
				$classes = false;
			}
		}

		// Page | Blank Page, Under Construction

		if (is_page_template('template-blank.php') || is_page_template('under-construction.php')) {
			$classes = false;
		}

		// check if has both sidebars

		if ($has_both) {
			return $both;
		}

		return $classes;
	}
}

/**
 * Body classes
 * Adds classes to the array of body classes.
 */

if (! function_exists('mfn_body_classes')) {
	function mfn_body_classes($classes)
	{
		$layoutID = mfn_layout_ID();

		// slider

		if (mfn_slider_isset()) {
			if (function_exists('is_woocommerce') && is_woocommerce()) {
				// do nothing
			} else {
				$classes[] = 'template-slider';
			}
		}

		// sidebar

		$classes[] = mfn_sidebar_classes();

		// skin

		if ($_GET && key_exists('mfn-c', $_GET)) {
			$classes[] = 'color-'. esc_html($_GET['mfn-c']); // demo
		} elseif ($layoutID) {
			$classes[] = 'color-'. get_post_meta($layoutID, 'mfn-post-skin', true);
		} else {
			$classes[] = 'color-'. mfn_opts_get('skin', 'custom');
		}

		// style: default & simple

		if ($_GET && key_exists('mfn-style', $_GET)) {
			$classes[] = 'style-'. esc_html($_GET['mfn-style']); // demo
		} else {
			$classes[] = 'style-'. mfn_opts_get('style', 'default');
		}

		// button style

		if ($_GET && key_exists('mfn-btn', $_GET)) {
			$classes[] = 'button-'. esc_html($_GET['mfn-btn']); // demo
		} else {
			$classes[] = 'button-'. mfn_opts_get('button-style', 'default');
		}

		// layout: full width & boxed

		if ($_GET && key_exists('mfn-box', $_GET)) {
			$classes[] = 'layout-boxed'; // demo
		} elseif ($layoutID) {
			$classes[] = 'layout-'. get_post_meta($layoutID, 'mfn-post-layout', true);
		} else {
			$classes[] = 'layout-'. mfn_opts_get('layout', 'full-width');
		}

		// one page

		if (get_post_meta(mfn_ID(), 'mfn-post-one-page', true)) {
			$classes[] = 'one-page';
		}

		// image frame: style

		if ($_GET && key_exists('mfn-if', $_GET)) {
			$classes[] = 'if-'. esc_html($_GET['mfn-if']); // demo
		} elseif (mfn_opts_get('image-frame-style')) {
			$classes[] = 'if-'. mfn_opts_get('image-frame-style');
		}

		// image frame: border

		if (mfn_opts_get('image-frame-border')) {
			$classes[] = 'if-border-'. mfn_opts_get('image-frame-border');
		}

		// image frame: caption

		if (mfn_opts_get('image-frame-caption')) {
			$classes[] = 'if-caption-on';
		}

		// content padding

		if (mfn_opts_get('content-remove-padding')) {
			$classes[] = 'no-content-padding';
		} elseif (get_post_meta(mfn_ID(), 'mfn-post-remove-padding', true)) {
			$classes[] = 'no-content-padding';
		}

		// single template

		if (get_post_meta(mfn_ID(), 'mfn-post-template', true)) {
			$classes[] = 'single-template-'. get_post_meta(mfn_ID(), 'mfn-post-template', true);
		}

		// love

		if (! mfn_opts_get('love')) {
			$classes[] = 'hide-love';
		}

		// table: hover

		if (mfn_opts_get('table-hover')) {
			$classes[] = 'table-'. mfn_opts_get('table-hover');
		}

		// plugin: Contact Form 7: form error

		if (mfn_opts_get('cf7-error')) {
			$classes[] = 'cf7p-'. mfn_opts_get('cf7-error');
		}

		// advanced | other

		$layout_options = mfn_opts_get('layout-options');
		if (is_array($layout_options)) {
			if (isset($layout_options['no-shadows'])) {
				$classes[] = 'no-shadows';
			}
			if (isset($layout_options['boxed-no-margin'])) {
				$classes[] = 'boxed-no-margin';
			}
		}

		// header -----

		$header_options = mfn_opts_get('header-fw') ? mfn_opts_get('header-fw') : false;

		// haeder | layout

		$classes[] = mfn_header_style();

		// header | full width

		if ($_GET && key_exists('mfn-hfw', $_GET)) {
			$classes[] = 'header-fw'; // demo
		} elseif (isset($header_options['full-width'])) {
			$classes[] = 'header-fw';
		}

		// header | boxed

		if (is_array($header_options) && isset($header_options['header-boxed'])) {
			$classes[] = 'header-boxed';
		}

		// header | minimalist

		if ($_GET && key_exists('mfn-min', $_GET)) {
			$classes[] = 'minimalist-header'; // demo
		} elseif ($layoutID) {
			if (get_post_meta($layoutID, 'mfn-post-minimalist-header', true) == 'no') {
				$classes[] = 'minimalist-header-no';
			} elseif (get_post_meta($layoutID, 'mfn-post-minimalist-header', true)) {
				$classes[] = 'minimalist-header';
			}
		} elseif (mfn_opts_get('minimalist-header') == 'no') {
			$classes[] = 'minimalist-header-no';
		} elseif (mfn_opts_get('minimalist-header')) {
			$classes[] = 'minimalist-header';
		}

		// header | sticky

		if ($layoutID) {
			if (get_post_meta($layoutID, 'mfn-post-sticky-header', true)) {
				$classes[] = 'sticky-header';
			}
		} elseif (mfn_opts_get('sticky-header')) {
			$classes[] = 'sticky-header';
		}

		// header | sticky: style

		if ($_GET && key_exists('mfn-ss', $_GET)) {
			$classes[] = 'sticky-'. esc_html($_GET['mfn-ss']); // demo
		} elseif ($layoutID) {
			$classes[] = 'sticky-'. get_post_meta($layoutID, 'mfn-post-sticky-header-style', true);
		} else {
			$classes[] = 'sticky-'. mfn_opts_get('sticky-header-style', 'white');
		}

		// action bar

		if (mfn_opts_get('action-bar')) {
			$classes[] = 'ab-show';
		} else {
			$classes[] = 'ab-hide';
		}

		// subheader | transparent

		$skin = mfn_opts_get('skin', 'custom');
		if ($_GET && key_exists('mfn-subtr', $_GET)) {
			$classes[] = 'subheader-transparent'; // demo
		} elseif (! in_array($skin, array('custom','one'))) {
			if (mfn_opts_get('subheader-transparent') != 100) {
				$classes[] = 'subheader-transparent';
			}
		}

		// subheader | style

		if ($_GET && key_exists('mfn-sh', $_GET)) {
			$classes[] = 'subheader-'. esc_html($_GET['mfn-sh']); // demo
		} else {
			$classes[] = 'subheader-'. mfn_opts_get('subheader-style', 'title-left');
		}

		// menu | style

		if ($_GET && key_exists('mfn-m', $_GET)) {
			$classes[] = 'menu-'. esc_html($_GET['mfn-m']); // demo
		} elseif (mfn_opts_get('menu-style')) {
			$classes[] = 'menu-'. mfn_opts_get('menu-style');
		}

		// menu | options

		$menu_options = mfn_opts_get('menu-options');
		if (is_array($menu_options) && isset($menu_options['align-right'])) {
			$classes[] = 'menuo-right';
		}
		if (is_array($menu_options) && isset($menu_options['menu-arrows'])) {
			$classes[] = 'menuo-arrows';
		}
		if (is_array($menu_options) && isset($menu_options['hide-borders'])) {
			$classes[] = 'menuo-no-borders';
		}
		if (is_array($menu_options) && isset($menu_options['submenu-active'])) {
			$classes[] = 'menuo-sub-active';
		}
		if (is_array($menu_options) && isset($menu_options['submenu-limit'])) {
			$classes[] = 'menuo-sub-limit';
		}
		if (is_array($menu_options) && isset($menu_options['last'])) {
			$classes[] = 'menuo-last';
		}

		// megamenu: style

		if (mfn_opts_get('menu-mega-style')) {
			$classes[] = 'mm-'. mfn_opts_get('menu-mega-style');
		}

		// logo

		if (mfn_opts_get('logo-vertical-align')) {
			$classes[] = 'logo-valign-'. mfn_opts_get('logo-vertical-align');
		}

		$logo_options = mfn_opts_get('logo-advanced');
		if (is_array($logo_options) && isset($logo_options['no-margin'])) {
			$classes[] = 'logo-no-margin';
		}
		if (is_array($logo_options) && isset($logo_options['overflow'])) {
			$classes[] = 'logo-overflow';
		}
		if (is_array($logo_options) && isset($logo_options['no-sticky-padding'])) {
			$classes[] = 'logo-no-sticky-padding';
		}

		// footer -----

		// footer | style

		if ($_GET && key_exists('mfn-ftr', $_GET)) {
			$classes[] = 'footer-'. esc_html($_GET['mfn-ftr']); // demo
		} elseif (mfn_opts_get('footer-style')) {
			$classes[] = 'footer-'. mfn_opts_get('footer-style');
		}

		// footer | copy & social

		if (mfn_opts_get('footer-hide') == 'center') {
			$classes[] = 'footer-copy-center';
		}

		// responsive -----

		if (! mfn_opts_get('responsive')) {
			$classes[] = 'responsive-off';
		}
		if (mfn_opts_get('responsive-boxed2fw')) {
			$classes[] = 'boxed2fw';
		}
		if (mfn_opts_get('no-hover')) {
			$classes[] = 'no-hover-'. mfn_opts_get('no-hover');
		}
		if (mfn_opts_get('no-section-bg')) {
			$classes[] = 'no-section-bg-'. mfn_opts_get('no-section-bg');
		}
		if (mfn_opts_get('responsive-top-bar')) {
			$classes[] = 'mobile-tb-'. mfn_opts_get('responsive-top-bar');
		}
		if (mfn_opts_get('responsive-mobile-menu')) {
			$classes[] = 'mobile-'. mfn_opts_get('responsive-mobile-menu');
		}
		if (mfn_opts_get('mobile-menu')) {
			$classes[] = 'mobile-menu';
		}

		$classes[] = 'mobile-mini-'. mfn_opts_get('responsive-header-minimal', 'mr-ll');

		// responsive | tablet | options

		$responsive_header_mob = mfn_opts_get('responsive-header-tablet');
		if (is_array($responsive_header_mob)) {
			if (isset($responsive_header_mob['sticky'])) {
				$classes[] = 'tablet-sticky';
			}
		}

		// responsive | mobile | options

		$responsive_header_mob = mfn_opts_get('responsive-header-mobile');
		if (is_array($responsive_header_mob)) {
			if (isset($responsive_header_mob['minimal'])) {
				$classes[] = 'mobile-header-mini';
			}
			if (isset($responsive_header_mob['sticky'])) {
				$classes[] = 'mobile-sticky';
			}
			if (isset($responsive_header_mob['transparent'])) {
				$classes[] = 'mobile-tr-header';
			}
		}

		// transparent -----

		$transparent_options = mfn_opts_get('transparent');
		if (is_array($transparent_options)) {
			if (isset($transparent_options['header'])) {
				$classes[] = 'tr-header';
			}
			if (isset($transparent_options['menu'])) {
				$classes[] = 'tr-menu';
			}
			if (isset($transparent_options['content'])) {
				$classes[] = 'tr-content';
			}
			if (isset($transparent_options['footer'])) {
				$classes[] = 'tr-footer';
			}
		}

		// demo / debug

		if ($_GET && key_exists('mfn-rtl', $_GET)) {
			$classes[] = 'rtl';
		}
		if ($layoutID) {
			$classes[] = 'dbg-lay-id-'. $layoutID;
		}

		$reg = mfn_is_registered() ? 'reg-' : '';
		$classes[] = 'be-'. $reg . str_replace('.', '', THEME_VERSION);

		return $classes;
	}
}
add_filter('body_class', 'mfn_body_classes');


/**
 * Annoying styles remover
 */
if (! function_exists('mfn_remove_recent_comments_style')) {
	function mfn_remove_recent_comments_style()
	{
		global $wp_widget_factory;
		if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
			remove_action('wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ));
		}
	}
}
add_action('widgets_init', 'mfn_remove_recent_comments_style');
