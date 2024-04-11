<?php
/**
 * Template Toc
 * 
 * This template can be overridden by copying it to your child_theme_folder/themify-builder/template-tab.php.
 *
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

defined( 'ABSPATH' ) || exit;

$element_id = $args['module_ID'];
$mod_name=$args['mod_name'];
$fields_args = $args['mod_settings']+ array(
    'm_t' => '',
    'in_tags' => 'h1|h2|h3|h4|h5|h6',
    'ex_tags' => '',
    'ex_m_t'=>'no',
    'in_cont' => 'b',
    'in_custom' => '',
    'mark'=>'none',
    'num'=>'no',
    'tree'=>'yes',
    'colapse'=>'yes',
    'cic'=>'-',
    'cmic'=>'+',
    'minimize'=>'no',
    'mic'=>'ti-angle-up',
    'mmic'=>'ti-angle-down',
    'bp'=>'tl',
    'min'=>2,
    'maxt'=>'',
    'maxh'=>32,
    'ic'=>'',
    'css'=>''
);
$class=array(
    'module',
    'module-' . $mod_name,
    'tb_toc_'.$fields_args['mark'],
    $args['module_ID'], 
    $fields_args['css']
);
if($fields_args['num']==='yes'){
    $class[]='tb_toc_show_num';
}
if($fields_args['tree']==='yes'){
    $class[]='tb_toc_tree';
}
$container_class = apply_filters('themify_builder_module_classes', $class, $mod_name, $element_id, $fields_args);

$args=$class=null;

$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args,array(
    'class' => implode(' ', $container_class),
)), $fields_args, $mod_name, $element_id);


if(Themify_Builder::$frontedit_active===false){
    $container_props['data-lazy']=1;
}
$container_props['data-tags']=$fields_args['in_tags'];
$container_props['data-maxh']=$fields_args['maxh'];

if($fields_args['min']>0){
    $container_props['data-min']=$fields_args['min'];
}
if($fields_args['maxt']!==''){
    $container_props['data-maxt']=$fields_args['maxt'];
}
if($fields_args['ex_tags']!==''){
    $container_props['data-excl']=$fields_args['ex_tags'];
}
$container_props['data-cont']=$fields_args['in_cont'];
if($fields_args['in_custom']!=='' && $fields_args['in_cont']==='cust'){
	$container_props['data-sel']=$fields_args['in_custom'];
}
if($fields_args['ex_m_t']==='yes'){
    $container_props['data-ex_m']=1;
}
if($fields_args['minimize']==='yes' && $fields_args['bp']!=='n'){
    $container_props['data-bp']=$fields_args['bp'];
}
self::sticky_element_props($container_props, $fields_args);
?>
<!-- module tab -->
<div <?php echo themify_get_element_attributes($container_props); ?>>
    <div class="tb_toc_head tf_clearfix">
	<?php $container_props=$container_class=null; 
	    echo Themify_Builder_Component_Module::get_module_title($fields_args,'m_t');
	    if($fields_args['minimize']==='yes'){
		echo themify_get_icon($fields_args['mic'],false,false,false,array('class'=>'tb_toc_mic_close')),
		    themify_get_icon($fields_args['mmic'],false,false,false,array('class'=>'tb_toc_mic tf_hide'));
	    }
	?>
    </div>
    <?php if($fields_args['mark']==='ic' && $fields_args['ic']!==''):?>
	<template class="tpl_toc_ic">
	    <?php echo themify_get_icon($fields_args['ic'],false,false,false,array('class'=>'tb_toc_ic'));?>
	</template>
    <?php endif; ?>
    <?php if($fields_args['tree']==='yes' && $fields_args['colapse']==='yes'):?>
	<template class="tpl_toc_cic">
		<?php echo $fields_args['cic']==='-'?'<span class="tf_fa tb_toc_cic"></span>':themify_get_icon($fields_args['cic'],false,false,false,array('class'=>'tb_toc_cic'))?>
	</template>
	<template class="tpl_toc_cic_close">
		<?php echo $fields_args['cmic']==='+'?'<span class="tf_fa tb_toc_cic_close tf_hide"></span>':themify_get_icon($fields_args['cmic'],false,false,false,array('class'=>'tb_toc_cic_close tf_hide'))?>
	</template>
    <?php endif; ?>
</div>
<!-- /module tab -->
