<?php
// integrations for various plugins and services
class ChainedIntegrations {
	// set the integrated service(s)
	public static function main() {
		// load some of the specific integrations instead of main
		if(!empty($_GET['tab'])) {
			switch($_GET['tab']) {
				case 'arigato':
				   return self :: arigato();
				break;
				case 'arigatopro':
					return self :: arigatopro();
				break;
				case 'mailchimp':
					return self :: mailchimp();
				break;
			}
		} // end loading specific integation		
		
		if(!empty($_POST['save']) and check_admin_referer('chained_integrations')) {
			$integrations = [];
			if(!empty($_POST['integrate_arigato'])) $integrations[] = 'arigato';
			if(!empty($_POST['integrate_arigatopro'])) $integrations[] = 'arigatopro';
			if(!empty($_POST['integrate_mailchimp'])) $integrations[] = 'mailchimp';
			
			update_option('chained_integrations', $integrations);
		}
		
		$integrations = get_option('chained_integrations');
		if(empty($integrations)) $integrations = [];
		
		// for Arigato & Arigato PRO let's check if plugins are installed and active
		// for remote services like MailChimp we don't need such a check
		$arigato_active = function_exists('bft_init');
		$arigatopro_active = class_exists('BFTPro');
		
		include(CHAINED_PATH . '/views/integrations.html.php');   
	}	// end main()
	
	// Arigato Autoresponder & Newsletter
	public static function arigato() {
		global $wpdb;
		
		$quizzes = self :: get_quizzes();
		
		// add/edit/delete relation
	  if(!empty($_POST['add']) and check_admin_referer('chained_mail_relations')) {
			// no duplicates		
			$exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM ".CHAINED_MAIL_RELATIONS."
				WHERE quiz_id=%d AND list_id=0 AND service='arigato' AND result_id=%d", 
				intval($_POST['quiz_id']), intval($_POST['result_id'])));   	  	
	  	
	  		if(!$exists) {
				$wpdb->query($wpdb->prepare("INSERT INTO ".CHAINED_MAIL_RELATIONS." SET 
					quiz_id = %d, list_id=0, service='arigato', result_id=%d", intval($_POST['quiz_id']), intval($_POST['result_id'])));
			}   	  
	  }

	  if(!empty($_POST['save']) and check_admin_referer('chained_mail_relations')) {
			$wpdb->query($wpdb->prepare("UPDATE ".CHAINED_MAIL_RELATIONS." SET 
				quiz_id=%d, result_id=%d WHERE id=%d", 
				 intval($_POST['quiz_id']), intval($_POST['result_id']), intval($_POST['id'])));   	  
	  }
	  
		if(!empty($_POST['del']) and check_admin_referer('chained_mail_relations')) {
			$wpdb->query($wpdb->prepare("DELETE FROM ".CHAINED_MAIL_RELATIONS." WHERE id=%d", intval($_POST['id'])));
		}  
		
		// select existing relations
   	$relations = $wpdb->get_results("SELECT * FROM ".CHAINED_MAIL_RELATIONS." WHERE service = 'arigato' ORDER BY id");
   	
   	// add grades to relations
   	$results = $wpdb->get_results("SELECT * FROM ".CHAINED_RESULTS." ORDER BY id");
   	
   	foreach($relations as $cnt => $relation) {
   		$rel_results = array();
	  	  foreach($results as $result) {
	  	  	if($result->quiz_id == $relation->quiz_id) $rel_results[] = $result;
		  }
		  
		  $relations[$cnt]->results = $rel_results;
   	} // end matching results to relations
   	
   	include(CHAINED_PATH . '/views/integrations-arigato.html.php');   	 	   
	} // end arigato()
	
	
	// Arigato PRO Autoresponder & Newsletter
	public static function arigatopro() {
		global $wpdb;
		
		$quizzes = self :: get_quizzes();
		
		// select mailing lists
   	$lists = $wpdb->get_results("SELECT * FROM ".BFTPRO_LISTS." ORDER BY name");
		
		// add/edit/delete relation
	  if(!empty($_POST['add']) and check_admin_referer('chained_mail_relations')) {
			// no duplicates		
			$exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM ".CHAINED_MAIL_RELATIONS."
				WHERE quiz_id=%d AND list_id=%d AND service='arigatopro' AND result_id=%d", 
				intval($_POST['quiz_id']), intval($_POST['list_id']), intval($_POST['result_id'])));   	  	
	  	
