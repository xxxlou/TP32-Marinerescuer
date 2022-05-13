<?php
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class ChainedQuizQuestion {
	function add($vars) {
		global $wpdb;
		
		$vars['title'] = sanitize_text_field($vars['title']);		
		if(!in_array($vars['qtype'], array('radio', 'checkbox', 'text'))) $vars['qtype'] = 'radio';
		if(!current_user_can('unfiltered_html')) {
			$vars['question'] = strip_tags($vars['question']);
		}
		$accept_comments = empty($vars['accept_comments']) ? 0 : 1;
		$accept_comments_label = sanitize_text_field($vars['accept_comments_label']);
		
		// sort order
		$sort_order = $wpdb->get_var($wpdb->prepare("SELECT MAX(sort_order) FROM ".CHAINED_QUESTIONS."
			WHERE quiz_id=%d", $vars['quiz_id']));
		$sort_order++;	 
		
		$result = $wpdb->query($wpdb->prepare("INSERT INTO ".CHAINED_QUESTIONS." SET
			quiz_id=%d, question=%s, qtype=%s, `rank`=%d, title=%s, autocontinue=%d, sort_order=%d, 
			accept_comments=%d, accept_comments_label=%s", 
			intval($vars['quiz_id']), $vars['question'], $vars['qtype'], intval(@$vars['rank']), $vars['title'], 
			intval(@$vars['autocontinue']), $sort_order, $accept_comments, $accept_comments_label));
			
		if($result === false) throw new Exception(__('DB Error', 'chained'));
		return $wpdb->insert_id;	
	} // end add
	
	function save($vars, $id) {
		global $wpdb;
		
		$vars['title'] = sanitize_text_field($vars['title']);
		if(!in_array($vars['qtype'], array('radio', 'checkbox', 'text'))) $vars['qtype'] = 'radio';		
		$vars['question'] = wp_kses_post($vars['question']);
		
		$accept_comments = empty($vars['accept_comments']) ? 0 : 1;
		$accept_comments_label = sanitize_text_field($vars['accept_comments_label']);
		
		$result = $wpdb->query($wpdb->prepare("UPDATE ".CHAINED_QUESTIONS." SET
			question=%s, qtype=%s, title=%s, autocontinue=%d, accept_comments=%d, accept_comments_label=%s WHERE id=%d", 
			$vars['question'], $vars['qtype'], $vars['title'], intval(@$vars['autocontinue']), 
			$accept_comments, $accept_comments_label, $id));
			
			
		if($result === false) throw new Exception(__('DB Error', 'chained'));
		return true;	
	}
	
	function delete($id) {
		global $wpdb;
	
		$id = intval($id);
		
		// delete choices		
		$wpdb->query($wpdb->prepare("DELETE FROM ".CHAINED_CHOICES." WHERE question_id=%d", $id));
		
		// delete question
		$result = $wpdb->query($wpdb->prepare("DELETE FROM ".CHAINED_QUESTIONS." WHERE id=%d", $id));
		
		if($result === false) throw new Exception(__('DB Error', 'chained'));
		return true;	
	}
	
	// saves the choices on a question
	function save_choices($vars, $id) {
		global $wpdb;
		
		$id = intval($id);
		
		// edit/delete existing choices
		$choices = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".CHAINED_CHOICES." WHERE question_id=%d ORDER BY id ", $id));
		
		foreach($choices as $choice) {
			if(!empty($_POST['dels']) and in_array($choice->id, $_POST['dels'])) {
				$wpdb->query($wpdb->prepare("DELETE FROM ".CHAINED_CHOICES." WHERE id=%d", $choice->id));
				continue;
			}
			
			if(!current_user_can('unfiltered_html')) {
				$_POST['answer'.$choice->id] = strip_tags($_POST['answer'.$choice->id]);				
			}
			$_POST['goto'.$choice->id] = sanitize_text_field($_POST['goto'.$choice->id]);
			if(!is_numeric($_POST['points'.$choice->id])) $_POST['points'.$choice->id] = 0;
			
			// else update
			$wpdb->query($wpdb->prepare("UPDATE ".CHAINED_CHOICES." SET
				choice=%s, points=%s, is_correct=%d, goto=%s WHERE id=%d", 
				$_POST['answer'.$choice->id], $_POST['points'.$choice->id], 
				intval(@$_POST['is_correct'.$choice->id]), $_POST['goto'.$choice->id], $choice->id));
		}	
		
		// add new choices
		$counter = 1;
		$correct_array = (!empty($_POST['is_correct']) and is_array($_POST['is_correct'])) ? $_POST['is_correct'] : [];
		foreach($_POST['answers'] as $answer) {
			$correct = @in_array($counter, $correct_array) ? 1 : 0;
			$counter++;
			if($answer === '') continue;
			
			if(!current_user_can('unfiltered_html')) {
				$answer = strip_tags($answer);
			}
			if(!is_numeric($_POST['points'][($counter-2)])) $_POST['points'][($counter-2)] = 0;
		
			// now insert the choice
			$wpdb->query( $wpdb->prepare("INSERT INTO ".CHAINED_CHOICES." SET
				question_id=%d, choice=%s, points=%s, is_correct=%d, goto=%s, quiz_id=%d", 
				$id, $answer, $_POST['points'][($counter-2)], $correct, $_POST['goto'][($counter-2)], 
				intval($_POST['quiz_id'])) );
		}
	} // end save_choices

	// displays the question contents
	function display_question($question) {
		// for now only add stripslashes and autop, we'll soon add a filter like in Watupro
		$content = empty($question->question) ? stripslashes($question->title) : stripslashes($question->question);		
		$content = wpautop($content);
		$content = $GLOBALS['wp_embed']->run_shortcode($content); // allow https://codex.wordpress.org/Embed_Shortcode
		$content = do_shortcode($content);		
		return $content;
	}

  // displays the possible choices on a question
  function display_choices($question, $choices) {
  	   $autocontinue = $output = '';
  	   if($question->qtype == 'radio' and $question->autocontinue) {
  	   	$autocontinue = "onclick=\"chainedQuiz.goon(".$question->quiz_id.", '".admin_url('admin-ajax.php')."');\"";
  	   	$output .= '<!--hide_go_ahead-->';
  	   }  	   
  	   
		switch($question->qtype) {
			case 'text':
				return "<div class='chained-quiz-choice'><textarea class='chained-quiz-frontend' required='required' name='answer' rows='5' cols='80'></textarea></div>";
			break;
			case 'radio':
			case 'checkbox':
				$type = $question->qtype;
				$name = ($question->qtype == 'radio') ? "answer": "answers[]";				
				
				foreach($choices as $choice) {
					$choice_text = stripslashes($choice->choice);
					$choice_text = do_shortcode($choice_text);
					
					$output .= "<div class='chained-quiz-choice'><label class='chained-quiz-label'><input class='chained-quiz-frontend chained-quiz-$type' type='$type' name='$name' value='".$choice->id."' $autocontinue> $choice_text</label></div>";
				}
						
				return $output;
			break;
		}
  } // end display_choices
  
  // calculate the points of a given answer
  function calculate_points($question, $answer) {
  	global $wpdb;
  	
  	$ids = array(0);
  	if(is_array($answer) and !empty($answer[0])) {
  		$answer = chained_int_array($answer);
  		$ids = array_merge($ids, $answer);
  	}
  	else $ids[] = intval($answer);
  	
  	// select points
  	if($question->qtype != 'text') {
	  	$points = $wpdb->get_var($wpdb->prepare("SELECT SUM(points) FROM ".CHAINED_CHOICES."
	  		WHERE question_id=%d AND id IN (".implode(",", $ids).")", $question->id));
	  }
	  else {
	  	 // for text questions directly compare the value
	  		$points = $wpdb->get_var($wpdb->prepare("SELECT points FROM ".CHAINED_CHOICES."
	  		WHERE question_id=%d AND choice LIKE %s", $question->id, sanitize_text_field($answer)));
		}
  	return $points;	
  }
  
  // gets the next question in a quiz, depending on the given answer
  function next($question, $answer) {
 		global $wpdb; 	
 		
		// select answer(s)
		$goto = array();
		$answer_ids = array(0);
		if(is_array($answer)) {
			foreach($answer as $ans) {
				 if(!empty($ans)) $answer_ids[] = $ans;
			}
		} 
		else {
			if($question->qtype == 'text') {
					$answer = $wpdb->get_var($wpdb->prepare("SELECT id FROM ".CHAINED_CHOICES."
	  		  WHERE question_id=%d AND choice LIKE %s", $question->id, $answer));				
			} 
			
			if(!empty($answer)) $answer_ids[] = $answer; // radio buttons and text areas
		} 
		
		$answer_ids = chained_int_array($answer_ids);
		if(empty($answer_ids)) $answer_ids = array(0);
		
		// select the choices selected by the user		
		$choices = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".CHAINED_CHOICES." 
			WHERE question_id=%d AND id IN (".implode(",", $answer_ids).") ", $question->id));
			
		foreach($choices as $choice) {
			if(isset($goto[$choice->goto])) $goto[$choice->goto]++;
			else $goto[$choice->goto] = 1;			
		}
	  
		// now sort goto to figure out what's the top goto selection	
		arsort($goto);		
		$goto = array_flip($goto);
		$key = array_shift($goto);
		
		//let's treat textareas in different way. If answer is not found, let's not finalize the quiz but go to next
		if($question->qtype == 'text' and empty($key)) $key = 'next';
		
		// echo $key.'x'; 
		if(empty($key) or $key == 'finalize') return false;
		
		if($key == 'next') {
			// select next question by sort_order
			$question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." 
				WHERE quiz_id=%d AND sort_order > %d ORDER BY sort_order LIMIT 1", $question->quiz_id, $question->sort_order));
			return $question;	
		}
	
	  if(is_numeric($key)) {
	  	$question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." 
				WHERE quiz_id=%d AND id=%d LIMIT 1", $question->quiz_id, $key));
			return $question;	
	  }
	
	  // just in case
	  return false;		
	} // end next()
	
	// calculate if answer is correct, return $is_correct and $user_answer text for ChainedQuizQuiz::answers_table()
	// and return if the whole question is correctly answered
	// IMPORTANT: $answer is an object that contains: answer (textual), choice (textual choice for single choice questions, joined by the choices table),
	// question_id, choice_correct joined by the choices table.is_correct
	static function calc_answer($answer) {
		global $wpdb;
		
		$user_answer = $is_correct = '';
		$correct_var = 0;
		
		if($answer->qtype == 'text') {
			$user_answer = wpautop(stripslashes($answer->answer));
			$is_correct = $wpdb->get_var($wpdb->prepare("SELECT is_correct FROM ".CHAINED_CHOICES."
				WHERE question_id=%d AND choice=%s", $answer->question_id, $answer->answer));
			$correct_var = $is_correct;
			$is_correct = $is_correct ? __('Yes', 'chained') : __('No', 'chained');	
		}
		else {
			if(is_numeric($answer->answer)) {
				$user_answer = wpautop(stripslashes($answer->choice));
				$correct_var = $answer->choice_correct;
				$is_correct = $answer->choice_correct ? __('Yes', 'chained') : __('No', 'chained');	
			}
			else {
				$choice_ids = explode(',', $answer->answer);
				$points = 0;
				foreach($choice_ids as $cnt=>$choice_id) {
					$choice_text = $wpdb->get_row($wpdb->prepare("SELECT choice, is_correct, points FROM ".CHAINED_CHOICES." WHERE id=%d", $choice_id));
					$points += $choice_text->points;
					if($cnt) { 
						$user_answer .= ', ';
						$is_correct .= ', ';
					} 
					$user_answer .= empty($choice_text->choice) ? '' : stripslashes($choice_text->choice);
					$is_correct .= empty($choice_text->is_correct) ? __('No', 'chained') : __('Yes', 'chained');	
				}
				$user_answer = wpautop($user_answer);
				//echo "POINTS: $points";
				$correct_var = ($points > 0) ? 1 : 0;
			} // end if multuple answers
		} // end if checkbox and radio
		
		return array($is_correct, $user_answer, $correct_var);
	} // end calc_answer()
} 