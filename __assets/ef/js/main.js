$('#container').html('');

var postkonten = {};
if(konten == "detailproduk"){
	postkonten["idix"] = idix;
	postkonten["jd"] = jd;
}else if(konten == "detailpaket"){
	postkonten["idix"] = idix;
	postkonten["jd"] = jd;
}else if(konten == "konfirmasi"){
	postkonten["ord"] = ord;
}else if(konten == "detailorder"){
	postkonten["ord"] = ord;
}else if(konten == "uploadfile"){
	postkonten["ord"] = ord;
}
$.post(host+'loading-'+konten, postkonten , function(respons){
	var parsing = $.parseJSON(respons);
	$('#container').html(parsing.page);
});

function kumpulAction(type, p1, p2, p3){
	var postkumpul = {};

	switch(type){
		case "krj":
			$.post(host+'keranjang-belanja', { }, function(resp){
				parsingan = $.parseJSON(resp)
				$('#keranjangbelonjo').html(parsingan.page);
			})
		break;
		case "krj_update":
			var qty = $('#qrt_'+p1).val();
			var subtot = ( parseInt(qty) * parseInt(p2) );
			
			postkumpul["xqt"] = $('#qrt_'+p1).val();
			postkumpul["rwid"] = p1;
			$.post(host+'perbaharui-keranjang', postkumpul, function(resp){
				if(resp){
					var parsing = $.parseJSON(resp);
					$("#qtynya").html(parsing.total_qty);
					$("#pricenya").html(parsing.total_harga);
					$("#sb_"+p1).html( subtot.format(0, 3, '.', ',') );
				}
			})
		break;
		case "krj_hapus":
			postkumpul["rwid"] = p1;
			$.post(host+'hapus-keranjang', postkumpul, function(resp){
				if(resp){
					$("#krjblj").trigger("click");
				}
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

