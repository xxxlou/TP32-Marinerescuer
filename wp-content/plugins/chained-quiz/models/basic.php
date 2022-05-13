<?php
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// main model containing general config and UI functions
class ChainedQuiz {
   static function install($update = false) {
   	global $wpdb;	
   	$wpdb -> show_errors();
   	
   	if(!$update) self::init();
   	
   	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   	$collation = $wpdb->get_charset_collate();
	  
	   // quizzes
   	if($wpdb->get_var("SHOW TABLES LIKE '".CHAINED_QUIZZES."'") != CHAINED_QUIZZES) {        
			$sql = "CREATE TABLE `" . CHAINED_QUIZZES . "` (
				  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `title` VARCHAR(255) NOT NULL DEFAULT '',
				  `output` TEXT				  
				) $collation";
			
			$wpdb->query($sql);
	  }
	  
	  // questions
   	if($wpdb->get_var("SHOW TABLES LIKE '".CHAINED_QUESTIONS."'") != CHAINED_QUESTIONS) {        
			$sql = "CREATE TABLE `" . CHAINED_QUESTIONS . "` (
				  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `quiz_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `title` VARCHAR(255) NOT NULL DEFAULT '',
				  `question` TEXT,
				  `qtype` VARCHAR(20) NOT NULL DEFAULT '',
				  `rank` INT UNSIGNED NOT NULL DEFAULT 0			  
				) $collation";
			
			$wpdb->query($sql);
	  } 
	  
	  // choices
     if($wpdb->get_var("SHOW TABLES LIKE '".CHAINED_CHOICES."'") != CHAINED_CHOICES) {        
			$sql = "CREATE TABLE `" . CHAINED_CHOICES . "` (
				  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `quiz_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `question_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `choice` TEXT,
				  `points` DECIMAL(8,2) NOT NULL DEFAULT '0.00',
				  `is_correct` TINYINT UNSIGNED NOT NULL DEFAULT 0,
				  `goto` VARCHAR(100) NOT NULL DEFAULT 'next'
				) $collation";
			
			$wpdb->query($sql);
	  } 
	  
	  // results
	  if($wpdb->get_var("SHOW TABLES LIKE '".CHAINED_RESULTS."'") != CHAINED_RESULTS) {        
			$sql = "CREATE TABLE `" . CHAINED_RESULTS . "` (
				  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `quiz_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `points_bottom` DECIMAL(8,2) NOT NULL DEFAULT '0.00',
				  `points_top` DECIMAL(8,2) NOT NULL DEFAULT '0.00',
				  `title` VARCHAR(255) NOT NULL DEFAULT '',
				  `description` TEXT 
				) $collation";
			
