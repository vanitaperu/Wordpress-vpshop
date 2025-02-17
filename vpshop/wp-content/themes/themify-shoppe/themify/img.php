<?php
/**
 * Routines for generation of custom image sizes and deletion of these sizes.
 *
 * @since 1.9.0
 * @package themify
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'themify_do_img' ) ) {
	/**
	 * Resize images dynamically using wp built in functions
	 *
	 * @param string|int $image Image URL or an attachment ID
	 * @param int $width
	 * @param int $height
	 * @param bool $crop
	 * @return array
	 */
	function themify_do_img( $image, $width, $height,bool $crop = false ):array {
		$attachment_id =$img_url= null;
		if(!is_numeric( $width ) ){
			$width='';
		}
		if(!is_numeric( $height ) ){
			$height='';
		}
		// if an attachment ID has been sent
		if( is_numeric( $image ) ) {
			$post = get_post( $image );
			if( $post ) {
				$attachment_id = $post->ID;
				$img_url = wp_get_attachment_url( $attachment_id );
			}
			unset($post);
		} else {
			if(strpos($image,'data:image/' )!==false ){
				return array(
					'url' =>$image,
					'width' => $width,
					'height' => $height
				);
			}
			// URL has been passed to the function
			$img_url = esc_url( $image );

			// Check if the image is an attachment. If it's external return url, width and height.
			if(strpos($img_url,themify_upload_dir('baseurl'))===false){
				if($width==='' || $height===''){
					$size = themify_get_image_size($img_url);
					if($size!==false){
						if($width===''){
							$width=$size['w'];
						}
						if($height===''){
							$height=$size['h'];
						}
					}
				}
				return array(
					'url' =>$img_url,
					'width' => $width,
					'height' => $height
				);
			}
			// Finally, run a custom database query to get the attachment ID from the modified attachment URL
			$attachment_id = themify_get_attachment_id_from_url( $img_url);
		}
		// Fetch attachment metadata. Up to this point we know the attachment ID is valid.
		$meta = $attachment_id ?wp_get_attachment_metadata( $attachment_id ):null;

		// missing metadata. bail.
		if (!is_array( $meta )) {
			if($img_url!==null){
				$ext=strtolower(strtok(pathinfo($img_url,PATHINFO_EXTENSION ),'?'));
				if($ext==='png' || $ext==='jpg' || $ext==='jpeg' || $ext==='webp' || $ext==='gif' ||$ext==='bmp' ){//popular types
					$upload_dir = themify_upload_dir();
					$attached_file=str_replace($upload_dir['baseurl'],$upload_dir['basedir'],$img_url);
					if(!is_file ($attached_file)){
						$attached_file=$attachment_id?get_attached_file( $attachment_id ):null;
					}
					if($attached_file){
						$size=themify_get_image_size($attached_file,true);
						if($size){
							$meta=array(
							'width'=>$size['w'],
							'height'=>$size['h'],
							'file'=>trim(str_replace($upload_dir['basedir'],'',$attached_file),'/')
							);
							//if the meta doesn't exist it means the image large size also doesn't exist,that is why checking if the image is too large before cropping,otherwise the site will down
							if($meta['width']>2560 || $meta['height']>2560){
								return array(
									'url' => $img_url,
									'width' => $width,
									'height' => $height,
									'is_large'=>true
								);
							}

						}
						unset($upload_dir,$ext,$size,$attached_file);
					}
				}
			}
			if ( ! is_array( $meta ) ) {
				return array(
					'url' => $img_url,
					'width' => $width,
					'height' => $height
				);
			}
		}

		// Perform calculations when height or width = 0
		if( empty( $width ) ) {
			$width = 0;
		}
		if ( empty( $height ) ) {
			// If width and height or original image are available as metadata
			if ( !empty( $meta['width'] ) && !empty( $meta['height'] ) ) {
				// Divide width by original image aspect ratio to obtain projected height
				// The floor function is used so it returns an int and metadata can be written
				$height = (int)(floor( $width / ( $meta['width'] / $meta['height'] ) ));
			} else {
				$height = 0;
			}
		}
		// Check if resized image already exists
		if ( is_array( $meta ) && isset( $meta['sizes']["resized-{$width}x{$height}"] ) ) {
			$size = $meta['sizes']["resized-{$width}x{$height}"];
			if( isset( $size['width'],$size['height'] )) {
				$split_url = explode( '/', $img_url );
				
				if( ! isset( $size['mime-type'] ) || $size['mime-type'] !== 'image/gif' ) {
					$split_url[ count( $split_url ) - 1 ] = $size['file'];
				}

				return array(
					'url' => implode( '/', $split_url ),
					'width' => $width,
					'height' => $height,
					'attachment_id' => $attachment_id
				);
			}
		}

		// Requested image size doesn't exists, so let's create one
		if ( true === $crop ) {
			add_filter( 'image_resize_dimensions', 'themify_img_resize_dimensions', 10, 5 );
		}
		// Patch meta because if we're here, there's a valid attachment ID for sure, but maybe the metadata is not ok.
		if ( empty( $meta ) ) {
			$meta['sizes'] = array( 'large' => array() );
		}
		// Generate image returning an array with image url, width and height. If image can't be generated, original url, width and height are used.
		$image = themify_make_image_size( $attachment_id, $width, $height, $meta, $img_url );
		
		if ( true === $crop ) {
			remove_filter( 'image_resize_dimensions', 'themify_img_resize_dimensions', 10 );
		}
		$image['attachment_id'] = $attachment_id;
		return $image;
	}
}
if ( ! function_exists( 'themify_make_image_size' ) ) {
	/**
	 * Creates new image size.
	 *
	 * @uses get_attached_file()
	 * @uses image_make_intermediate_size()
	 * @uses wp_update_attachment_metadata()
	 * @uses get_post_meta()
	 * @uses update_post_meta()
	 *
	 * @param int $attachment_id
	 * @param int $width
	 * @param int $height
	 * @param array $meta
	 * @param string $img_url
	 *
	 * @return array
	 */
	function themify_make_image_size( $attachment_id, $width, $height, $meta, $img_url ):array {
		if($width!==0 || $height!==0){
			$upload_dir = themify_upload_dir();
			$attached_file=str_replace($upload_dir['baseurl'],$upload_dir['basedir'],$img_url);
			unset($upload_dir);
			if(!Themify_Filesystem::is_file ($attached_file)){
				$attached_file=get_attached_file( $attachment_id );
			}
			$source_size = apply_filters( 'themify_image_script_source_size', themify_get( 'setting-img_php_base_size', 'large', true ) );
			if ( $source_size !== 'full' && isset( $meta['sizes'][ $source_size ]['file'] ) ){
				$attached_file = str_replace( $meta['file'], trailingslashit( dirname( $meta['file'] ) ) . $meta['sizes'][ $source_size ]['file'], $attached_file );
			}
			unset($source_size);
			$resized = image_make_intermediate_size( $attached_file, $width, $height, true );
			if ( $resized && ! is_wp_error( $resized ) ) {
				// Save the new size in metadata
				$key = sprintf( 'resized-%dx%d', $width, $height );
				$meta['sizes'][$key] = $resized;
				$img_url = str_replace( basename( $img_url ), $resized['file'], $img_url );

				wp_update_attachment_metadata( $attachment_id, $meta );
				// Save size in backup sizes, so it's deleted when the original attachment is deleted.
				$backup_sizes = get_post_meta( $attachment_id, '_wp_attachment_backup_sizes', true );
				if ( ! is_array( $backup_sizes ) ){
					$backup_sizes = array();
				}
				$backup_sizes[$key] = $resized;
				update_post_meta( $attachment_id, '_wp_attachment_backup_sizes', $backup_sizes );
				$img_url=esc_url($img_url);
			}
		}
		// Return original image url, width and height.
		return array(
			'url' => $img_url,
			'width' => $width,
			'height' => $height
		);
	}
}



