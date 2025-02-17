<?php
/**
 * Template Gallery
 * 
 * This template can be overridden by copying it to your child_theme_folder/themify-builder/template-gallery.php.
 *
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

defined( 'ABSPATH' ) || exit;

$mod_name=$args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+array(
    'mod_title_gallery' => '',
    'layout_gallery' => 'grid',
    'image_size_gallery' => 'thumbnail',
    'shortcode_gallery' => '',
    'thumb_w_gallery' => '',
    'thumb_h_gallery' => '',
    's_image_w_gallery' => '',
    's_image_h_gallery' => '',
    's_image_size_gallery' => 'full',
    'appearance_gallery' => '',
    'css_gallery' => '',
    'gallery_images' => array(),
    'gallery_columns' => 3,
    'link_opt' => false,
    'link_image_size' => 'full',
    'gallery_pagination' => false,
    'gallery_per_page' => '',
    'slider_thumbs' => false,
    'gallery_image_title' => false,
    'gallery_exclude_caption' => false,
    'layout_masonry' => '',
    'visible_opt_slider' => '',
    'tab_visible_opt_slider' => '',
    'mob_visible_opt_slider' => '',
    'auto_scroll_opt_slider' => 'off',
	'scroll_opt_slider'=>1,
    'speed_opt_slider' => 1,
    'effect_slider' => 'scroll',
    'pause_on_hover_slider' => 'resume',
    'play_pause_control' => 'no',
    'wrap_slider' => 'yes',
    'show_nav_slider' => 'yes',
    'show_arrow_slider' => 'yes',
    'show_arrow_buttons_vertical' => '',
    'unlink_feat_img_slider'=>'no',
    'unlink_post_title_slider'=>'no',
    'left_margin_slider' => '',
    'right_margin_slider' => '',
    'animation_effect' => '',
    'height_slider' => 'variable',
    'lightbox_title' => '',
    'lightbox' => '',
);
if (!empty($fields_args['appearance_gallery'] )) {
    $fields_args['appearance_gallery'] = self::get_checkbox_data($fields_args['appearance_gallery']);
	Themify_Builder_Model::load_appearance_css($fields_args['appearance_gallery']);
}
if (!empty($fields_args['shortcode_gallery'])) {
    $fields_args['gallery_images'] = themify_get_gallery_shortcode($fields_args['shortcode_gallery']);
    if (!$fields_args['link_opt']) {
	    $fields_args['link_opt'] = themify_get_gallery_shortcode_params($fields_args['shortcode_gallery']);
    }
    $sc_image_size = themify_get_gallery_shortcode_params($fields_args['shortcode_gallery'], 'size');
    if (!empty($sc_image_size)) {
	    $fields_args['image_size_gallery'] = $sc_image_size;
    }
}
$container_class = array(
    'module gallery', 
    'module-' . $mod_name,
    $element_id, 
    'layout-' . $fields_args['layout_gallery'],
    $fields_args['appearance_gallery'],
    $fields_args['css_gallery']
);
$container_class = apply_filters('themify_builder_module_classes', $container_class, $mod_name, $element_id, $fields_args);

if(!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active===false){
    $container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args,array(
    'class' => implode(' ', $container_class),
	)), $fields_args, $mod_name, $element_id);

if(Themify_Builder::$frontedit_active===false){
    $container_props['data-lazy']=1;
}
if($fields_args['layout_gallery']==='showcase' || $fields_args['layout_gallery']==='grid'){
	Themify_Builder_Model::load_module_self_style($mod_name, $fields_args['layout_gallery']);
}
if($fields_args['layout_gallery']==='slider' || $fields_args['layout_gallery']==='grid'){
	if ( $fields_args['lightbox'] !=='' ) {
		$fields_args['lightbox'] = 'n' !== $fields_args['lightbox'];
	} 
	else {
		$fields_args['lightbox'] = 'disable' !== themify_builder_get( 'setting-page_builder_gallery_lightbox', 'builder_lightbox' );
	}
}
self::sticky_element_props($container_props, $fields_args);
?>
<!-- module gallery -->
<div <?php echo themify_get_element_attributes($container_props); ?>>
    <?php 
	$container_props=$container_class=$args=null;
	echo Themify_Builder_Component_Module::get_module_title($fields_args,'mod_title_gallery');
    if (!empty($fields_args['gallery_images'])) {
	// render the template
		self::retrieve_template('template-' . $mod_name . '-' . $fields_args['layout_gallery'] . '.php', array(
			'module_ID' => $element_id,
			'mod_name' => $mod_name,
			'settings' => $fields_args
		), __DIR__);
    }
    $fields_args=null;
    ?>
</div>
<!-- /module gallery -->