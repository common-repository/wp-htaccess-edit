<?php
if ( ! defined( 'ABSPATH' ) ) die( 'Silence is golden.' );

// Create a backup of the original htaccess file 

function wphe_create_backup(){

	$wphe_backup_path = ABSPATH.'htaccess.backup';
	$wphe_orig_path = ABSPATH.'.htaccess';
	@clearstatcache();

	if(file_exists($wphe_backup_path)){

		wphe_delete_backup();

		if(file_exists(ABSPATH.'.htaccess')){

			$htaccess_content_orig = @file_get_contents($wphe_orig_path, false, NULL);
			$htaccess_content_orig = trim($htaccess_content_orig);
			$htaccess_content_orig = str_replace('\\\\', '\\', $htaccess_content_orig);
			$htaccess_content_orig = str_replace('\"', '"', $htaccess_content_orig);
			@chmod($wphe_backup_path, 0666);
			$wphe_success = @file_put_contents($wphe_backup_path, $htaccess_content_orig, LOCK_EX);

			if($wphe_success === false){

				unset($wphe_backup_path);
				unset($wphe_orig_path);
				unset($htaccess_content_orig);
				unset($wphe_success);
				return false;

			}else{

				unset($wphe_backup_path);
				unset($wphe_orig_path);
				unset($htaccess_content_orig);
				unset($wphe_success);
				return true;

			}
			@chmod($wphe_backup_path, 0644);

		}else{

			unset($wphe_backup_path);
			unset($wphe_orig_path);
			return false;
		}

	}else{

		if(file_exists(ABSPATH.'.htaccess')){

			$htaccess_content_orig = @file_get_contents($wphe_orig_path, false, NULL);
			$htaccess_content_orig = trim($htaccess_content_orig);
			$htaccess_content_orig = str_replace('\\\\', '\\', $htaccess_content_orig);
			$htaccess_content_orig = str_replace('\"', '"', $htaccess_content_orig);
			@chmod($wphe_backup_path, 0666);
			$wphe_success = @file_put_contents($wphe_backup_path, $htaccess_content_orig, LOCK_EX);

			if($wphe_success === false){

				unset($wphe_backup_path);
				unset($wphe_orig_path);
				unset($htaccess_content_orig);
				unset($wphe_success);
				return false;

			}else{

				unset($wphe_backup_path);
				unset($wphe_orig_path);
				unset($htaccess_content_orig);
				unset($wphe_success);
				return true;

			}

			@chmod($wphe_backup_path, 0644);

		}else{

			unset($wphe_backup_path);
			unset($wphe_orig_path);
			return false;
		}
	}
}


// Restore backup 

function wphe_restore_backup(){

	$wphe_backup_path = ABSPATH.'htaccess.backup';
	$wphe_orig_path = ABSPATH.'.htaccess';

	@clearstatcache();

	if(!file_exists($wphe_backup_path)){

		unset($wphe_backup_path);
		unset($wphe_orig_path);
		return false;

	}else{

		if(file_exists($wphe_orig_path)){

			if(is_writable($wphe_orig_path)){

				@unlink($wphe_orig_path);

			}else{

				@chmod($wphe_orig_path, 0666);
				@unlink($wphe_orig_path);
			}
		}

		$wphe_htaccess_content_backup = @file_get_contents($wphe_backup_path, false, NULL);

		if(wphe_write_htaccess($wphe_htaccess_content_backup) === false){

			unset($wphe_success);
			unset($wphe_orig_path);
			unset($wphe_backup_path);
			return $wphe_htaccess_content_backup;

		}else{

			wphe_delete_backup();
			unset($wphe_success);
			unset($wphe_htaccess_content_backup);
			unset($wphe_orig_path);
			unset($wphe_backup_path);
			return true;
		}
	}
}



// Delete backup file

function wphe_delete_backup(){

	$wphe_backup_path = ABSPATH.'htaccess.backup';

	@clearstatcache();

	if(file_exists($wphe_backup_path)){

		if(is_writable($wphe_backup_path)){

			@unlink($wphe_backup_path);

		}else{

			@chmod($wphe_backup_path, 0666);
			@unlink($wphe_backup_path);
		}

		@clearstatcache();

		if(file_exists($wphe_backup_path)){

			unset($wphe_backup_path);

			return false;

		}else{

			unset($wphe_backup_path);
			return true;
		}

	}else{

		unset($wphe_backup_path);
		return true;
	}
}



// Create a new htaccess file 

function wphe_write_htaccess($wphe_new_content){

	$wphe_orig_path = ABSPATH.'.htaccess';

	@clearstatcache();

	if(file_exists($wphe_orig_path)){

		if(is_writable($wphe_orig_path)){

			@unlink($wphe_orig_path);

		}else{

			@chmod($wphe_orig_path, 0666);
			@unlink($wphe_orig_path);
		}
	}

	$wphe_new_content = trim($wphe_new_content);
	$wphe_new_content = str_replace('\\\\', '\\', $wphe_new_content);
	$wphe_new_content = str_replace('\"', '"', $wphe_new_content);
	$wphe_write_success = @file_put_contents($wphe_orig_path, $wphe_new_content, LOCK_EX);
	@clearstatcache();

	if(!file_exists($wphe_orig_path) && $wphe_write_success === false){

		unset($wphe_orig_path);
		unset($wphe_new_content);
		unset($wphe_write_success);
		return false;

	}else{

		unset($wphe_orig_path);
		unset($wphe_new_content);
		unset($wphe_write_success);
		return true;
	}
}
