<?php
/**
 * Template Contact
 *
 * Access original fields: $args['mod_settings']
*/
defined( 'ABSPATH' ) || exit;

$mod_name = $args['mod_name'];
$element_id=$args['module_ID'];
$fields_args = $args['mod_settings']+ array(
	'mod_title_contact' => '',
	'layout_contact' => 'style1',
	'field_name_label' => '',
	'field_name_placeholder' => '',
	'field_email_label' => '',
	'field_email_placeholder' => '',
	'field_subject_label' => '',
	'field_subject_placeholder' => '',
	'field_recipients_label' => '',
	'gdpr' => '',
	'gdpr_label' => '',
	'field_captcha_label' => '',
	'field_extra' => '{"fields":[]}',
	'field_order' => '{}',
	'field_message_label' => '',
	'field_message_require' => '',
	'field_message_placeholder' => '',
	'field_sendcopy_label' => '',
	'field_send_label' => '',
	'field_send_align' => 'left',
	'animation_effect' => '',
	'css_class_contact' => '',
	'field_message_active' => 'yes',
	'field_subject_active' => 'yes',
	'field_subject_require' => 'yes',
	'field_name_require' => 'yes',
	'field_email_require' => 'yes',
	'field_email_active' => 'yes',
	'field_name_active' => 'yes',
	'field_sendcopy_active' => '',
	'field_captcha_active' => '',
	'field_optin_active' => '',
	'field_optin_label' => '',
	'captcha_provider' => 'r', // Captcha service provider
	'provider' => '', // Optin service provider
	'nw'=>'',
	'name_icon' => '',
	'email_icon' => '',
	'subject_icon' => '',
	'message_icon' => '',
	'sr_display'=>'radio',
	'sr'=>array()
);
if(!isset($fields_args['v7'])){//is old data
	$def=[
		'gdpr_label' => __('I consent to my submitted data being collected and stored', 'builder-contact'),
		'field_recipients_label'=>__('Recipient', 'builder-contact'),
		'field_captcha_label' => __('Captcha', 'builder-contact'),
		'field_send_label' => __('Send', 'builder-contact'),
		'field_sendcopy_label'=>__('Send Copy', 'builder-contact'),
		'field_optin_label' => __( 'Subscribe to my newsletter.', 'builder-contact' )
	];
	foreach($def as $k=>$v){
		if($fields_args[$k]===''){
			$fields_args[$k]=$v;
		}
	}
	$def=[
		'name'=>__('Name', 'builder-contact'),
		'email'=>__('Email', 'builder-contact'),
		'subject'=>__('Subject', 'builder-contact'),
		'message'=>__('Message', 'builder-contact')
	];
	foreach($def as $k=>$v){
		if($fields_args['field_'.$k.'_label']==='' && $fields_args['field_'.$k.'_placeholder']===''){
			$fields_args['field_'.$k.'_label']=$v;
		}
	}
	unset($def);
}
$field_extra = is_string($fields_args['field_extra'])?json_decode( $fields_args['field_extra'], true ):$fields_args['field_extra'];
$field_order = is_string($fields_args['field_order'])?json_decode( $fields_args['field_order'], true ):$fields_args['field_order'];
$container_class = apply_filters('themify_builder_module_classes', array(
    'module','module-'.$mod_name, $element_id, 'contact-' . $fields_args['layout_contact'], $fields_args['css_class_contact']
), $mod_name, $element_id, $fields_args);

if(!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active===false){
    $container_class[] = $fields_args['global_styles'];
}

$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
	'id' => $element_id,
'class' => implode(' ',$container_class),
)), $fields_args, $mod_name, $element_id);

