<?php

class dataImporter
{

	public $debug = true;
	public $gea = true;
	public $img_url = "http://flacso.org.br/gea/administracion/gea_clipping/imagenes/";
	//$img_url = "http://flacso.org.br/portal/intranet/flacsomidia/imagenes/";
	//$img_url = "http://flacso.org.br/gea/administracion/gea_clipping/imagenes/";
	
	public function ImportCsV($file_name = '/tmp/import.csv' )
	{
		if(file_exists($file_name))
		{
			ini_set("memory_limit", "2048M");
			set_time_limit(0);
			
			$file = fopen ($file_name, 'r');
			$row = fgetcsv( $file, 0, ';');
			$i = 0;
			$limit = 0;
			
			$user_id_from_email = get_user_by('email', get_blog_option(get_current_blog_id(), 'admin_email'));
			$author = get_userdata($user_id_from_email);
			
			if($i > 0)
			{
				for ($y = 0; $y < $i; $y++)
				{
					$row = fgetcsv( $file, 0, ';');
				}
			}
			
			do 
			{
				$post = array(
						'post_author'   => is_object($author) ? $author->ID : 1, //The user ID number of the author.
						'post_content'  => $row[4],
						'post_title'    => $row[1], //The title of your post.
						'post_type'     => 'post',
						'post_status'	=> $row[6] == 'ON' ? 'publish' : 'draft',
						'post_date'		=> $row[5]
				);
				$post_id = 0;
				
				if(!$this->debug) $post_id = wp_insert_post($post);
				
				FlacsoSettingsPage::log("OK (".$post_id.")");
				
				
				/**
				 * Metas
				 */
				if($this->debug)
				{
					FlacsoSettingsPage::log("fonte:".$row[2], $this->debug);
					FlacsoSettingsPage::log("editoria:".$row[9], $this->debug);
					FlacsoSettingsPage::log("url:".$row[11], $this->debug);
				}
				else
				{
					update_post_meta($post_id, $row[2], 'fonte');
					update_post_meta($post_id, $row[9], 'editoria');
					update_post_meta($post_id, $row[11], 'url');
				}
				
				/**
				 * Taxonomies
				 */
				
				$country_id = self::TreatCountry($post_id, $row[8]);
				self::TreatState($post_id, $row[10], $country_id);
				self::TreatTerritory($post_id, $row[13]);
				if(!is_null($row[12]) && $row[12] != 'NULL' && strlen($row[12]) > 1)
				{
					self::sideload_post_thumb($post_id, $this->img_url.$row[12],sanitize_title($row[1]));
				}
				if($this->gea)
				{
					self::TreatGea($post_id);
				}
				
				$i++;
				
				$row = fgetcsv( $file, 0, ';');
				
			}
			while ($row !== false && ( $limit == 0 || $i < $limit) );
		}
		else 
		{
			FlacsoSettingsPage::log("File not found!", $this->debug);
		}
	}
	
	public function TreatCountry($post_id, $data)
	{
		$clean_data = ucwords(strtolower($data));
		
		$term = get_term_by('name', $clean_data, 'country');
		
		FlacsoSettingsPage::log("Term country ID: ".(print_r($term, true)).", Field: $clean_data", $this->debug);
		
		if($term !== false)
		{
			$this->InsertTerm($post_id, 'country', $term);
		}
		else 
		{
			if(!$this->debug)
			{
				$term = wp_insert_term($clean_data, 'country');
			}
			else
			{
				$term = $clean_data;
			}
			
			$this->InsertTerm($post_id, 'country', $term);
		}
		
		return $term;
	}
	
	public function TreatState($post_id, $state, $country)
	{
		$clean_state = ucwords(strtolower($state));
		$term = false;
		if(is_array($country))
		{
			$country = $country['term_id'];
		}
		elseif(is_object($country))
		{
			$country = $country->term_id;
		}
		
		$term = get_term_by('name', $clean_state, 'country');
		
		FlacsoSettingsPage::log("Term State ID: ".(print_r($term, true)).", Field: $state, Country: ".print_r($country, true), $this->debug);
		
		if($term !== false)
		{
			$this->InsertTerm($post_id, 'country', $term);
		}
		else
		{
			if(!$this->debug)
			{
				$term = wp_insert_term($clean_state, 'country',
					array(
					  	'description'=> $state,
					  	'slug' => sanitize_title($clean_state),
						'parent'=> $country
					)
				);
			}
			else 
			{
				$term = "T:".$country." => ".$clean_state;
			}
			$this->InsertTerm($post_id, 'country', $term);
		}
		
		return $term;
	}
	
