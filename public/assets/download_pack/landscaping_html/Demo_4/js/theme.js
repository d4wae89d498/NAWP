"use strict"; // Start of use strict

function bootstrapAnimatedLayer() {

    /* Demo Scripts for Bootstrap Carousel and Animate.css article
     * on SitePoint by Maria Antonietta Perna
     */

    //Function to animate slider captions 
    function doAnimations(elems) {
        //Cache the animationend event in a variable
        var animEndEv = 'webkitAnimationEnd animationend';

        elems.each(function() {
            var $this = $(this),
                $animationType = $this.data('animation');
            $this.addClass($animationType).one(animEndEv, function() {
                $this.removeClass($animationType);
            });
        });
    }

    //Variables on page load 
    var $myCarousel = $('#minimal-bootstrap-carousel'),
        $firstAnimatingElems = $myCarousel.find('.item:first').find("[data-animation ^= 'animated']");

    //Initialize carousel 
    $myCarousel.carousel({
        interval: 7000
    });

    //Animate captions in first slide on page load 
    doAnimations($firstAnimatingElems);

    //Pause carousel  
    $myCarousel.carousel('pause');


    //Other slides to be animated on carousel slide event 
    $myCarousel.on('slide.bs.carousel', function(e) {
        var $animatingElems = $(e.relatedTarget).find("[data-animation ^= 'animated']");
        doAnimations($animatingElems);
    });
}
function projectCarousel() {
    if ($('.project-carousel').length) {
        $('.project-carousel').owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: false,
            autoWidth: true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            navText: [
                '',
                ''
            ],
            responsive: {
                0: {
                    items: 1,
                    autoWidth: false
                },
                480: {
                    items: 1,
                    autoWidth: false
                },
                600: {
                    items: 1,
                    autoWidth: false
                },
                1000: {
                    items: 1,
                    autoWidth: false
                }
            }
        });
    };
}

function clientCarousel() {
    if ($('.client-carousel').length) {
        $('.client-carousel').owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: false,
            autoWidth: true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            navText: [
                '<i class="fa fa-angle-left"></i>',
                '<i class="fa fa-angle-right"></i>'
            ],
            responsive: {
                0: {
                    items: 1,
                    autoWidth: false
                },
                480: {
                    items: 2,
                    autoWidth: false
                },
                600: {
                    items: 3,
                    autoWidth: false
                },
                1000: {
                    items: 6,
                    autoWidth: false
                }
            }
        });
    };
}
function clientCarousel2() {
    if ($('.client-carousel2').length) {
        $('.client-carousel2').owlCarousel({
            loop: false,
            margin: 30,
            nav: false,
            dots: false,
            autoWidth: true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: false,
            responsive: {
                0: {
                    items: 1,
                    autoWidth: false
                },
                480: {
                    items: 2,
                    autoWidth: false
                },
                600: {
                    items: 3,
                    autoWidth: false
                },
                1000: {
                    items: 6,
                    autoWidth: false
                }
            }
        });
    };
}
function teamCarousel() {
    if ($('.team-carousel').length) {
        $('.team-carousel').owlCarousel({
            loop: false,
            margin: 30,
            nav: false,
            dots: false,
            autoWidth: true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: false,
            responsive: {
                0: {
                    items: 1,
                    autoWidth: false
                },
                480: {
                    items: 2,
                    autoWidth: false
                },
                600: {
                    items: 3,
                    autoWidth: false
                },
                1000: {
                    items: 3,
                    autoWidth: false
                }
            }
        });
    };
}
function causesCarousel() {
    if ($('.causes-carousel').length) {
        $('.causes-carousel').owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: false,
            autoWidth: true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            navText: [
                '<i class="fa fa-angle-left"></i>',
                '<i class="fa fa-angle-right"></i>'
            ],
            responsive: {
                0: {
                    items: 1,
                    autoWidth: false
                },
                480: {
                    items: 2,
                    autoWidth: false
                },
                600: {
                    items: 3,
                    autoWidth: false
                },
                1000: {
                    items: 4,
                    autoWidth: false
                }
            }
        });
    };
}
function thmProjectFilter() {
    if ($('.mixit-gallery').length) {
        $('.mixit-gallery').mixItUp();
    };
}

