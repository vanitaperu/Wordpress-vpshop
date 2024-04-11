<?php 
	global $product, $post,$themify;
	$attachment_ids = $product->get_gallery_image_ids();
	$isZoom=themify_get_gallery_type()!=='disable-zoom';
	$id=$product->get_image_id();
	if(!empty($id)){
		array_unshift($attachment_ids,$id);
	}
	$width=$themify->width;
	$height=$themify->height;
	$size = wc_get_image_size(Themify_WC::ThumbImageSize );
	$thumbW=$size['width'];
	$thumbH=$size['height'];
	$images=array();
?>
<div class="tf_swiper-container tf_carousel product-images-carousel tf_overflow">
	<div class="tf_swiper-wrapper tf_rel tf_w tf_h">
		<?php foreach ( $attachment_ids as $i=>$attach ):?>
			<?php 
				if(has_post_thumbnail()){
					$props = wc_get_product_attachment_props( $attach, $post );
					if(!empty($props['url'])){
						$images[]=$props['url'];
						$image = themify_get_image( array('src'=>$props['url'],'w'=>$width,'h'=>$height,'is_slider'=>$i!==0,'lazy_load'=>$i!==0,'image_meta'=>false,'image_size'=>Themify_WC::SingleImageSize) );
						$hasZoom=$isZoom;
					}
				}
				else{
					$src=wc_placeholder_img_src();
					$images[]=$src;
					$image=sprintf( '<img src="%s" width="%s" height="%s" alt="%s" class="wp-post-image">'
					,$src
					,$width
					,$height
					,esc_html__( 'Awaiting product image', 'themify' ) );
					$hasZoom=false;
					if($i!==0){
						$image=themify_make_lazy($image,false);
					}
				}
			?>
			<div <?php if($hasZoom===true ):?>data-zoom-image="<?php echo $props['url']?>" <?php endif;?> class="tf_swiper-slide woocommerce-main-image woocommerce-product-gallery__image post-image<?php if($hasZoom===true ):?> zoom<?php endif;?>">
				<?php echo $image;?>
			</div>
		<?php endforeach;?>
	</div>
</div>
<?php 
$count=count($images);
if($count>1): ?>
<div class="product_sw_thumb tf_rel"><?php //need for navigation when there are a lot of images?>
	<div class="tf_swiper-container tf_sw_thumbs tf_rel tf_overflow <?php echo $count>6?'sw_has_arrows':'tf_h'?>">
		<div class="tf_swiper-wrapper tf_abs_t tf_h">
		<?php foreach ( $images as $i=>$url ):?>
			<div class="tf_swiper-slide post-image">
				<?php echo themify_get_image( array('src'=>$url,'w'=>$thumbW,'h'=>$thumbH,'is_slider'=>$i>6,'lazy_load'=>$i>6,'image_meta'=>false,'image_size'=>Themify_WC::ThumbImageSize,'disable_responsive'=>true) ); ?>
			</div>
		<?php  endforeach;?>
		</div>
	</div>
</div>
<?php endif; ?>