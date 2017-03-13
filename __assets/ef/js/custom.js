(function ($) {
"use strict";


/*---------------------------------------------------
    Categories Accordion
----------------------------------------------------- */
$('#cat_accordion').cutomAccordion({
		saveState: false,
		autoExpand: true
	});

/*---------------------------------------------------
    Main Menu
----------------------------------------------------- */
$('#menu .nav > li > .dropdown-menu').each(function() {
		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();

		var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 5) + 'px');
		}
	});

var $screensize = $(window).width();
$('#menu .nav > li, #header .links > ul > li').on("mouseover", function() {
																		
			if ($screensize > 991) {
			$(this).find('> .dropdown-menu').stop(true, true).slideDown('fast');
			}			
			$(this).bind('mouseleave', function() {

			if ($screensize > 991) {
				$(this).find('> .dropdown-menu').stop(true, true).css('display', 'none');
			}
		});});
$('#menu .nav > li div > ul > li').on("mouseover", function() {
			if ($screensize > 991) {
			$(this).find('> div').css('display', 'block');
			}			
			$(this).bind('mouseleave', function() {
			if ($screensize > 991) {
				$(this).find('> div').css('display', 'none');
			}
		});});
$('#menu .nav > li > .dropdown-menu').closest("li").addClass('sub');

