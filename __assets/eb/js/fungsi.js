var index_row;
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
if(dd<10){dd='0'+dd} 
if(mm<10){mm='0'+mm}
today = yyyy+'-'+mm+'-'+dd;
function formatDate(date) {
	var bulan=date.getMonth() +1;
	var tgl=date.getDate();
	if(bulan < 10){
		bulan='0'+bulan;
	}
	
	if(tgl < 10){
		tgl='0'+tgl;
	}
	return date.getFullYear() + "-" + bulan + "-" + tgl;
}
function myparser(s){
    if (!s) return new Date();
    var ss = (s.split('-'));
    var y = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var d = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
        return new Date(y,m-1,d);
    } else {
        return new Date();
    }
}		
function loadUrl(obj, urls,table){
    $("#main-content").html("").addClass("loading");
	$.post(urls,{table:table},function (html){
	    $("#main-content").html(html).removeClass("loading");
    });
}

function getClientHeight(){
	var theHeight;
	if (window.innerHeight)
		theHeight=window.innerHeight;
	else if (document.documentElement && document.documentElement.clientHeight) 
		theHeight=document.documentElement.clientHeight;
	else if (document.body) 
		theHeight=document.body.clientHeight;
	
	return theHeight;
}

var divcontainer;
function windowFormPanel(html,judul,width,height){
	divcontainer = $('#jendela');
	$(divcontainer).unbind();
	$('#isiJendela').html(html);
    $(divcontainer).window({
		title:judul,
		width:width,
		height:height,
		autoOpen:false,
		top: Math.round(frmHeight/2)-(height/2),
		left: Math.round(frmWidth/2)-(width/2),
		modal:true,
		maximizable:false,
		minimizable: false,
		collapsible: false,
		closable: true,
		resizable: false,
	    onBeforeClose:function(){	   
			$(divcontainer).window("close",true);
			//$(divcontainer).window("destroy",true);
			//$(divcontainer).window('refresh');
			return true;
	    }		
    });
    $(divcontainer).window('open');       
}
function windowFormClosePanel(){
    $(divcontainer).window('close');
	//$(divcontainer).window('refresh');
}

var container;
function windowForm(html,judul,width,height){
    container = "win"+Math.floor(Math.random()*9999);
    $("<div id="+container+"></div>").appendTo("body");
    container = "#"+container;
    $(container).html(html);
    $(container).css('padding','5px');
    $(container).window({
       title:judul,
       width:width,
       height:height,
       autoOpen:false,
       maximizable:false,
       minimizable: false,
	   collapsible: false,
       resizable: false,
       closable:true,
       modal:true,
	   onBeforeClose:function(){	   
			$(container).window("close",true);
			$(container).window("destroy",true);
			return true;
	   }
    });
    $(container).window('open');        
}
function closeWindow(){
    $(container).window('close');
    $(container).html("");
}


function getClientWidth(){
	var theWidth;
	if (window.innerWidth) 
		theWidth=window.innerWidth;
	else if (document.documentElement && document.documentElement.clientWidth) 
		theWidth=document.documentElement.clientWidth;
	else if (document.body) 
		theWidth=document.body.clientWidth;

	return theWidth;
}