			$wpdb->query($sql);
	  } 
	  
	  // completed quizzes	
	  if($wpdb->get_var("SHOW TABLES LIKE '".CHAINED_COMPLETED."'") != CHAINED_COMPLETED) {        
			$sql = "CREATE TABLE `" . CHAINED_COMPLETED . "` (
				  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `quiz_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `points` DECIMAL(8,2) NOT NULL DEFAULT '0.00',
				  `result_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `datetime` DATETIME,
				  `ip` VARCHAR(30) NOT NULL DEFAULT '',
				  `user_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `snapshot` TEXT
				) $collation";
			
			$wpdb->query($sql);
	  } 	 
	  
	  // details of user answers
	  if($wpdb->get_var("SHOW TABLES LIKE '".CHAINED_USER_ANSWERS."'") != CHAINED_USER_ANSWERS) {        
			$sql = "CREATE TABLE `" . CHAINED_USER_ANSWERS . "` (
				  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `quiz_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `completion_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `question_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `answer` TEXT,
				  `points` DECIMAL(8,2) NOT NULL DEFAULT '0.00'				  
				) $collation";
			
			$wpdb->query($sql);
	  } 	 
	  
	  // mailing services relations (Arigato & Pro, Mailchimp, etc)
	  $sql = "CREATE TABLE ".CHAINED_MAIL_RELATIONS." (
	  	   id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	  	   quiz_id int(11) UNSIGNED NOT NULL DEFAULT 0,
	  	   service varchar(255) NOT NULL DEFAULT '',
	  	   list_id varchar(255) NOT NULL DEFAULT '',
	  	   result_id int(11) UNSIGNED NOT NULL DEFAULT 0,
	  	   PRIMARY KEY  (id)			  
			) $collation";
		dbDelta( $sql );	  	
	  
	  
	  chainedquiz_add_db_fields(array(
	  	  array("name" => 'autocontinue', 'type' => 'TINYINT UNSIGNED NOT NULL DEFAULT 0'),
	  	  array("name" => 'sort_order', 'type' => 'INT UNSIGNED NOT NULL DEFAULT 0'),
		  array("name" => 'accept_comments', 'type' => 'TINYINT UNSIGNED NOT NULL DEFAULT 0'),
		  array("name" => 'accept_comments_label', 'type' => "VARCHAR(255) NOT NULL DEFAULT ''"),
	  ), CHAINED_QUESTIONS);
	  
	  chainedquiz_add_db_fields(array(
	  	  array("name" => 'redirect_url', 'type' => "VARCHAR(255) NOT NULL DEFAULT ''"),
	  ), CHAINED_RESULTS);
	  
	   chainedquiz_add_db_fields(array(
	  	  array("name" => 'comments', 'type' => "TEXT"),
	  	  array("name" => 'is_correct', 'type' => "TINYINT UNSIGNED NOT NULL DEFAULT 0"),
	  ), CHAINED_USER_ANSWERS);
	  
	  chainedquiz_add_db_fields(array(
	  	  array("name" => 'email_admin', 'type' => "TINYINT UNSIGNED NOT NULL DEFAULT 0"),
	  	  array("name" => 'email_user', 'type' => "TINYINT UNSIGNED NOT NULL DEFAULT 0"),
		  array("name" => 'require_login', 'type' => "TINYINT UNSIGNED NOT NULL DEFAULT 0"),
		  array("name" => 'times_to_take', 'type' => "INT UNSIGNED NOT NULL DEFAULT 0"),
		  array("name" => 'save_source_url', 'type' => "TINYINT UNSIGNED NOT NULL DEFAULT 0"),
		  array("name" => 'set_email_output', 'type' => "TINYINT UNSIGNED NOT NULL DEFAULT 0"),
		  array("name" => 'email_output', 'type' => "TEXT"),
		  array("name" => 'admin_email', 'type' => "VARCHAR(255) NOT NULL DEFAULT ''"),
		  array("name" => 'email_required', 'type' => "TINYINT UNSIGNED NOT NULL DEFAULT 0"),
		  array("name" => 'hide_email_field', 'type' => "TINYINT UNSIGNED NOT NULL DEFAULT 0"),
	  ), CHAINED_QUIZZES);
	  
	  chainedquiz_add_db_fields(array(
	  	  array("name" => 'not_empty', 'type' => "TINYINT NOT NULL DEFAULT 0"), /*When initially creating a record, it is empty. If it remains so we have to delete it.*/
	  	  array("name" => 'source_url', 'type' => "VARCHAR(255) NOT NULL DEFAULT ''"), /* Page where the quiz is published */ 
	  	  array("name" => 'email', 'type' => "VARCHAR(255) NOT NULL DEFAULT ''"), /* email of non-logged in users when required */
	  ), CHAINED_COMPLETED);
	  
	   chainedquiz_add_db_fields(array(
	  	  array("name" => 'tags', 'type' => "TEXT"),
	  ), CHAINED_MAIL_RELATIONS);
	  
	  // fix sort order once for old quizzes (in version 0.7.5)
		if(get_option('chained_fixed_sort_order') != 1) {
			ChainedQuizQuestions :: fix_sort_order_global();
			update_option('chained_fixed_sort_order', 1);
		}	
		
		// update not_empty = 1 for all completed records prior to version 0.8.7 and DB version 0.66
		$version = get_option('chainedquiz_version');
		if($version < 0.67) {
			$wpdb->query("UPDATE ".CHAINED_COMPLETED." SET not_empty=1");
		}
	  
		// setup the default options (when not yet saved ever)
		if(get_option('chained_sender_name') == '') {
			update_option('chained_sender_name', __('WordPress', 'chained'));
			update_option('chained_sender_email', get_option('admin_email'));
			update_option('chained_admin_subject', __('User results on {{quiz-name}}', 'chained'));
			update_option('chained_user_subject', __('Your results on {{quiz-name}}', 'chained'));		
		}	  
	  
	  update_option('chainedquiz_version', "0.86");
	  // exit;
   }
   
   // main menu
   static function menu() {
   	$chained_caps = current_user_can('manage_options') ? 'manage_options' : 'chained_manage';
   	
   	add_menu_page(__('Chained Quiz', 'chained'), __('Chained Quiz', 'chained'), $chained_caps, "chained_quizzes", 
   		array('ChainedQuizQuizzes', "manage"));
   	add_submenu_page('chained_quizzes', __('Quizzes', 'chained'), __('Quizzes', 'chained'), $chained_caps, 
   		'chained_quizzes', array('ChainedQuizQuizzes', "manage"));					
   	add_submenu_page('chained_quizzes', __('Settings', 'chained'), __('Settings', 'chained'), 'manage_options', 
   		'chainedquiz_options', array('ChainedQuiz','options'));				
   	add_submenu_page('chained_quizzes', __('Social Sharing', 'chained'), __('Social Sharing', 'chained'), $chained_caps, 
   		'chainedquiz_social_sharing', array('ChainedSharing','options'));				
  		add_submenu_page('chained_quizzes', __('Integrations', 'chained'), __('Integrations', 'chained'), $chained_caps, 
   		'chainedquiz_integrations', array('ChainedIntegrations','main'));		
   		
   	add_submenu_page(NULL, __('Chained Quiz Results', 'chained'), __('Chained Quiz Results', 'chained'), $chained_caps, 
   		'chainedquiz_results', array('ChainedQuizResults','manage'));	
   	add_submenu_page(NULL, __('Chained Quiz Questions', 'chained'), __('Chained Quiz Questions', 'chained'), $chained_caps, 
   		'chainedquiz_questions', array('ChainedQuizQuestions','manage'));	
   	add_submenu_page(NULL, __('Users Completed Quiz', 'chained'), __('Users Completed Quiz', 'chained'), $chained_caps, 
   		'chainedquiz_list', array('ChainedQuizCompleted','manage'));		
   	
	}
	
	// CSS and JS
	static function scripts() {
		// CSS
		wp_register_style( 'chained-css', CHAINED_URL.'css/main.css?ver=1.0.7');
	  wp_enqueue_style( 'chained-css' );
   
   	wp_enqueue_script('jquery');
	   
	   // Chained quiz's own Javascript
		wp_register_script(
				'chained-common',
				CHAINED_URL.'js/common.js',
				false,
				'0.9.0',
				false
		);
		wp_enqueue_script("chained-common");
		
		$ui = get_option('chained_ui');
		
		$translation_array = array(
			'please_answer' => __('Please answer the question', 'chained'),
			'please_provide_email' => __('Please provide valid email address', 'chained'),
			'dont_autoscroll' => (empty($ui['dont_autoscroll']) ? 0 : 1),
		);
		wp_localize_script( 'chained-common', 'chained_i18n', $translation_array );	
	}
	
	// initialization
	static function init() {
		global $wpdb;
		load_plugin_textdomain( 'chained', false, CHAINED_RELATIVE_PATH."/languages/" );
		// start session only on front-end and Chained Quiz admin pages
		if (!session_id() and ( (!empty($_GET['page']) and (strstr($_GET['page'], 'chained'))) or !is_admin()
			or (wp_doing_ajax() and !empty($_POST['action'])) )) {
				@session_start();
		}
		
		// define table names 
		define( 'CHAINED_QUIZZES', $wpdb->prefix. "chained_quizzes");
		define( 'CHAINED_QUESTIONS', $wpdb->prefix. "chained_questions");
		define( 'CHAINED_CHOICES', $wpdb->prefix. "chained_choices");
		define( 'CHAINED_RESULTS', $wpdb->prefix. "chained_results");
		define( 'CHAINED_COMPLETED', $wpdb->prefix. "chained_completed");
		define( 'CHAINED_USER_ANSWERS', $wpdb->prefix. "chained_user_answers");
		define( 'CHAINED_MAIL_RELATIONS', $wpdb->prefix.'chained_mail_relations');
		
		define( 'CHAINED_VERSION', get_option('chained_version'));
		
		if(get_option('chained_debug_mode'))  {		
			$wpdb->show_errors();
			if(!defined('DIEONDBERROR')) define( 'DIEONDBERROR', true );
		}
				
		// shortcodes
		add_shortcode('chained-quiz', array("ChainedQuizShortcodes", "quiz"));
		add_shortcode('chained-share', array("ChainedSharing", "display"));		
		
		// once daily delete empty records older than 1 day
		if(get_option('chainedquiz_cleanup') != date("Y-m-d") and defined('CHAINED_COMPLETED') and $wpdb->get_var("SHOW TABLES LIKE '".CHAINED_COMPLETED."'") == CHAINED_COMPLETED) {			
			$wpdb->query("DELETE FROM ".CHAINED_COMPLETED." WHERE not_empty=0 AND datetime < '".current_time('mysql')."' - INTERVAL 24 HOUR");
			update_option('chainedquiz_cleanup', date("Y-m-d"));
		}
		
		add_action('template_redirect', array('ChainedSharing', 'social_share_snippet'));
		
		// catch own action for the mailing service integrations
		add_action('chained_quiz_completed', array('ChainedIntegrations', 'completed_quiz'));
		
		// default CSV separator if not set
		if(get_option('chained_csv_delim') == '') {
			update_option('chained_csv_delim', ',');
			update_option('chained_csv_quotes', '1');
		}
				
		$version = get_option('chainedquiz_version');
		if(version_compare($version, '0.86') == -1) self::install(true);
	}
			
	// manage general options
	static function options() {
		global $wpdb, $wp_roles;
		$roles = $wp_roles->roles;		
		
		if(!empty($_POST['ok']) and check_admin_referer('chained_options')) {
			// sender's email and email subjects
			update_option('chained_sender_name', sanitize_text_field($_POST['sender_name']));
			update_option('chained_sender_email', sanitize_email($_POST['sender_email']));
			update_option('chained_admin_subject', sanitize_text_field($_POST['admin_subject']));
			update_option('chained_user_subject', sanitize_text_field($_POST['user_subject']));						
			update_option('chained_csv_delim', sanitize_text_field($_POST['csv_delim']));
			$_POST['csv_quotes'] = empty($_POST['csv_quotes']) ? 0 : 1;
			update_option('chained_csv_quotes', $_POST['csv_quotes']);
			$gdpr_ips = empty($_POST['gdpr_ips']) ? 0 : 1;
			update_option('chained_gdpr_ips', $gdpr_ips);
			$debug_mode = empty($_POST['debug_mode']) ? 0 : 1;
			update_option('chained_debug_mode', $debug_mode);
			
			// user interface options
			$hide_go_ahead = empty($_POST['hide_go_ahead']) ? 0 : 1;
			$dont_autoscroll = empty($_POST['dont_autoscroll']) ? 0 : 1;
			$ui = array('hide_go_ahead' => $hide_go_ahead, 'go_ahead_value' => sanitize_text_field($_POST['go_ahead_value']),
				'dont_autoscroll' => $dont_autoscroll);			
			update_option('chained_ui', $ui);			
			
			if(current_user_can('manage_options')) {
				foreach($roles as $key=>$role) {
					$r = get_role($key);
					 
					if(!empty($_POST['manage_roles']) and is_array($_POST['manage_roles']) and in_array($key, $_POST['manage_roles'])) {					
	    				if(!$r->has_cap('chained_manage')) $r->add_cap('chained_manage');
					}
					else $r->remove_cap('chained_manage');
				}
			}
		}	
		
		$ui = get_option('chained_ui');
		$delim = get_option('chained_csv_delim');
		$gdpr_ips = get_option('chained_gdpr_ips');
		   	
		require(CHAINED_PATH."/views/options.html.php");
	}	
	
	static function help() {
		require(CHAINED_PATH."/views/help.php");
	}	
}