/**
 * Disable the min commands to choose the minimum dimension, thus enabling image enlarging.
 *
 * @param $default
 * @param $orig_w
 * @param $orig_h
 * @param $dest_w
 * @param $dest_h
 * @return array
 */
function themify_img_resize_dimensions( $default, $orig_w, $orig_h, $dest_w, $dest_h ):array {
	// set portion of the original image that we can size to $dest_w x $dest_h
	$aspect_ratio = $orig_w / $orig_h;
	$new_w = $dest_w;
	$new_h = $dest_h;

	if ( !$new_w ) {
		$new_w = (int)( $new_h * $aspect_ratio );
	}

	if ( !$new_h ) {
		$new_h = (int)( $new_w / $aspect_ratio );
	}

	$size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

	$crop_w = round( $new_w / $size_ratio );
	$crop_h = round( $new_h / $size_ratio );

	$s_x = floor( ( $orig_w - $crop_w ) / 2 );
	$s_y = floor( ( $orig_h - $crop_h ) / 2 );

	// the return array matches the parameters to imagecopyresampled()
	// int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
	return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}

if( ! function_exists( 'themify_get_attachment_id_from_url' ) ) :
	/**
	 * Get attachment ID for image from its url.
	 * @param deprecated $base_url
	 */
	function themify_get_attachment_id_from_url(string $url = '', $base_url = '' ):int {
		/* cache IDs, for when an image is displayed multiple times on the same page */
		static $cache = array();

		// If this is the URL of an auto-generated thumbnail, get the URL of the original image
		$url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif|webp|bmp)$)/i', '', $url );
		if ( ! empty( $url ) ) {
			if ( ! isset( $cache[ $url ] ) ) {
				$cache[ $url ] = themify_get_attachment_id_cache( $url );
			}
			return $cache[ $url ];
		}
		return 0;
	}
