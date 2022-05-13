<?php
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class ChainedQuizQuiz {
	function add($vars) {
		global $wpdb;
		
		$require_login = empty($vars['require_login']) ? 0 : 1;
		$times_to_take = empty($vars['times_to_take']) ? 0 : intval($vars['times_to_take']);
		$save_source_url = empty($vars['save_source_url']) ? 0 : 1;
		$vars['title'] = sanitize_text_field($vars['title']);
		$email_admin = empty($vars['email_admin']) ? 0 : 1;
      $email_user = empty($vars['email_user']) ? 0 : 1;
      $set_email_output = empty($vars['set_email_output']) ? 0 : 1;
      $email_output = chained_strip_tags($vars['email_output']);
      $admin_email = sanitize_text_field($vars['admin_email']); // don't use sanitize_email here, we can use multiple email addresses
      $email_required = empty($_POST['email_required']) ? 0 : 1;
      $hide_email_field = empty($_POST['hide_email_field']) ? 0 : 1;
		
		if(!current_user_can('unfiltered_html')) {
			$vars['output'] = strip_tags($vars['output']);
		}
		
		$result = $wpdb->query($wpdb->prepare("INSERT INTO ".CHAINED_QUIZZES." SET
			title=%s, output=%s, email_admin=%d, email_user=%d, require_login=%d, times_to_take=%d, 
			save_source_url=%d, set_email_output=%d, email_output=%s, admin_email=%s, email_required=%d, hide_email_field=%d", 
			$vars['title'], $vars['output'], $email_admin, $email_user, $require_login, $times_to_take, 
			$save_source_url, $set_email_output, $email_output, $admin_email, $email_required, $hide_email_field));
			
		if($result === false) throw new Exception(__('DB Error', 'chained'));

		$quiz_id = $wpdb->insert_id;
		if(!empty($vars['auto_publish'])) $this->auto_publish($quiz_id, $vars);		
		
		return $quiz_id;	
	} // end add
	
	function save($vars, $id) {
		global $wpdb;
		
		$id = intval($id);
		
		$require_login = empty($vars['require_login']) ? 0 : 1;
		$times_to_take = empty($vars['times_to_take']) ? 0 : intval($vars['times_to_take']);
		$save_source_url = empty($vars['save_source_url']) ? 0 : 1;
		$vars['title'] = sanitize_text_field($vars['title']);
		$email_admin = empty($vars['email_admin']) ? 0 : 1;
      $email_user = empty($vars['email_user']) ? 0 : 1;
      $set_email_output = empty($vars['set_email_output']) ? 0 : 1;
      $email_output = chained_strip_tags($vars['email_output']);
      $admin_email = sanitize_text_field($vars['admin_email']); 
      $email_required = empty($_POST['email_required']) ? 0 : 1;
      $hide_email_field = empty($_POST['hide_email_field']) ? 0 : 1;
		
		if(!current_user_can('unfiltered_html')) {
			$vars['output'] = strip_tags($vars['output']);
		}
		
		$result = $wpdb->query($wpdb->prepare("UPDATE ".CHAINED_QUIZZES." SET
			title=%s, output=%s, email_admin=%d, email_user=%d, require_login=%d, times_to_take=%d, 
			save_source_url=%d, set_email_output=%d, email_output=%s, admin_email=%s, email_required=%d,
			hide_email_field=%d  WHERE id=%d", 
			$vars['title'], $vars['output'], $email_admin, $email_user, 
			$require_login, $times_to_take, $save_source_url, $set_email_output, $email_output,$admin_email, 
			$email_required, $hide_email_field, $id));
			
		if($result === false) throw new Exception(__('DB Error', 'chained'));
		
		if(!empty($vars['auto_publish'])) $this->auto_publish($id, $vars);
		return true;	
	}
	
	function delete($id) {
		global $wpdb;
		
		$id = intval($id);
		
		// delete questions
		$wpdb->query($wpdb->prepare("DELETE FROM ".CHAINED_QUESTIONS." WHERE quiz_id=%d", $id));
		
		// delete choices
		$wpdb->query($wpdb->prepare("DELETE FROM ".CHAINED_CHOICES." WHERE quiz_id=%d", $id));
		
		// delete completed records
		$wpdb->query($wpdb->prepare("DELETE FROM ".CHAINED_COMPLETED." WHERE quiz_id=%d", $id));
		
		// delete the quiz
		$wpdb->query($wpdb->prepare("DELETE FROM ".CHAINED_QUIZZES." WHERE id=%d", $id));
	}

	function finalize($quiz, $points) {		
	    global $wpdb, $user_ID;
	    
	    $user_id = empty($user_ID) ? 0 : $user_ID;
	    $completion_id = empty($_COOKIE['chained_completion_id'.$quiz->id]) ? 0 : intval($_COOKIE['chained_completion_id'.$quiz->id]);
	     
		 $_result = new ChainedQuizResult();
		 // calculate result
		 $result = $_result->calculate($quiz, $points);
		 
		 if(!is_user_logged_in()) $user_name = __('Guest', 'chained');
			else {
				$user = get_userdata($user_ID);
				$user_name = $user->display_name;
			}
			
		 $user_email = '';
		 if($user_ID) {
				$user = get_userdata($user_ID);
				$user_email = $user->user_email;
		 }
		   		
		 if(!empty($_POST['chained_email'])) {
				$user_email = sanitize_email($_POST['chained_email']);
		 }	 	
		 
		 // get final screen and replace vars
		 $output = stripslashes($quiz->output);
		 $email_output = $quiz->set_email_output ? stripslashes($quiz->email_output) : $output;
		 
		 $output = str_replace('{{result-title}}', stripslashes(@$result->title), $output);
		 $output = str_replace('{{result-text}}', stripslashes(@$result->description), $output);
		 $output = str_replace('{{points}}', $points, $output);
		 $output = str_replace('{{questions}}', intval($_POST['total_questions']), $output);
		 $output = str_replace('{{user-name}}', $user_name, $output);
		 $output = str_replace('{{user-email}}', $user_email, $output);
		 
		 if(strstr($output, '{{answers-table}}')) {
		 	 $output = str_replace('{{answers-table}}', $this->answers_table($completion_id), $output);
		 }
		 
		 // {{correct}} and {{percentage}}
		 if(strstr($output, '{{correct}}') or strstr($output, '{{percentage}}') or strstr($email_output, '{{correct}}') or strstr($email_output, '{{percentage}}')) {
		 	 $num_correct = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM ".CHAINED_USER_ANSWERS." WHERE is_correct=1 AND completion_id=%d", $completion_id));
		 	 $percent_correct = intval($_POST['total_questions']) ? round( 100 * $num_correct / intval($_POST['total_questions']) ): 0;
		 	 
		 	 $output = str_replace('{{correct}}', $num_correct, $output);
		 	 $email_output = str_replace('{{correct}}', $num_correct, $email_output);
		 	 $output = str_replace('{{percentage}}', $percent_correct, $output);
		 	 $email_output = str_replace('{{percentage}}', $percent_correct, $email_output);
		 }
		 
		 $email_output = str_replace('{{result-title}}', stripslashes(@$result->title), $email_output);
		 $email_output = str_replace('{{result-text}}', stripslashes(@$result->description), $email_output);
		 $email_output = str_replace('{{points}}', $points, $email_output);
		 $email_output = str_replace('{{questions}}', intval($_POST['total_questions']), $email_output);
		 $email_output = str_replace('{{user-name}}', $user_name, $email_output);
		 $email_output = str_replace('{{user-email}}', $user_email, $email_output);
		 
		 if(strstr($email_output, '{{answers-table}}')) {
		 	 $email_output = str_replace('{{answers-table}}', $this->answers_table($completion_id), $email_output);
		 }
		 
		 // email user / admin?
		 $email_output = do_shortcode($email_output);
		 $this->send_emails($quiz, $email_output);
		 
		 $GLOBALS['chained_completion_id'] = $completion_id;
		 $GLOBALS['chained_result_id'] = @$result->id;
		 $output = do_shortcode($output);
		 $output = wpautop($output);
		 
		 // only if the quiz is published on more than one page, store info about source url
		 $source_url = '';
		 if(!empty($quiz->save_source_url)) $source_url = esc_url_raw($_SERVER['HTTP_REFERER']);	
		 
		 // now insert in completed
		 if(!empty($_COOKIE['chained_completion_id'.$quiz->id])) {		 	
		 	$wpdb->query( $wpdb->prepare("UPDATE ".CHAINED_COMPLETED." SET
		 		quiz_id = %d, points = %f, result_id = %d, datetime = NOW(), ip = %s, user_id = %d, 
		 		snapshot = %s, source_url=%s, email=%s WHERE id=%d",
		 		$quiz->id, $points, @$result->id, chained_user_ip(), $user_id, $output, 
		 		$source_url, $user_email, intval($_COOKIE['chained_completion_id'.$quiz->id])));

		 	$taking_id = $_COOKIE['chained_completion_id'.$quiz->id];
		 	setcookie('chained_completion_id'.$quiz->id, '', time() - 30*24*3600, '/');
		 }	 
		 else {
		 	
		 	// normally this shouldn't happen, but just in case
		 	$wpdb->query( $wpdb->prepare("INSERT INTO ".CHAINED_COMPLETED." SET
		 		quiz_id = %d, points = %f, result_id = %d, datetime = NOW(), ip = %s, user_id = %d, snapshot = %s, 
		 		source_url=%s, email=%s",
		 		$quiz->id, $points, @$result->id, chained_user_ip(), $user_id, $output, $source_url, $user_email));		 	
		 	$taking_id = $wpdb->insert_id;		
		 }
		 
		 // send API call for other plugins
		 do_action('chained_quiz_completed', $taking_id);
		 
		 // if the result needs to redirect, replace the output with the redirect URL
		 if(!empty($result->redirect_url)) $output = "[CHAINED_REDIRECT]".$result->redirect_url;
		 
		 return $output;
   } // end finalize
   
   // send email to user and admin if required
   function send_emails($quiz, $output) {
   	global $user_ID;
   	
   	if(empty($quiz->email_admin) and empty($quiz->email_user)) return true;
   	$admin_email = chained_admin_email();
   	$admin_receiver = empty($quiz->admin_email) ? get_option('chained_sender_email') : $quiz->admin_email; // admin email can be override at quiz level
   	
   	$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= 'From: '.$admin_email . "\r\n";
		
		$admin_output = $user_output = $output;
		
		if(strstr($output, '{{{split}}}')) {
			$parts = explode('{{{split}}}', $output);
			$user_output = trim($parts[0]);
			$admin_output = trim($parts[1]);
		}
   	
   	if(!empty($quiz->email_admin)) {   		
   		$subject = stripslashes(get_option('chained_admin_subject'));
   		$subject = str_replace('{{quiz-name}}', stripslashes($quiz->title), $subject);
   		
			if($user_ID) {
				$user = get_userdata($user_ID);
				$user_msg = sprintf(__('Username: %s <br> User email: %s', 'chained'), $user->user_login, $user->user_email); 
			}   		
			else {
				$user_msg = sprintf(__('User IP: %s', 'chained'), chained_user_ip());
				if(!empty($_POST['chained_email'])) $user_msg .= "<br>". sprintf(__('User email: %s', 'chained'), $_POST['chained_email']);
			}
   		
   		$message='<html><head><title>$subject</title>
			</head>
			<html><body>'.wpautop($admin_output).'</body></html>';	
			// echo $admin_receiver;		
			
			//echo $message;
			wp_mail($admin_receiver, $subject, $message, $headers);
   	}
   	
   	if(!empty($quiz->email_user)) {
   		$subject = stripslashes(get_option('chained_user_subject'));
   		$subject = str_replace('{{quiz-name}}', stripslashes($quiz->title), $subject);
   		
			if($user_ID) {
				$user = get_userdata($user_ID);
				$user_email = $user->user_email;
			}   		
			else {
				$user_email = $_POST['chained_email'];
			}
			
			if(empty($user_email)) return false;
   		
   		$message='<html><head><title>$subject</title>
			</head>
			<html><body>'.wpautop($user_output).'</body></html>';
			
			wp_mail($user_email, $subject, $message, $headers);
   	}
	} // end send_emails()
	
	function auto_publish($quiz_id, $vars) {
		global $wpdb;
	
		$post = array('post_content' => '[chained-quiz '.$quiz_id.']', 'post_name'=> sanitize_text_field($vars['title']), 
			'post_title'=>sanitize_text_field($vars['title']), 'post_status'=>'publish');
		wp_insert_post($post);
	}
	
	// creates a table from questions and answers along with correct / wrong answer and points collected
	function answers_table($completion_id) {
		global $wpdb;
		
		$_question = new ChainedQuizQuestion();
		
		$answers = $wpdb->get_results($wpdb->prepare("SELECT tUA.*, tC.choice as choice, tC.is_correct as choice_correct,
			tQ.question as question, tQ.qtype as qtype
			FROM ".CHAINED_USER_ANSWERS." tUA
			JOIN ".CHAINED_QUESTIONS." tQ ON tQ.id = tUA.question_id
			LEFT JOIN ".CHAINED_CHOICES." tC ON tC.id = tUA.answer AND tC.question_id=tQ.id
			WHERE tUA.completion_id=%d ORDER BY tUA.ID", $completion_id));
			
		$output = '<table class="chained-quiz-answers"><tr><th>'.__('Question', 'chained').'</th>
		<th>'.__('Answer', 'chained').'</th><th>'.__('Correct answer?', 'chained').'</th>
		<th>'.__('Points', 'chained').'</th></tr>';
		
		foreach($answers as $answer) {
			// prepare answer and correct info for checkboxes and other answers
			$user_answer = $is_correct = '';
			list($is_correct, $user_answer, $correct_var) = ChainedQuizQuestion :: calc_answer($answer);
			
			$output .= '<tr><td>'.$_question->display_question($answer).'</td><td>';
			$output .= $user_answer;
			if(!empty($answer->comments)) $output .= wpautop(stripslashes($answer->comments));
			$output .= '</td><td>';
			$output .= $is_correct;
			$output .= '</td><td>'.$answer->points.'</td></tr>';
		}
		
		$output .= '</table>';
		
		return $output;	
	} // end answers_table
	
	// copy / duplicate quiz
	static function copy($id) {
	   global $wpdb;
      $id = intval($id);
	   
	   // select & copy quiz
      $quiz = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUIZZES." WHERE id=%d", $id), ARRAY_A);
      $quiz['title'] = sprintf(__('%s (Copy)', 'chained'), stripslashes($quiz['title']));
      $quiz['output'] = stripslashes($quiz['output']);
      $_quiz = new ChainedQuizQuiz();  
      $new_id = $_quiz->add($quiz);
	   
	   // select & copy results
	   $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".CHAINED_RESULTS." WHERE quiz_id=%d ORDER BY id", $id), ARRAY_A);
	   $_result = new ChainedQuizResult();
	   foreach($results as $result) {
	      $result['quiz_id'] = $new_id;
	      $result['title'] = stripslashes($result['title']);
         $result['description'] = stripslashes($result['description']);
         $_result->add($result);
	   }
	   
	   // select & copy questions and choices
	   $id_matches = array(); // we'll use this to match old IDs with new IDs. Important because choices may contain ID of new question in "goto"
	   $questions = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . CHAINED_QUESTIONS . " WHERE quiz_id=%d ORDER BY id", $id), ARRAY_A);
	   $_question = new ChainedQuizQuestion();
	   foreach($questions as $cnt => $question) {
	      $question['title'] = stripslashes($question['title']);
	      $question['question'] = stripslashes($question['question']);
	      $question['quiz_id'] = $new_id;
	      $new_q_id = $_question->add($question);
	      $id_matches[$question['id']] = $new_q_id;
	      $questions[$cnt]['new_id'] = $new_q_id;
	   }
	   
	   // now transfer choices
	   foreach($questions as $question) {
	      $choices = $wpdb->get_results($wpdb->prepare("SELECT * FROM ". CHAINED_CHOICES." WHERE question_id=%d ORDER BY id", $question['id']));
	      foreach($choices as $choice) {
	         $choice->choice = stripslashes($choice->choice);
	         $choice->question_id = $question['new_id'];
	         if(is_numeric($choice->goto)) $choice->goto = @$id_matches[$choice->goto];
	         
	         $wpdb->query($wpdb->prepare("INSERT INTO " . CHAINED_CHOICES . " SET quiz_id=%d, question_id=%d, choice=%s, points=%f, is_correct=%d, goto=%s",
   	         $new_id, $choice->question_id, $choice->choice, $choice->points, $choice->is_correct, $choice->goto));
	      } // end foreach choice
	   }
	} // end copy()
}