	public function TreatTerritory($post_id, $data)
	{
		$territory = 'National';
		switch ($data)
		{
			case 1:
			{
				$territory = 'National';
			}break;
			case 2:
			{
				$territory = 'International';
			}break;
			case 3:
			{
				$territory = 'State';
			}break;
			default:
			{
				$territory = 'National';
			}break;
		}
		
		$term = get_term_by('name', $territory, 'territory');
		
		FlacsoSettingsPage::log("Term Ter: ".(print_r($term, true)).", Field: $territory", $this->debug);
		
		if($term !== false)
		{
			$this->InsertTerm($post_id, 'territory', $term);
		}
		else
		{
			FlacsoSettingsPage::log("Term Ter NULL: ".(print_r($term, true)).", Field: $territory", $this->debug);
			$term = wp_insert_term($territory, 'territory');
			$this->InsertTerm($post_id, 'territory', $term);
		}
	
		return $term;
	}
	
	public function TreatGea($post_id)
	{
		$term = get_term_by('name', 'GEA', 'gea');
		
		if($term !== false)
		{
			$this->InsertTerm($post_id, 'gea', $term);
		}
		else
		{
			FlacsoSettingsPage::log("Term Gea NULL: ".(print_r($term, true)), $this->debug);
			$term = wp_insert_term('GEA', 'gea');
			$this->InsertTerm($post_id, 'gea', $term);
		}
		
		return $term;
	}
	
	public function InsertTerm($post_id, $taxonomy, $term)
	{
		if(!is_object($term))
		{
			$term_obj = new stdClass();
			if(is_int($term))
			{
				$term_obj->term_id = $term;
			}
			elseif(is_array($term))
			{
				$term_obj->term_id = $term['term_id'];
			}
			
			$term = $term_obj;
		}
		if($this->debug)
		{
			FlacsoSettingsPage::log("InsertTerm: ".$taxonomy.": => ".(is_array($term) ? implode(" => ", $term) : $term), $this->debug);
		}
		else
		{
			wp_set_object_terms($post_id, intval($term->term_id), $taxonomy, true );
		}
	}
	
	public function fetch_remote_file( $url, $post ) {
	
		global $url_remap;
	
		// extract the file name and extension from the url
		$file_name = basename( $url );
	
		// get placeholder file in the upload dir with a unique, sanitized filename
		$upload = wp_upload_bits( $file_name, 0, '',
				array_key_exists('upload_date', $post) ? $post['upload_date'] : null );
		if ( $upload['error'] )
			return new WP_Error( 'upload_dir_error', $upload['error'] );
	
		// fetch the remote url and write it to the placeholder file
		$headers = wp_get_http( $url, $upload['file'] );
	
		// request failed
		if ( ! $headers ) {
			@unlink( $upload['file'] );
			return new WP_Error( 'import_file_error', __('Remote server did not respond', 'wordpress-importer') );
		}
	
		// make sure the fetch was successful
		if ( $headers['response'] != '200' ) {
			@unlink( $upload['file'] );
			return new WP_Error( 'import_file_error', sprintf( __('Remote server returned error response %1$d %2$s', 'wordpress-importer'), esc_html($headers['response']), get_status_header_desc($headers['response']) ) );
		}
	
		$filesize = filesize( $upload['file'] );
	
		if ( isset( $headers['content-length'] ) && $filesize != $headers['content-length'] ) {
			@unlink( $upload['file'] );
			return new WP_Error( 'import_file_error', __('Remote file is incorrect size', 'wordpress-importer') );
		}
	
		if ( 0 == $filesize ) {
			@unlink( $upload['file'] );
			return new WP_Error( 'import_file_error', __('Zero size file downloaded', 'wordpress-importer') );
		}
	
	
		// keep track of the old and new urls so we can substitute them later
		$url_remap[$url] = $upload['url'];
	
	
		return $upload;
	}
	
