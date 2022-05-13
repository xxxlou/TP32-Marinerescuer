<div class="wrap">
	<h1><?php _e('Add/Edit Chained Quiz', 'chained')?></h1>
	
	<div class="postbox-container" style="width:73%;margin-right:2%;">
	
		<p><a href="admin.php?page=chained_quizzes"><?php _e('Back to quizzes', 'chained')?></a>
		<?php if(!empty($quiz->id)):?>
			| <a href="admin.php?page=chainedquiz_questions&quiz_id=<?php echo $quiz->id?>"><?php _e('Manage Questions', 'chained')?></a>
			| <a href="admin.php?page=chainedquiz_results&quiz_id=<?php echo $quiz->id?>"><?php _e('Manage Results / Outcomes', 'chained')?></a>
		<?php endif;?></p>
		
		<form method="post" onsubmit="return validateChainedQuiz(this);">
			<p><label><?php _e('Quiz Title', 'chained')?></label> <input type="text" name="title" size="60" value="<?php echo stripslashes(@$quiz->title)?>"></p>
			
			<p><label><?php _e('Final Output', 'chained')?></label> <?php echo wp_editor(stripslashes($output), 'output')?></p>
			
			<p><?php _e('This is the content that is shown to the user after they complete the quiz. The following variables can be used:', 'chained')?></p>
			
			<ul>
				<li>{{result-title}} <?php _e('- The result (grade) title', 'chained')?></li>
				<li>{{result-text}} <?php _e('- The result (grade) text/description', 'chained')?></li>
				<li>{{points}} <?php _e('- Points collected', 'chained')?></li>
				<li>{{questions}} <?php _e('- The number of total questions answered', 'chained')?></li>
				<li>{{correct}} <?php _e('- The number of correctly answered questions. Multiple choice questions will be considered correctly answered when the amount of points collected on them is positive.', 'chained')?></li>
				<li>{{percentage}} <?php _e('- The percentage of correctly answered questions from all questions.', 'chained')?></li>
				<li>{{answers-table}} <?php _e('- A table with the questions, answers given by the user, correct / wrong info and points collected.', 'chained')?></li>
				<li>{{user-name}} <?php _e('The user name of the logged in user - use it only if you require / expect logged in users. If the user is not logged in, the variable will display "Guest".', 'chained') ?></li>
				<li>{{user-email}} <?php _e('The user email address if the user is logged in or email address has been requested with the option "Send email to user". Otherwise it will be empty.', 'chained') ?></li>
			</ul>	
			
			<p><input type="checkbox" name="require_login" value="1" <?php if(!empty($quiz->require_login)) echo 'checked'?> onclick="this.checked ? jQuery('#timesToTake').show() : jQuery('#timesToTake').hide(); "> <?php _e('Require user login to take this quiz.', 'chained');?>
				<span id="timesToTake" style='display:<?php echo empty($quiz->require_login) ? 'none' : 'inline';?>'>
					<?php printf(__('Limit quiz attempts to %s. (Enter 0 for unlimited attempts.)', 'chained'), '<input type="text" size="3" name="times_to_take" value="'.(empty($quiz->times_to_take) ? 0 : $quiz->times_to_take).'">');?> 			
				</span>		
			</p>		
			
			<p><input type="checkbox" name="email_admin" value="1" <?php if(!empty($quiz->email_admin)) echo 'checked'?> onclick="if(this.checked || this.form.email_user.checked) {jQuery('#chainedEmailSettings').show()} else {jQuery('#chainedEmailSettings').hide()};"> <?php _e('Send me email when user completes this quiz. Send to this email address:', 'chained');?>
			<input type="text" name="admin_email" size="30" value="<?php echo empty($quiz->admin_email) ? get_option('chained_sender_email') : $quiz->admin_email;?>"> <?php _e('You can enter multiple emails separated by comma', 'chained');?></p>
			<p><input type="checkbox" name="email_user" value="1" <?php if(!empty($quiz->email_user)) echo 'checked'?> onclick="chainedEmailUserChk(this);"> <?php _e('Send email to user with their result. If the user is not logged in visitor an optional "Enter email" field will automatically appear above the quiz.', 'chained');?></p>
			<div id="chainedEmailRequired" style='margin-left: 50px;display:<?php echo empty($quiz->email_user) ? 'none' : 'block';?>'>
				<p><input type="checkbox" name="email_required" value="1" <?php if(!empty($quiz->email_required)) echo 'checked';?>> <?php _e('Providing email address is required to do the quiz.', 'chained');?>
					&nbsp; <input type="checkbox" name="hide_email_field" value="1" <?php if(!empty($quiz->hide_email_field)) echo 'checked';?>> <?php _e('Once the email address is provided, hide the field on next questions.', 'chained');?>	
				</p>
			</div>
			
			<div id="chainedEmailSettings" style='display:<?php echo (empty($quiz->email_admin) and empty($quiz->email_user)) ? 'none' : 'block'?>;'>
				<p><input type="checkbox" name="set_email_output" value="1" <?php if(!empty($quiz->set_email_output)) echo 'checked'?> onclick="this.checked ? jQuery('#chainedEmailOutputs').show() : jQuery('#chainedEmailOutputs').hide();"> <?php _e('Set email contents (if you skip this, the final screen output will be used for emails).', 'chained');?></p>
				
				<div id="chainedEmailOutputs" style='display:<?php echo empty($quiz->set_email_output) ? 'none' : 'email';?>'>
					<?php echo wp_editor(empty($quiz->email_output) ? '' : stripslashes($quiz->email_output), 'email_output')?>
					<br />
					<p><?php _e('You can use the same variables as in the Final Output box', 'chained');?><br>
					<?php _e('By default this content is used for both the email sent to user, and the email sent to admin. You can however use the {{{split}}} tag to make the email contents different. The content before the {{{split}}} tag will be sent to the user and the content after the {{{split}}} tag - to the admin.','chained');?></p>
				</div>
			</div>
			
			<p><input type="checkbox" name="save_source_url" value="1" <?php if(!empty($quiz->save_source_url)) echo 'checked'?>> <?php _e('Save source URL when submitting the quiz (useful if you have published the quiz in multiple places on your site).', 'chained');?></p>
				
			<?php if(!$is_published):?>
				 	<p><input type="checkbox" name="auto_publish" value="1"> <?php _e('Automatically publish this quiz in new post once I hit the "Save" button. (The new post will be auto-generated with the quiz title used for post title.)', 'chained')?></p>
			<?php endif;?>
			
			<p><input type="submit" value="<?php _e('Save Quiz', 'chained')?>" class="button-primary"></p>
			<input type="hidden" name="ok" value="1">
			<?php wp_nonce_field('chained_quiz');?>
		</form>
		
	</div>
	<div id="chained-sidebar">
			<?php include(CHAINED_PATH."/views/sidebar.html.php");?>
	</div>	
</div>

<script type="text/javascript" >
function validateChainedQuiz(frm) {
	if(frm.title.value == '') {
		alert("<?php _e('Title is required', 'chained')?>");
		frm.title.focus();
		return false;
	}
	
	return true;
}

function chainedEmailUserChk(chk) {
	if(chk.checked || chk.form.email_admin.checked) {
			jQuery('#chainedEmailSettings').show();
	} 
	else {
		jQuery('#chainedEmailSettings').hide(); 
	};
	
	if(chk.checked) jQuery('#chainedEmailRequired').show();
	else jQuery('#chainedEmailRequired').hide();
}
</script>