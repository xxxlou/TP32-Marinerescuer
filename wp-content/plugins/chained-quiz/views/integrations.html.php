<div class="wrap">
	<h1><?php _e("Integrations with newsletter services", 'chained');?></h1>
	
	<?php self :: tabs('general');?>
	
	<p><?php _e('Here you can define integration with various newsletter and email marketing plugins and services. This means that a quiz is completed the email of the respondend can be subscribed to selected mailing lists. Currently supported:','chained');?></p>
	<ul>
		<li><a href="https://calendarscripts.info/bft-pro/" target="_blank"><?php _e('Arigato Autoresponder and Newsletter.', 'chained');?></a></li>
		<li><a href="https://wordpress.org/plugins/bft-autoresponder/" target="_blank"><?php _e('Arigato PRO Drip Marketing Suite', 'chained');?></a></li>
		<li><a href="https://mailchimp.com/" target="_blank"><?php _e('MailCHimp', 'chained');?></a></li>
	</ul>
	
	<p><?php _e('Note that only quizzes which have the option "Send email to user with their result" selected can be integrated.', 'chained');?></p>
	
	<form method="post">
		<p><?php if($arigato_active):?>
			<input type="checkbox" name="integrate_arigato" value="1" <?php if(in_array('arigato', $integrations)) echo 'checked'?>> <?php _e('Integrate with Arigato','chained');?>
		<?php else: _e('The plugin Arigato is not installed.', 'chained'); endif;?></p>
		
		<p><?php if($arigatopro_active):?>
			<input type="checkbox" name="integrate_arigatopro" value="1" <?php if(in_array('arigatopro', $integrations)) echo 'checked'?>> <?php _e('Integrate with Arigato PRO','chained');?>
		<?php else: _e('The plugin Arigato PRO is not installed.', 'chained'); endif;?></p>
		
		<p><input type="checkbox" name="integrate_mailchimp" value="1" <?php if(in_array('mailchimp', $integrations)) echo 'checked'?>> <?php _e('Integrate with MailChimp','chained');?>
		
		<p><input type="submit" value="<?php _e('Save Integrations', 'chained')?>" class="button button-primary"></p>
		<?php wp_nonce_field('chained_integrations');?>
		<input type="hidden" name="save" value="1">
	</form>
</div>