	public function process_attachment( $post, $url )
	{
		 
		// if the URL is absolute, but does not contain address, then upload it assuming base_site_url
		//if ( preg_match( '|^/[\w\W]+$|', $url ) )
		//	$url = rtrim( $this->base_url, '/' ) . $url;
		  
			global $url_remap;
		  
			$upload = $this->fetch_remote_file( $url, $post );
			if ( is_wp_error( $upload ) )
				return $upload;
	
			if ( $info = wp_check_filetype( $upload['file'] ) )
				$post['post_mime_type'] = $info['type'];
			else
				return new WP_Error( 'attachment_processing_error', __('Invalid file type', 'wordpress-importer') );
	
			$post['guid'] = $upload['url'];
	
			// as per wp-admin/includes/upload.php
			$post_id = wp_insert_attachment( $post, $upload['file'] );
			wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload['file'] ) );
	
			update_post_meta($post_id, '_pin_anchor', array('x' => 0, 'y' => 30 ));
	
			return $post_id;
	}
	
	function sideload_post_thumb($post_ID, $url, $desc = "")
	{
		FlacsoSettingsPage::log("Has Att.: $post_ID, $url, $desc");
		$thumb_url = $url;
		 
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		require_once(ABSPATH . 'wp-admin/includes/media.php');
		set_time_limit(300);
	
		if ( ! empty($thumb_url) ) {
			// Download file to temp location
			$tmp = download_url( $thumb_url );
	
			// Set variables for storage
			// fix file filename for query strings
			$pathinfo = pathinfo($url);
			$file_array['name'] = $pathinfo['basename'];
			$file_array['tmp_name'] = $tmp;
			if($desc == "") $desc = $file_array['name'];
	
			// If error storing temporarily, unlink
			if ( is_wp_error( $tmp ) ) {
				@unlink($file_array['tmp_name']);
				$file_array['tmp_name'] = '';
				FlacsoSettingsPage::log("Att. Error: $post_ID, ".print_r($tmp, true));
			}
	
			if($this->debug)
			{
				FlacsoSettingsPage::log('Image Url: '.$url.$file_array['name'].print_r($pathinfo, true));
			}
			else 
			{
				// do the validation and storage stuff
				$thumbid = media_handle_sideload( $file_array, $post_ID, $desc );
				// If error storing permanently, unlink
				if ( is_wp_error($thumbid) ) {
					@unlink($file_array['tmp_name']);
					FlacsoSettingsPage::log("Att. Error on handle: $post_ID, ".print_r($thumbid, true));
					return $thumbid;
				}
				
				set_post_thumbnail( $post_ID, $thumbid );
				FlacsoSettingsPage::log("Att. saved: $post_ID, $thumbid");
			}
		}
	
		
	}
	
	public function ImportDocsCsV($file_name = '/tmp/importgeadocs.csv' )
	{
		if(file_exists($file_name))
		{
			ini_set("memory_limit", "2048M");
			set_time_limit(0);
				
			$file = fopen ($file_name, 'r');
			$row = fgetcsv( $file, 0, ';');
			$i = 0;
			$limit = 0;
				
			$user_id_from_email = get_user_by('email', get_blog_option(get_current_blog_id(), 'admin_email'));
			$author = get_userdata($user_id_from_email);
				
			if($i > 0)
			{
				for ($y = 0; $y < $i; $y++)
				{
					$row = fgetcsv( $file, 0, ';');
				}
			}
				
			do
			{
				$post = array(
					'post_author'   => is_object($author) ? $author->ID : 1, //The user ID number of the author.
					'post_content'  => '',
					'post_title'    => $row[7], //The title of your post.
					'post_type'     => 'document',
					'post_status'	=> 'publish',
					'post_date'		=> $row[12]
				);
				$post_id = 0;
		
				if(!$this->debug) $post_id = wp_insert_post($post);
		
				FlacsoSettingsPage::log("OK Doc: (".$post_id.")");
		
		
				/**
				* Metas
				*/
				if($this->debug)
				{
					FlacsoSettingsPage::log("autor:".$row[8], $this->debug);
					FlacsoSettingsPage::log("fonte:".$row[10], $this->debug);
				}
				else
				{
					update_post_meta($post_id, $row[8], 'custom-author');
					update_post_meta($post_id, $row[10], 'reference');
				}

				/**
				* Taxonomies
				*/

				self::TreatDocType($post_id, $row[1]);
				
				$country_id = self::TreatCountry($post_id, $row[8]);
				self::TreatState($post_id, $row[10], $country_id);
				self::TreatTerritory($post_id, $row[13]);
				if(!is_null($row[12]) && $row[12] != 'NULL' && strlen($row[12]) > 1)
				{
					self::sideload_post_thumb($post_id, $this->img_url.$row[12],sanitize_title($row[1]));
				}
				if($this->gea)
				{
					self::TreatGea($post_id);
				}

				$i++;
		
				$row = fgetcsv( $file, 0, ';');
		
			}
			while ($row !== false && ( $limit == 0 || $i < $limit) );
		}
		else
		{
			FlacsoSettingsPage::log("File not found!", $this->debug);
		}
	}
}