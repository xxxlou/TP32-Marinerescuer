<?php // requires login?
if(!empty($quiz->require_login) and !is_user_logged_in()) {
	 echo "<p><b>".__('You need to be registered and logged in to take this quiz.', 'chained') . 
		      	" <a href='".wp_login_url($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"])."'>".__('Log in', 'chained')."</a>";
		      if(get_option("users_can_register")) {
						echo " ".__('or', 'chained')." <a href='".site_url("/wp-login.php?watu_register=1&action=register&redirect_to=".urlencode(get_permalink( $post->ID )))."'>".__('Register', 'chained')."</a></b>";        
					}
					echo "</p>";
	return false;
}
// can re-take?
if(!empty($quiz->require_login) and !empty($quiz->times_to_take)) {
	$cnt_takings=$wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM ".CHAINED_COMPLETED."
				WHERE quiz_id=%d AND user_id=%d", $quiz->id, $user_ID)); 
	
	// multiple times allowed, but number is specified	
	if($quiz->times_to_take and $cnt_takings >= $quiz->times_to_take) {
		echo "<p><b>";
		printf(__("Sorry, you can take this quiz only %d times.", 'chained'), $quiz->times_to_take);
		echo "</b></p>";
		return false;
	}			
}
?>
<?php if(!empty($first_load)):?><div class="chained-quiz" id="chained-quiz-div-<?php echo $quiz->id?>"><?php endif;?>
<form method="post" id="chained-quiz-form-<?php echo $quiz->id?>">
	<div class="chained-quiz-area" id="chained-quiz-wrap-<?php echo $quiz->id?>">
		<?php if(!empty($quiz->email_user) and !is_user_logged_in()):?>
			<div class="chained-quiz-email" <?php if($quiz->hide_email_field and !empty($_POST['chained_email']) and preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $_POST['chained_email'])):?>style="display:none;"<?php endif;?>>
				<p><label><?php _e('Your email address:', 'chained');?></label> <input type="text" name="chained_email" value="<?php echo sanitize_email($_POST['chained_email']) ?? ''?>" id="chainedUserEmail" class="chained-quiz-email <?php if(!empty($quiz->email_required)) echo 'required';?>"></p>
			</div>
		<?php endif;?> 
		<div class="chained-quiz-question" id="chained-quiz-question-<?php echo $question->id?>">
			<?php echo $_question->display_question($question);?>
		</div>
		
		<div class="chained-quiz-choices" id="chained-quiz-choices-<?php echo $question->id?>">
				<?php echo $_question->display_choices($question, $choices);?>
		</div>
		
		<?php if(!empty($question->accept_comments)):?>
		<div class="chained-quiz-comments" id="chained-quiz-comments-<?php echo $question->id?>">
				<label><?php echo stripslashes($question->accept_comments_label);?></label>
				<input class='chained-quiz-frontend chained-quiz-comment' type='text' name='comments'>
		</div>
		<?php endif;?>
		
		<div class="chained-quiz-action" style='display:<?php echo ($question->autocontinue and $question->qtype == 'radio') ? 'none' : 'block';?>'>
			<input type="button" id="chained-quiz-action-<?php echo $quiz->id?>" value="<?php echo empty($ui['go_ahead_value']) ?__('Go ahead', 'chained') : $ui['go_ahead_value'] ?>" onclick="chainedQuiz.goon(<?php echo $quiz->id?>, '<?php echo admin_url('admin-ajax.php')?>');" disabled="true">
			<img src="<?php echo CHAINED_URL.'img/loading.gif'?>" width="32" height="32" alt="Loading..." style="display: none;" id="chainedLoading">
		</div>
	</div>
	<input type="hidden" name="question_id" value="<?php echo $question->id?>">
	<input type="hidden" name="quiz_id" value="<?php echo $quiz->id?>">
	<input type="hidden" name="post_id" value="<?php echo $post->ID?>">
	<input type="hidden" name="question_type" value="<?php echo $question->qtype?>">
	<input type="hidden" name="points" value="0">
</form>
<?php if(!empty($first_load)):?>
</div>
<script type="text/javascript" >
jQuery(function(){
	chainedQuiz.initializeQuestion(<?php echo $quiz->id?>);	
});

// prevent enter key from reloading the form
jQuery(document).ready(function() {
  jQuery(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
</script><?php endif;?>