function thmBarChart() {
    if ($('#thm-bar-chart').length) {
        var ctx = $("#thm-bar-chart");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [{
                    label: "Company dataset",
                    backgroundColor: "rgba(248,248,248,0.8)",
                    borderColor: "rgba(218,218,218,1)",
                    borderWidth: 1,
                    hoverBackgroundColor: "rgba(59,65,94,0.5)",
                    hoverBorderColor: "rgba(218,218,218,1)",
                    data: [55, 65, 90, 85, 75, 95, 98],
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    };
}

function doughnutChartBox() {
    if ($('#doughnut-chartBox').length) {
        var ctx = $("#doughnut-chartBox");
        var myDoughnutChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    "65%",
                    "13%",
                    "22%"
                ],
                datasets: [{
                    data: [300, 50, 100],
                    backgroundColor: [
                        "#CEC2AB",
                        "#8D6DC4",
                        "#F79468"
                    ],
                    hoverBackgroundColor: [
                        "#3B415E",
                        "#3B415E",
                        "#3B415E"
                    ],
                    hoverBorderColor: [
                        "#fff",
                        "#fff",
                        "#fff"
                    ]
                }]
            },
            option: {
                position: "left",
                responsive: true,
            }
        });
    };
}

function testiCarousel() {
    if ($('.testi-carousel').length) {
        $('.testi-carousel').owlCarousel({
            loop: true,
            margin: 0,
            nav: false,
            dots: true,
            autoWidth: true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            navText: [
                '<i class="fa fa-angle-left"></i>',
                '<i class="fa fa-angle-right"></i>'
            ],
            responsive: {
                0: {
                    items: 1,
                    autoWidth: false
                },
                480: {
                    items: 1,
                    autoWidth: false
                },
                600: {
                    items: 1,
                    autoWidth: false
                },
                1000: {
                    items: 1,
                    autoWidth: false
                }
            }
        });
    };
}

function maineNavToggle() {
    if ($('#main-navigation-wrapper .navbar-nav li .dropdown-submenu').length) {
        $('#main-navigation-wrapper .navbar-nav li .dropdown-submenu').parent('li').children('a').append(function() {
            return '<button class="dopdown-nav-toggler"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>';
        });
        $('#main-navigation-wrapper .navbar-nav .dopdown-nav-toggler').on('click', function() {
            if($(this).hasClass("dopdown-nav-toggler-active"))
            {
                $(this).removeClass("dopdown-nav-toggler-active");
                $('#main-navigation-wrapper .navbar-nav li ul.dropdown-submenu').fadeOut();
            }
            else
            {
                $('#main-navigation-wrapper .navbar-nav .dopdown-nav-toggler').removeClass("dopdown-nav-toggler-active");
                $('#main-navigation-wrapper .navbar-nav li ul.dropdown-submenu').hide();
                var Self = $(this);
                Self.addClass("dopdown-nav-toggler-active");
                Self.parent().parent().children('.dropdown-submenu').slideToggle();
            }
            
            return false;
        });
        

    };
}

function requestFormValidation() {

    if ($('#request-call-form').length) {
        $('#request-call-form').validate({ // initialize the plugin
            rules: {
                name: {
                    required: true
                },
                phone: {
                    required: true
                }
            },
            submitHandler: function(form) {
                 var name = $('#name').val();
                  var phone = $('#phone').val();
                  if(name != 'null' && phone != 'null')
                  {
                    $('#success').html('Message has been sent');
                    $('#name').val('');
                    $('#phone').val('');
                  }
            }
        });
    }
}


