<?php
$action = (isset($_GET['action'])) ? sanitize_text_field( $_GET['action'] ) : '';
$heading = '';
$loader_iamge = '';
$id = ( isset( $_GET['question_category'] ) ) ? absint( intval( $_GET['question_category'] ) ) : null;
$question_category = array(
    'id'            => '',
    'title'         => '',
    'description'   => '',
    'published'     => '1'
);
switch( $action ) {
    case 'add':
        $heading = __('Add new category', $this->plugin_name);
        break;
    case 'edit':
        $heading = __('Edit category', $this->plugin_name);
        $question_category = $this->question_categories_obj->get_question_category( $id );
        break;
}

// General Settings | options
$gen_options = ($this->settings_obj->ays_get_setting('options') === false) ? array() : json_decode( stripcslashes($this->settings_obj->ays_get_setting('options') ), true);

$loader_iamge = "<span class='display_none ays_quiz_loader_box'><img src='". AYS_QUIZ_ADMIN_URL ."/images/loaders/loading.gif'></span>";

if( isset( $_POST['ays_submit'] ) ) {
    $_POST['id'] = $id;
    $result = $this->question_categories_obj->add_edit_question_category();
}
if(isset($_POST['ays_apply'])){
    $_POST["id"] = $id;
    $_POST['ays_change_type'] = 'apply';
    $this->question_categories_obj->add_edit_question_category();
}

$question_category_title = (isset($question_category['title']) && $question_category['title'] != '') ? stripslashes( esc_attr($question_category['title']) ) : "";
$question_category_description = (isset($question_category['description']) && $question_category['description'] != '') ? stripslashes($question_category['description']) : "";
$question_category_published = (isset($question_category["published"]) && $question_category["published"] != '') ? stripslashes(esc_attr($question_category["published"])) : 1;

// WP Editor height
$quiz_wp_editor_height = (isset($gen_options['quiz_wp_editor_height']) && $gen_options['quiz_wp_editor_height'] != '') ? absint( sanitize_text_field($gen_options['quiz_wp_editor_height']) ) : 100 ;

?>
<div class="wrap">
    <div class="container-fluid">
        <h1><?php echo $heading; ?></h1>
        <hr/>
        <form class="ays-quiz-category-form" id="ays-quiz-category-form" method="post">
            <input type="hidden" class="quiz_wp_editor_height" value="<?php echo $quiz_wp_editor_height; ?>">
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for='ays-title'>
                        <?php echo __('Title', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Title of the question category',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-10">
                    <input class='ays-text-input' id='ays-title' name='ays_title' required type='text' value='<?php echo $question_category_title; ?>'>
                </div>
            </div>

            <hr/>
            <div class='ays-field'>
                <label for='ays-description'>
                    <?php echo __('Description', $this->plugin_name); ?>
                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Provide more information about the question category',$this->plugin_name)?>">
                        <i class="ays_fa ays_fa_info_circle"></i>
                    </a>
                </label>
                <?php
                $content = $question_category_description;
                $editor_id = 'ays-description';
                $settings = array('editor_height'=> $quiz_wp_editor_height,'textarea_name'=>'ays_description','editor_class'=>'ays-textarea');
                wp_editor($content, $editor_id, $settings);
                ?>
            </div>

            <hr/>
            <div class="form-group row">
                <div class="col-sm-2">
                    <label>
                        <?php echo __('Category status', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose whether the question category is active or not. If you choose Unpublished option, the question category won’t be shown anywhere on your website',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>

                <div class="col-sm-3">
                    <div class="form-check form-check-inline">
                        <input type="radio" id="ays-publish" name="ays_publish" value="1" <?php echo ( $question_category_published == '' ) ? "checked" : ""; ?> <?php echo ( $question_category_published == '1') ? 'checked' : ''; ?> />
                        <label class="form-check-label" for="ays-publish"> <?php echo __('Published', $this->plugin_name); ?> </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="ays-unpublish" name="ays_publish" value="0" <?php echo ( $question_category_published  == '0' ) ? 'checked' : ''; ?> />
                        <label class="form-check-label" for="ays-unpublish"> <?php echo __('Unpublished', $this->plugin_name); ?> </label>
                    </div>
                </div>
            </div>

            <hr/>
            <?php
            wp_nonce_field('question_category_action', 'question_category_action');
            $other_attributes = array( 'id' => 'ays-button' );
            submit_button( __( 'Save and close', $this->plugin_name ), 'primary ays-quiz-loader-banner', 'ays_submit', true, $other_attributes );
            $other_attributes = array( 'id' => 'ays_apply' );
            submit_button( __( 'Save', $this->plugin_name), 'ays-quiz-loader-banner', 'ays_apply', false, $other_attributes);
            echo $loader_iamge;
            ?>
        </form>
    </div>
</div>