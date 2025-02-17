<?php
/**
 * Template Social Share
 *
 * This template can be overridden by copying it to your child_theme_folder/themify-builder/template-social-share.php.
 *
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

defined( 'ABSPATH' ) || exit;

$mod_name=$args['mod_name'];
$element_id = $args['module_ID'];
$fields_args =  $args['mod_settings']+ array(
    'mod_title' => '',
    'networks' => '',
    'size' => 'normal',
    'shape' => 'none',
    'arrangement' => 'h',
    'title' => 'yes',
    'animation_effect' => '',
    'css' => ''
);

if($fields_args['networks']!==''){
    $fields_args['networks'] = explode( '|', $fields_args['networks'] );
    if ( !empty( $fields_args['networks'] ) ) {
	    //$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
	    $info = array(
		    'fb' => array( 'icon' => 'facebook', 'title' => __( 'Facebook', 'themify' ),'type'=>'facebook' ),
		    'tw' => array( 'icon' => 'fab x-twitter', 'title' => __( 'Twitter', 'themify' ),'type'=>'twitter' ),
		    'lk' => array( 'icon' => 'linkedin', 'title' => __( 'Linkedin', 'themify' ),'type'=>'linkedin' ),
		    'pi' => array( 'icon' => 'pinterest', 'title' => __( 'Pinterest', 'themify' ),'type'=>'pinterest' ),
		    'em' => array( 'icon' => 'email', 'title' => __( 'Email', 'themify' ),'type'=>'email' )
	    );
    }
}
$container_class = apply_filters( 'themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css'],
	'tb_ss_size_' . $fields_args['size'],
	'tb_ss_shape_' . $fields_args['shape']
), $mod_name, $element_id, $fields_args );

if ( !empty( $fields_args['global_styles'] ) && Themify_Builder::$frontedit_active === false ) {
	$container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters( 'themify_builder_module_container_props', self::parse_animation_effect($fields_args,array( 'class' => implode( ' ', $container_class ) )), $fields_args, $mod_name, $element_id );
$loop = Themify_Builder::$is_loop===true || in_the_loop();
if(Themify_Builder::$frontedit_active===false){
    $container_props['data-lazy']=1;
}
self::sticky_element_props($container_props, $fields_args);
?>
<!-- module social share -->
<div <?php echo themify_get_element_attributes($container_props);?> data-title="<?php true === $loop?the_title():wp_title(); ?>" data-url="<?php true===$loop?the_permalink():''; ?>">
	<?php $container_props = $container_class = $args=null; 
		echo Themify_Builder_Component_Module::get_module_title($fields_args);
	?>
	<div class="module-social-share-wrapper">
		<?php if($fields_args['networks']!==''):?>
				<?php foreach ( $fields_args['networks'] as $net ): ?>
					<div class="ss_anchor_wrap<?php echo 'h'===$fields_args['arrangement']?' tf_inline_b':''; ?>">
						<a href="#" data-type="<?php echo $info[ $net ]['type']; ?>">
							<em class="tb_social_share_icon"><?php echo themify_get_icon($info[ $net ]['icon'], $net === 'tw' ? 'fa' : 'ti', false,false,array('aria-label'=>$info[$net]['title'])); ?></em>
							<?php if('no' === $fields_args['title']): ?>
								<span class="tb_social_share_title"><?php echo $info[ $net ]['title']; ?></span>
							<?php endif; ?>
						</a>
					</div>
				<?php endforeach; ?>
		<?php endif;?>
	</div>
</div>
<!-- /module social share -->
