	<h2 class="nav-tab-wrapper">
		<a class='nav-tab <?php if($active == 'general'):?>nav-tab-active<?php endif;?>' href="admin.php?page=chainedquiz_integrations"><?php _e('General Settings', 'chained')?></a>		
		<?php if($arigato_active and in_array('arigato', $integrations)):?><a class='nav-tab <?php if($active == 'arigato'):?>nav-tab-active<?php endif;?>' href="admin.php?page=chainedquiz_integrations&tab=arigato"><?php _e('Arigato', 'chained')?></a><?php endif;?>
		<?php if($arigatopro_active and in_array('arigatopro', $integrations)):?><a class='nav-tab <?php if($active == 'arigatopro'):?>nav-tab-active<?php endif;?>' href="admin.php?page=chainedquiz_integrations&tab=arigatopro"><?php _e('Arigato PRO', 'chained')?></a><?php endif;?>
		<?php if(in_array('mailchimp', $integrations)):?><a class='nav-tab <?php if($active == 'mailchimp'):?>nav-tab-active<?php endif;?>' href="admin.php?page=chainedquiz_integrations&tab=mailchimp"><?php _e('MailChimp', 'chained')?></a><?php endif;?>
	</h2>

<?php if(!empty($quizzes)):?>	
<script type="text/javascript" >
function chainedConfirmDelete(frm) {
		if(confirm("<?php _e('Are you sure?', 'chained')?>")) {
			frm.del.value=1;
			frm.submit();
		}
}

function chainedChangeQuiz(quizID, selectorID) {
	// array containing all grades by exams
	var grades = {<?php foreach($quizzes as $quiz): echo $quiz->id.' : {';
			foreach($quiz->results as $result):
				echo $result->id .' : "'.$result->title.'",';
			endforeach;
		echo '},';
	endforeach;?>};
	
	// construct the new HTML
	var newHTML = '<select name="result_id">';
	newHTML += "<option value='0'><?php _e('- Any result -', 'chained');?></option>";
	jQuery.each(grades, function(i, obj){
		if(i == quizID) {
			jQuery.each(obj, function(j, grade) {
				newHTML += "<option value=" + j + ">" + grade + "</option>\n";
			}); // end each grade
		}
	});
	newHTML += '</select>'; 
	
	jQuery('#'+selectorID).html(newHTML);
}
</script>
<?php endif;?>