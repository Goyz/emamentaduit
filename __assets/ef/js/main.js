$('#main-template').html('');
$.post(host+'loading-'+cont+'/'+cont2, {}, function(respons){
	var parsing = $.parseJSON(respons);
	$('#main-template').html(parsing.page);
});

function kumpulAction(type, p1, p2, p3){
	switch(type){
		case "krj":
			//$('.modal-dialog').css({'width':'00px'});
			$('#modalencuk').html('');
			$.post(host+'keranjang-belanja', { }, function(resp){
				parsingan = $.parseJSON(resp)
				$('#modalencuk').html(parsingan.page);
				$('#productModal').modal('show'); 
			})
		break;
		case "lgnpmb":
			$('#modalencuk').html('');
			$.post(host+'form-login', { }, function(resp){
				parsingan = $.parseJSON(resp)
				$('#modalencuk').html(parsingan.page);
				/*
				$('#productModal').modal({
					backdrop: 'static',
					keyboard: false
				});
				*/
				$('#productModal').modal('show'); 
			})
		break;
	}
}

function fillCombo(url, SelID, value, value2, value3, value4){
	if (value == undefined) value = "";
	if (value2 == undefined) value2 = "";
	if (value3 == undefined) value3 = "";
	if (value4 == undefined) value4 = "";
	
	$('#'+SelID).empty();
	$.post(url, {"v": value, "v2": value2, "v3": value3, "v4": value4},function(data){
		$('#'+SelID).append(data);
	});

}

function submit_form(frm,func){
	var url = jQuery('#'+frm).attr("url");
    jQuery('#'+frm).form('submit',{
            url:url,
            onSubmit: function(){
				  return $(this).form('validate');
            },
            success:function(data){
				//$.unblockUI();
                if (func == undefined ){
                     if (data == "1"){
                        pesan('Data Sudah Disimpan ','Sukses');
                    }else{
                         pesan(data,'Result');
                    }
                }else{
                    func(data);
                }
            },
            error:function(data){
				//$.unblockUI();
                 if (func == undefined ){
                     pesan(data,'Error');
                }else{
                    func(data);
                }
            }
    });
}

function openWindowWithPost(url,params){
    var newWindow = window.open(url, 'winpost'); 
    if (!newWindow) return false;
    var html = "";
    html += "<html><head></head><body><form  id='formid' method='post' action='" + url + "'>";

    $.each(params, function(key, value) { 
		if (value instanceof Array || value instanceof Object) {
			$.each(value, function(key1, value1) { 
				html += "<input type='hidden' name='" + key + "["+key1+"]' value='" + value1 + "'/>";
			});
		}else{
			html += "<input type='hidden' name='" + key + "' value='" + value + "'/>";
		}
    });
   
    html += "</form><script type='text/javascript'>document.getElementById(\"formid\").submit()</script></body></html>";
    newWindow.document.write(html);
    return newWindow;
}

function forcart(t, rwd, az){
	switch(t){
		case "apdeting":
			$.post(host+'perbaharui-keranjang', { 'qt':$('#qrt_'+rwd).val(), 'rws':rwd, 'ck':az }, function(resp){
				parsinganxxx = $.parseJSON(resp)
				if(az == 'ck'){
					$('#content-keranjang-checkout').html(parsinganxxx.page);
				}else{
					$('#content-keranjang').html(parsinganxxx.page);
				}
			});
		break;
		case "delting":
			$.post(host+'hapus-keranjang', { 'rws':rwd, 'ck':az }, function(resp){
				parsinganxxx = $.parseJSON(resp)
				if(az == 'ck'){
					$('#content-keranjang-checkout').html(parsinganxxx.page);
				}else{
					$('#content-keranjang').html(parsinganxxx.page);
				}
				$.post(host+'banyak-belanja', { } , function(prsp) {
					$('#tot_item').html(prsp);
					if(prsp == 0){
						$('#selesai_bel').remove();
					}
				} );
			});
		break;
	}
}

(function($) {
    "use strict";

    /*----------------------------
     	jQuery MeanMenu
    ------------------------------ */
    jQuery('nav#dropdown').meanmenu();


    /*----------------------------
    	tab-carosual-list
     ------------------------------ */
    $('.tab-carosual-list').bxSlider({
        slideWidth: 100,
        minSlides: 2,
        maxSlides: 3,
        slideMargin: 10,
        controls: true,
        prevText: ["<i class='fa fa-angle-left'></i>"],
        nextText: ["<i class='fa fa-angle-right'></i>"],
        pager: false,
    });
	
    /*--------------------------
     scrollUp
    ---------------------------- */
    $.scrollUp({
        scrollText: '<i class="fa fa-angle-up"></i>',
        easingType: 'linear',
        scrollSpeed: 900,
        animation: 'fade'
    });

    /*--------------------------
     counter
    ---------------------------- */
    $('.counter').counterUp({
        delay: 10,
        time: 2000
    });

    /*--------------------------
     counter
    ---------------------------- */
    $('.masonry-active').masonry({
        // options
        itemSelector: '.col-md-4',
        columnWidth: 1
    });
    /*--------------------------
     sliphover
    ---------------------------- */
    $(".blog-flickr").sliphover();

    /*--------------------------
     Select Option
    ---------------------------- */
    $('.selectpicker').selectpicker({
        style: 'btn-info',
        size: 4
    });
	
    /*---------------------
    	Category menu
    --------------------- 
    $('#categorymenu li.active').addClass('open').children('ul').show();
    $('#categorymenu li.has-sub>a').on('click', function() {
        $(this).removeAttr('href');
        var element = $(this).parent('li');
        if (element.hasClass('open')) {
            element.removeClass('open');
            element.find('li').removeClass('open');
            element.find('ul').slideUp(400);
        } else {
            element.addClass('open');
            element.children('ul').slideDown(400);
            element.siblings('li').children('ul').slideUp(400);
            element.siblings('li').removeClass('open');
            element.siblings('li').find('li').removeClass('open');
            element.siblings('li').find('ul').slideUp(400);
        }
    });
	*/
    // coming soon template
    $('[data-countdown]').each(function() {
        var $this = $(this),
            finalDate = $(this).data('countdown');
        $this.countdown(finalDate, function(event) {
            $this.html(event.strftime('<span class="estut-count days"><span class="time-count">%-D</span> <p>Days</p></span> <span class="estut-count hour"><span class="time-count">%-H</span> <p>Hours</p></span> <span class="estut-count minutes"><span class="time-count">%M</span> <p>Minutes</p></span> <span class="estut-count second"> <span><span class="time-count">%S</span> <p>Seconds</p></span>'));
        });
    });

    /*---------------------
    	Mobile menu
    --------------------- */
    $('nav#mobile-menu').meanmenu();
	
})(jQuery);