/* whether selective recipients is active, this shows a list of potential recipients for the email */
$selective_recipients = isset($fields_args['user_role'])   && $fields_args['user_role'] === 'sr' && (!isset( $fields_args['send_to_admins'] ) || $fields_args['send_to_admins'] === 'true');
$orders = array();
if ( 'yes' === $fields_args['field_name_active'] ) {
    $orders['name']=0;
}
if ( 'yes' === $fields_args['field_email_active'] ) {
    $orders['email']=1;
}
if ( 'yes' === $fields_args['field_subject_active'] ) {
    $orders['subject']=2;
}
if ( $selective_recipients ) {
    $orders['recipients'] = 3;
}
if ( 'yes' === $fields_args['field_message_active'] ) {
    $orders['message']=4;
}

foreach($orders as $k=>$v){
    $orders[$k]=isset($field_order['field_'.$k.'_label'])?(int)$field_order['field_'.$k.'_label']:0;
}
if(!empty($field_extra['fields'])){
    foreach( $field_extra['fields'] as $i => $field ){
		$orders[ 'extra_' . $i ] = (int) ( isset( $field['label'], $field_order[ $field['label'] ] ) ? $field_order[ $field['label'] ] : ( isset( $field['order'] ) ? $field['order'] : 0 ) );
    }
}