endif;

/**
 * Convert image URL to attachment ID, data is cached in a db for faster access
 */
function themify_get_attachment_id_cache(string $url ):int {
	$k=$url.'_id';
	$id = Themify_Storage::get($k);
	if ($id==='0' || ($id>0 && get_post_type($id)==='attachment') ) {
	    return (int) $id;
	} 
	$id = attachment_url_to_postid( $url );
	Themify_Storage::set($k,$id);
	return $id;
}


/**
 * Removes protocol and www from URL and returns it
 *
 * @return string
 */
function themify_remove_protocol_from_url( $url ) {//deprecated will be removed
	return preg_replace( '/https?:\/\/(www\.)?/', '', $url );
}


function themify_create_webp(string $url):string{//@todo move to class

    $res=$url;
    $info = pathinfo($res);
    if(!isset($info['extension'])){
        return $url;
    }
    $orig_ex = strtok($info['extension'],'?');
    if($orig_ex!=='png' && $orig_ex!=='jpg' && $orig_ex!=='jpeg' && $orig_ex!=='gif'){
        return $url;
    }
    static $available=null;
    if($available===NULL){
        $available=array();
        if(apply_filters('themify_disable_webp',false)===false){
            if(class_exists('Imagick',false)){
                $im = new Imagick();
                if (in_array('WEBP', $im->queryFormats('WEBP'),true) ) {
                    $available['Imagick']=true;
                }
                $im->clear();
                $im=null;
            }
            if(!isset($available['Imagick']) &&function_exists('imagewebp') && (function_exists('imagecreatefromjpeg') || function_exists('imagecreatefrompng'))){
                $available['GD']=true;
            }
        }
    }
    if(!empty($available)){
        $upload_dir=  themify_upload_dir();
        $sameDomain=strpos($url,$upload_dir['baseurl'])!==false;
        if($sameDomain===false && strpos($url,'http')!==0){//relative to absolute
            $tmp_url = home_url($url);
            $sameDomain=strpos($tmp_url,$upload_dir['baseurl'])!==false;
            if($sameDomain===true){
                $res=$tmp_url;
            }
        }
        if(is_multisite()){
            if($sameDomain===false){
                if(is_subdomain_install()){
                    $blog_name = explode('.',$_SERVER['SERVER_NAME']);
                    $blog_name=$blog_name[0];
                    if(strpos($url,$blog_name)===false){
                        return $url;
                    }
                }
                else{
                    if(!isset($_SERVER['SERVER_NAME']) || strpos($url,$_SERVER['SERVER_NAME'])===false){
                        return $url;
                    }
                    static $site_url=null;
                    if($site_url===null){
                        $site_url = dirname(site_url());
                    }
                    if(strpos($url,$site_url)===false){
                        return $url;
                    }
                    $blog_name =explode('/',trim(str_replace($site_url,'',$url),'/'));
                    $blog_name=$blog_name[0];
                }
                static $sites=array();
                if(!isset($sites[$blog_name])){
                    $blog = get_id_from_blogname($blog_name);
                    if($blog===null){
                        $sites[$blog_name]=false;
                        return $url;
                    }
                    $currentBlog=pathinfo(get_site_url(),PATHINFO_FILENAME);
                    switch_to_blog($blog );

                    $blog_upload_dir_info = wp_get_upload_dir();
                    restore_current_blog();
                    $sites[$blog_name] = array('basedir'=>$blog_upload_dir_info['basedir'],'baseurl'=>str_replace('/'.$currentBlog.'/','/'.$blog_name.'/',$blog_upload_dir_info['baseurl']));// bug in WP return the current blog name url,not switched
                }
                elseif($sites[$blog_name]===false){
                    return $url;
                }
                $upload_dir=$sites[$blog_name];
            }
        }
        elseif($sameDomain===false){
            return $url;
        }
        $res=str_replace($upload_dir['baseurl'],$upload_dir['basedir'],$res);
        if(strpos($res,'http')===0){
            return $url;
        }
        $resUrl=str_replace('.'.$orig_ex, '.webp', $res);
        if(is_file ($resUrl)){
            return str_replace($upload_dir['basedir'],$upload_dir['baseurl'],$resUrl);
        }
        if(!is_file ($res)){
            return $url;
        }
        $webp_quality = (int) themify_builder_get( 'setting-webp-quality', 'performance-webp_quality' );
        if ( empty( $webp_quality ) ) {
            $webp_quality = 5;
        }
        if(isset($available['Imagick'])){
            $im = new Imagick($res);
            $lowerExt=explode('/',$im->getImageMimeType());
            if(isset($lowerExt[1])){
                $lowerExt=str_replace('x-','',$lowerExt[1]);
            }else{
                $lowerExt=false;
            }
            if(($lowerExt!=='png' && $lowerExt!=='jpg' && $lowerExt!=='jpeg' && $lowerExt!=='gif') || $im->getImageWidth()>2560 || $im->getImageHeight()>2560){
                $im->clear();
                $im=null;
                return $url;
            }
            try {
                if($im->setImageFormat( 'webp' ) && $im->setOption( 'webp:method', $webp_quality ) && $im->setOption('webp:lossless','false') && $im->setOption('webp:low-memory', 'true') && $im->setOption('webp:use-sharp-yuv', 'true')) {

                    if (($lowerExt !== 'png' || ($im->setOption('webp:alpha-compression', 1) && $im->setOption('webp:alpha-quality', 85))) &&  $im->stripImage()) {

                        try {
                            $webp = $lowerExt === 'gif' ? $im->writeImages($resUrl, true) : $im->writeImage($resUrl);
                        }
                        catch (Throwable $e ){
                            $webp=false;
                        }
                        if(!$webp){
                            if($lowerExt === 'gif') {
                                try {
                                    $im->optimizeImageLayers();
                                }
                                catch (Throwable $e) {

                                }
                            }
                            $webp = file_put_contents($resUrl, ($lowerExt==='gif'?$im->getImagesBlob():$im->getImageBlob()));
                            if ($webp) {
                                $res = $resUrl;
                            }else{
                                Themify_Filesystem::delete($resUrl,'f');
                            }
                        }
                        $im->clear();
                        $im = null;
                    }
                }
            }
			catch (Throwable $e ){
                $im->clear();
                $im=null;
                return $url;
            }
        }
        else{
            if(function_exists('exif_imagetype')){
                $size=image_type_to_mime_type(exif_imagetype($res));
            }
            elseif(function_exists('finfo_file')){
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
				if($finfo!==false){
					$size=finfo_file($finfo, $res);
					finfo_close($finfo);
				}
                unset($finfo);
            }
			if(empty($size)){
				if(function_exists('mime_content_type')){
					$size = mime_content_type($res);
				}
				else{
					$size = getimagesize($res);
					if(!isset($size['mime']) || !isset($size[0]) || !isset($size[1]) || $size[0]>2560 || $size[1]>2560){
						return '';
					}
					$size=$size['mime'];
				}
			}
            if(empty($size)){
                return $url;
            }

            $size=explode('/',$size);
            if(!isset($size[1])){
                return $url;
            }
            $lowerExt=$size[1];
            unset($size);
            if($lowerExt!=='png' && $lowerExt!=='jpg' && $lowerExt!=='jpeg'){
                return $url;
            }

            switch($lowerExt){
                case 'jpeg':
                case 'jpg':
                    if(!function_exists('imagecreatefromjpeg')){
                        return $url;
                    }
                    $im = imagecreatefromjpeg($res);
                    break;
                case 'png':
                    if(!function_exists('imagecreatefrompng')){
                        return $url;
                    }
                    if(function_exists('imagepalettetotruecolor')){
                        $im = imagecreatefrompng($res);
                        if($im!==false && (!imagepalettetotruecolor($im) || !imagealphablending($im, true) || !imagesavealpha($im, true))){
                            imagedestroy($im);
                            $im=null;
                        }
                    }
                    else{
                        $pngimg  = imagecreatefrompng($res);
                        if($pngimg!==false) {
                            // get dimens of image
                            $w = imagesx($pngimg);
                            $h = imagesy($pngimg);
                            if ($w !== false && $h !== false) {
                                $im = imagecreatetruecolor($w, $h);
                                if ($im !== false && imagealphablending($im, false) && imagesavealpha($im, true)) {
                                    // By default, the canvas is black, so make it transparent
                                    $trans = imagecolorallocatealpha($im, 0, 0, 0, 127);
                                    if($trans===false || !imagefilledrectangle($im, 0, 0, $w - 1, $h - 1, $trans) || !imagecopy($im, $pngimg, 0, 0, 0, 0, $w, $h)) {
                                        imagedestroy($im);
                                        $im=null;
                                    }
                                }
                            }
                            imagedestroy($pngimg);
                        }
                        $pngimg=null;
                    }
                    break;
                default:
                    return $url;
            }

            if(empty($im)){
                return $url;
            }
            $res=$resUrl;
            $quality = array( 0 => 40, 1 => 50, 2 => 60, 3 => 70, 4 => 80, 5 => 90, 6 => 100 );
            $webp =imagewebp($im, $res, $quality[ $webp_quality ] );
            if(!$webp){
                Themify_Filesystem::delete($res,'f');
            }
            imagedestroy($im);
            $im=null;
        }
        return !empty($webp)?str_replace($upload_dir['basedir'],$upload_dir['baseurl'],$res):$url;
    }
    else{
        return $url;
    }
}

