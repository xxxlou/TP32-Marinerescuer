jQuery(document).ready(function($) {

    //vilva js end

    var slider_auto, slider_loop, rtl, header_layout;
    
    if( vilva_data.auto == '1' ){
        slider_auto = true;
    }else{
        slider_auto = false;
    }
    
    if( vilva_data.loop == '1' ){
        slider_loop = true;
    }else{
        slider_loop = false;
    }
    
    if( vilva_data.rtl == '1' ){
        rtl = true;
    }else{
        rtl = false;
    }

    //sticky t bar toggle
    $('.sticky-t-bar .close').on( 'click', function(){
        $('.sticky-bar-content').slideToggle();
        $('.sticky-t-bar').toggleClass('active');
    });

    //header search toggle js
    $('.header-search .search-toggle').on( 'click', function(e){
        $(this).parent('.header-search').addClass('active');
        e.stopPropagation();
    });

    $('.header-search .search-form').on( 'click', function(e){
        e.stopPropagation();
    });

    $(window).on( 'click', function(){
        $('.header-search').removeClass('active');
    });

    //For main navigation
    $('.menu-item-has-children').prepend('<button class="submenu-toggle"><i class="fas fa-chevron-down"></i></button>');

    $('.menu-item-has-children .submenu-toggle').on( 'click', function(){
        $(this).toggleClass('active');
        $(this).siblings('.sub-menu').slideToggle();
    });

    $('.style-two .secondary-menu .toggle-btn, .style-nine .secondary-menu .toggle-btn, .style-ten .secondary-menu .toggle-btn, .style-eleven .secondary-menu .toggle-btn, .style-twelve .secondary-menu .toggle-btn, .style-fifteen .secondary-menu .toggle-btn, .style-sixteen .secondary-menu .toggle-btn, .style-seventeen .secondary-menu .toggle-btn, .style-eighteen .secondary-menu .toggle-btn, .style-nineteen .secondary-menu .toggle-btn').on( 'click', function(e){
        $(this).parent('.secondary-menu').addClass('menu-toggled');
        e.stopPropagation();
    });

    $('.style-one .secondary-menu .toggle-btn, .style-three .secondary-menu .toggle-btn, .style-four .secondary-menu .toggle-btn, .style-five .secondary-menu .toggle-btn, .style-six .secondary-menu .toggle-btn, .style-seven .secondary-menu .toggle-btn, .style-eight .secondary-menu .toggle-btn, .style-thirteen .secondary-menu .toggle-btn, .style-fourteen .secondary-menu .toggle-btn').on( 'click', function(e){
        $('.secondary-menu .mobile-menu').slideDown();
    });

    $('.style-one .secondary-menu .close-nav-toggle, .style-three .secondary-menu .close-nav-toggle, .style-four .secondary-menu .close-nav-toggle, .style-five .secondary-menu .close-nav-toggle, .style-six .secondary-menu .close-nav-toggle, .style-seven .secondary-menu .close-nav-toggle, .style-eight .secondary-menu .close-nav-toggle, .style-thirteen .secondary-menu .close-nav-toggle, .style-fourteen .secondary-menu .close-nav-toggle').on( 'click', function(e){
        $('.secondary-menu .mobile-menu').slideUp();
    });

    if($(window).width() < 1025) {
        $('.style-two .secondary-menu .toggle-btn, .style-nine .secondary-menu .toggle-btn, .style-ten .secondary-menu .toggle-btn, .style-eleven .secondary-menu .toggle-btn, .style-twelve .secondary-menu .toggle-btn, .style-fifteen .secondary-menu .toggle-btn, .style-sixteen .secondary-menu .toggle-btn, .style-seventeen .secondary-menu .toggle-btn, .style-eighteen .secondary-menu .toggle-btn, .style-nineteen .secondary-menu .toggle-btn').on( 'click', function(e){
            $('.secondary-menu .mobile-menu').slideToggle();
        });
    }

    $('.main-navigation .toggle-btn').on( 'click', function(e){
        $(this).siblings('.primary-menu-list').animate({
            width: 'toggle'
        });
    });

    $('.main-navigation .close').on( 'click', function(){
        $(this).parents('.primary-menu-list').animate({
            width: 'toggle'
        });
    });

    $('.nav-menu .close, body').on( 'click', function(){
        $('.secondary-menu').removeClass('menu-toggled');
    });

    $(window).on('keyup', function(event){
        if(event.key == 'Escape') {
            $('.secondary-menu').removeClass('menu-toggled');
            $('.secondary-menu .mobile-menu').slideUp();
            $('.header-search').removeClass('active');
        }
    });

    $('.nav-menu').on( 'click', function(e){
        e.stopPropagation();
    });

    $(window).on('load resize', function() {
        var adminHeight = $('.admin-bar #wpadminbar').outerHeight();
        var headerHeight = $('.site-header').outerHeight();
        if($(window).width() > 600){
            $('.admin-bar .sticky-header').css('margin-top', adminHeight);
        }
        $(window).on( 'scroll', function(){
            if($(this).scrollTop() > headerHeight) {
                $('.sticky-header').addClass('sticky');
            }else {
                $('.sticky-header').removeClass('sticky');
            }
        });
    });

    //for accessibility 
    $('.main-navigation ul li a, .secondary-menu ul li a, .submenu-toggle').on( 'focus', function() {
        $(this).parents('li').addClass('focused');
    }).on( 'blur', function() {
        $(this).parents('li').removeClass('focused');
    });

    //Banner slider js
    $('.site-banner.style-one .item-wrap').owlCarousel({
        items: 1,
        autoplay: slider_auto,
        loop: slider_loop,
        nav: true,
        dots: false,
        autoplaySpeed: 800,
        autoplayTimeout: 3000,
        rtl : rtl,
        responsive : {
            0 : {
                margin: 10,
                stagePadding: 20,
            }, 
            768 : {
                margin: 10,
                stagePadding: 80,
            }, 
            1025 : {
                margin: 40,
                stagePadding: 150,
            }, 
            1200 : {
                margin: 60,
                stagePadding: 200,
            }, 
            1367 : {
                margin: 80,
                stagePadding: 300,
            }, 
            1501 : {
                margin: 110,
                stagePadding: 342,
            }
        }
    });

    //add span in widget title
    $('.site-footer .widget .widget-title').wrapInner('<span></span>');

    //scroll to top js
    $(window).on( 'scroll', function(){
        if($(window).scrollTop() > 200){
            $('.back-to-top').addClass('active');
        }else {
            $('.back-to-top').removeClass('active');
        }
    });

    $('.back-to-top').on( 'click', function(){
        $('body,html').animate({
            scrollTop: 0,
        }, 600);
    });

    $('.widget_bttk_image_text_widget .bttk-itw-holder li .btn-readmore').wrap('<div class="btn-holder"></div>');

    if($('.single-post .site-main .article-meta').length) {
        $('.single-post .site-main article').addClass('has-article-meta');
    }   

    //alignfull js
    $(window).on('load resize', function() {
        var metaWidth;
        if($(window).width() > 1024){
            metaWidth = $('.single .site-main .article-meta').outerWidth() + 50;
        } else {
            metaWidth = $('.single .site-main .article-meta').outerWidth() + 30;
        }
        var gbWindowWidth = $(window).width();
        var gbContainerWidth = $('.vilva-has-blocks .site-content > .container').width();
        var gbContentWidth;
        if($('.single-post .site-main .article-meta').length){
            if($(window).width() > 767) {
                gbContentWidth = $('.vilva-has-blocks .site-main .entry-content').width() - metaWidth;
            } else {
                gbContentWidth = $('.vilva-has-blocks .site-main .entry-content').width();
            }
            $('.vilva-has-blocks.full-width .wp-block-cover-image .wp-block-cover__inner-container, .vilva-has-blocks.full-width .wp-block-cover .wp-block-cover__inner-container, .vilva-has-blocks.full-width-centered .wp-block-cover-image .wp-block-cover__inner-container, .vilva-has-blocks.full-width-centered .wp-block-cover .wp-block-cover__inner-container').css('padding-left', metaWidth);
        } else {
            gbContentWidth = $('.vilva-has-blocks .site-main .entry-content').width();
        }
        var gbMarginFull = (parseInt(gbContentWidth) - parseInt(gbWindowWidth)) / 2;
        var gbMarginFull2 = (parseInt(gbContentWidth) - parseInt(gbContainerWidth)) / 2;
        var gbMarginCenter = (parseInt(gbContentWidth) - parseInt(gbWindowWidth)) / 2;
        $(".vilva-has-blocks.full-width .site-main .entry-content .alignfull").css({"max-width": gbWindowWidth, "width": gbWindowWidth, "margin-left": gbMarginFull});
        $(".vilva-has-blocks.full-width-centered .site-main .entry-content .alignfull").css({"max-width": gbWindowWidth, "width": gbWindowWidth, "margin-left": gbMarginCenter});
        $(".vilva-has-blocks.full-width-centered .site-main .entry-content .alignwide").css({"max-width": gbContainerWidth, "width": gbContainerWidth, "margin-left": gbMarginFull2});
    });
    
});