// Clearfix for sub Menu column
$( document ).ready(function() {
  $screensize = $(window).width();
    if ($screensize > 1199) {
        $('#menu .nav > li.mega-menu > div > .column:nth-child(6n)').after('<div class="clearfix visible-lg-block"></div>');
    }
    if ($screensize < 1199) {
        $('#menu .nav > li.mega-menu > div > .column:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
  }
});
$( window ).resize(function() {
    $screensize = $(window).width();
    if ($screensize > 1199) {
        $("#menu .nav > li.mega-menu > div .clearfix.visible-lg-block").remove();
        $('#menu .nav > li.mega-menu > div > .column:nth-child(6n)').after('<div class="clearfix visible-lg-block"></div>');
    } 
    if ($screensize < 1199) {
        $("#menu .nav > li.mega-menu > div .clearfix.visible-lg-block").remove();
        $('#menu .nav > li.mega-menu > div > .column:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
    }
});

// Clearfix for Brand Menu column
$( document ).ready(function() {
$screensize = $(window).width();
    if ($screensize > 1199) {
        $('#menu .nav > li.menu_brands > div > div:nth-child(12n)').after('<div class="clearfix visible-lg-block"></div>');
    }
    if ($screensize < 1199) {
        $('#menu .nav > li.menu_brands > div > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
    }
    if ($screensize < 991) {
		$("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
        $('#menu .nav > li.menu_brands > div > div:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
		$('#menu .nav > li.menu_brands > div > div:last-child').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
    }
	if ($screensize < 767) {
		$("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
        $('#menu .nav > li.menu_brands > div > div:nth-child(2n)').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
		$('#menu .nav > li.menu_brands > div > div:last-child').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
    }
});
$( window ).resize(function() {
    $screensize = $(window).width();
    if ($screensize > 1199) {
        $("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
        $('#menu .nav > li.menu_brands > div > div:nth-child(12n)').after('<div class="clearfix visible-lg-block"></div>');
    } 
    if ($screensize < 1199) {
        $("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
        $('#menu .nav > li.menu_brands > div > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
    }
	if ($screensize < 991) {
        $("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
        $('#menu .nav > li.menu_brands > div > div:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
		$('#menu .nav > li.menu_brands > div > div:last-child').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
    }
	if ($screensize < 767) {
        $("#menu .nav > li.menu_brands > div > .clearfix.visible-lg-block").remove();
        $('#menu .nav > li.menu_brands > div > div:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
		$('#menu .nav > li.menu_brands > div > div:last-child').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
    }
});

/*---------------------------------------------------
    Language and Currency Dropdowns
----------------------------------------------------- */
$('#currency, #language, #my_account').hover(function() {
    $(this).find('ul').stop(true, true).slideDown('fast');
  },function() {
    $(this).find('ul').stop(true, true).css('display', 'none');
  });

/*---------------------------------------------------
    Mobile Main Menu
----------------------------------------------------- */
$('#menu .navbar-header > span').on("click", function() {
	  $(this).toggleClass("active");  
	  $("#menu .navbar-collapse").slideToggle('medium');
	  return false;
	});

//mobile sub menu plus/mines button
$('#menu .nav > li > div > .column > div, .submenu, #menu .nav > li .dropdown-menu').before('<span class="submore"></span>');

//mobile sub menu click function
$('span.submore').on("click", function() {
	$(this).next().slideToggle('fast');
	$(this).toggleClass('plus');
	return false;
});
//mobile top link click
$('.drop-icon').on("click", function() {
	  $('#header .htop').find('.left-top').slideToggle('fast');
	  return false;
	});

/*---------------------------------------------------
    Increase and Decrease Button Quantity for Product Page
----------------------------------------------------- */
$(".qtyBtn").on("click", function() {
		if($(this).hasClass("plus")){
			var qty = $(".qty #input-quantity").val();
			qty++;
			$(".qty #input-quantity").val(qty);
		}else{
			var qty = $(".qty #input-quantity").val();
			qty--;
			if(qty>0){
				$(".qty #input-quantity").val(qty);
			}
		}
		return false;
	});	

/*---------------------------------------------------
    Product List
----------------------------------------------------- */
$('#list-view').on("click", function() {
	$(".products-category > .clearfix.visible-lg-block").remove();
	$('#content .product-layout').attr('class', 'product-layout product-list col-xs-12');
  localStorage.setItem('display', 'list');		
	$('.btn-group').find('#list-view').addClass('selected');
	$('.btn-group').find('#grid-view').removeClass('selected');
	return false;
});

/*---------------------------------------------------
   Product Grid
----------------------------------------------------- */
$(document).on('click', '#grid-view', function(e){
	$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-4 col-xs-12');
		
$screensize = $(window).width();
    if ($screensize > 1199) {
		$(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(4n)').after('<span class="clearfix visible-lg-block"></span>');
    }
    if ($screensize < 1199) {
		$(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(4n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
		$(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');
    }
$( window ).resize(function() {
    $screensize = $(window).width();
    if ($screensize > 1199) {
        $(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(4n)').after('<span class="clearfix visible-lg-block"></span>');
    } 
    if ($screensize < 1199) {
        $(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(4n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
        $(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');
    }
	if ($screensize < 767) {
        $(".products-category > .clearfix").remove();
    }
});
localStorage.setItem('display', 'grid');
$('.btn-group').find('#grid-view').addClass('selected');
$('.btn-group').find('#list-view').removeClass('selected');
	});
if (localStorage.getItem('display') == 'list') {
		$('#list-view').trigger('click');
	} else {
		$('#grid-view').trigger('click');
	}

/*---------------------------------------------------
   tooltips
----------------------------------------------------- */
$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});

/*---------------------------------------------------
   Scroll to top
----------------------------------------------------- */
$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 150) {
				$('#back-top').fadeIn();
			} else {
				$('#back-top').fadeOut();
			}
		});
		});
$('#back-top').on("click", function() {
	$('html, body').animate({scrollTop:0}, 'slow');
	return false;
});

/*---------------------------------------------------
   Facebook Side Block
----------------------------------------------------- */
$(function(){        
        $("#facebook.fb-left").hover(function(){            
        $(this).stop(true, false).animate({left: "0" }, 800, 'easeOutQuint' );        
        },
  function(){            
        $(this).stop(true, false).animate({left: "-241px" }, 800, 'easeInQuint' );        
        },1000);    
  });
$(function(){        
        $("#facebook.fb-right").hover(function(){            
        $(this).stop(true, false).animate({right: "0" }, 800, 'easeOutQuint' );        
        },
  function(){            
        $(this).stop(true, false).animate({right: "-241px" }, 800, 'easeInQuint' );        
        },1000);    
  });

/*---------------------------------------------------
   Twitter Side Block
----------------------------------------------------- */
$(function(){        
        $("#twitter_footer.twit-left").hover(function(){            
        $(this).stop(true, false).animate({left: "0" }, 800, 'easeOutQuint' );        
        },
  function(){            
        $(this).stop(true, false).animate({left: "-215px" }, 800, 'easeInQuint' );        
        },1000);    
  });
$(function(){        
        $("#twitter_footer.twit-right").hover(function(){            
        $(this).stop(true, false).animate({right: "0" }, 800, 'easeOutQuint' );        
        },
  function(){            
        $(this).stop(true, false).animate({right: "-215px" }, 800, 'easeInQuint' );        
        },1000);    
  });

/*---------------------------------------------------
   Video Side Block
----------------------------------------------------- */
$(function(){        
        $("#video_box.vb-left").hover(function(){            
        $(this).stop(true, false).animate({left: "0" }, 800, 'easeOutQuint' );        
        },
  function(){            
        $(this).stop(true, false).animate({left: "-566px" }, 800, 'easeInQuint' );        
        },1000);    
  });
$(function(){        
        $("#video_box.vb-right").hover(function(){            
        $(this).stop(true, false).animate({right: "0" }, 800, 'easeOutQuint' );        
        },
  function(){            
        $(this).stop(true, false).animate({right: "-566px" }, 800, 'easeInQuint' );        
        },1000);    
  });

/*---------------------------------------------------
   Custom Side Block
----------------------------------------------------- */
$(function(){        
        $('#custom_side_block.custom_side_block_left').hover(function(){            
        $(this).stop(true, false).animate({left: '0' }, 800, 'easeOutQuint' );        
        },
  function(){            
        $(this).stop(true, false).animate({left: '-215px' }, 800, 'easeInQuint' );        
        },1000);    
  });
$(function(){        
        $("#custom_side_block.custom_side_block_right").hover(function(){            
        $(this).stop(true, false).animate({right: "0" }, 800, 'easeOutQuint' );        
        },
  function(){            
        $(this).stop(true, false).animate({right: "-215px" }, 800, 'easeInQuint' );        
        },1000);    
  });

})(jQuery);