function themify_get_video_size(string $url):?array{
    $k=$url.'_size';
    $found=Themify_Storage::get($k);
    if($found===false){
		$found=null;
        $attachment_id=themify_get_attachment_id_from_url($url);
        if($attachment_id>0){
            $meta=wp_get_attachment_metadata( $attachment_id );
            if(empty($meta)){
                require_once ABSPATH . 'wp-admin/includes/media.php';
                $meta=wp_read_video_metadata(get_attached_file($attachment_id));
            }
            if(!empty($meta)){
                $found=array(
                    'w'=>isset($meta['width'])?$meta['width']:'',
                    'h'=>isset($meta['height'])?$meta['height']:'',
                    's'=>isset($meta['filesize'])?$meta['filesize']:'',
                    'f'=>isset($meta['fileformat'])?$meta['fileformat']:'',
                    'l'=>isset($meta['length_formatted'])?$meta['length_formatted']:'',
                    't'=>isset($meta['mime_type'])?$meta['mime_type']:''
                );
                Themify_Storage::set($k,$found,MONTH_IN_SECONDS*6);
            }
        }
    }
    else{
	    $found=json_decode($found,true);
		if($found===null && Themify_Storage::delete($k)!==false){
			return themify_get_video_size($url);
		}
    }
    return $found;
}