function genGrid(modnya, divnya, lebarnya, tingginya, param_tambahan){
	if(lebarnya == undefined){
		lebarnya = getClientWidth-250;
	}
	if(tingginya == undefined){
		tingginya = getClientHeight-300
	}

	var kolom ={};
	var frozen ={};
	var judulnya;
	var param={};
	var urlnya;
	var urlglobal="";
	var url_detil="";
	var post_detil={};
	var fitnya;
	var klik=false;
	var doble_klik=false;
	var pagesizeboy = 10;
	var singleSelek = true;
	var footer=false;
	var row_number=true;
	var nowrap=true;
	switch(modnya){
		case "pic_sales":
			judulnya = "Daftar PIC Sales MKS";
			urlnya = "tbl_registration";
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			param['id_paket'] = param_tambahan;
			kolom[modnya] = [	
				{field:'status',title:'Status',width:100, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						if(value==1){
							return "Aktif";
						}else{
							return "Tidak Aktif";
						}
					},
					styler:function(value,rowData,rowIndex){
						if(value==1){return 'background:green;color:#ffffff;'}
						else {return 'background:red;color:#ffffff;'}
						
					}
				},
				{field:'email_address',title:'Email',width:230, halign:'center',align:'left'},
				{field:'nama_lengkap',title:'Nama',width:150, halign:'center',align:'left'},
				{field:'role',title:'Jabatan',width:120, halign:'center',align:'center'},				
				{field:'tanggal_daftar',title:'Tgl. Register',width:150, halign:'center',align:'center'},
				{field:'lokasi',title:'Alamat Lengkap',width:400, halign:'center',align:'left'},
				{field:'no_handphone',title:'No. HP',width:100, halign:'center',align:'center'},
			];
		break;
		case "manajemen_gudang_sekolah":
			judulnya = "Manajemen Order Pelanggan Sekolah";
			urlnya = "manajemen_gudang_sekolah";
			fitnya = true;
			nowrap=false;
			//footer=true;
			//param['flag']=(modnya=='gudang_konfirmasi' ? 'P' : 'PK');
			row_number=true;
			frozen[modnya] = [	
				{field:'id_pemesanan',title:'Detail',width:80, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						var modul="invoice";
						return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modnya+"\",\""+value+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/activity.png'></a>";
					}
				},
				{field:'id',title:'Status',width:120, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						var modul="cetak_bast";
						//return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modul+"\",\""+value+"\")'><img src='"+host+"__assets/easyui/themes/icons/print.png'></a>";
						if(rowData.flag=='P'){
							return "Proses Cetak/Produksi";
						}else if(rowData.flag=='PK'){
							return "Proses Packing";
						}else if(rowData.flag=='PP'){
							return "Proses Pengiriman";
						}
						else{
							return "Selesai";
						}
					},
					styler:function(value,rowData,rowIndex){
						if(rowData.flag=='P'){return 'background:green;color:#ffffff;'}
						else if(rowData.flag=='PK'){return 'background:yellow;color:navy;'}
						else if(rowData.flag=='PP'){return 'background:blue;color:#ffffff;'}
					}
					
				},
				
				{field:'flag',title:'Packing',width:80, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						var modul="set_packing";
						if(value=='P'){
							return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modul+"\",\""+rowData.id+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/bank.png'></a>";
						}else{
							return "<img src='"+host+"__assets/eb/easyui/themes/icons/ok.png'>";
						}
					}
				},
				{field:'create_by',title:'Kirim',width:80, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						var modul="set_kirim_sekolah";
						if(rowData.flag=='PK'){
							return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modul+"\",\""+rowData.id+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/send-icon.png'></a>";
						}else if(rowData.flag=='P'){
							return "<img src='"+host+"__assets/eb/easyui/themes/icons/edit_remove.png'>";
						}else{
							return "<img src='"+host+"__assets/eb/easyui/themes/icons/ok.png'>";
						}
					}
				},
				{field:'create_date',title:'Cetak Surat Jalan',width:120, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						var mod='surat_jalan';
						if(rowData.flag=='PP'){
							return "<a href='javascript:void(0);' class='btn btn-small' onclick='openWindowWithPost(\""+host+"backoffice-Cetak\", {mod : \""+mod+"\",id:\""+rowData.id_pemesanan+"\"} );'><img src='"+host+"__assets/eb/easyui/themes/icons/Printer-Ink-icon.png'></a>";
						}else{
							return "<img src='"+host+"__assets/eb/easyui/themes/icons/cancel.png'>";
						}
					}
				},
				
			];
			kolom[modnya] = [	
				{field:"nama_sekolah",title:'Nama Sekolah',width:200, halign:'center',align:'left'},				
				{field:'no_gudang',title:'No. Gudang - Tgl',width:170, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						return "NO : "+value+" <br>Tgl : "+rowData.tanggal_masuk
					}
				},				
				{field:'no_order',title:'No. Pesanan - Tgl ',width:170, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						return "NO : "+value+" <br>Tgl : "+rowData.tanggal_order
					}
				},
				{field:'zona',title:'Zona',width:40, halign:'center',align:'center'},
				{field:'alamat_pengiriman',title:'Alamat Pengiriman',width:350, halign:'center',align:'left'},
				{field:'jasa_pengiriman',title:'Jasa Kirim',width:100, halign:'center',align:'left'},
				/*
				{field:'nama_lengkap',title:'PIC',width:200, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						if(rowData.jenis_pembeli=='SEKOLAH')
							return rowData.nama_kepala_sekolah;
						else 
							return value
					}
				},
				
				//{field:'nama_sekolah',title:'Nama Sekolah',width:200, halign:'center',align:'left'},
				{field:'grand_total',title:'Total',width:150, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
				*/
			];
		break;
		case "manajemen_gudang_umum":
			judulnya = "Manajemen Order Pelanggan Umum";
			urlnya = "manajemen_gudang_umum";
			fitnya = true;
			nowrap=false;
			//footer=true;
			//param['flag']=(modnya=='gudang_konfirmasi' ? 'P' : 'PK');
			row_number=true;
			frozen[modnya] = [	
				{field:'id_pemesanan',title:'Detail',width:80, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						var modul="invoice";
						return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modnya+"\",\""+value+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/activity.png'></a>";
					}
				},
				{field:'id',title:'Status',width:120, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						var modul="cetak_bast";
						//return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modul+"\",\""+value+"\")'><img src='"+host+"__assets/easyui/themes/icons/print.png'></a>";
						if(rowData.flag=='P'){
							return "Proses Cetak/Produksi";
						}else if(rowData.flag=='PK'){
							return "Proses Packing";
						}else{
							return "Selesai";
						}
					},
					styler:function(value,rowData,rowIndex){
						if(rowData.flag=='P'){return 'background:green;color:#ffffff;'}
						else if(rowData.flag=='PK'){return 'background:yellow;color:navy;'}
					}
					
				},
				
				{field:'flag',title:'Packing',width:80, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						var modul="set_packing";
						if(value=='P'){
							return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modul+"\",\""+rowData.id+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/bank.png'></a>";
						}else{
							return "<img src='"+host+"__assets/eb/easyui/themes/icons/ok.png'>";
						}
					}
				},
				{field:'create_by',title:'Kirim',width:80, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						var modul="set_kirim_umum";
						if(rowData.flag=='PK'){
							return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modul+"\",\""+rowData.id+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/send-icon.png'></a>";
						}else if(rowData.flag=='P'){
							return "<img src='"+host+"__assets/eb/easyui/themes/icons/edit_remove.png'>";
						}else{
							return "<img src='"+host+"__assets/eb/easyui/themes/icons/ok.png'>";
						}
					}
				},				
			];
			kolom[modnya] = [	
				{field:"nama_lengkap",title:'Nama Pelanggan',width:200, halign:'center',align:'left'},		
				{field:'no_gudang',title:'No Gudang - TGL',width:150, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						return "NO : "+value+" <br>Tgl : "+rowData.tgl_masuk
					}
				},				
				{field:'no_konfirmasi',title:'No. Konfirmasi - Tgl',width:150, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						return "NO : "+value+" <br>Tgl : "+rowData.tanggal_konfirmasi
					}
				},
				{field:'no_order',title:'No. Pesanan - Tgl ',width:150, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						return "NO : "+value+" <br>Tgl : "+rowData.tanggal_order
					}
				},
				{field:'zona',title:'Zona',width:40, halign:'center',align:'center'},
				{field:'alamat_pengiriman',title:'Alamat Pengiriman',width:350, halign:'center',align:'left'},
				{field:'jasa_pengiriman',title:'Jasa Kirim',width:100, halign:'center',align:'left'},
				{field:'nama_lengkap',title:'PIC',width:200, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						if(rowData.jenis_pembeli=='SEKOLAH')
							return rowData.nama_kepala_sekolah;
						else 
							return value
					}
				},
				
				
				//{field:'nama_sekolah',title:'Nama Sekolah',width:200, halign:'center',align:'left'},
				{field:'total_pembayaran',title:'Total',width:150, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
			];
		break;
		case "ver_gudang_sekolah":
		case "ver_gudang_umum":
			judulnya = (modnya=='ver_gudang_sekolah' ? "Daftar Order Pelanggan Sekolah" : "Daftar Order Pelanggan Umum");
			urlnya = modnya;
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			frozen[modnya] = [	
				{field:'id',title:'Detail',width:80, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modnya+"\",\""+value+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/log.gif'></a>";
					}
				},
				{field:'flag_ver_gudang',title:'Verifikasi Gudang',width:120, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						if(value=='P'){
							return 'Belum Diverifikasi';
						}
						else if(value=='PA'){
							return 'Tunggu Verifikasi dari Admin';
						}
						else if(value=='RA'){
							return 'Return Verifikasi dari Admin';
						}
						else if(value=='F'){
							return 'Sudah Diverifikasi';
						}
						else{
							return 'Belum Diverifikasi';
						} 
					},
					styler:function(value,rowData,rowIndex){
						if(value=='P'){
							return 'background:yellow;color:navy;'
						}
						else if(value=='PA'){
							return 'background:yellow;color:navy;'
						}
						else if(value=='RA'){
							return 'background:yellow;color:navy;'
						}
						else if(value=='F'){
							return 'background:green;color:navy;'
						}
						else {
							return 'background:yellow;color:navy;'
						}
						
					}
				},
				{field:'flag_ver',title:'Verifikasi Admin',width:120, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						if(value=='P'){
							return 'Belum Diverifikasi';
						}
						else if(value=='RG'){
							return 'Return Verifikasi dari Gudang';
						}
						else if(value=='TG'){
							return 'Tunggu Verifikasi dari Gudang';
						}
						else if(value=='C'){
							return 'Cancel';
						}
						else{
							return 'Sudah Diverifikasi';
						} 
					},
					styler:function(value,rowData,rowIndex){
						if(value=='P'){
							return 'background:yellow;color:navy;'
						}
						else if(value=='RG'){
							return 'background:yellow;color:navy;'
						}
						else if(value=='TG'){
							return 'background:blue;color:#ffffff;'
						}
						else if(value=='C'){
							return 'background:red;color:navy;'
						}
						else {
							return 'background:green;color:#ffffff;'
						}
						
					}
				},
								
			];
			kolom[modnya] = [	
				{field:'no_order',title:'No. Pesanan',width:130, halign:'center',align:'center'},
				{field:'tanggal_order',title:'Tgl. Order',width:150, halign:'center',align:'center'},
				{field:'zona',title:'Zona',width:80, halign:'center',align:'center'},
				{field:(modnya=='ver_gudang_sekolah' ? "nama_sekolah" : "nama_lengkap"),title:(modnya=='ver_gudang_sekolah' ? 'Nama Sekolah' : "Nama Lengkap"),width:200, halign:'center',align:'left'},
				/*
				{field:'sub_total',title:'Sub. Total',width:150, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
				{field:'pajak',title:'Pajak',width:150, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
				{field:'grand_total',title:'Grand Total',width:150, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
				*/
			];
		break;
		case "ver_sekolah":
		case "ver_umum":
			judulnya = (modnya=='ver_sekolah' ? "Daftar Order Pelanggan Sekolah" : "Daftar Order Pelanggan Umum");
			urlnya = modnya;
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			frozen[modnya] = [	
				{field:'id',title:'Detail',width:80, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modnya+"\",\""+value+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/log.gif'></a>";
					}
				},
				{field:'flag_ver',title:'Verifikasi Admin',width:120, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						if(value=='P'){
							return 'Belum Diverifikasi';
						}
						else if(value=='RG'){
							return 'Return Verifikasi dari Gudang';
						}
						else if(value=='TG'){
							return 'Tunggu Verifikasi dari Gudang';
						}
						else if(value=='C'){
							return 'Cancel';
						}
						else{
							return 'Sudah Diverifikasi';
						} 
					},
					styler:function(value,rowData,rowIndex){
						if(value=='P'){
							return 'background:yellow;color:navy;'
						}
						else if(value=='RG'){
							return 'background:yellow;color:navy;'
						}
						else if(value=='TG'){
							return 'background:blue;color:#ffffff;'
						}
						else if(value=='C'){
							return 'background:red;color:navy;'
						}
						else {
							return 'background:green;color:#ffffff;'
						}
						
					}
				},
				{field:'flag_ver_gudang',title:'Verifikasi Gudang',width:120, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						if(value=='P'){
							return 'Belum Diverifikasi';
						}
						else if(value=='PA'){
							return 'Tunggu Verifikasi dari Admin';
						}
						else if(value=='RA'){
							return 'Return Verifikasi dari Admin';
						}
						else if(value=='F'){
							return 'Sudah Diverifikasi';
						}
						else{
							return 'Belum Diverifikasi';
						} 
					},
					styler:function(value,rowData,rowIndex){
						if(value=='P'){
							return 'background:yellow;color:navy;'
						}
						else if(value=='PA'){
							return 'background:yellow;color:navy;'
						}
						else if(value=='RA'){
							return 'background:yellow;color:navy;'
						}
						else if(value=='F'){
							return 'background:green;color:navy;'
						}
						else {
							return 'background:yellow;color:navy;'
						}
						
					}
				},				
			];
			kolom[modnya] = [	
				{field:'no_order',title:'No. Pesanan',width:130, halign:'center',align:'center'},
				{field:'tanggal_order',title:'Tgl. Order',width:150, halign:'center',align:'center'},
				{field:'zona',title:'Zona',width:80, halign:'center',align:'center'},			
				{field:(modnya=='invoice' || modnya=='ver_sekolah' ? "nama_sekolah" : "nama_lengkap"),title:(modnya=='invoice' || modnya=='ver_sekolah'  ? 'Nama Sekolah' : "Nama Pelanggan"),width:200, halign:'center',align:'left'},
				{field:'email',title:'Email',width:200, halign:'center',align:'left'},
				{field:(modnya=='ver_sekolah' ? "no_telp_sekolah" : "no_hp_customer"),title:(modnya=='ver_sekolah'  ? 'Telp. Sekolah' : "No. Kontak"),width:200, halign:'center',align:'left'},
				
				/*
				{field:'sub_total',title:'Sub. Total',width:150, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
				{field:'pajak',title:'Pajak',width:150, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
				*/
				{field:'grand_total',title:'Grand Total',width:150, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return "Rp. "+NumberFormat(value);
					}
				},
				
				
				
				
			];
		break;
		case "mapping_paket_belum":
			judulnya = "Data Master Buku";
			urlnya = "mapping_paket_belum";
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			param['id_paket'] = param_tambahan;
			kolom[modnya] = [	
				{field:'kelas',title:'Kelas',width:70, halign:'center',align:'left'},
				{field:'nama_group',title:'Jenis Buku',width:80, halign:'center',align:'left'},
				{field:'nama_kategori',title:'Kategori',width:70, halign:'center',align:'left'},
				{field:'judul_buku',title:'Judul Buku',width:300, halign:'center',align:'left'}
			];
		break;
		case "mapping_paket_sudah":
			judulnya = "Data Mapping Paket";
			urlnya = "mapping_paket_sudah";
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			param['id_paket'] = param_tambahan;
			kolom[modnya] = [	
				{field:'kelas',title:'Kelas',width:70, halign:'center',align:'left'},
				{field:'nama_group',title:'Jenis Buku',width:80, halign:'center',align:'left'},
				{field:'nama_kategori',title:'Kategori',width:70, halign:'center',align:'left'},
				{field:'judul_buku',title:'Judul Buku',width:300, halign:'center',align:'left'}
			];
		break;
		case "paket":
			judulnya = "Daftar Paket Buku MKS - STORE ";
			urlnya = "tbl_paket";
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			kolom[modnya] = [	
				{field:'nama_paket',title:'Nama Paket',width:350, halign:'center',align:'left'},
				//{field:'nama_kategori',title:'Harga Paket',width:150, halign:'center',align:'left'},
			];
		break;
	
		case "reg_marketing":
			judulnya = "Daftar Marketing MKS - STORE ";
			urlnya = "reg_marketing";
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			kolom[modnya] = [	
				{field:'nama_lengkap',title:'Nama',width:200, halign:'center',align:'left'},
				{field:'registration_date',title:'Tgl. Reg',width:150, halign:'center',align:'center'},
				{field:'sts_member',title:'Status',width:100, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						if(value=='P'){return 'Not Member';}
						else{return value;}
					},
					styler:function(value,rowData,rowIndex){
						if(value=='P'){return 'background-color:red;color:#FFFFFF';}
						
					}
				},
				{field:'alamat',title:'Alamat',width:250, halign:'center',align:'left'},
				{field:'kode_pos',title:'Kode Pos',width:100, halign:'center',align:'left'},
				{field:'email_address',title:'Email',width:200, halign:'center',align:'left'},
			];
		break;
		case "pengguna":
			judulnya = "Daftar Pengguna";
			urlnya = "admin";
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			kolom[modnya] = [	
				{field:'username',title:'UserName',width:200, halign:'center',align:'left'},
				{field:'status',title:'Status',width:200, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						if(value==1){return 'Aktif';}
						else{return 'Tidak Aktif';}
					}
				},
				{field:'flag_group',title:'Group',width:300, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						if(value=='A'){return 'Administrator';}
						else if(value=='G'){return 'Gudang';}
						else if(value=='S'){return 'Superadmin';}
						else if(value=='L'){return 'LKPP';}
					}
				}
			];
		break;
		case "komplain":
			judulnya = "Daftar Komplain Pelanggan ";
			urlnya = "tbl_komplain";
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			kolom[modnya] = [	
				{field:'no_order',title:'No Order',width:200, halign:'center',align:'left'},
				{field:'no_komplain',title:'No Komplain',width:200, halign:'center',align:'left'},
				{field:'komplain',title:'Komplain',width:400, halign:'center',align:'left'},
				{field:'create_date',title:'Tgl. komplain',width:150, halign:'center',align:'center'}
			];
		break;
		case "komentar":
			judulnya = "Daftar Komentar Pelanggan ";
			urlnya = "tbl_komentar";
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			kolom[modnya] = [	
				{field:'nama_lengkap',title:'Nama Lengkap',width:200, halign:'center',align:'left'},
				{field:'rating',title:'Rating',width:120, halign:'center',align:'center',
					styler:function(value,rowData,rowIndex){
						if(value==1){return 'background-color:red;color:#FFFFFF';}
						else if(value==2){return 'background-color:red;color:#FFFFFF';}
						else if(value==3){return 'background-color:yellow;color:navy';}
					}
				},
				{field:'komentar',title:'Komentar',width:400, halign:'center',align:'left'},
				{field:'create_date',title:'Tgl. Komentar',width:150, halign:'center',align:'center'},
				{field:'flag',title:'Status',width:100, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						var mod_na='komentar_set_flag';
						if(value==1){
							return "<a href='javascript:void(0);' class='btn btn-small btn-success' onclick='get_detil(\""+mod_na+"\",\""+rowData.id+"\",0)'>Aktif</a>";
						}else{
							return "<a href='javascript:void(0);' class='btn btn-small btn-alert' onclick='get_detil(\""+mod_na+"\",\""+rowData.id+"\",1)'>Tidak Aktif</a>";
						}
					},
					styler:function(value,rowData,rowIndex){
						if(value==0){return 'background-color:red;color:#FFFFFF';}
					}
				}
			];
		break;
		case "monitor_order":
			judulnya = "Monitoring Order Pelanggan ";
			urlnya = "tbl_monitor";
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=false;
			frozen[modnya] = [	
				{field:'no_order',title:'No. Order',width:150, halign:'center',align:'center'},
				{field:'jenis_pembeli',title:'Jenis Pelanggan',width:100, halign:'center',align:'center'},	
				{field:'id',title:'Nama Pelanggan',width:150, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						if(rowData.jenis_pembeli=='SEKOLAH'){
							return rowData.nama_sekolah;
						}else if(rowData.jenis_pembeli=='UMUM'){
							return rowData.nama_lengkap;
						}
					},
				},				
			];

			kolom[modnya] = [	
				{field:'verifikasi',title:'Stat. Verifikasi',width:150, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						if(value=='P'){
							return "Proses Verifikasi <br/>"+rowData.tanggal_p_ver;
						}else if(value=='F'){
							return "Sudah Terverifikasi <br/>"+rowData.tanggal_f_ver;
						}else{
							return "-";
						}
					},
					styler:function(value,rowData,rowIndex){
						if(value=='P'){return 'background-color:red;color:#FFFFFF';}
						else if(value=='F'){return 'background-color:#edf1f4;color:navy';}
						else return 'background-color:red;color:#FFFFFF';
					}
				},
				{field:'konfirmasi',title:'Stat. Konfirmasi',width:150, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						if(value=='P'){
							return "Proses Konfirmasi <br/>"+rowData.tanggal_p_konf;
						}else if(value=='F'){
							return "Sudah Konfirmasi <br/>"+rowData.tanggal_f_konf;
						}else{
							return "-";
						}
					},
					styler:function(value,rowData,rowIndex){
						if(value=='P'){return 'background-color:red;color:#FFFFFF';}
						else if(value=='F'){return 'background-color:#edf1f4;color:navy';}
						else return 'background-color:red;color:#FFFFFF';
					}
				},
				{field:'produksi',title:'Stat. Produksi',width:150, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						if(value=='P'){
							return "Proses Produksi <br/>"+rowData.tanggal_p_prod;
						}else if(value=='F'){
							return "Produksi Selesai <br/>"+rowData.tanggal_f_prod;
						}else{
							return "-";
						}
					},
					styler:function(value,rowData,rowIndex){
						if(value=='P'){return 'background-color:red;color:#FFFFFF';}
						else if(value=='F'){return 'background-color:#edf1f4;color:navy';}
						else return 'background-color:red;color:#FFFFFF';
					}
				},
				{field:'packing',title:'Stat. Packing',width:150, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						if(value=='P'){
							return "Proses Packing <br/>"+rowData.tanggal_p_pack;
						}else if(value=='F'){
							return "Packing Selesai <br/>"+rowData.tanggal_f_pack;
						}else{
							return "-";
						}
					},
					styler:function(value,rowData,rowIndex){
						if(value=='P'){return 'background-color:red;color:#FFFFFF';}
						else if(value=='F'){return 'background-color:#edf1f4;color:navy';}
						else return 'background-color:red;color:#FFFFFF';
					}
				},
				{field:'kirim',title:'Stat. Pengiriman',width:150, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						if(value=='P'){
							return "Proses Pengiriman";
						}else if(value=='F'){
							return "Sudah Dikirim Dengan No Resi : "+rowData.no_resi+" <br/>"+rowData.tanggal_kirim;
						}else{
							return "-";
						}
					},
					styler:function(value,rowData,rowIndex){
						if(value=='P'){return 'background-color:red;color:#FFFFFF';}
						else if(value=='F'){return 'background-color:#edf1f4;color:navy';}
						else return 'background-color:red;color:#FFFFFF';
					}
				},
				{field:'tanda_terima',title:'Stat. Tnd.Terima',width:150, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						if(value=='P'){
							return "Menunggu Tanda Terima";
						}else if(value=='F'){
							return "Sudah Diterima <br/>"+rowData.tanggal_tandaterima;
						}else{
							return "-";
						}
					},
					styler:function(value,rowData,rowIndex){
						if(value=='P'){return 'background-color:red;color:#FFFFFF';}
						else if(value=='F'){return 'background-color:#edf1f4;color:navy';}
						else return 'background-color:red;color:#FFFFFF';
					}
				},
				{field:'bast',title:'Stat. BAST',width:150, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						if(value=='P'){
							return "Menunggu BAST";
						}else if(value=='F'){
							return "Sudah BAST <br/>"+rowData.tanggal_bast;;
						}else{
							return "-";
						}
					},
					styler:function(value,rowData,rowIndex){
						if(value=='P'){return 'background-color:red;color:#FFFFFF';}
						else if(value=='F'){return 'background-color:#edf1f4;color:navy';}
						else return 'background-color:red;color:#FFFFFF';
					}
				}
			];
		break;
		case "member_sekolah":
		case "member_umum":
			judulnya = "Daftar Member Sekolah";
			urlnya = (modnya=='member_umum' ? "tbl_registrasi_umum" : "tbl_registrasi");
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			if(modnya=='member_sekolah'){
				frozen[modnya] = [	
					{field:'nama_sekolah',title:'Nama Sekolah',width:150, halign:'center',align:'left'},
					{field:'npsn',title:'NPSN',width:80, halign:'center',align:'center'},															
				];
				kolom[modnya] = [	
					{field:'email',title:'Email Dapodik',width:150, halign:'center',align:'left'},					
					{field:'prov_kota',title:'Prov.KabKota',width:300, halign:'center',align:'left'},
					{field:'alamat_pengiriman',title:'Alamat',width:200, halign:'center',align:'left'},
					{field:'kode_pos',title:'Kode Pos',width:100, halign:'center',align:'left'},
					{field:'no_telp_sekolah',title:'No. Telp Sekolah',width:150, halign:'center',align:'left'},
					{field:'nama_kepala_sekolah',title:'Nama Kepala Sekolah',width:200, halign:'center',align:'left'},
					{field:'no_hp_kepsek',title:'No. HP Kepala Sekolah',width:150, halign:'center',align:'left'},
					{field:'email_kepsek',title:'Email Kepala Sekolah',width:200, halign:'center',align:'left'},
					{field:'nama_bendahara',title:'Nama Bendahara',width:200, halign:'center',align:'left'},
					{field:'no_hp_bendahara',title:'No. HP Bendahara',width:150, halign:'center',align:'left'},
					{field:'registrasi_date',title:'Reg. Date',width:150, halign:'center',align:'center'},					
				];
			}else{
				frozen[modnya] = [	
					{field:'nama_lengkap',title:'Nama Lengkap',width:130, halign:'center',align:'left'},
					{field:'email',title:'Email',width:130, halign:'center',align:'left'},
					{field:'reg_date',title:'Reg. Date',width:130, halign:'center',align:'center'},
					
				];
				kolom[modnya] = [	
					{field:'prov_kota',title:'Prov.KabKota',width:300, halign:'center',align:'left'},
					{field:'alamat_pengiriman',title:'Alamat',width:300, halign:'center',align:'left'},
					{field:'kode_pos',title:'Kode Pos',width:55, halign:'center',align:'left'},
					{field:'no_hp_customer',title:'No. HP',width:100, halign:'center',align:'left'},
					{field:'no_telp_customer',title:'No Telp.',width:120, halign:'center',align:'left'},
					
				];

			}
		break;
		case "gudang_kirim":
		case "gudang_konfirmasi":
			judulnya = "Manajemen Order Pelanggan ";
			urlnya = "tbl_gudang";
			fitnya = true;
			nowrap=false;
			//footer=true;
			//param['flag']=(modnya=='gudang_konfirmasi' ? 'P' : 'PK');
			row_number=true;
			frozen[modnya] = [	
				{field:'id_pemesanan',title:'Detail',width:80, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						var modul="invoice";
						return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modnya+"\",\""+value+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/activity.png'></a>";
					}
				},
				{field:'id',title:'Status',width:80, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						var modul="cetak_bast";
						//return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modul+"\",\""+value+"\")'><img src='"+host+"__assets/easyui/themes/icons/print.png'></a>";
						if(rowData.flag=='P'){
							return "Proses";
						}else if(rowData.flag=='PK'){
							return "Proses Packing";
						}else{
							return "Selesai";
						}
					},
					styler:function(value,rowData,rowIndex){
						if(rowData.flag=='P'){return 'background:green;color:#ffffff;'}
						else if(rowData.flag=='PK'){return 'background:yellow;color:navy;'}
					}
					
				},
				
				{field:'flag',title:'Packing',width:80, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						var modul="set_packing";
						if(value=='P'){
							return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modul+"\",\""+rowData.id+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/bank.png'></a>";
						}else{
							return "<img src='"+host+"__assets/eb/easyui/themes/icons/ok.png'>";
						}
					}
				},
				{field:'create_by',title:'Kirim',width:80, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						var modul="set_kirim";
						if(rowData.flag=='PK'){
							return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modul+"\",\""+rowData.id+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/send-icon.png'></a>";
						}else if(rowData.flag=='P'){
							return "<img src='"+host+"__assets/eb/easyui/themes/icons/edit_remove.png'>";
						}else{
							return "<img src='"+host+"__assets/eb/easyui/themes/icons/ok.png'>";
						}
					}
				},
				{field:'no_gudang',title:'No Gudang - TGL',width:150, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						return "NO : "+value+" <br>Tgl : "+rowData.tgl_masuk
					}
				}
				
			];
			kolom[modnya] = [	
				{field:'no_konfirmasi',title:'No Konfirmasi - TGL',width:150, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						return "NO : "+value+" <br>Tgl : "+rowData.tgl_konfirmasi
					}
				},
				{field:'no_order',title:'No Order - TGL ',width:150, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						return "NO : "+value+" <br>Tgl : "+rowData.tgl_order
					}
				},
				{field:'zona',title:'Zona',width:40, halign:'center',align:'center'},
				{field:'alamat_pengiriman',title:'Alamat Pengiriman',width:350, halign:'center',align:'left'},
				{field:'jasa_pengiriman',title:'Jasa Kirim',width:100, halign:'center',align:'left'},
				{field:'nama_lengkap',title:'PIC',width:200, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						if(rowData.jenis_pembeli=='SEKOLAH')
							return rowData.nama_kepala_sekolah;
						else 
							return value
					}
				},
				
				
				//{field:'nama_sekolah',title:'Nama Sekolah',width:200, halign:'center',align:'left'},
				{field:'total_pembayaran',title:'Total',width:150, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
			];
		break;
		
		case "konfirmasi_sekolah":
		case "konfirmasi_umum":
			judulnya = (modnya=='konfirmasi_sekolah' ? "Daftar Konfirmasi Order Pelanggan Sekolah" : "Daftar Konfirmasi Order Pelanggan Umum");
			urlnya = (modnya=='konfirmasi_sekolah' ? "konfirmasi_sekolah" : "konfirmasi_umum");
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			frozen[modnya] = [	
				{field:'id_pemesanan',title:'Detail',width:120, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						//var modul="invoice";
						return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modnya+"\",\""+value+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/Windows-View-Detail-icon.png'></a>";
					}
				},
				
				/*{field:'flag',title:'Kirim Gudang',width:80, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						var modul="kirim_gudang";
						if(value=='P'){
							return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modul+"\",\""+rowData.id+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/Mail-send-receive-icon.png'></a>";
						}else if(value=='B'){
							return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modul+"\",\""+rowData.id+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/Mail-send-receive-icon.png'></a>";
						}else if(value=='C'){
							return "DiCancel";
						}else{
							return "<img src='"+host+"__assets/eb/easyui/themes/icons/ok.png'>";
						}
					}
				},*/
				{field:'create_by',title:'Cancel Pemesanan',width:120, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						var modul="cancel_pesanan";
						if(rowData.flag=='P'){
							return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modul+"\",\""+rowData.id+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/no.png'></a>";
						}else if(rowData.flag=='B'){
							return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modul+"\",\""+rowData.id+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/no.png'></a>";
						}else if(rowData.flag=='F'){
							return "Sudah Terkonfirmasi";
						}else{
							return "<img src='"+host+"__assets/easyui/themes/icons/ok.png'>";
						}
					}
				},
				
				
			];
			kolom[modnya] = [	
				{field:'zona',title:'Zona',width:80, halign:'center',align:'center'},
				{field:(modnya=='konfirmasi_sekolah' ? "nama_sekolah" : "nama_lengkap"),title:(modnya=='konfirmasi_sekolah'  ? 'Nama Sekolah' : "Nama Pelanggan"),width:200, halign:'center',align:'left'},								
				{field:'email',title:'Email',width:200, halign:'center',align:'left'},
				{field:(modnya=='konfirmasi_sekolah' ? "no_telp_sekolah" : "no_hp_customer"),title:(modnya=='konfirmasi_sekolah'  ? 'Telp. Sekolah' : "No. Kontak"),width:200, halign:'center',align:'left'},
				
				{field:'no_konfirmasi',title:'No Konfirmasi - TGL',width:200, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						return "NO : "+value+" <br>Tgl : "+rowData.tanggal_konfirmasi
					}
				},
				/*{field:'file_bukti_bayar',title:'Bukti Bayar',width:150, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						if(value == null){
							return "Belum Bayar";
						}else{
							return "<a href='"+host+"__repository/konfirmasi/"+value+"' target='_blank'>Lihat File</a>"	
						}
					}
				},*/
				{field:'no_order',title:'No Order - TGL ',width:200, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						return "NO : "+value+" <br>Tgl : "+rowData.tanggal_order
					}
				},
				{field:'konfirmasi',title:'Remark',width:200, halign:'center',align:'left'},
				{field:'nama_bank_pengirim',title:'Bank Pengirim',width:200, halign:'center',align:'left'},
				{field:'nama_bank_penerima',title:'Bank Penerima',width:200, halign:'center',align:'left'},
				{field:'total_pembayaran',title:'Total',width:150, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return "Rp. "+NumberFormat(value);
					}
				},
			];
		break;
		/*case "konfirmasi_sekolah":
			judulnya = "Daftar Invoice Order Pelanggan Sekolah";
			urlnya = "tbl_h_pemesanan";
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			frozen[modnya] = [	
				{field:'id',title:'Lihat Detil & Konfirm',width:120, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modnya+"\",\""+value+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/log.gif'></a>";
					}
				},
				{field:'status',title:'Status',width:120, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						if(value=='P' || value=='T'){
							return 'Proses Pembayaran';
						}else if(value=='C'){
							return 'DiCancel';
						}else if(value=='B'){
							return 'Proses';
						}else{
							return 'Sudah Bayar';
						} 
					},
					styler:function(value,rowData,rowIndex){
						if(value=='P' || value=='T'){
							return 'background:yellow;color:navy;'
						}else if(value=='B'){
							return 'background:yellow;color:yellow;color:navy;';
						}else if(value=='C'){
							return 'background:red;color:#ffffff;';
						}else {
							return 'background:green;color:#ffffff;'
						}
						
					}
				},
				{field:'no_order',title:'No Order/Invoice',width:130, halign:'center',align:'center'},
				{field:'tgl_order',title:'Tgl. Order',width:150, halign:'center',align:'center'},
				{field:'zona',title:'Zona',width:80, halign:'center',align:'center'},
				
			];
			kolom[modnya] = [	
				{field:(modnya=='invoice' ? "nama_sekolah" : "nama_lengkap"),title:(modnya=='invoice' ? 'Nama Sekolah' : "Nama Lengkap"),width:200, halign:'center',align:'left'},
				{field:'sub_total',title:'Sub. Total',width:150, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
				{field:'pajak',title:'Pajak',width:150, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
				{field:'grand_total',title:'Grand Total',width:150, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
				
				
				
				
			];
		break;
		*/
		case "invoice":
		case "invoice_umum":
			judulnya = (modnya=='invoice' ? "Daftar Invoice Order Pelanggan Sekolah" : "Daftar Invoice Order Pelanggan Umum");
			urlnya = (modnya=='invoice' ? "tbl_h_pemesanan" : "tbl_h_pemesanan_umum");
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			frozen[modnya] = [	
				{field:'id',title:'Detail',width:80, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_detil(\""+modnya+"\",\""+value+"\")'><img src='"+host+"__assets/eb/easyui/themes/icons/log.gif'></a>";
					}
				},
				{field:'status',title:'Status',width:120, halign:'center',align:'left',
					formatter:function(value,rowData,rowIndex){
						if(value=='P' || value=='T'){
							return 'Proses Pembayaran';
						}else if(value=='C'){
							return 'DiCancel';
						}else if(value=='B'){
							return 'Proses';
						}else{
							return 'Sudah Bayar';
						} 
					},
					styler:function(value,rowData,rowIndex){
						if(value=='P' || value=='T'){
							return 'background:yellow;color:navy;'
						}else if(value=='B'){
							return 'background:yellow;color:yellow;color:navy;';
						}else if(value=='C'){
							return 'background:red;color:#ffffff;';
						}else {
							return 'background:green;color:#ffffff;'
						}
						
					}
				},
				
			];
			kolom[modnya] = [	
				{field:'no_order',title:'No Order/Invoice',width:130, halign:'center',align:'center'},
				{field:'tanggal_order',title:'Tgl. Order',width:150, halign:'center',align:'center'},
				{field:'zona',title:'Zona',width:80, halign:'center',align:'center'},			
				{field:(modnya=='invoice' ? "nama_sekolah" : "nama_lengkap"),title:(modnya=='invoice' ? 'Nama Sekolah' : "Nama Lengkap"),width:200, halign:'center',align:'left'},
				{field:'email',title:'Email',width:200, halign:'center',align:'left'},
				{field:(modnya=='invoice' ? "no_telp_sekolah" : "no_hp_customer"),title:(modnya=='invoice'  ? 'Telp. Sekolah' : "No. Kontak"),width:200, halign:'center',align:'left'},
				
				/*
				{field:'sub_total',title:'Sub. Total',width:150, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
				{field:'pajak',title:'Pajak',width:150, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
				*/
				{field:'grand_total',title:'Grand Total',width:150, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
				
				
				
				
			];
		break;
		case "produk":
			judulnya = "Daftar Buku MKS - STORE ";
			urlnya = "tbl_buku";
			fitnya = true;
			nowrap=false;
			//footer=true;
			pagesizeboy=20;
			row_number=true;
			frozen[modnya] = [	
				{field:'kelas',title:'Kelas',width:70, halign:'center',align:'left'},
				{field:'nama_group',title:'Jenis Buku',width:80, halign:'center',align:'left'},
				{field:'nama_kategori',title:'Kategori',width:70, halign:'center',align:'left'},
				{field:'judul_buku',title:'Judul Buku',width:400, halign:'center',align:'left'}
			];
			kolom[modnya] = [	
			//	{field:'deskripsi_buku',title:'Desc. Buku',width:245, halign:'center',align:'left'},
				{field:'jml_hal',title:'Jml. Hal',width:50, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
				{field:'ukuran_buku',title:'Ukuran',width:125, halign:'center',align:'center'},
				{field:'harga_zona_1',title:'Hrg. Zona 1',width:85, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
				{field:'harga_zona_2',title:'Hrg. Zona 2',width:85, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
				{field:'harga_zona_3',title:'Hrg. Zona 3',width:85, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
				{field:'harga_zona_4',title:'Hrg. Zona 4',width:85, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				},
				{field:'harga_zona_5',title:'Hrg. Zona 5',width:85, halign:'center',align:'right',
					formatter:function(value,rowData,rowIndex){
						return NumberFormat(value);
					}
				}
				
				
			];
		break;
		
		case "tingkatan":
			judulnya = "Daftar Tingkatan Sekolah ";
			urlnya = "cl_tingkatan";
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			
			kolom[modnya] = [	
				{field:'tingkatan',title:'Tingkatan',width:300, halign:'center',align:'left'},
				{field:'deskripsi',title:'Deskripsi Tingkatan',width:400, halign:'center',align:'left'}
			];
		break;
		case "kelas":
			judulnya = "Daftar Kelas ";
			urlnya = "cl_kelas";
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			
			kolom[modnya] = [	
				{field:'tingkatan',title:'Tingkatan',width:300, halign:'center',align:'left'},
				{field:'kelas',title:'Kelas',width:400, halign:'center',align:'left'},
				{field:'create_date',title:'DiBuat Oleh',width:150, halign:'center',align:'center'},
				{field:'create_by',title:'Dibuat Tgl',width:100, halign:'center',align:'left'}
			];
		break;
		case "edisi":
			judulnya = "Daftar Edisi Pelajaran ";
			urlnya = "cl_edisi";
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			
			kolom[modnya] = [	
				{field:'nama_edisi',title:'Edisi',width:400, halign:'center',align:'left'},
				{field:'create_date',title:'DiBuat Oleh',width:150, halign:'center',align:'center'},
				{field:'create_by',title:'Dibuat Tgl',width:100, halign:'center',align:'left'}
			];
		break;
		case "group_sekolah":
			judulnya = "Daftar Group Sekolah ";
			urlnya = "cl_group_sekolah";
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			
			kolom[modnya] = [	
				{field:'nama_group',title:'Nama Group',width:300, halign:'center',align:'left'},
				{field:'create_date',title:'DiBuat Oleh',width:150, halign:'center',align:'center'},
				{field:'create_by',title:'Dibuat Tgl',width:100, halign:'center',align:'left'}
			];
		break;
		case "kategori":
			judulnya = "Daftar Kategori Produk ";
			urlnya = "cl_kategori";
			fitnya = true;
			nowrap=false;
			//footer=true;
			row_number=true;
			
			kolom[modnya] = [	
				{field:'nama_kategori',title:'Nama Group',width:200, halign:'center',align:'left'},
				{field:'deskripsi',title:'Deskripsi Kategori',width:300, halign:'center',align:'left'},
				{field:'user_create',title:'DiBuat Oleh',width:150, halign:'center',align:'left'},
				{field:'tgl_buat',title:'Dibuat Tgl',width:100, halign:'center',align:'center'},
				{field:'user_update',title:'DiUbah Oleh',width:150, halign:'center',align:'left'},
				{field:'tgl_update',title:'DiUbah Tgl',width:100, halign:'center',align:'center'}
			];
		break;
	}
	
	grid_nya=$("#"+divnya).datagrid({
		title:'',
        height:tingginya,
        width:lebarnya,
		rownumbers:row_number,
        fit:fitnya,
        striped:true,
        pagination:true,
        remoteSort: false,
		showFooter:footer,
		singleSelect:singleSelek,
        url: (urlglobal == "" ? host+"backend/getdata/"+urlnya : urlglobal),		
		nowrap: nowrap,
		pageSize:pagesizeboy,
		pageList:[10,20,30,40,50,75,100,200],
		queryParams:param,
		frozenColumns:[
            frozen[modnya]
        ],
		columns:[
            kolom[modnya]
        ],
		onLoadSuccess:function(d){
			
		},
		rowStyler: function(index,row){
			if(modnya=='konfirmasi'){
				if(row.flag=='P'){return 'background-color:#8EC354;color:#navy;'}
			}else if(modnya=='produk'){
				if(row.flag_disable_enable == 0){
					return 'background-color:#FFD1BB;color:#navy;'
				}
			}else if(modnya=='paket'){
				if(row.flag_disable_enable == 0){
					return 'background-color:#FFD1BB;color:#navy;'
				}
			}
		},
		onClickRow:function(rowIndex,rowData){
		  if(klik==true){
			  if(modnya=='list_act'){
				  $('#id').val(rowData.id);
				  $('#activity_code').val(rowData.activity_code);
				  $('#descript').val(rowData.descript);
				  $('#editstatus').val('edit');
			  }
		  }
        },
		onDblClickRow:function(rowIndex,rowData){
			if(doble_klik==true){
				switch(modnya){
					case "list_act":
						post_detil['id']=rowData.id;
						$('#daftar_grid').hide();
						$('#detil_grid').show();
						$('#detil_grid').addClass('loading').html('');
						$.post(url_detil,post_detil,function(r){
							$('#detil_grid').removeClass('loading').html(r);
						});
					break;
					case "employees":
						post_detil['id'] = rowData.id;
						$('#grid_nya_'+modnya).addClass('loading').html('');
						$.post(url_detil,post_detil,function(r){
							$('#grid_nya_'+modnya).removeClass('loading').html(r);
						});
					break;
					
				}
			}
		},
		toolbar: '#tb_'+modnya,
	});
}


function genform(type, modulnya, submodulnya, stswindow, tabel){
	var urlpost = host+'backoffice-form/'+modulnya;
	var urldelete = host+'backoffice-delete';
	var id_tambahan = "";
	
	switch(modulnya){
		case "201":
			var lebar = getClientWidth()-990;
			var tinggi = getClientHeight()-535;
			var judulwindow = 'Form Data Provinsi';
			var table="cl_provinsi";
		break;
				
		//End tabs Location - Cost Object
	}
	
	switch(type){
		
		case "add":
			if(stswindow == undefined){
				$('#grid_nya_'+modulnya).hide();
				$('#detil_nya_'+modulnya).html('').show().addClass("loading");
			}
			$.post(urlpost, {'editstatus':'add', 'id_tambahan':id_tambahan }, function(resp){
				if(stswindow == 'windowform'){
					windowForm(resp, judulwindow, lebar, tinggi);
				}else if(stswindow == 'windowpanel'){
					windowFormPanel(resp, judulwindow, lebar, tinggi);
				}else{
					$('#detil_nya_'+modulnya).show();
					$('#detil_nya_'+modulnya).html(resp).removeClass("loading");
				}
			});
		break;
		case "edit":
		case "delete":
		case "disable_enable":
		case "mapping":
		case "updateprofile":
			var row = $("#grid_"+modulnya).datagrid('getSelected');
			if(row){
				if(type=='edit'){
					if(stswindow == undefined){
						$('#grid_nya_'+modulnya).hide();
						$('#detil_nya_'+modulnya).html('').show().addClass("loading");	
					}
					$.post(urlpost, { 'editstatus':'edit', id:row.id, 'tabel':table, 'modul':modulnya, 'bulan':row.bulan, 'tahun':row.tahun, 'id_tambahan':id_tambahan }, function(resp){
						if(stswindow == 'windowform'){
							windowForm(resp, judulwindow, lebar, tinggi);
						}else if(stswindow == 'windowpanel'){
							windowFormPanel(resp, judulwindow, lebar, tinggi);
						}else{
							$('#detil_nya_'+modulnya).show();
							$('#detil_nya_'+modulnya).html(resp).removeClass("loading");
						}
					});
				}else if(type=='delete'){
					if(confirm("Do You Want To Delete This Data ?")){
						loadingna();
						$.post(urldelete, {id:row.id,'editstatus':'delete',mod:tabel}, function(r){
							if(r==1){
								winLoadingClose();
								$.messager.alert('MKS - STORE',"Data Sudah Terhapus",'info');
								$('#grid_'+modulnya).datagrid('reload');
									
							}else{
								winLoadingClose();
								console.log(r)
								$.messager.alert('MKS - STORE',"Gagal",'error');
							}
						});	
					}
				}else if(type=='disable_enable'){
					$.post(host+'onoff-produk', { id:row.id, 'flag_disable_enable':row.flag_disable_enable, 'editstatus':'disable_enable', mod:tabel }, function(resp){
						var jsrw = JSON.parse(resp);
						if(jsrw.msg == 1){
							winLoadingClose();
							if(row.flag_disable_enable == 0){
								$.messager.alert('MKS - STORE',"Data Kembali Ada Katalog Webstore",'info');
							}else{
								$.messager.alert('MKS - STORE',"Data Sudah Hilang Dari Katalog Webstore",'info');
							}
							$('#grid_'+modulnya).datagrid('reload');
								
						}else{
							winLoadingClose();
							console.log(resp)
							$.messager.alert('MKS - STORE',"Gagal",'error');
						}
					});
				}else if(type=='mapping'){
					$('#grid_nya_'+modulnya).hide();
					$('#detil_nya_'+modulnya).html('').show().addClass("loading");	
					
					$.post(host+'backoffice-form/mapping_paket', { 'editstatus':'mapping', id:row.id, nama_paket:row.nama_paket, 'tabel':table }, function(resp){
						$('#detil_nya_'+modulnya).show();
						$('#detil_nya_'+modulnya).html(resp).removeClass("loading");
					});
				}else if(type=='updateprofile'){
					$('#grid_nya_'+modulnya).hide();
					$('#detil_nya_'+modulnya).html('').show().addClass("loading");	
					
					$.post(host+'backoffice-form/update_profil_sekolah', { 'editstatus':'edit', id:row.id, 'tabel':table }, function(resp){
						$('#detil_nya_'+modulnya).show();
						$('#detil_nya_'+modulnya).html(resp).removeClass("loading");
					});				
				}		
			}else{
				$.messager.alert('MKS - STORE',"Pilih Data Dalam List",'error');
			}
		break;
	}
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
function genTab(div,mod,sub_mod,tab_array,div_panel,judul_panel,mod_num, height_panel, height_tab,width_panel,width_tab){
	var id_sub_mod=sub_mod.split("_");
	if(typeof(div_panel)!= "undefined" || div_panel!=""){
	$(div_panel).panel({
		width:(typeof(width_panel) == "undefined" ? getClientWidth()-268 : width_panel),
		height:(typeof(height_panel) == "undefined" ? getClientHeight()-100 : height_panel),
		title:judul_panel,
		//fit:true,
		tools:[{
				iconCls:'icon-cancel',
				handler:function(){
					$('#grid_nya_'+id_sub_mod[1]).show();
					$('#detil_nya_'+id_sub_mod[1]).hide();
					$('#grid_'+id_sub_mod[1]).datagrid('reload');
				}
		}]
	}); 
	}
	$(div).tabs({
		title:'AA',
		//height: getClientHeight()-190,
		height: (typeof(height_tab) == "undefined" ? getClientHeight()-190 : height_tab),
		width: (typeof(width_tab) == "undefined" ? getClientWidth()-280 : width_tab),
		plain: false,
		//fit:true,
		selected:0
	});
	
	if(tab_array.length > 0){
		for(var x in tab_array){
			var isi_tab=tab_array[x].replace(/ /g,"_");
			$(div).tabs('add',{
					title:tab_array[x],
					iconCls:'database',
					content:'<div style="padding: 5px;"><div id="'+isi_tab.toLowerCase()+'" style="height: 200px;">'+isi_tab.toLowerCase()+'</div></div>'
			});
		}
		$(div).tabs({
			onSelect: function(title){
				var isi_tab=title.replace(/ /g,"_");
				var par={};
				$('#'+isi_tab.toLowerCase()).html('').addClass('loading');
				urlnya = host+'home/modul/'+mod+'/'+isi_tab.toLowerCase();
				$(div_panel).panel({title:title});
				
				switch(mod){
					case "activity_master":
						par['par_1']=$('#par_1').val();
						par['par_2']=$('#par_2').val();
						par['par_3']=$('#par_3').val();
					break;
					case "model":
						par['par_1']=$('#id_activity').val();
					break;
					case "activity":
						if(typeof(id_act) != "undefined")par['id_act']=id_act;
						par['bulan']=$('#bulan_main').val();
						par['tahun']=$('#tahun_main').val();
					break;
					case "process_master":
						par['par_1']=$('#par_1').val();
						par['par_2']=$('#par_2').val();
					break;
					
					case "resource":
						urlnya = host+'homex/modul/'+mod+'/'+isi_tab.toLowerCase();
					break;
					case "cost_object":
						urlnya = host+'homex/modul/'+mod+'/'+isi_tab.toLowerCase();
					break;
					case "report":
						switch(isi_tab.toLowerCase()){
							case "activity_master":
							case "activity_driver":
							case "activity_by_segment":
							case "activity_resource":
								main_report=isi_tab.toLowerCase();
								par['bulan']=$('#bulan_activity').val();
								par['tahun']=$('#tahun_activity').val();
								urlnya = host+'home/modul/'+mod+'/'+isi_tab.toLowerCase();
							break;
							case "resource_employee":
							case "resource_expense":
							case "resource_assets":
								main_report=isi_tab.toLowerCase();
								urlnya = host+'homex/modul/'+mod+'/'+isi_tab.toLowerCase();
							break;
							case "costobject_cost":
							case "costobject_customer":
							case "costobject_location":
								main_report=isi_tab.toLowerCase();
								urlnya = host+'homex/modul/'+mod+'/'+isi_tab.toLowerCase();
							break;
							default:
								urlnya = host+'homex/modul/'+mod+'/'+isi_tab.toLowerCase();
							break;
						}
					break;
					case "parameter":
						urlnya = host+'homex/modul/'+mod+'/'+isi_tab.toLowerCase();
					break;
					//default:urlnya = host+'home/modul/'+mod+'/'+isi_tab.toLowerCase();
				}
				console.log(par);
					$.post(urlnya,par,function(r){
						//$('#'+isi_tab).html(isi_tab+' -> '+title);
						$('#'+isi_tab.toLowerCase()).removeClass('loading').html(r);
						//$('#'+isi_tab.toLowerCase()).html(r);
					});
				//console.log(title);
			}
		});
		var tab = $(div).tabs('select',0);
		
	}
	
}

function cariData(acak){
	var post_search = {};
	post_search['kat'] = $('#kat_'+acak).val();
	post_search['key'] = $('#key_'+acak).val();
	if($('#kat_'+acak).val()!=''){
		grid_nya.datagrid('reload',post_search);
	}else{
		$.messager.alert('MKS - STORE Back-Office',"Pilih Kategori Pencarian",'error');
	}
	//$('#grid_'+typecari).datagrid('reload', post_search);
}

function fillCombo(url, SelID, value, value2, value3, value4){
	//if(Ext.get(SelID).innerHTML == "") return false;
	if (value == undefined) value = "";
	if (value2 == undefined) value2 = "";
	if (value3 == undefined) value3 = "";
	if (value4 == undefined) value4 = "";
	
	$('#'+SelID).empty();
	$.post(url, {"v": value, "v2": value2, "v3": value3, "v4": value4},function(data){
		$('#'+SelID).append(data);
	});

}
function formatDate(date) {
	var bulan=date.getMonth() +1;
	var tgl=date.getDate();
	if(bulan < 10){
		bulan='0'+bulan;
	}
	
	if(tgl < 10){
		tgl='0'+tgl;
	}
	return date.getFullYear() + "-" + bulan + "-" + tgl;
}

function kumpulAction(type, p1, p2, p3){
	var post_detil = {};
	
	switch(type){
		case 'changemodul':
			var param = $('#modul_reference').val();
			if(param == ""){
				$('#template').html("");
			}else{
				var textnya = $('#modul_reference option:selected').text();
				var htmlnya = "<a style='text-decoration:none;' href='"+host+"homex/download/"+param+"' target='_blank' >Template "+textnya+"</a>";
				$('#template').html(htmlnya);
			}
		break;
		case "userrole":
			$.post(host+'homex/modul/setting/form_user_role', {'id':p1, 'editstatus':'add'}, function(resp){
				var lebar = getClientWidth()-500;
				var tinggi = getClientHeight()-200;
				windowForm(resp, "User Group Role Privilleges", lebar, tinggi);
			});
		break;
		case "form_data_production":
			
			var row = $("#master_prm").datagrid('getSelected');
			if(row){
				$('#'+p1).html('');
				$.post(host+'homex/modul/production/form_data_production', { 'bulan':p2 , 'tahun':p3 ,'deskripsi':row.descript, 'prod_id':row.prod_id, 'tbl_prm_id':row.id },function(resp){
					$('#'+p1).html(resp);
				});
			}else{
				$.messager.alert('ABC System',"Select Row In Grid",'error');
			}
		break;
		case 'removeproduction':
			var row = $("#tabel_prd").edatagrid('getSelected');
			if(row){
				$.post(host+'homex/simpansavedata/tbl_prd/', { 'editstatus':'delete', 'id':row.id },function(resp){
					$("#tabel_prd").edatagrid('reload');
					$("#master_cdm").datagrid('reload');
				});
			}else{
				$.messager.alert('ABC System',"Select Row In Grid Data Production",'error');
			}
		break;
		
		//Assignment Data : Resource
		case "list_activity_employee":
		case "list_expense_employee":		
			var row = $("#grid_"+type).edatagrid('getSelections');
			if(row){
				var assignment = $('#jenis_assignment').val();
				post_detil['tbl_emp_id'] = $('#hdn_'+type).val();
				post_detil['editstatus'] = 'kontel';
				post_detil['datanya'] = row;
				post_detil['id'] = 'kontel';
				
				if(assignment == 'list_activity_employee' || assignment == 'list_expense_employee'){
					post_detil['bulan']	= $('#bulan_employee').val();
					post_detil['tahun']	= $('#tahun_employee').val();
				}
				
				$.post(host+'homex/simpansavedata/'+type, post_detil, function(r){
					if(r == 1){
						$.messager.alert('ABC System',"Data Saved",'info');
					}else{
						$.messager.alert('ABC System', "Failed Saved -"+r, 'error');
					}
					closeWindow();
					
					if(type == 'list_activity_employee'){
						$('#grid_assign_act_employee').datagrid('reload');
					}else if(type == 'list_expense_employee'){
						$('#grid_expense_source_employee').datagrid('reload');
					}
				});
				
			}else{
				$.messager.alert('ABC System',"Select Row In Grid Data",'error');
			}
		break;
		
		case 'list_activity_expense':
		case 'list_employee_expense':
		case 'list_assets_expense':
			var row = $("#grid_"+type).edatagrid('getSelections');
			if(row){
				var assignment = $('#jenis_assignment').val();
				post_detil['tbl_exp_id'] = $('#hdn_'+type).val();
				post_detil['editstatus'] = 'kontel';
				post_detil['datanya'] = row;
				post_detil['id'] = 'kontel';
				post_detil['bulan']	= $('#bulan_expense').val();
				post_detil['tahun']	= $('#tahun_expense').val();
				
				
				$.post(host+'homex/simpansavedata/'+type, post_detil, function(r){
					if(r == 1){
						$.messager.alert('ABC System',"Data Saved",'info');
					}else{
						$.messager.alert('ABC System', "Failed Saved -"+r, 'error');
					}
					closeWindow();
					
					if(type == 'list_activity_expense'){
						$('#grid_assign_act_expense').datagrid('reload');
					}else if(type == 'list_employee_expense'){
						$('#grid_assign_emp_expense').datagrid('reload');
					}else if(type == 'list_assets_expense'){
						$('#grid_assign_assets_expense').datagrid('reload');
					}
				});
				
			}else{
				$.messager.alert('ABC System',"Select Row In Grid Data",'error');
			}
		break;
		
		case 'list_activity_assets':
		case 'list_expense_assets':
			var row = $("#grid_"+type).edatagrid('getSelections');
			if(row){
				var assignment = $('#jenis_assignment').val();
				post_detil['tbl_assets_id'] = $('#hdn_'+type).val();
				post_detil['editstatus'] = 'kontel';
				post_detil['datanya'] = row;
				post_detil['id'] = 'kontel';
				post_detil['bulan']	= $('#bulan_assets').val();
				post_detil['tahun']	= $('#tahun_assets').val();
				
				$.post(host+'homex/simpansavedata/'+type, post_detil, function(r){
					if(r == 1){
						$.messager.alert('ABC System',"Data Saved",'info');
					}else{
						$.messager.alert('ABC System', "Failed Saved -"+r, 'error');
					}
					closeWindow();
					
					if(type == 'list_activity_assets'){
						$('#grid_assign_act_assets').datagrid('reload');
					}else if(type == 'list_expense_assets'){
						$('#grid_assign_exp_assets').datagrid('reload');
					}
				});
				
			}else{
				$.messager.alert('ABC System',"Select Row In Grid Data",'error');
			}
		break;
		//End Assignment
		
		// Modul Cost Object
		case 'list_activity_costobject':
		case 'list_customer_costobject':
		case 'list_location_costobject':
			var row = $("#grid_"+type).edatagrid('getSelections');
			if(row){
				var assignment = $('#jenis_assignment').val();
				post_detil['tbl_prm_id'] = $('#hdn_'+type).val();
				post_detil['editstatus'] = 'kontel';
				post_detil['datanya'] = row;
				post_detil['id'] = 'kontel';
				post_detil['bulan']	= $('#bulan_cost_object').val();
				post_detil['tahun']	= $('#tahun_cost_object').val();
				
				$.post(host+'homex/simpansavedata/'+type, post_detil, function(r){
					if(r == 1){
						$.messager.alert('ABC System',"Data Saved",'info');
					}else{
						$.messager.alert('ABC System', "Failed Saved -"+r, 'error');
					}
					closeWindow();
					
					if(type == 'list_activity_costobject'){
						$('#grid_assign_act_costobject').datagrid('reload');
					}else if(type == 'list_customer_costobject'){
						$('#grid_assign_cust_costobject').datagrid('reload');
					}else if(type == 'list_location_costobject'){
						$('#grid_assign_loc_costobject').datagrid('reload');
					}
				});
				
			}else{
				$.messager.alert('ABC System',"Select Row In Grid Data",'error');
			}
		break;
		
		case 'list_costobject_customer':
		case 'list_location_customer':
			var row = $("#grid_"+type).edatagrid('getSelections');
			if(row){
				var assignment = $('#jenis_assignment').val();
				post_detil['tbl_cust_id'] = $('#hdn_'+type).val();
				post_detil['editstatus'] = 'kontel';
				post_detil['datanya'] = row;
				post_detil['id'] = 'kontel';
				post_detil['bulan']	= $('#bulan_customer').val();
				post_detil['tahun']	= $('#tahun_customer').val();
				
				$.post(host+'homex/simpansavedata/'+type, post_detil, function(r){
					if(r == 1){
						$.messager.alert('ABC System',"Data Saved",'info');
					}else{
						$.messager.alert('ABC System', "Failed Saved -"+r, 'error');
					}
					closeWindow();
					
					if(type == 'list_costobject_customer'){
						$('#grid_assign_costobject_cust').datagrid('reload');
					}else if(type == 'list_location_customer'){
						$('#grid_assign_location_cust').datagrid('reload');
					}
				});
				
			}else{
				$.messager.alert('ABC System',"Select Row In Grid Data",'error');
			}
		break;
		
		case 'list_costobject_location':
		case 'list_customer_location':
			var row = $("#grid_"+type).edatagrid('getSelections');
			if(row){
				var assignment = $('#jenis_assignment').val();
				post_detil['tbl_location_id'] = $('#hdn_'+type).val();
				post_detil['editstatus'] = 'kontel';
				post_detil['datanya'] = row;
				post_detil['id'] = 'kontel';
				post_detil['bulan']	= $('#bulan_location').val();
				post_detil['tahun']	= $('#tahun_location').val();
				
				$.post(host+'homex/simpansavedata/'+type, post_detil, function(r){
					if(r == 1){
						$.messager.alert('ABC System',"Data Saved",'info');
					}else{
						$.messager.alert('ABC System', "Failed Saved -"+r, 'error');
					}
					closeWindow();
					
					if(type == 'list_costobject_location'){
						$('#grid_assign_costobject_location').datagrid('reload');
					}else if(type == 'list_customer_location'){
						$('#grid_assign_cust_location').datagrid('reload');
					}
				});
				
			}else{
				$.messager.alert('ABC System',"Select Row In Grid Data",'error');
			}
		break;
		
		// End Modul Cost Object
		
		//Modul Report
		case "profit":
			post_detil['bulan']	= $('#bulan_profit').val();
			post_detil['tahun']	= $('#tahun_profit').val();
			
			$('#main-report').html('').addClass("loading");
			$.post(host+'homex/modul/report/profit_detail', post_detil, function(r){
				$('#main-report').html(r).removeClass("loading");
			});
		break;
		//End Modul Report
	}
}		

function clear_form(id){
	$('#'+id).find("input[type=text], textarea,select").val("");
	//$('.angka').numberbox('setValue',0);
}

var divcontainerz;
function windowLoading(html,judul,width,height){
    divcontainerz = "win"+Math.floor(Math.random()*9999);
    $("<div id="+divcontainerz+"></div>").appendTo("body");
    divcontainerz = "#"+divcontainerz;
    $(divcontainerz).html(html);
    $(divcontainerz).css('padding','5px');
    $(divcontainerz).window({
       title:judul,
       width:width,
       height:height,
       autoOpen:false,
       modal:true,
       maximizable:false,
       resizable:false,
       minimizable:false,
       closable:false,
       collapsible:false,  
    });
    $(divcontainerz).window('open');        
}
function winLoadingClose(){
    $(divcontainerz).window('close');
    //$(divcontainer).html('');
}
function loadingna(){
	windowLoading("<img src='"+host+"__assets/images/loading.gif' style='position: fixed;top: 50%;left: 50%;margin-top: -10px;margin-left: -25px;'/>","Please Wait",200,100);
}

function transfer_data(from,to,grid_id_from,grid_id_to, grid_id_destination,flag_oke){
	//var row=$('#'+grid_id_from).datagrid('getSelected');
	console.log(grid_id_from);
	var row=$('#'+grid_id_from).datagrid('getSelections');
	var post={};
	var id_grid=[];
		
		if(row.length > 0){
			//console.log(row_emp.id);
			loadingna();
			for(x in row){
				id_grid.push(row[x].id);
			}
				switch(to){
					case "tbl_emp_act":
						post['editstatus']='add';
						post['tbl_emp_id']=id_grid;
					break;
					case "tbl_asset_are":
						post['editstatus']='add';
						post['tbl_acm_id']=id_act;
						post['bulan']=$('#bulan_main').val();
						post['tahun']=$('#tahun_main').val();
						post['tbl_assets_id']=id_grid;
					break;
					case "tbl_are_asset":
						to="tbl_are";
						post['editstatus']='delete';
						post['id']=id_grid;
					break;
					case "tbl_are":
						post['editstatus']='add';
						post['tbl_emp_id']=id_grid;
						post['tbl_acm_id']=id_act;
						post['bulan']=$('#bulan_main').val();
						post['tahun']=$('#tahun_main').val();
					break;
					case "tbl_are_exp":
						post['editstatus']='add';
						post['tbl_exp_id']=id_grid;
						post['tbl_acm_id']=id_act;
						post['bulan']=$('#bulan_main').val();
						post['tahun']=$('#tahun_main').val();
					break;
					case "tbl_act_to_act":
						post['editstatus']='add';
						post['tbl_acm_child_id']=id_grid;
						post['tbl_acm_id']=id_act;
						post['bulan']=$('#bulan_main').val();
						post['tahun']=$('#tahun_main').val();
					break;
					case "tbl_act_to_act3":
						post['editstatus']='add';
						post['tbl_acm_child_id']=id_act;
						post['tbl_acm_id']=id_grid;
						post['bulan']=$('#bulan_main').val();
						post['tahun']=$('#tahun_main').val();
					break;
					case "tbl_act_to_act2":
						to="tbl_act_to_act";
						post['editstatus']='delete';
						post['id']=id_grid;
					break;
					
					case "tbl_emp":
						to="tbl_emp_act";
						post['editstatus']='delete';
						post['id']=id_grid;
					break;
					case "tbl_are_emp":
						to="tbl_are";
						post['editstatus']='delete';
						post['id']=id_grid;
					break;	
					case "tbl_efx":
						post['editstatus']='add';
						post['tbl_exp_id']=row.id;
					break;
					case "tbl_exp":
						to="tbl_efx";
						post['editstatus']='delete';
						post['id']=row.id;
					break;
					case "tbl_acm":
						to="tbl_bpd";
						post['editstatus']='delete';
						post['id']=row.id;
					break;
					case "tbl_bpd":
						post['editstatus']='add';
						post['tbl_acm_id']=row.id;
					break;
					
					//Modul Data Production
					case "tbl_prd":
						post['editstatus']='add';
						post['id']="";
						post['tbl_cdm_id']=row.id;
						post['tbl_prm_id']=tbl_prm_id;
						post['bulan']= bulan_pilihan;
						post['tahun']= tahun_pilihan;
					break;
					//End Modul
				}
			
			
			
			if(flag_oke == 'jenonk'){
				var urlnyacrot = host+'homex/simpansavedata/'+to;
			}else{
				var urlnyacrot = host+'home/simpansavedata/'+to;
			}	
			
			$.post(urlnyacrot, post, function(r){
				if(r==1){
					winLoadingClose();
					if(flag_oke == 'jenonk'){
						$('#master_cdm').datagrid('reload');
						$('#'+grid_id_destination).edatagrid('reload');
					}else{
						$('#'+grid_id_to).edatagrid('reload');
					}
				}
				else{
					winLoadingClose();
					$.messager.alert('ABC System',"Transfer Data Failed",'error');
					console.log(r);
				}
			});
		}
		else{
			$.messager.alert('ABC System',"Please Select List",'error');
		}
	
}
function aktif_non(id,sts){
	loadingna();
		$.post(host+'home/set_model',{id:id,status:sts},function(r){
			var resp=JSON.parse(r);
			//if(r==1){
					$("#grid_100").datagrid('reload');
					//console.log(resp.id);
					$('#model_na').html(resp.nama_model.toUpperCase());
					winLoadingClose();
			//}
			//else{
				//alert(r);winLoadingClose();	
			//}
		});
}

function NumberFormat(value) {
	
    var jml= new String(value);
    if(jml=="null" || jml=="NaN") jml ="0";
    jml1 = jml.split("."); 
    jml2 = jml1[0];
    amount = jml2.split("").reverse();

    var output = "";
    for ( var i = 0; i <= amount.length-1; i++ ){
        output = amount[i] + output;
        if ((i+1) % 3 == 0 && (amount.length-1) !== i)output = '.' + output;
    }
    //if(jml1[1]===undefined) jml1[1] ="00";
   // if(isNaN(output))  output = "0";
    return output; // + "." + jml1[1];
}

function pilih_tree(select_tor,id,mod,tahun){
	$('#'+select_tor).addClass('loading').html('');
	$.post(host+'konten',{ id:id,modul:mod,tahun:tahun },function(resp){
		$('#'+select_tor).removeClass('loading').html(resp);
	});
}


var seconds = 0, minutes = 0, hours = 0,t,waktu

function hitung_waktu(){
	t = setTimeout(add, 1000);
}

function hapus_waktu() {
    $('#waktu_na').html("00:00:00")
    seconds = 0; minutes = 0; hours = 0;
}

function add() {
	//var res;
    seconds++;
    if (seconds >= 60) {
        seconds = 0;
        minutes++;
        if (minutes >= 60) {
            minutes = 0;
            hours++;
        }
    }
    
    waktu = (hours ? (hours > 9 ? hours : "0" + hours) : "00") + ":" + (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") + ":" + (seconds > 9 ? seconds : "0" + seconds);
	$('#waktu_na').html(waktu);
    hitung_waktu();
}
function refreshCaptcha(imgCapcha){
	capcha = $('#'+imgCapcha);
	capcha.css({"background-image":"url('"+host+"capcha/"+Math.random()+"')"});	
}
function hapus_file(mod,id,id_list){
	loadingna();
	$.post(host+'backoffice-hapusFile',{mod:mod,id:id},function(r){
		if(r==1){
			winLoadingClose();
			$('#'+id_list).remove();
		}else{
			console.log(r);
			winLoadingClose();
			$.messager.alert('MKS - STORE',"Gagal Menghapus File",'error');
		}
	});
}
function myformatter(date){
	var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
}
function myparser(s){
    if (!s) return new Date();
    var ss = (s.split('-'));
    var y = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var d = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
        return new Date(y,m-1,d);
    } else {
        return new Date();
    }
}

function upload_video(obj,id_mat,flag_vid,id_judul){
	if(obj.getFileCount() > 0){
		jml_vid=jml_vid+1;
		console.log($('#'+id_judul).val());
		if($('#'+id_judul).val()!=""){
			obj.update({ dynamicFormData:function()
				{
					var data ={ id_materi:id_mat,flag:flag_vid,judul:$('#'+id_judul).val() }
					return data;
				} 
			});
			obj.startUpload();
			sts_vid=true;
		}else{
			sts_vid=false;
			winLoadingClose();
			//$.messager.alert('Bimbel',"Harap Berikan Judul Pada Video Yang akan DiUpload",'error');
		}
	}
}
var newWindow;
function openWindowWithPost(url,params)
{
    var x = Math.floor((Math.random() * 10) + 1);
	
	if (!newWindow || typeof(newWindow)=="undefined"){
		newWindow = window.open(url, 'winpost'); 
	}else{
		newWindow.close();
		newWindow = window.open(url, 'winpost'); 
		//return false;
	}
	
	var formid= "formid"+x;
    var html = "";
    html += "<html><head></head><body><form  id='"+formid+"' method='post' action='" + url + "'>";

    $.each(params, function(key, value) {
        if (value instanceof Array || value instanceof Object) {
            $.each(value, function(key1, value1) { 
                html += "<input type='hidden' name='" + key + "["+key1+"]' value='" + value1 + "'/>";
            });
        }else{
            html += "<input type='hidden' name='" + key + "' value='" + value + "'/>";
        }
    });
   
    html += "</form><script type='text/javascript'>document.getElementById(\""+formid+"\").submit()</script></body></html>";
    newWindow.document.write(html);
    return newWindow;
}
function gen_editor(id){
	tinymce.init({
		  selector: id,
		  height: 100,
		  plugins: [
				"advlist autolink lists link image charmap print preview anchor",
				"searchreplace visualblocks code fullscreen tiny_mce_wiris",
				"insertdatetime media table contextmenu paste jbimages"
		    ],
			
		  // ===========================================
		  // PUT PLUGIN'S BUTTON on the toolbar
		  // ===========================================
		  menubar: false,
		  toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ",
			
		  // ===========================================
		  // SET RELATIVE_URLS to FALSE (This is required for images to display properly)
		  // ===========================================
			
		  relative_urls: false
		});
		
		tinyMCE.execCommand('mceRemoveControl', true, id);
		tinyMCE.execCommand('mceAddControl', true, id);
	
}
function simpan_form(id_form,id_cancel,msg){
	
	if ($('#'+id_form).form('validate')){
		loadingna();
		submit_form(id_form,function(r){
			//console.log(r)
			if(r==1){
				winLoadingClose();
				$.messager.alert('MKS - STORE Back-Office', msg, 'info');
				$('#'+id_cancel).trigger('click');
				grid_nya.datagrid('reload');
			}else{
				winLoadingClose();
				console.log(r);
				$.messager.alert('MKS - STORE Back-Office',"Tdak Dapat Menyimpan Data <br/>" + r,'error');
			}
		});
	}else{
		$.messager.alert('MKS - STORE Back-Office',"Isi Data Yang Kosong ",'info');
	}
}
function get_detil(mod,id_data,par1){
	switch(mod){
		case "komentar_set_flag":
			$.post(host+'backoffice-SetFlag',{mod:mod,id:id_data,flag:par1},function(r){
				if(r==1){
					grid_nya.datagrid('reload');
				}else{
					alert('Gagal' + r);
				}
			});
		break;
		case "konfirmasi_sekolah_form":
			$.post(host+'backoffice-form/konfirmasi_sekolah',{mod:mod,id:id_data,editstatus:'add'},function(r){
				windowForm(r,'Konfirmasi Pembayaran',700,450);
			});
		break;
		case "invoice_edit":
			$.post(host+'backoffice-form/invoice_edit',{mod:mod,id:id_data,editstatus:'edit',par_mod:par1},function(r){
				windowForm(r,'Detil Invoice',700,400);
			});
		break;
		case "cetak_bast":
			openWindowWithPost(host+'backoffice-Cetak',{mod:mod,id:id_data});
			//openWindowWithPost(host+'backoffice-Cetak',{mod:mod,id:id_data});
		break;
		case "kirim_gudang":
		case "kirim_gudang_umum":
			$.post(host+'backoffice-form/remark',{mod:mod,id:id_data},function(r){
				windowForm(r,'Pesan Gudang',580,250);
			});
		break;
		case "cancel_pesanan":
			$.messager.confirm('Cancel Order','Yakin Ingin MengCancel Order Ini? ',function(r){
				if (r){
					$.post(host+'backoffice-form/remark',{mod:mod,id:id_data},function(r){
						windowForm(r,'Manajemen Order',580,250);
					});
				}
			});
		break;
		case "set_packing":
		case "set_kirim":
		case "set_kirim_sekolah":
		case "set_kirim_umum":
			$.post(host+'backoffice-form/remark',{mod:mod,id:id_data},function(r){
				windowForm(r,'Manajemen Order',580,300);
			});
		break;
		
		case "rekap_penjualan":
		case "detil_penjualan":
		case "lap_bast":
		case "lap_kwitansi":
		
			$('#isi_laporan_'+id_data).html('').addClass('loading');
			$.post(host+'backoffice-GetDetil',{mod:mod+'_'+$('#kat_'+id_data).val(),tgl_mulai:$('#tgl_mulai_'+id_data).datebox('getValue'),tgl_akhir:$('#tgl_akhir_'+id_data).datebox('getValue'),'kat':$('#kat_'+id_data).val()},function(r){
				$('#isi_laporan_'+id_data).removeClass('loading').html(r);
			});
			
		break;
		case "lap_distribusi_kabkota":
		case "lap_buku_kabkota":
			$('#isi_laporan_'+id_data).html('').addClass('loading');
			$.post(host+'backoffice-GetDetil',{mod:mod,tgl_mulai:$('#tgl_mulai_'+id_data).datebox('getValue'),tgl_akhir:$('#tgl_akhir_'+id_data).datebox('getValue')},function(r){
				$('#isi_laporan_'+id_data).removeClass('loading').html(r);
			});
			
		break;
		case "terima_barang":
			$.post(host+'backoffice-form/terima_barang',{mod:mod,id:id_data},function(r){
				windowForm(r,'Form Terima Barang',580,250);
			});
		break;
		
		default:
			$('#grid_nya_'+mod).hide();
			$('#detil_nya_'+mod).html('').show().addClass("loading");
			$.post(host+'backoffice-GetDetil',{mod:mod,id:id_data},function(r){
				$('#detil_nya_'+mod).html(r).removeClass("loading");
			});
		break;
	}
	
}
function chart_na(id_selector,type,title,subtitle,title_y,data_x,data_y,satuan){
	switch(type){
	case "column":
	$('#'+id_selector).highcharts({
			chart: {
				type: type
			},
			title: {
				text: title
			},
			subtitle: {
				text: subtitle
			},
			xAxis: {
				type: 'category'
			},
			yAxis: {
				title: {
					text: title_y
				}

			},
			legend: {
				enabled: false
			},
			plotOptions: {
				series: {
					borderWidth: 0,
					dataLabels: {
						enabled: true,
						format: '{point.y:.1f}'
					}
				}
			},

			tooltip: {
				headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> of total<br/>'
			},

			series: data_x
			
			
			
        });
		break;
		case "line" :
			$('#'+id_selector).highcharts({
				title: {
				text: title,
				x: -20 //center
				},
				subtitle: {
					text: subtitle,
					x: -20
				},
				xAxis: {
					categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
						'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
				},
				yAxis: {
					title: {
						text: 'Temperature (°C)'
					},
					plotLines: [{
						value: 0,
						width: 1,
						color: '#808080'
					}]
				},
				tooltip: {
					valueSuffix: '°C'
				},
				legend: {
					layout: 'vertical',
					align: 'right',
					verticalAlign: 'middle',
					borderWidth: 0
				},
				series: [{
					name: 'Tokyo',
					data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
				}, {
					name: 'New York',
					data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
				}, {
					name: 'Berlin',
					data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
				}, {
					name: 'London',
					data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
				}]
			});
		break;
		case "pie":
			 $('#'+id_selector).highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: title
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: false
						},
						showInLegend: true
					}
				},
				series: data_y
			});
		break;
	}
}

function get_pesan(){
	$.get(host+'Backoffice-Pesan',function(r){
		var js=JSON.parse(r);
		$('#pesan_main').html(js.html);
		$('#jml_pesan').html(js.jml);
	});
}
function konversi_pwd_text(id){
	if($('input#'+id)[0].type=="password")$('input#'+id)[0].type = 'text';
	else $('input#'+id)[0].type = 'password';
}