	  		if(!$exists) {
				$wpdb->query($wpdb->prepare("INSERT INTO ".CHAINED_MAIL_RELATIONS." SET 
					quiz_id = %d, list_id=%d, service='arigatopro', result_id=%d", 
					intval($_POST['quiz_id']), intval($_POST['list_id']), intval($_POST['result_id'])));
			}   	  
	  }

	  if(!empty($_POST['save']) and check_admin_referer('chained_mail_relations')) {
			$wpdb->query($wpdb->prepare("UPDATE ".CHAINED_MAIL_RELATIONS." SET 
				quiz_id=%d, list_id=%d, result_id=%d WHERE id=%d", 
				 intval($_POST['quiz_id']), intval($_POST['list_id']), intval($_POST['result_id']), intval($_POST['id'])));   	  
	  }
	  
		if(!empty($_POST['del']) and check_admin_referer('chained_mail_relations')) {
			$wpdb->query($wpdb->prepare("DELETE FROM ".CHAINED_MAIL_RELATIONS." WHERE id=%d", intval($_POST['id'])));
		}  
		
		// select existing relations
   	$relations = $wpdb->get_results("SELECT * FROM ".CHAINED_MAIL_RELATIONS." WHERE service = 'arigatopro' ORDER BY id");
   	
   	// add grades to relations
   	$results = $wpdb->get_results("SELECT * FROM ".CHAINED_RESULTS." ORDER BY id");
   	
   	foreach($relations as $cnt => $relation) {
   		$rel_results = array();
	  	  foreach($results as $result) {
	  	  	if($result->quiz_id == $relation->quiz_id) $rel_results[] = $result;
		  }
		  
		  $relations[$cnt]->results = $rel_results;
   	} // end matching results to relations
   	
   	include(CHAINED_PATH . '/views/integrations-arigatopro.html.php');   
	}
	
	// MailChimp
	public static function mailchimp() {
		global $wpdb;
		
	 // save MailChimp API key and password
	  if(!empty($_POST['set_key']) and check_admin_referer('chained_mail_settings')) {
		  $double_optin = empty($_POST['no_optin']) ? 0 : 1;   	  	
	  	
	  	  update_option('chainedchimp_api_key', $_POST['api_key']);
	  	  update_option('chainedchimp_no_optin', $double_optin);
	  }
	  $api_key = get_option('chainedchimp_api_key');
	  if(!empty($api_key) and strstr($api_key, '-')) {
	  		list($nothing, $dc) = explode('-', $api_key);
	  	   $url = 'https://'.$dc.'.api.mailchimp.com/3.0/';
	  	   
	  	    // select mailing lists from mailchimp
   	 	$json_result = wp_remote_get($url.'lists?count=1000', array(
				'timeout' => 45,			
				
			    'headers'     => array('Authorization' => 'Basic ' . base64_encode( 'user:'. $api_key )),			   
			    ));		
		    	
			if( is_wp_error( $json_result  ) ) {
			    echo $json_result->get_error_message();
			    $result = '';
			}
			else $result = json_decode($json_result['body']);
			//print_r($json_result);
			$lists = @$result->lists;
	  }
	  else $lists = [];
		
		$quizzes = self :: get_quizzes();
			
		// add/edit/delete relation
	  if(!empty($_POST['add']) and check_admin_referer('chained_mail_relations')) {
			// no duplicates		
			$exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM ".CHAINED_MAIL_RELATIONS."
				WHERE quiz_id=%d AND list_id=%s AND service='mailchimp' AND result_id=%d", 
				intval($_POST['quiz_id']), sanitize_text_field($_POST['list_id']), intval($_POST['result_id'])));   	  	
	  	
	  		if(!$exists) {
				$wpdb->query($wpdb->prepare("INSERT INTO ".CHAINED_MAIL_RELATIONS." SET 
					quiz_id = %d, list_id=%s, service='mailchimp', result_id=%d, tags = %s", 
					intval($_POST['quiz_id']), sanitize_text_field($_POST['list_id']), intval($_POST['result_id']), sanitize_text_field($_POST['tags'])));
			}   	  
	  }

	  if(!empty($_POST['save']) and check_admin_referer('chained_mail_relations')) {
			$wpdb->query($wpdb->prepare("UPDATE ".CHAINED_MAIL_RELATIONS." SET 
				quiz_id=%d, list_id=%s, result_id=%d, tags=%s WHERE id=%d", 
				 intval($_POST['quiz_id']), sanitize_text_field($_POST['list_id']), intval($_POST['result_id']), 
				 sanitize_text_field($_POST['tags']), intval($_POST['id'])));   	  
	  }
	  
		if(!empty($_POST['del']) and check_admin_referer('chained_mail_relations')) {
			$wpdb->query($wpdb->prepare("DELETE FROM ".CHAINED_MAIL_RELATIONS." WHERE id=%d", intval($_POST['id'])));
		}  
		
		// select existing relations
   	$relations = $wpdb->get_results("SELECT * FROM ".CHAINED_MAIL_RELATIONS." WHERE service = 'mailchimp' ORDER BY id");
   	
   	// add grades to relations
   	$results = $wpdb->get_results("SELECT * FROM ".CHAINED_RESULTS." ORDER BY id");
   	
   	foreach($relations as $cnt => $relation) {
   		$rel_results = array();
	  	  foreach($results as $result) {
	  	  	if($result->quiz_id == $relation->quiz_id) $rel_results[] = $result;
		  }
		  
		  $relations[$cnt]->results = $rel_results;
   	} // end matching results to relations
   			
		
		include(CHAINED_PATH . '/views/integrations-mailchimp.html.php');
	}
	
	// call the bridges when a chained quiz is completed
	public static function completed_quiz($taking_id) {
		global $wpdb;
		
		// are there any integrations at all?
		$integrations = get_option('chained_integrations');
		if(empty($integrations)) return false;
		
		$taking = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_COMPLETED." WHERE id=%d", $taking_id));
		
		if(empty($taking->email)) return false;
		
		// name?
		if(!empty($taking->user_id)) {
			$user = get_userdata( $taking->user_id );
			$name = $user->display_name;
		}
		else $name = __('Guest', 'chained');
		
		// find relations for this quiz and result_id
		$relations = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".CHAINED_MAIL_RELATIONS." 
			WHERE quiz_id=%d AND (result_id=%d OR result_id=0)", $taking->quiz_id, $taking->result_id));
						
		// dispatch relations if any
		foreach($relations as $relation) {
			switch($relation->service) {
				case 'arigato':
					if(function_exists('bft_subscribe')) bft_subscribe($taking->email, $name, true, true);
				break;
				case 'arigatopro':
					require_once(BFTPRO_PATH."/models/user.php");
					$_user = new BFTProUser();
					$vars = array("list_id" => $relation->list_id, "email" => $taking->email, "name"=> $name, "source"=>"chained quiz");
					
					// fill any required fields with "1" to avoid errors			
					$fields = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".BFTPRO_FIELDS." WHERE list_id=%d", $l_id));
					foreach($fields as $field) $vars['field_'.$field->id] = 1;					
					// ignore exceptions
					try {
						$message = '';
						$_user->subscribe($vars, $message, true);
					}
					catch(Exception $e) {}
				break;
				case 'mailchimp':
					
				   $api_key = get_option('chainedchimp_api_key');
				   if(empty($api_key) or !strstr($api_key, '-')) return false;
   	         list($nothing, $dc) = explode('-', $api_key);
   	            	         
   	         $api_url = 'https://' . substr($api_key,strpos($api_key,'-')+1) . '.api.mailchimp.com/3.0/lists/' . $relation->list_id;	
   	         
   	         
   				if(strstr($name, ' ')) {
						$parts = explode(' ', $name);
						$fname = $parts[0];
						array_shift($parts);
						$lname = implode(' ', $parts);
					}
					else {
						$fname = $name;
						$lname = '';
					}   	
					
					$email = $taking->email;	
   	         
   	         // member already exists? Do not add them
					$result = wp_remote_get(
					  $api_url.'/members/'.md5(strtolower($email)),
					  [
					  	  'headers' => ['Authorization' => 'Basic ' . base64_encode( 'user:'. $api_key )]
					  ]
					);
								
					$exists = false;
					if(isset($result['body'])) {
						$result_body = json_decode($result['body']);
						if(!empty($result_body->status) and $result_body->status == 'subscribed') $exists = true;
					}
   	
   				$status = (get_option('chainedchimp_no_optin') == '1') ? 'subscribed' : 'pending';
   				
   				/************************/
   				if(!$exists) {
   					$args = array(
							'method' => 'PUT',
						 	'headers' => array(
								'Authorization' => 'Basic ' . base64_encode( 'user:'. $api_key )
							),
							'body' => json_encode(array(
						    	'email_address' => $email,
						    	"merge_fields" => array("FNAME" => $fname, "LNAME" =>$lname, "NAME" => $name),
								'status'        => $status
							))
						);
						
						$response = wp_remote_post( $api_url . '/members/' . md5(strtolower($email)), $args );						$body = json_decode( $response['body'] );
						 
						if ( $response['response']['code'] == 200 && $body->status == $status ) {
							//echo 'The user has been successfully ' . $status . '.';
							$result = 'The user has been successfully ' . $status . '.';
						} else {
							//echo '<b>' . $response['response']['code'] . $body->title . ':</b> ' . $body->detail;
							$result = '<b>' . $response['response']['code'] . $body->title . ':</b> ' . $body->detail;
							//print_r($response);
						}
   				} // end if not exists
					
					/*************************/
					
					// add tags? Need another request
					if(!empty($relation->tags)) {
						$reltags = explode(',', $relation->tags);
						$tags = [];
						foreach($reltags as $tag) $tags[] = ['name' => trim($tag), 'status' => 'active'];				
						
						$args = array(
							'method' => 'POST',
						 	'headers' => array(
								'Authorization' => 'Basic ' . base64_encode( 'user:'. $api_key )
							),
							'body' => json_encode(array(
						    	'tags' => $tags,
						    	'is_syncing' => false,
							))
						);				
						
						$response = wp_remote_post( $api_url. '/members/' . md5(strtolower($email)).'/tags', 
							$args );
						
						$body = json_decode( $response['body'] );	
						if ( $response['response']['code'] == 204) {
							//echo 'The user has been successfully ' . $status . '.';
							$result = 'The tags have been successfully added.';
						} else {
							//echo '<b>' . $response['response']['code'] . $body->title . ':</b> ' . $body->detail;
							$result = '<b>' . $response['response']['code'] . $body->title . ':</b> ' . $body->detail;
							//print_r($response);
						}								
					
					} // end adding tags	
				break; // end mailchimp
			}
		}	// end foreach relation
	} // end completed_quiz

	// outputs the tabs to avoid repeating the code in all functions
	private static function tabs($active, $quizzes = null) {
		$integrations = get_option('chained_integrations');
		if(empty($integrations)) $integrations = [];
		
		// for Arigato & Arigato PRO let's check if plugins are installed and active
		// for remote services like MailChimp we don't need such a check
		$arigato_active = function_exists('bft_init');
		$arigatopro_active = class_exists('BFTPro');
		include(CHAINED_PATH . '/views/integrations-tabs.html.php');   	 	   
	}

	// get quizzes & grades for the dropdowns	
	private static function get_quizzes() {
		global $wpdb;
		
		$quizzes = $wpdb->get_results("SELECT * FROM ".CHAINED_QUIZZES." WHERE email_user=1 ORDER BY id");
		
		$results = $wpdb->get_results("SELECT * FROM ".CHAINED_RESULTS." ORDER BY id");
		
		foreach($quizzes as $cnt => $quiz) {
			$quiz_results = [];
			
			foreach($results as $result) {
				if($result->quiz_id == $quiz->id) $quiz_results[] = $result;
			}
			
			$quizzes[$cnt]->results = $quiz_results;
		}	 
		
		return $quizzes;
	} // end get quizzes
}