function themify_get_image_size(?string $url,bool $isLocal=false,int $color=0){//@todo move to class
	if(!isset($url[2])){
        return false;
	}
    if(strpos($url,'x',3)!==false){
		preg_match('/\-(\d+x\d+)\./i',$url,$m);
		if(isset($m[1])){
			$m=explode('x',$m[1]);
			$size= array('w'=>$m[0],'h'=>$m[1]);
			if($color===0){
				return $size;
			}
		}
		unset($m);
	}
    elseif(strpos($url,'gravatar.com')!==false){
        $parts = parse_url($url,PHP_URL_QUERY);
        if(!empty($parts)){
            parse_str($parts, $query_params);
            if(!empty($query_params['s'])){
                return array('w'=>$query_params['s'],'h'=>$query_params['s']);
            }
        }
    }
	$k=$url.'_size';
	if(!isset($size) || $color>0){
		$found=Themify_Storage::get($k);
		if($found!==''){
			if(strpos($found,':')!==false){
				$found=explode(':',$found)[1];
			}
			$found=explode('-',$found);
			if(isset($found[1])){
				$size= array('w'=>$found[0],'h'=>$found[1]);
				if($color===0){
					return $size;
				}
				if(isset($found[2])){
					$size['c']=explode(',',$found[2]);
					return $size;
				}
			}
		}
	}
    if($isLocal===false){
		if(defined('THEME_URI') && strpos($url,THEME_URI)!==false){
			$url=str_replace(THEME_URI,THEME_DIR,$url);
			$isLocal=true;
		}
		else{
			$upload_dir = themify_upload_dir();
			if(strpos($url,$upload_dir['baseurl'])!==false){
				$isLocal=true;
				$url=str_replace($upload_dir['baseurl'],$upload_dir['basedir'],$url);
			}
			unset($upload_dir);
		}
    }
	if(empty($size)){
		if (class_exists('Themify_Get_Image_Size',false) || is_file( THEMIFY_DIR . '/class-themify-get-image-size.php' ) ) {
			if($isLocal===false) {
				static $is = null;
				if ($is === null) {
					$is = apply_filters('tf_disable_remote_size', true);
				}
				if ($is === false) {
					return false;
				}
			}
			require_once THEMIFY_DIR . '/class-themify-get-image-size.php';
			$size=Themify_Get_Image_Size::getSize($url,$isLocal);
		}
		else{
			$size=false;
		}
		if($size===false && $isLocal===true && function_exists('getimagesize')){
			$size=getimagesize($url);
			$size=empty($size)?false:array('w'=>$size[0],'h'=>$size[1]);
		}
	}
	if($size!==false){
		$value=$size['w'].'-'.$size['h'];
		if($color>0 && $isLocal===true ){
			$colors=themify_get_image_color($url,$size['w'],$size['h'],$color);
			
			if(!empty($colors)){
				$size['c']=$colors;
				$value.='-'.implode(',',$colors);
			}
		}
	    Themify_Storage::set($k,$value);
	}
	return $size;
}

