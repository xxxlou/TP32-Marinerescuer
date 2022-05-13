<div id="ays-quiz-subscribe-email-page-main">
    <div class="ays-quiz-subscribe-email-page-info-header">
        <span>Grab your GIFT</span>
    </div>
    <div class="ays-quiz-subscribe-email-page-info-box">
        <span>Want a free addon for your <a href="https://ays-pro.com/wordpress/quiz-maker" target="_blank">Quiz Maker</a> plugin to make your experience more advanced and better?<br>
        Subscribe with a valid email address to get the <strong><a href="https://ays-pro.com/export-results-addon-for-quiz-maker" target="_blank">Export Results</a></strong> addon as a <strong>GIFT</strong>.<br>
        Do not forget to check your spam folder in case you cannot find it in your inbox after subscription!
        </span>
    </div>
    <form action="" method="post">
        <div class="ays-quiz-subscribe-email-page">
            <div class="ays-quiz-subscribe-email-page-box">
                <div class="ays-quiz-subscribe-email-page-title-box">
                    <div>
                        <span class="ays-quiz-subscribe-email-page-title-text"><?php echo sprintf(__("Please enter a valid email address to get the Export Results addon for %sFREE%s.", $this->plugin_name), "<strong>", "</strong>"); ?></span>
                    </div>

                </div>
                <div class="ays-quiz-subscribe-email-page-box-inputs">
                    <div class="ays-quiz-subscribe-email-page-box-text">
                        <input type="text" class="ays-text-input ays-quiz-subscribe-email-address">
                    </div>
                    <div class="ays-quiz-subscribe-email-page-box-button">
                        <button type="button" class="button ays-quiz-subscribe-button"><?php echo __("Subscribe", $this->plugin_name);?></button>
                    </div>
                </div>
                <div>
                    <span class="ays-quiz-subscribe-email-page-title-text-two"><?php echo __("Please check your spam folder if you can't find it in your inbox.", $this->plugin_name); ?></span>
                </div>
                <div class="ays-quiz-subscribe-email-error-message">
                    <span class="ays-quiz-subscribe-email-errors"></span>
                </div>
                <div class="ays-quiz-subscribe-email-loader " style="display: none;">
                    <img src="<?php echo AYS_QUIZ_ADMIN_URL;?>/images/loaders/tail-spin.svg">
                </div>
                <div class="ays-quiz-subscribe-email-success-message">
                    <div><span class="ays-quiz-subscribe-email-success-message-text"></span></div>
                    <div class="ays-quiz-subscribe-email-success-message-true">
                        <img src="<?php echo AYS_QUIZ_PUBLIC_URL;?>/images/correct-style-4.png">
                    </div>
                    <div class="ays-quiz-subscribe-email-success-message-false">
                        <img src="<?php echo AYS_QUIZ_PUBLIC_URL;?>/images/wrong-style-4.png">                        
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>