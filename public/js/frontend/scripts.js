/*!
    * Start Bootstrap - Agency v6.0.2 (https://startbootstrap.com/template-overviews/agency)
    * Copyright 2013-2020 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-agency/blob/master/LICENSE)
    */
    (function ($) {
    "use strict"; // Start of use strict

    // Smooth scrolling using jQuery easing
    $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function () {
        if (
            location.pathname.replace(/^\//, "") ==
                this.pathname.replace(/^\//, "") &&
            location.hostname == this.hostname
        ) {
            var target = $(this.hash);
            target = target.length
                ? target
                : $("[name=" + this.hash.slice(1) + "]");
            if (target.length) {
                $("html, body").animate(
                    {
                        scrollTop: target.offset().top - 72,
                    },
                    1000,
                    "easeInOutExpo"
                );
                return false;
            }
        }
    });
    
    $('.contactLink').click(function(){
        $('a[href="#contact"]').click();
    });
       
    $(window).scroll(function () {
        var x = $(this).scrollTop();
        $('.masthead').css('background-position', '50% ' + parseInt(-x / 5) + 'px' + ', 50% ' + parseInt(-x / 10) + 'px, center center');
    });
    
    // Carousel height equalizer
    var maxCarouselHeight = 0;
    $('.carousel-item').each(function(){        
       if($(this).height() > maxCarouselHeight) maxCarouselHeight = $(this).height();
    });
    $('.carousel-item').css('min-height', maxCarouselHeight + 'px');

    // Team hover
    $('.team-link').hover(
        function(){
            $(this).find('img').addClass('team-imageHover');
            $(this).find('.team-more').addClass('team-moreHover');
        },
        function(){
            $(this).find('img').removeClass('team-imageHover');
            $(this).find('.team-more').removeClass('team-moreHover');
        }
    );

    // Closes responsive menu when a scroll trigger link is clicked
    $(".js-scroll-trigger").click(function () {
        $(".navbar-collapse").collapse("hide");
    });

    // Activate scrollspy to add active class to navbar items on scroll
    $("body").scrollspy({
        target: "#mainNav",
        offset: 74,
    });

    // Collapse Navbar
    var navbarCollapse = function () {
        if ($("#mainNav").offset().top > 100) {
            $("#mainNav").addClass("navbar-shrink");
        } else {
            $("#mainNav").removeClass("navbar-shrink");
        }
    };
    // Collapse now if page is not at top
    navbarCollapse();
    // Collapse the navbar when page is scrolled
    $(window).scroll(navbarCollapse);
})(jQuery); // End of use strict