asort($orders,SORT_NUMERIC);
if(Themify_Builder::$frontedit_active===false){
    $container_props['data-lazy']=1;
}
if ( 'yes' === $fields_args['field_captcha_active'] ) {
	if ( 'r' === $fields_args['captcha_provider'] ) {
		$captcha_provider = 'recaptcha';
		$captcha_site_key = Builder_Contact::get_option( 'public_key' );
		$captcha_secret_key = Builder_Contact::get_option( 'private_key' );
		$recaptcha_version = Builder_Contact::get_option( 'version', 'v2' );
	} 
	elseif ( $fields_args['captcha_provider'] == 'h' ) {
		$captcha_provider = 'hcaptcha';
		$captcha_secret_key = themify_builder_get( 'setting-hcaptcha_secret', 'hcaptcha_secret' );
		$captcha_site_key = themify_builder_get( 'setting-hcaptcha_site', 'hcaptcha_site' );
	}
}
?>
<!-- module contact -->
<div <?php echo self::get_element_attributes(self::sticky_element_props($container_props,$fields_args)); ?>>
    <?php 
		$builderId=$args['builder_id'];
		$container_props=$container_class=$field_order=$args=null;
		$isAnimated=$fields_args['layout_contact']==='animated-label';
		if(method_exists('Themify_Builder_Component_Module','get_module_title')){
			echo Themify_Builder_Component_Module::get_module_title($fields_args,'mod_title_contact');
		}
		elseif ($fields_args['mod_title_contact'] !== ''){
			echo $fields_args['before_title'] , apply_filters('themify_builder_module_title', $fields_args['mod_title_contact'], $fields_args) , $fields_args['after_title'];
		}
		do_action('themify_builder_before_template_content_render'); 
	?>

	<form class="builder-contact"
		id="<?php echo $element_id; ?>-form"
		method="post"
		data-post-id="<?php esc_attr_e( $builderId ); ?>"
		data-element-id="<?php echo str_replace( 'tb_', '', $element_id); ?>"
		data-orig-id="<?php echo get_the_ID(); ?>"
	>
    <div class="contact-message"></div>
	<div class="builder-contact-fields tf_rel">
	<?php foreach($orders as $k=>$i):?>
	    <?php if ( $k==='name' || $k==='email' || $k==='subject' || $k==='message' || $k === 'recipients' ) :
			
			$label=$fields_args['field_'.$k.'_label'];
			$required = $k === 'recipients' || ('yes' === $fields_args["field_{$k}_active"] && isset($fields_args["field_{$k}_require"]) && 'yes' === $fields_args["field_{$k}_require"]);
			$placeholder= ( $isAnimated === false && $k !== 'recipients' ) ? $fields_args['field_'.$k.'_placeholder'] : ' ';
		?>
		    <div class="builder-contact-field builder-contact-field-<?php echo $k,($k==='message'?' builder-contact-textarea-field':' builder-contact-text-field')?>">
			    <label class="control-label" for="<?php echo $element_id; ?>-contact-<?php echo $k?>">
					<?php if ( ! empty( $fields_args[ $k . '_icon' ] ) ) : ?>
						<em><?php echo themify_get_icon( $fields_args[ $k . '_icon' ] ); ?></em>
					<?php endif; ?>
					<span class="tb-label-span"><?php if ($label!== ''): ?><?php echo $label; ?> </span><?php if ( $required ) : ?><span class="required">*</span><?php endif; endif; ?>
				</label>
			    <div class="control-input tf_rel">
				    <?php if ( $k === 'recipients' ) : ?>

						<?php if ( $fields_args['sr_display'] === 'select' ) : ?><select name="contact-recipients" id="<?php echo $element_id; ?>-contact-recipients"><?php endif; ?>
						<?php foreach( $fields_args['sr'] as $i => $recipient ) :
							if ( empty( $recipient['email'] ) ) {
								continue;
							} 
							if ( empty( $recipient['label'] ) ) {
								$recipient['label'] = $recipient['email'];
							}
						?>
							<?php if ( $fields_args['sr_display'] === 'radio' ) : ?>
							<label><input type="radio" name="contact-recipients" value="<?php echo $i; ?>" required <?php checked( $i, 0 ); ?>><?php esc_html_e( $recipient['label'] ); ?></label>
							<?php else : ?>
								<option value="<?php echo $i; ?>" required><?php esc_html_e( $recipient['label'] ); ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
						<?php if ( $fields_args['sr_display'] === 'select' ) : ?></select><?php endif; ?>

				    <?php elseif ( $k === 'message' ) : ?>
					    <textarea name="contact-message" placeholder="<?php echo $placeholder ?>" id="<?php echo $element_id; ?>-contact-message" class="form-control"<?php echo $required ? ' required' : '' ?>></textarea>
				    <?php else:?>
					    <input type="<?php echo $k === 'email' ? 'email' : 'text'; ?>" name="contact-<?php echo $k?>" placeholder="<?php echo $placeholder; ?>" id="<?php echo $element_id; ?>-contact-<?php echo $k?>" value="" class="form-control" <?php echo $required===true ? 'required' : '' ?>>
				    <?php endif;?>
				    <?php if($isAnimated===true):?>
					    <span class="tb_contact_label">
						    <span class="tb-label-span"><?php if ($label !== ''): ?><?php echo $label; ?> </span><?php if ( $required ) : ?><span class="required">*</span><?php endif; endif; ?>
					    </span>
				    <?php endif;?>
			    </div>
		</div>
	    <?php else:?>
		    <?php 
		    $index = str_replace('extra_','',$k);
		    if(!isset($field_extra['fields'][$index])){
				continue;
		    }
		    $field = $field_extra['fields'][$index];
			$type=$field['type'];
		    $value = isset( $field['value'] ) ? $field['value'] : '';
			$label = isset( $field['label'] ) ? $field['label'] : '';
		    $required = isset( $field['required'] ) && true === $field['required']?' required':'';
			$inputName='field_extra_'.$index;
			$inputId='field_extra_'.$element_id . '_' . $index;
			?>
		    <div class="builder-contact-field builder-contact-field-extra<?php if($type==='tel'):?> builder-contact-text-field<?php endif;?> builder-contact-<?php echo $type; ?>-field">
				
				<label class="control-label"<?php if ( ! in_array( $type, [ 'radio', 'checkbox', 'static' ], true ) ) : ?> for="<?php echo $inputId ?>"<?php endif; ?>>
					<?php if ( ! empty( $field['icon'] ) ) : ?>
						<em><?php echo themify_get_icon( $field['icon'] ); ?></em>
					<?php endif; ?>

					<?php echo $label; ?>
					<?php if( 'static' !== $type ):?>
						<input type="hidden" name="field_extra_name_<?php echo $index; ?>" value="<?php echo $label; ?>">
					<?php endif;
					if( $required!==''): ?>
						<span class="required">*</span>
					<?php endif; ?>
				</label>
				<div class="control-input tf_rel">
					<?php if( 'textarea' === $type ): ?>
						<textarea name="<?php echo $inputName; ?>" id="<?php echo $inputId ?>" placeholder="<?php echo $isAnimated===false?esc_html($value):' '; ?>" class="form-control"<?php echo $required ?>></textarea>
					<?php elseif( 'text' === $type ||  'tel' === $type || 'upload' === $type|| $type === 'email' || $type === 'number' ) : ?>
						<input type="<?php echo $type==='upload' ? 'file' : $type; ?>" name="<?php echo $inputName; ?>" id="<?php echo $inputId?>" placeholder="<?php echo ($isAnimated===false &&  'upload' !== $type)?esc_html($value):' '; ?>" class="form-control"<?php echo $required ?><?php if ( $type === 'upload' && ! empty( $field['allowed'] ) ) : ?> accept="<?php echo Builder_Contact::get_allowed_types_attr( $field['allowed'] ) ?>"<?php endif; ?>>
					<?php elseif( 'date' === $type) : ?>
						<input type="<?php echo isset( $field['show'] ) ? $field['show'] : 'datetime-local' ?>" name="<?php echo $inputName; ?>" id="<?php echo $inputId?>" class="form-control"<?php echo $required ?> autocomplete="off">
					<?php elseif( 'static' === $type ): ?>
						<?php echo do_shortcode( $value ); ?>
					<?php elseif(!empty($value)):?>
						<?php if( 'radio' === $type|| 'checkbox' === $type ): ?>
							<?php 
							$count =count($value);
							foreach( $value as $value ): ?>
								<label>
									<input type="<?php echo $type?>" name="<?php echo $inputName,($type==='checkbox'?'[]':'')?>" value="<?php esc_attr_e($value); ?>" class="form-control"<?php echo ($required!=='' && ('radio' === $type || $count===1))?$required:''?>><?php echo $value; ?>
								</label>
							<?php endforeach; ?>
						<?php elseif( 'select' === $type ): ?>
							<select id="<?php echo $inputId ?>" name="<?php echo $inputName; ?>" class="form-control tf_scrollbar"<?php echo $required ?>>
								<?php if($required===''):?>
									<option value=""></option>
								<?php endif;?>
								<?php foreach( $value as $value ): ?>
									<option value="<?php esc_attr_e($value); ?>"> <?php echo strip_tags($value); ?> </option>
								<?php endforeach; ?>
							</select>
						<?php endif; ?>
					<?php endif; ?>

					<?php if($isAnimated===true && ('text' === $type || 'tel' === $type || 'textarea' === $type)):?>
						<span class="tb_contact_label">
							<?php echo $label; 
							if( $required!==''): ?>
								<span class="required">*</span>
							<?php endif; ?>
						</span>
					<?php endif;?>
				</div>
		    </div>
	    <?php endif;?>

	<?php endforeach;?>
	    <?php if ( 'yes' === $fields_args['field_sendcopy_active'] ) : ?>
		<div class="builder-contact-field builder-contact-field-sendcopy">
		    <div class="control-label">
				<div class="control-input tf_rel">
					<label class="send-copy">
						<input type="checkbox" name="contact-sendcopy" id="<?php echo $element_id; ?>-sendcopy" value="1">
						<span><?php echo $fields_args['field_sendcopy_label']; ?></span>
					</label>
				</div>
		    </div>
		</div>
	    <?php endif; ?>
		<?php if ( $fields_args['field_optin_active'] && $fields_args['provider']!=='') : ?>
			<?php
			if ( ! class_exists( 'Builder_Optin_Service' ,false) ){
				include_once( THEMIFY_BUILDER_INCLUDES_DIR. '/optin-services/base.php' );
			}
			$optin_instance = method_exists('Builder_Optin_Service', 'get_settings')?Builder_Optin_Service::get_providers( $fields_args['provider'],true ):Builder_Optin_Service::get_providers( $fields_args['provider']);
			$optin_inputs='';
			if($optin_instance){			
				$options=is_string($optin_instance)?$optin_instance::get_settings():$optin_instance->get_options();	
				foreach ( $options as $provider_field ) {
					if ( isset( $provider_field['id'], $fields_args[ $provider_field['id'] ] ) ){
						$optin_inputs .= '<input type="hidden" name="contact-optin-'.$provider_field['id'].'" value="'.esc_attr( $fields_args[ $provider_field['id'] ] ).'" />';
					}
				}
				unset($options);
			}
			unset($optin_instance);
			if ( ''!==$optin_inputs ) : ?>
				<div class="builder-contact-field builder-contact-field-optin">
					<div class="control-label">
						<div class="control-input tf_rel">
							<input type="hidden" name="contact-optin-provider" value="<?php esc_attr_e( $fields_args['provider'] ); ?>">
							<?php echo $optin_inputs; ?>
							<label class="optin">
								<input type="checkbox" name="contact-optin" id="<?php echo $element_id; ?>-optin" value="1"> <?php echo $fields_args['field_optin_label']; ?>
							</label>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( 'accept' === $fields_args['gdpr'] ) : ?>
			<div class="builder-contact-field builder-contact-field-gdpr">
				<div class="control-label">
					<div class="control-input tf_rel">
						<label class="field-gdpr">
							<input type="checkbox" name="gdpr" value="1" required>
							<span><?php echo $fields_args['gdpr_label']; ?></span>
							<span class="required">*</span>
						</label>
					</div>
				</div>
			</div>
		<?php endif; ?>

	    <?php if ( 'yes' === $fields_args['field_captcha_active'] && $captcha_site_key != '' && $captcha_secret_key != '' ) : ?>
			<div class="builder-contact-field builder-contact-field-captcha">

				<?php if ( ! isset( $recaptcha_version ) || 'v3' !== $recaptcha_version ) : ?>
					<label class="control-label">
						<span><?php echo $fields_args['field_captcha_label']; ?></span>
						<span class="required">*</span>
					</label>
				<?php endif; ?>

				<div class="control-input tf_rel">
					<?php if ( $captcha_provider === 'recaptcha' ): ?>
						<div class="themify_captcha_field <?php if ( 'v2' === $recaptcha_version ) :?>g-recaptcha<?php endif; ?>" data-sitekey="<?php esc_attr_e( $captcha_site_key ); ?>" data-ver="<?php esc_attr_e($recaptcha_version); ?>"></div>
					<?php elseif ( $captcha_provider === 'hcaptcha' ): ?>
						<div class="themify_captcha_field h-captcha" data-sitekey="<?php esc_attr_e( $captcha_site_key ); ?>"></div>
					<?php endif; ?>
				</div>

			</div><!-- .builder-contact-field-captcha -->
	    <?php endif; ?>

	    <div class="builder-contact-field builder-contact-field-send control-input tf_text<?php echo $fields_args['field_send_align'][0];?> tf_clear tf_rel">
			<button type="submit" class="btn btn-primary">
				<?php if(Themify_Builder::$frontedit_active===false):?><span class="tf_loader"></span><?php endif;?>
				<span class="tf_submit_icon"><?php if ( ! empty( $fields_args['send_icon'] ) ) echo themify_get_icon( $fields_args['send_icon'] ); ?></span> 
				<?php echo $fields_args['field_send_label']; ?>
			</button>
	    </div>
	</div>
    </form>
    <?php do_action('themify_builder_after_template_content_render'); ?>
</div>
<!-- /module contact -->
