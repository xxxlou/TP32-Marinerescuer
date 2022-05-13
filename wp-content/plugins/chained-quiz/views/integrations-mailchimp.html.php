<div class="wrap">
	<h1><?php _e("Integrate with MailChimp", 'chained');?></h1>
	
	<?php self :: tabs('mailchimp', $quizzes);?>
	
	<form method="post">
		<p><?php _e('Your MailChimp API Key:', 'chained');?> <input type="text" name="api_key" value="<?php echo $api_key?>" size="60"> <br />
		<input type="checkbox" name="no_optin" value="1" <?php if(get_option('chainedchimp_no_optin') == 1) echo 'checked'?>> <?php _e('Do not require email confirmation (Abusing this may cause your MailChimp account to be suspended.)', 'chained');?><br><br>
		<input type="submit" name="set_key" value="<?php _e('Save Settings', 'chained');?>" class="button-primary"> </p>
		<?php wp_nonce_field('chained_mail_settings');?>
	</form> 
	
	<?php if(empty($api_key)):?>
		<p><b><?php _e('You will not be able to add any rules until you enter your MailChimp API Key.', 'chained');?></b></p>
	<?php return;
	endif;
	if(!empty($result->error) or empty($result->total)):?>
		<p><?php _e("We couldn't retrieve any mailing lists from your MailChimp account.", 'chained');?></p>
		<?php if(!empty($result->error)):?>
			<p><?php _e("We got this error from MailChimp:", 'chained');?> <b><?php echo $result->error;?></b></p>
		<?php endif;?>
		<p><a href="#" onclick="jQuery('#mailChimpResponse').toggle();return false;"><?php _e('Raw MailChimp response (debug)', 'chained');?></a></p>
		<div style="display:none;" id="mailChimpResponse"><?php echo $json_result;?></div>
	<?php endif;?>
			
	<p><?php _e('Here you can define which quizzes will automatically subscribe the respondents into selected audiences. Note that only quizzes which collect user email address are shown.', 'chained');?></p>
	
	<h2><?php _e('Add New Subscription Rule', 'chained')?></h2>	  
	  
	 <form method="post">
	 	<div class="wrap">
	 			<?php _e('User completes', 'chained')?> <select name="quiz_id" onchange="chainedChangeQuiz(this.value, 'chainedGradeSelector');">
	 			<?php foreach($quizzes as $quiz):?>
	 				<option value="<?php echo $quiz->id?>"><?php echo stripslashes($quiz->title)?></option>
	 			<?php endforeach;?>
	 			</select> 
				
				<?php _e('with the following result:', 'chained')?>
				<span id="chainedGradeSelector">
					<select name="result_id">
					   <option value="0"><?php _e('- Any result -', 'chained');?></option>
					   <?php foreach($quizzes[0]->results as $result):?>
					   	<option value="<?php echo $result->id?>"><?php echo stripslashes($result->title);?></option>
					   <?php endforeach;?>
					</select>
				</span>
				
				<?php _e('subscribe them to audience','chained')?> 
	 			<select name="list_id">
	 				<?php foreach($lists as $list):?>
	 					<option value="<?php echo $list->id?>"><?php echo stripslashes($list->name)?></option>
	 				<?php endforeach;?>
	 			</select>		
	 			
	 			<?php _e('and add these tags (optional)','chained')?> 		
	 			<input type="text" name="tags">
				
	 			<input type="submit" name="add" value="<?php _e('Add Rule', 'chained')?>" class="button button-primary">
	 	</div>
	 	<?php wp_nonce_field('chained_mail_relations');?>
	 </form> 
	 
	 <?php if(count($relations)):?>
	 	<h2><?php _e('Manage Existing Rules', 'chained')?></h2>
	 	
	 	<?php foreach($relations as $relation):?>
	 	<form method="post">
	 	<input type="hidden" name="id" value="<?php echo $relation->id?>">
	 	<input type="hidden" name="del" value="0">
	 	<div class="wrap">
	 			<?php _e('When user completes', 'chained')?> <select name="quiz_id" onchange="chainedChangeQuiz(this.value, 'chainedGradeSelector<?php echo $relation->id?>');">
	 			<?php foreach($quizzes as $quiz):
	 				$selected = ($quiz->id == $relation->quiz_id) ? " selected" : "";?>
	 				<option value="<?php echo $quiz->id?>"<?php echo $selected?>><?php echo stripslashes($quiz->title)?></option>
	 			<?php endforeach;?>
	 			</select> 
	 			
				<?php _e('with the following result:', 'chained')?>
				<span id="chainedGradeSelector<?php echo $relation->id?>">
					<select name="result_id">
					   <option value="0"><?php _e('- Any result -', 'chained');?></option>
					   <?php foreach($relation->results as $result):
					   	$selected = ($result->id == $relation->result_id) ? " selected" : "";?>
					   	<option value="<?php echo $result->id?>"<?php echo $selected?>><?php echo stripslashes($result->title);?></option>
					   <?php endforeach;?>
					</select>
				</span>		
				
				<select name="list_id">
	 				<?php foreach($lists as $list):
	 					$selected = ($list->id == $relation->list_id) ? " selected" : "";?>
	 					<option value="<?php echo $list->id?>"<?php echo $selected?>><?php echo stripslashes($list->name)?></option>
	 				<?php endforeach;?>
	 			</select>
	 			
	 			<?php _e('and add these tags (optional)','chained')?> 		
	 			<input type="text" name="tags" value="<?php echo stripslashes($relation->tags);?>">
	 			
	 			<input type="submit" name="save" value="<?php _e('Save Rule', 'chained')?>" class="button button-primary">
	 			<input type="button" value="<?php _e('Delete Rule', 'chained')?>" onclick="chainedConfirmDelete(this.form);" class="button"> 
	 	</div>
	 	<?php wp_nonce_field('chained_mail_relations');?>
	 </form> 
	 	<?php endforeach;?>
	 <?php endif;?>
</div>