function contactFormValidation() {

    if ($('.contact-form').length) {
        $('.contact-form').validate({ // initialize the plugin
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                message: {
                    required: true
                },
                subject: {
                    required: true
                }
            },
            submitHandler: function(form) {
                 var name = $('#name').val();
                  var email = $('#email').val();
                  var subject = $('#subject').val();
                  var message = $('#message').val();
                  if(name != 'null' && email != 'null' && subject !='' && message !='' ) 
                  {
                    $('#success').html('Message has been sent');
                    $('#name').val('');
                    $('#email').val('');
                    $('#subject').val('');
                    $('#message').val('');
                  }
            }
        });
    }
}

function thmCounter() {
    if ($('.counter').length) {
        $('.counter').counterUp({
            delay: 10,
            time: 3000
        });
    };
}
function scrollMenu()
{
	if ($('.finance-navbar').length > 0)
	{
		$(window).on('scroll', function(){
		var $topG = $('.finance-navbar').offset().top;
		if ( $(window).scrollTop() > $topG + 66 ) {
			$('.finance-navbar').addClass('affix-coming')
		}
		else {
			$('.finance-navbar').removeClass('affix-coming')
		}
		$('.finance-navbar').affix({
					offset: {
						top: $topG + 150
					}
				})
	})
	}
}
function scrollMenu2()
{
    if ($('.finance-navbar2').length > 0)
    {
        $(window).on('scroll', function(){
        var $topG = $('#main-navigation-wrapper').offset().top;
        if ( $(window).scrollTop() > $topG + 66 ) {
            $('.finance-navbar2').addClass('affix-coming2');
        }
        else {
            $('.finance-navbar2').removeClass('affix-coming2');
        }
		$('.finance-navbar2').affix({
			offset: {
				top: $topG + 15
			}

		})

    })
    }
}
function scrollMenu3()
{
	if ($('.finance-navbar3').length > 0)
	{
		$(window).on('scroll', function(){
		var $topG = $('.finance-navbar3').offset().top;
		if ( $(window).scrollTop() > $topG + 66 ) {
			$('.finance-navbar3').addClass('affix-coming')
		}
		else {
			$('.finance-navbar3').removeClass('affix-coming')
		}
		$('.finance-navbar3').affix({
					offset: {
						top: $topG + 150
					}
				})
	})
	}
}
function scrollMenu4()
{
    if ($('.finance-navbar4').length > 0)
    {
        $(window).on('scroll', function(){
        var $topG = $('.finance-navbar4').offset().top;
        if ( $(window).scrollTop() > $topG + 66 ) {
            $('.finance-navbar4').addClass('affix-coming')
        }
        else {
            $('.finance-navbar4').removeClass('affix-coming')
        }
        $('.finance-navbar4').affix({
                    offset: {
                        top: $topG + 150
                    }
                })
    })
    }
}

// instance of fuction while Document ready event   
jQuery(document).on('ready', function() {
    (function($) {
        bootstrapAnimatedLayer();
        clientCarousel();
        thmProjectFilter();
        thmBarChart();
        doughnutChartBox();
        testiCarousel();
        maineNavToggle();
        contactFormValidation();
        thmCounter();
		scrollMenu();
        scrollMenu2();
        clientCarousel2();
        teamCarousel();
		scrollMenu3();
        scrollMenu4();
        causesCarousel();
        projectCarousel();
        requestFormValidation();
		$("li#search").click(function(e){
			if($("#main-navigation-wrapper").hasClass("active"))
			{
				$("#main-navigation-wrapper").removeClass("active");
			}
			else
			{
				$("#main-navigation-wrapper").addClass("active");
			}
		})
    })(jQuery);
});

// instance of fuction while Window Load event
jQuery(window).on('load', function() {
    (function($) {

    })(jQuery);
});

// instance of fuction while Window Scroll event
jQuery(window).on('scroll', function() {
    (function($) {
		
    })(jQuery);
});
