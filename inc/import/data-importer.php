<?php

class dataImporter
{

	public $debug = true;
	
	public function ImportCsV($file_name = '/tmp/import.csv' )
	{
		if(file_exists($file_name))
		{
			ini_set("memory_limit", "2048M");
			set_time_limit(0);
			
			$file = fopen ($file_name, 'r');
			$row = fgetcsv( $file, 0, ';');
			$i = 0;
			
			$gea = true;
			
			do 
			{
				$post = array(
						'post_author'   => 1, //The user ID number of the author.
						'post_content'  => $row[4],
						'post_title'    => $row[1], //The title of your post.
						'post_type'     => 'post',
						'post_status'	=> $row[6] == 'ON' ? 'publish' : 'draft',
						'post_date'		=> $row[5]
				);
				$post_id = 0;
				
				if(!$this->debug) $post_id = wp_insert_post($post);
				
				
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
				
				FlacsoSettingsPage::
				
				$i++;
				
			}
			while ($row !== false/*);//*/ && $i < 10);
		}
		else 
		{
			FlacsoSettingsPage::log("File not found!", $this->debug);
		}
	}
	
	public function TreatCountry($post_id, $data)
	{
		$clean_data = ucwords(strtolower($data));
		 
		if($term = term_exists($clean_data, 'country'))
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
		
		if($term = term_exists($clean_state, 'country', $country))
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
			
		if($term = term_exists($territory, 'territory'))
		{
			$this->InsertTerm($post_id, 'territory', $term);
		}
		else
		{
			$term = wp_insert_term($territory, 'territory');
			$this->InsertTerm($post_id, 'territory', $term);
		}
	
		return $term;
	}
	
	public function InsertTerm($post_id, $taxonomy, $term)
	{
		if($this->debug)
		{
			FlacsoSettingsPage::log("InsertTerm: ".$taxonomy.": => ".(is_array($term) ? implode(" => ", $term) : $term), $this->debug);
		}
		else
		{
			wp_set_object_terms($post_id, is_array($term) ? $term['term_id'] : $term, $taxonomy );
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
	
}