<div class="wrap">
	<h1><?php _e("Integrate with Arigato Lite", 'chained');?></h1>
	
	<?php self :: tabs('arigato', $quizzes);?>	
	
	<p><?php _e('Here you can define which quizzes will automatically subscribe the respondents into your mailing list. Note that only quizzes which collect user email address are shown.', 'chained');?></p>
	
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
	 			
	 			<input type="submit" name="save" value="<?php _e('Save Rule', 'chained')?>" class="button button-primary">
	 			<input type="button" value="<?php _e('Delete Rule', 'chained')?>" onclick="chainedConfirmDelete(this.form);" class="button"> 
	 	</div>
	 	<?php wp_nonce_field('chained_mail_relations');?>
	 </form> 
	 	<?php endforeach;?>
	 <?php endif;?>
</div>