function themify_get_image_color(string $dir,int $w,int $h,int $rows=4):array{//@todo move to class
	$colors=[];
	if($w<=2560 || $h<=2560){
		$isImagIck=null;
		if(class_exists('Imagick',false)){
			try {
				$im = new Imagick($dir);
				$isImagIck=true;
			}
			catch(Throwable $e) {
				if(isset($im)){
					$im->clear();
					$im=null;
				}
			}
            
		}
		if(!isset($im)){
			$ext=strtok(pathinfo($dir,PATHINFO_EXTENSION),'?');
			if ( $ext === 'png' ) {
				if(function_exists('imagecreatefrompng')){
					$im = imagecreatefrompng($dir);
				}
			}
			elseif($ext==='jpg' || $ext==='jpeg'){
				if(function_exists('imagecreatefromjpeg')){
					$im = imagecreatefromjpeg($dir);
				}
			}
			elseif($ext==='gif'){
				if(function_exists('imagecreatefromgif')){
					$im = imagecreatefromgif($dir);
				}
			}
			elseif($ext==='webp'){
				if(function_exists('imagecreatefromwebp')){
					$im = imagecreatefromwebp($dir);
				}
			}
			elseif($ext==='bmp' && function_exists('imagecreatefrombmp')){
				$im = imagecreatefrombmp($dir);
			}
			unset($ext);
		}
		if(!empty($im)){
			$box_w=floor($w/$rows);
			$box_h=floor($h/$rows);
			$half_w=(int)($box_w/2);
			$half_h=(int)($box_h/2);
			for($y=1;$y<=$rows;++$y){
				$y_coord=$y*$box_h-$half_h;
				for($x=1;$x<=$rows;++$x){
					$x_coord=$x*$box_w-$half_w;
					if($isImagIck===true){
						try {
							$tmp=$im->getImagePixelColor($x_coord,$y_coord)->getColor(0);
							$color=array('red'=>$tmp['r'],'green'=>$tmp['g'],'blue'=>$tmp['b']);
						}
						catch(Throwable $e) {
							
						}
					}
					else{
						$color=imagecolorsforindex($im, imagecolorat($im, $x_coord,$y_coord));
					}
					if(isset($color)){
						if ($color['red']>=256){
							$color['red']=240;
						}
						if ($color['green']>=256){
							$color['green']=240;
						}
						if ($color['blue']>=256){
							$color['blue']=240;
						}
						$colors[]=substr('0'.dechex($color['red']),-2).substr('0'.dechex($color['green']),-2).substr('0'.dechex($color['blue']),-2);
					}
				}
			}
			if(isset($im)){
				if($isImagIck===true){
					$im->clear();
				}
				else{
					imagedestroy($im);
				}
				$im=null;
			}
		}
	}
	return $colors;
}