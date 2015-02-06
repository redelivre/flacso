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
					update_post_meta($post_id, 'fonte', $row[2]);
					update_post_meta($post_id, 'editoria', $row[9]);
					update_post_meta($post_id, 'url', $row[11]);
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
		$clean_data = ucwords(mb_strtolower($data, 'UTF-8'));
		
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
	
	public function TreatCountryDocs($post_id, $data)
	{
		
		$clean_data = intval($data);
		
		/**
		 * 	<option value="29">América Latina</option>
		 * 	<option value="28">América Latina e Caribe</option>
		 * 	<option value="30">Caribe</option>
		 * 	<option value="32">Iberoamérica</option>
		 * 	<option value="1">Argentina</option>
		 * 	<option value="7">Bolivia</option>
		 * 	<option value="2">Brasil</option>
		 * 	<option value="3">Chile</option>
		 * 	<option value="4">Colômbia</option>
		 * 	<option value="11">Costa Rica</option>
		 * 	<option value="12">Cuba</option>
		 * 	<option value="5">Ecuador</option>
		 * 	<option value="13">El Salvador</option>
		 * 	<option value="33">Estados Unidos</option>
		 * 	<option value="34">França</option>
		 * 	<option value="14">Guatemala</option>
		 * 	<option value="22">Haití</option>
		 * 	<option value="15">Honduras</option>
		 * 	<option value="6">México</option>
		 * 	<option value="16">Nicaragua</option>
		 * 	<option value="17">Panamá</option>
		 * 	<option value="18">Paraguay</option>
		 * 	<option value="8">Peru</option>
		 * 	<option value="31">Portugal</option>
		 * 	<option value="19">Puerto Rico</option>
		 * 	<option value="20">República Dominicana</option>
		 * 	<option value="21">Uruguai</option>
		 * 	<option value="10">Venezuela</option>
		 */
		switch ($clean_data)
		{
			case 29:
				$clean_data = "América Latina";
				break;
			case 28:
				$clean_data = "América Latina e Caribe";
				break;
			case 30:
				$clean_data = "Caribe";
				break;
			case 32:
				$clean_data = "Iberoamérica";
				break;
			case 1:
				$clean_data = "Argentina";
				break;
			case 7:
				$clean_data = "Bolivia";
				break;
			case 2:
				$clean_data = "Brasil";
				break;
			case 3:
				$clean_data = "Chile";
				break;
			case 4:
				$clean_data = "Colômbia";
				break;
			case 11:
				$clean_data = "Costa Rica";
				break;
			case 12:
				$clean_data = "Cuba";
				break;
			case 5:
				$clean_data = "Ecuador";
				break;
			case 13:
				$clean_data = "El Salvador";
				break;
			case 33:
				$clean_data = "Estados Unidos";
				break;
			case 34:
				$clean_data = "França";
				break;
			case 14:
				$clean_data = "Guatemala";
				break;
			case 22:
				$clean_data = "Haití";
				break;
			case 15:
				$clean_data = "Honduras";
				break;
			case 6:
				$clean_data = "México";
				break;
			case 16:
				$clean_data = "Nicaragua";
				break;
			case 17:
				$clean_data = "Panamá";
				break;
			case 18:
				$clean_data = "Paraguay";
				break;
			case 8:
				$clean_data = "Peru";
				break;
			case 31:
				$clean_data = "Portugal";
				break;
			case 19:
				$clean_data = "Puerto Rico";
				break;
			case 20:
				$clean_data = "República Dominicana";
				break;
			case 21:
				$clean_data = "Uruguai";
				break;
			case 10:
				$clean_data = "Venezuela";
				break;
		}
		
		return self::TreatCountry($post_id, $clean_data);
	}
	
	public function TreatState($post_id, $state, $country)
	{
		$clean_state = ucwords(mb_strtolower($state, 'UTF-8'));
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
	
	public function TreatDocType($post_id, $data)
	{

		$clean_data = $data;
		/**
		 * 	<option value="1">Dissertação</option>
		 * 	<option value="2">Tese</option>
		 * 	<option value="3">Documento</option>
		 * 	<option value="4">Artigo</option>
		 * 	<option value="5">Livro</option>
		 * 	<option value="6">Leis e normas institucionais</option>
		 */
		switch ((int)$data)
		{
			case 1:
				$clean_data = 'Dissertações';
			break;
			case 2:
				$clean_data = 'Teses';
			break;
			case 3:
				$clean_data = 'Documentos';
			break;
			case 4:
				$clean_data = 'Artigos';
			break;
			case 5:
				$clean_data = 'Livros';
			break;
			case 6:
				$clean_data = 'Leis e Normas Institucionais';
			break;
			default:
				$clean_data = 'Documentos';
			break;
		}
		
		$term = get_term_by('name', $clean_data, 'publication-type');
		
		if($term !== false)
		{
			$this->InsertTerm($post_id, 'publication-type', $term);
		}
		else
		{
			FlacsoSettingsPage::log("Term publication-type NULL: ".(print_r($term, true)).", Field: $clean_data", $this->debug);
			$term = wp_insert_term($clean_data, 'publication-type');
			$this->InsertTerm($post_id, 'publication-type', $term);
		}
		
		return $term;
	}
	
	public function TreatYear($post_id, $data)
	{
		$clean_data = intval($data);
		
		$term = get_term_by('name', $clean_data, 'year');
		
		FlacsoSettingsPage::log("Term year ID: ".(print_r($term, true)).", Field: $clean_data", $this->debug);
		
		if($term !== false)
		{
			$this->InsertTerm($post_id, 'year', $term);
		}
		else
		{
			if(!$this->debug)
			{
				$term = wp_insert_term($clean_data, 'year');
			}
			else
			{
				$term = $clean_data;
			}
				
			$this->InsertTerm($post_id, 'year', $term);
		}
		
		return $term;
	}
	
	public function TreatHigherEducation($post_id, $data)
	{
		$clean_data = intval($data);
		
		/**
		 * 	<option value="1">Estatística da Educação Superior</option>
		 * 	<option value="2">Leis, normas e regulamentos da Educação Superior</option>
		 * 	<option value="3">Avaliação da Educação Superior</option>
		 * 	<option value="4">Financiamento da Educação Superior</option>
		 * 	<option value="5">Organização acadêmica da Educação Superior</option>
		 * 	<option value="6">Institutos Federais e CEFET's</option>
		 * 	<option value="7">Universidades Públicas</option>
		 * 	<option value="8">Instituições Privadas</option>
		 * 	<option value="9">Ações Afirmativas e Sistema de Cotas ou Bônus</option>
		 * 	<option value="10">Expansão da Educação Superior no Brasil</option>
		 * 	<option value="11">Investimentos estrangeiros na educação superior</option>
		 * 	<option value="12">Cooperação Internacional, organismos internacionais</option>
		 * 	<option value="13">Docentes</option>
		 * 	<option value="14">Assistência estudantil, financiamentos e bolsas</option>
		 * 	<option value="15">Estudantes, movimento estudantil, mobilidade estudantil</option>
		 * 	<option value="16">Educação no campo</option>
		 * 	<option value="17">Educação Indígena</option>
		 * 	<option value="18">Educação Básica</option>
		 * 	<option value="19">Acesso e permanência na educação superior na América Latina</option>
		 * 	<option value="20">Expansão da educação superior na América Latina</option>
		 * 	<option value="21">Igualdade e desigualdade na Educação Superior na América Latina</option>
		 * 	<option value="22">Governo e gestão da educação superior</option>
		 * 	<option value="23">Métodos e processos de ensino na educação superior</option>
		 * 	<option value="24">Políticas de pesquisa na educação superior</option>
		 * 	<option value="25">História da educação superior</option>
		 * 	<option value="26">Internacionalização e globalização da educação superior</option>
		 * 	<option value="27">Trajetórias dos alunos da educação superior</option>
		 */
		
		switch ($clean_data)
		{
			case 1:
				$clean_data = "Estatística da Educação Superior";
				break;
			case 2:
				$clean_data = "Leis, normas e regulamentos da Educação Superior";
				break;
			case 3:
				$clean_data = "Avaliação da Educação Superior";
				break;
			case 4:
				$clean_data = "Financiamento da Educação Superior";
				break;
			case 5:
				$clean_data = "Organização acadêmica da Educação Superior";
				break;
			case 6:
				$clean_data = "Institutos Federais e CEFET's";
				break;
			case 7:
				$clean_data = "Universidades Públicas";
				break;
			case 8:
				$clean_data = "Instituições Privadas";
				break;
			case 9:
				$clean_data = "Ações Afirmativas e Sistema de Cotas ou Bônus";
				break;
			case 10:
				$clean_data = "Expansão da Educação Superior no Brasil";
				break;
			case 11:
				$clean_data = "Investimentos estrangeiros na educação superior";
				break;
			case 12:
				$clean_data = "Cooperação Internacional, organismos internacionais";
				break;
			case 13:
				$clean_data = "Docentes";
				break;
			case 14:
				$clean_data = "Assistência estudantil, financiamentos e bolsas";
				break;
			case 15:
				$clean_data = "Estudantes, movimento estudantil, mobilidade estudantil";
				break;
			case 16:
				$clean_data = "Educação no campo";
				break;
			case 17:
				$clean_data = "Educação Indígena";
				break;
			case 18:
				$clean_data = "Educação Básica";
				break;
			case 19:
				$clean_data = "Acesso e permanência na educação superior na América Latina";
				break;
			case 20:
				$clean_data = "Expansão da educação superior na América Latina";
				break;
			case 21:
				$clean_data = "Igualdade e desigualdade na Educação Superior na América Latina";
				break;
			case 22:
				$clean_data = "Governo e gestão da educação superior";
				break;
			case 23:
				$clean_data = "Métodos e processos de ensino na educação superior";
				break;
			case 24:
				$clean_data = "Políticas de pesquisa na educação superior";
				break;
			case 25:
				$clean_data = "História da educação superior";
				break;
			case 26:
				$clean_data = "Internacionalização e globalização da educação superior";
				break;
			case 27:
				$clean_data = "Trajetórias dos alunos da educação superior";
				break;
		}
	
		$term = get_term_by('name', $clean_data, 'higher-education');
	
		FlacsoSettingsPage::log("Term higher-education ID: ".(print_r($term, true)).", Field: $clean_data", $this->debug);
	
		if($term !== false)
		{
			$this->InsertTerm($post_id, 'higher-education', $term);
		}
		else
		{
			if(!$this->debug)
			{
				$term = wp_insert_term($clean_data, 'higher-education');
			}
			else
			{
				$term = $clean_data;
			}
	
			$this->InsertTerm($post_id, 'higher-education', $term);
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
			FlacsoSettingsPage::log("InsertTerm: ".$taxonomy.": => ".(print_r($term, true)), $this->debug);
		}
		elseif (get_class($term) == WP_Error)
		{
			FlacsoSettingsPage::log("Error on InsertTerm: ".$taxonomy.": => ".(print_r($term, true)), $this->debug);
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
	
	function sideload_post_thumb($post_ID, $url, $desc = "", $is_thumbnail = true)
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
				
				if($is_thumbnail) set_post_thumbnail( $post_ID, $thumbid );
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
					'post_title'    => ucwords(mb_strtolower($row[7], 'UTF-8')), //The title of your post.
					'post_type'     => 'publication',
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
					FlacsoSettingsPage::log("url:".$row[9], $this->debug);
				}
				else
				{
					update_post_meta($post_id, 'custom-author', $row[8]);
					update_post_meta($post_id, 'fonte', $row[10]);
					update_post_meta($post_id, 'url', $row[9]);
				}

				/**
				* Taxonomies
				*/

				self::TreatDocType($post_id, $row[1]);
				$country_id = self::TreatCountryDocs($post_id, $row[5]);
				self::TreatYear($post_id, $row[6]);
				
				self::TreatHigherEducation($post_id, $row[2]);
				self::TreatHigherEducation($post_id, $row[3]);
				self::TreatHigherEducation($post_id, $row[4]);
				
				if(!is_null($row[11]) && $row[11] != 'NULL' && strlen($row[11]) > 1)
				{
					self::sideload_post_thumb($post_id, $this->img_url.$row[11], ucwords(mb_strtolower($row[7], 'UTF-8')), false);
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