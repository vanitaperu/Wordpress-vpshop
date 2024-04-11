<?php
/***************************************************************************
 *
 * 	----------------------------------------------------------------------
 * 						DO NOT EDIT THIS FILE
 *	----------------------------------------------------------------------
 * 
 *  				     Copyright (C) Themify
 * 
 *	----------------------------------------------------------------------
 *
 ***************************************************************************/

/* 	Database Functions
/***************************************************************************/

/**
 * Save Data
 * @since 1.0.0
 * @package themify
 */
function themify_set_data(?array $data ):bool {
	if ( empty( $data )) {
            $data = array();
	}
	else{
		unset($data['save'],$data['page']);
		foreach ( $data as $name => $value ) {
			if ($value==='' || $value==='default' || $value==='[]') {
				unset( $data[$name] );
			}
		}
	}
	if(update_option( 'themify_data', $data,false )){
		themify_get_data(true);
		return true;
	}
	//check if it's error, because wp returns false on errors and when old value and new are the same
	$old=themify_get_data();
	return $old===$data || maybe_serialize( $old ) === maybe_serialize( $data );
}

/**
 * Return cached data
 */
function themify_get_data($reinit=false,$from=false):array {
	static $data=null;
	if ($data===null || $reinit!==false) {
		$skip_cache = defined( 'THEMIFY_SKIP_DATA_CACHE' ) && true === THEMIFY_SKIP_DATA_CACHE;
		if ( $skip_cache===false ) {
			if(defined('THEMIFY_SETTING_CACHE_DIR')){
				$dir=trailingslashit(THEMIFY_SETTING_CACHE_DIR);
			}
			else{
				$dir=__DIR__.DIRECTORY_SEPARATOR.'.data'.DIRECTORY_SEPARATOR;
			}
			if(is_multisite()){
				$dir.=get_current_blog_id().DIRECTORY_SEPARATOR;
			}
			$prefix='themify_settings_';
			$fname=$prefix.basename(dirname(__DIR__)).'_'.THEMIFY_VERSION.'_'.Themify_Enqueue_Assets::$themeVersion;
			$orig=$fname.'.php';
			if($reinit!==true && $from!=='db' && is_file($dir.$orig)){
				include $dir.$orig;
				if(isset($_arr)){
					$data =$_arr;
				}
			}
		}
		if($data===null || $reinit===true){
			$data=get_option( 'themify_data', array() );
			if(empty($data)){
				$data=array();
			}
			themify_sanitize_data($data);
			if($reinit===false){
				$data = apply_filters( 'themify_get_data', $data );
			}
			if ( $skip_cache===false ) {
				$tmpName=$dir.uniqid($prefix,true).'.php';
				if(is_file($dir.$orig) && !Themify_Filesystem::rename($dir.$orig,$tmpName)){
					Themify_Filesystem::delete($dir.$orig);
				}
				clearstatcache();
				if(Themify_Filesystem::mkdir($dir,true,0755) && is_writable($dir)){
					$str="<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly";
					$str.=PHP_EOL.'$_arr='.var_export ($data,true).';';
					//create a tmp file than rename, because the rename is atomic
					if (file_put_contents($tmpName, $str) && Themify_Filesystem::rename($tmpName,$dir.$orig) && ($handle = opendir($dir))) {//remove old caches
						while (false !== ($f = readdir($handle))) {
							if ($f !== '.' && $f !== '..' && $f!==$orig  && strpos($f, $prefix) === 0 && pathinfo($f,PATHINFO_EXTENSION)=== 'php') {
								Themify_Filesystem::delete($dir . $f);
							}
						}
						closedir($handle);
						clearstatcache();
					}else{
						Themify_Filesystem::delete($tmpName);
					}
				}
			}
		}
	}
	return $data;
}

/**
 * Abstract away normalizing the data
 */
function themify_sanitize_data(array &$data ){
	if ( !empty( $data )) {
		$html=array( 'setting-custom_css', 'setting-header_html', 'setting-footer_html', 'setting-footer_text_left', 'setting-footer_text_right', 'setting-homepage_welcome', 'setting-store_info_address' );
		foreach( $data as $name => &$value ){
			if ( in_array( $name,$html ,true )
				|| ( false !== stripos( $name, 'setting-hooks' ) )
			) {
				$value = str_replace( "\'", "'", $value );
			} else {
				$value = stripslashes( $value );
			}
		}
	}
}