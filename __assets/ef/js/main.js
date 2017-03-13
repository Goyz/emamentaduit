$('#container').html('');
$.post(host+'loading-'+konten, {}, function(respons){
	var parsing = $.parseJSON(respons);
	$('#container').html(parsing.page);
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

