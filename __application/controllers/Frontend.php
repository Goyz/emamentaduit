<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frontend extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("If-Modified-Since: Mon, 22 Jan 2008 00:00:00 GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Cache-Control: private");
		header("Pragma: no-cache");
		
		$this->auth = unserialize(base64_decode($this->session->userdata('mkspembeli')));
		$this->host	= $this->config->item('base_url');
		
		$this->client_id		= $this->config->item('client_id');
		$this->client_secret	= $this->config->item('client_secret');;
		$this->redirect_uri		= $this->config->item('redirect_uri');;
		
		$this->token_endpoint		= $this->config->item('token_endpoint');
		$this->authorize_endpoint	= $this->config->item('authorize_endpoint');
		$this->profile_endpoint		= $this->config->item('profile_endpoint');
		$this->sekolah_endpoint		= $this->config->item('sekolah_endpoint');
		$this->sess_endpoint		= $this->config->item('sess_endpoint');
		$this->infosp				= 'http://data.dikdasmen.kemdikbud.go.id/sso/infosp';		
		
		$host = $this->host;
		$this->nsmarty->assign('host',$this->host);
		$this->nsmarty->assign('auth', $this->auth);
		
		$this->nsmarty->assign('client_id', $this->client_id);
		$this->nsmarty->assign('client_secret', $this->client_secret);
		$this->nsmarty->assign('redirect_uri', $this->redirect_uri);
		$this->nsmarty->assign('token_endpoint', $this->token_endpoint);
		$this->nsmarty->assign('authorize_endpoint', $this->authorize_endpoint);
		$this->nsmarty->assign('profile_endpoint', $this->profile_endpoint);
		$this->nsmarty->assign('sekolah_endpoint', $this->sekolah_endpoint);
		$this->nsmarty->assign('sess_endpoint', $this->sess_endpoint);
		
		$this->load->library(array('cart', 'encrypt'));
	}
	
	function index(){				
		if($this->auth){
			$this->nsmarty->assign('konten', 'profile');
		}else{
			$this->nsmarty->assign('konten', 'beranda');		
		}
		
		$this->nsmarty->display( 'frontend/main-index.html');		
	}
	
	function getdisplay($type="", $p1="", $p2="", $p3="", $p4=""){
		$zona_pilihan = $this->session->userdata('zonaxtreme');
		$this->nsmarty->assign('zona', $zona_pilihan);
		switch($type){
			case "main_page":				
				$data_cart = $this->cart->contents();
				$jumlah_item = count($data_cart);
				
				if($p1 == "beranda"){
				
				}elseif($p1 == "ubahpassword"){
					
				}elseif($p1 == "shop"){
					$code = $this->input->get('code');
					if($code){
						$token = $this->lib->oauthtoken($this->client_id, $this->client_secret, $code, $this->redirect_uri, $this->token_endpoint);
						if(isset($token['error'])){
							echo "Error : <b>".$token['error']." </b> Pesan : <b>".$token['error_description']."</b>";exit;
						}
						
						//print_r($token);
						//echo $token;
						//exit;
						
						$getidentity = $this->lib->oauthidentity($token['access_token'], $this->profile_endpoint);
						$infosp = $this->lib->oauthidentity($token['access_token'], $this->infosp);
						//echo "identitiy : <pre>";print_r($getidentity);echo "</pre>";
						//echo "infosp : <pre>";print_r($infosp);echo "</pre>";exit;
						
						$cek_user=$this->mfrontend->getdata('data_login_dapotik','row_array',$getidentity['username']);
						if(isset($cek_user['email'])){
							$data=array('email'=>$getidentity['username'],
										'nama_user'=>$getidentity['username'],
										'nama_lengkap'=>$getidentity['nama'],
										'no_telp_sekolah'=>$getidentity['no_telepon'],
										'kode_wilayah'=>$getidentity['kode_wilayah'],
										'sekolah_id'=>$getidentity['sekolah_id'],
										'peran_id'=>$getidentity['peran_id'],
										'bentuk_pendidikan'=>$getidentity['bentuk_pendidikan'],
										'jenis_pembeli'=>'SEKOLAH',
										'status'=>1,
										'pengguna_id'=>$getidentity['pengguna_id'],
										'alamat_pengiriman'=>$infosp['alamat'],
										'nama_sekolah'=>$infosp['nama_sekolah'],
										'nama_kepala_sekolah'=>$infosp['nama_kepsek'],
										'nip'=>$infosp['nip_kepsek'],
										'nama_bendahara'=>$infosp['nama_operator'],
										'npsn'=>$infosp['npsn'],
										'kd_prov'=>$infosp['kd_prov'],
										'cl_provinsi_kode'=>$infosp['kd_prov'],
										'prov'=>$infosp['prov'],
										'kd_kab'=>$infosp['kd_kab'],
										'cl_kab_kota_kode'=>$infosp['kd_kab'],
										'kab'=>$infosp['kab'],
										'cl_kecamatan_kode'=>$infosp['kd_kec'],
										'kd_kec'=>$infosp['kd_kec'],
										'kec'=>$infosp['kec'],
										'desa'=>$infosp['desa'],
										'zona'=>$infosp['zona'],
										'kode_pos'=>$infosp['kode_pos'],
										'email_kepsek'=>$infosp['email_kepsek'],
										'email_operator'=>$infosp['email_operator'],
										'no_hp_kepsek'=>$infosp['hp_kepsek'],
										'no_hp_bendahara'=>$infosp['hp_operator']
										
							);
							$simpan_dapotik=$this->mfrontend->simpansavedata('tbl_reg_dapotik',$data,'edit');
							if($simpan_dapotik==1){
								$cek_user=$this->mfrontend->getdata('data_login_dapotik','row_array',$getidentity['username']);
								$sess = array();
								$sess['zona_pilihan'] = $infosp['zona'];
								$sess['bentuk_pendidikan'] = $infosp['bentuk_pendidikan'];
								$this->session->set_userdata("zonaxtreme", $sess);
								$this->session->set_userdata('mkspembeli', base64_encode(serialize($cek_user)));
								header("Location: " . $this->host ."profile");
							}else{
								echo "FAILED SAVE ";exit;
							}
						}else{
							$data=array('email'=>$getidentity['username'],
										'nama_user'=>$getidentity['username'],
										'nama_lengkap'=>$getidentity['nama'],
										'no_telp_sekolah'=>$getidentity['no_telepon'],
										'kode_wilayah'=>$getidentity['kode_wilayah'],
										'sekolah_id'=>$getidentity['sekolah_id'],
										'peran_id'=>$getidentity['peran_id'],
										'bentuk_pendidikan'=>$getidentity['bentuk_pendidikan'],										
										'jenis_pembeli'=>'SEKOLAH',
										'status'=>1,
										'pengguna_id'=>$getidentity['pengguna_id'],
										'alamat_pengiriman'=>$infosp['alamat'],
										'nama_sekolah'=>$infosp['nama_sekolah'],
										'nama_kepala_sekolah'=>$infosp['nama_kepsek'],
										'nip'=>$infosp['nip_kepsek'],
										'nama_bendahara'=>$infosp['nama_operator'],
										'npsn'=>$infosp['npsn'],
										'kd_prov'=>$infosp['kd_prov'],
										'cl_provinsi_kode'=>$infosp['kd_prov'],
										'prov'=>$infosp['prov'],
										'kd_kab'=>$infosp['kd_kab'],
										'cl_kab_kota_kode'=>$infosp['kd_kab'],
										'kab'=>$infosp['kab'],
										'cl_kecamatan_kode'=>$infosp['kd_kec'],
										'kd_kec'=>$infosp['kd_kec'],
										'kec'=>$infosp['kec'],
										'desa'=>$infosp['desa'],
										'zona'=>$infosp['zona'],
										'kode_pos'=>$infosp['kode_pos'],
										'email_kepsek'=>$infosp['email_kepsek'],
										'email_operator'=>$infosp['email_operator'],
										'no_hp_kepsek'=>$infosp['hp_kepsek'],
										'no_hp_bendahara'=>$infosp['hp_operator']
										
							);
							$simpan_dapotik=$this->mfrontend->simpansavedata('tbl_reg_dapotik',$data,'add');
							if($simpan_dapotik==1){
								$cek_user=$this->mfrontend->getdata('data_login_dapotik','row_array',$getidentity['username']);
								$sess = array();
								$sess['zona_pilihan'] = $infosp['zona'];
								$sess['bentuk_pendidikan'] = $infosp['bentuk_pendidikan'];								
								$this->session->set_userdata("zonaxtreme", $sess);
								$this->session->set_userdata('mkspembeli', base64_encode(serialize($cek_user)));
								header("Location: " . $this->host ."profile");
							}else{
								echo "FAILED SAVE ";exit;
							}
						}
					}
				}elseif($p1 == "profile"){
				
				}elseif($p1 == "login"){
					
				}elseif($p1 == "bantuan"){
					
				}elseif($p1 == "datapesanan"){				
				}elseif($p1 == "katalog"){				
				}elseif($p1 == "detailproduk"){				
					$this->nsmarty->assign( 'idproduk', $p2 );
					$this->nsmarty->assign( 'judulproduk', $p3 );
				}elseif($p1 == "detailpaket"){				
					$this->nsmarty->assign( 'idproduk', $p2 );
					$this->nsmarty->assign( 'judulproduk', $p3 );
				}elseif($p1 == "katalogpaket"){
					
				}elseif($p1 == "lacakpesanan"){
					$this->nsmarty->assign( 'judulbesar', "Lacak Pesanan" );
					$this->nsmarty->assign( 'judulkecil', "Lacak & Ketahui Data Pesanan Anda" );
				}elseif($p1 == "detailorder"){
					$this->nsmarty->assign( 'no_order', $p2 );
				}elseif($p1 == "konfirmasi"){
					$this->nsmarty->assign( 'no_order', $p2 );
				}elseif($p1 == "riwayat"){
					$this->nsmarty->assign( 'judulbesar', "Riwayat Pesanan" );
					$this->nsmarty->assign( 'judulkecil', "Lacak & Ketahui Data Riwayat Pesanan Anda di www.aldeaz.id" );
				}elseif($p1 == "komplain"){
					$this->nsmarty->assign( 'judulbesar', "Layanan Komplain" );
					$this->nsmarty->assign( 'judulkecil', "Tuliskan Komplain Anda." );
				}elseif($p1 == "pembatalan"){
					$this->nsmarty->assign( 'judulbesar', "Pembatalan Pesanan" );
					$this->nsmarty->assign( 'judulkecil', "Layanan Pembatalan Pesanan Anda." );
				}elseif($p1 == "registrasipembeli"){
				
				}elseif($p1 == "komentarpelanggan"){
				
				}elseif($p1 == "selesaibelanja"){
					
				}elseif($p1 == "uploadfile"){
					$this->nsmarty->assign( 'no_order', $p2 );
				}
				
				if($zona_pilihan){
					$this->nsmarty->assign('zona_pilihan', "Anda Berada Dalam Zona Harga ".$zona_pilihan['zona_pilihan']);
				}
				$this->nsmarty->assign( 'tot_item', $jumlah_item );
				$this->nsmarty->assign( 'konten', $p1);
				if(isset($p2)){
					$this->nsmarty->assign('param2', $p2);	
				}
				$this->nsmarty->display( 'frontend/main-index.html');	
			break;
			case "loading_page":
				switch($p1){
					case "ubahpassword":
						$temp = "frontend/modul/ubahpassword.html";
					break;
					case "uploadfile":
						$temp = "frontend/modul/uploadfile.html";
						
						$no_order = $this->input->post("ord");
						$cek_no_order = $this->db->get_where("tbl_h_pemesanan", array("no_order"=>$no_order) )->row_array();
						if($cek_no_order){ 
							$cek_no_order["grand_total"] = "Rp. ".number_format($cek_no_order['grand_total'],0,",",".");
							$this->nsmarty->assign( 'data_order', $cek_no_order ); 
							$this->nsmarty->assign( 'cek_order', "true" ); 
						}else{ 
							$this->nsmarty->assign( 'cek_order', "false" ); 
						}	
						
					break;
					case "login":
						$temp = "frontend/modul/login.html";
					break;
					case "registrasi":
						$temp = "frontend/modul/registrasi.html";
						$this->nsmarty->assign('combo_prov', $this->lib->fillcombo('cl_provinsi_old', 'return'));
					break;
					case "profile":
						$temp = "frontend/modul/profile.html";
						
						if($this->auth["jenis_pembeli"] == "SEKOLAH"){
							$this->nsmarty->assign('combo_prov', $this->lib->fillcombo('cl_provinsi', 'return', $this->auth["cl_provinsi_kode"]));
							$this->nsmarty->assign('combo_kab', $this->lib->fillcombo('cl_kab_kota', 'return', $this->auth["cl_kab_kota_kode"], $this->auth["cl_provinsi_kode"]  ));
							$this->nsmarty->assign('combo_kec', $this->lib->fillcombo('cl_kecamatan', 'return', $this->auth["cl_kecamatan_kode"], $this->auth["cl_kab_kota_kode"]  ));
						}elseif($this->auth["jenis_pembeli"] == "UMUM"){
							$this->nsmarty->assign('combo_prov', $this->lib->fillcombo('cl_provinsi_old', 'return', $this->auth["cl_provinsi_kode"]));
							$this->nsmarty->assign('combo_kab', $this->lib->fillcombo('cl_kab_kota_old', 'return', $this->auth["cl_kab_kota_kode"], $this->auth["cl_provinsi_kode"]  ));
							$this->nsmarty->assign('combo_kec', $this->lib->fillcombo('cl_kecamatan_old', 'return', $this->auth["cl_kecamatan_kode"], $this->auth["cl_kab_kota_kode"]  ));
						}
					break;
					
					case "finish_registrasi":
						$temp = "frontend/modul/finish-registrasi-page.html";
					break;
					case "testimonial":
						$temp = "frontend/modul/testimonial.html";
						$datatestimonial = $this->mfrontend->getdata("data_testimonial", "result_array");
						$this->nsmarty->assign('datatestimonial', $datatestimonial);
					break;
					case "beranda":
						$temp = "frontend/modul/beranda-page.html";
						$data_buku = $this->mfrontend->getdata('data_buku', 'result_array', '', 1, 16);
						foreach($data_buku as $k=>$v){
							//$data_buku[$k]['judul_buku'] = $this->lib->cutstring($v['judul_buku'], 20);
							if($data_buku[$k]['foto_buku'] != null){
								$data_buku[$k]['foto_buku'] = $this->host."__repository/produk/".$v['foto_buku'];
							}else{
								$data_buku[$k]['foto_buku'] = $this->host."__repository/no-image.jpeg";
							}
						}
						$this->nsmarty->assign('data_buku', $data_buku);
					break;
					case "tentangkami":
						$temp = "frontend/modul/tentangkami-page.html";
					break;
					case "kontak":
						$temp = "frontend/modul/kontak.html";
					break;
					case "detail_order":
						$temp = "frontend/modul/detail_order.html";
						
						$no_order = $this->input->post("ord");
						$data_invoice = $this->mfrontend->getdata('header_pesanan', 'row_array', $no_order);
						if($data_invoice){ 
							$data_customer = $this->db->get_where('tbl_registrasi', array('id'=>$data_invoice['tbl_registrasi_id']))->row_array();
							$data_tracking = $this->mfrontend->getdata('tracking_pesanan', 'row_array', $data_invoice["idpesan"]);
							$data_invoice['grand_total'] = number_format($data_invoice['grand_total'],0,",",".");
							
							$this->nsmarty->assign( 'data_invoice', $data_invoice ); 
							$this->nsmarty->assign( 'data_tracking', $data_tracking ); 
							$this->nsmarty->assign( 'data_customer', $data_customer);
							$this->nsmarty->assign( 'cek_order', "true" ); 
						}else{ 
							$this->nsmarty->assign( 'cek_order', "false" ); 
						}	
						
						$this->nsmarty->assign('no_order', $no_order);
					break;
					case "datapesanan":
						$temp = "frontend/modul/datapesanan.html";
						$data_pesanan = $this->mfrontend->getdata('data_pesanan_user', 'result_array');
						foreach($data_pesanan as $k=>$v){
							if($this->auth["jenis_pembeli"] == "UMUM"){
								$cek_data_konfirmasi = $this->db->get_where("tbl_konfirmasi", array("tbl_h_pemesanan_id"=>$v["id"]) )->row_array();
								
								if($cek_data_konfirmasi){
									if($cek_data_konfirmasi["flag"] == "P"){
										$data_pesanan[$k]['flag_konfirm'] = 2; //Menunggu verifikasi
									}elseif($cek_data_konfirmasi["flag"] == "F"){										
										$data_pesanan[$k]['flag_konfirm'] = 1; //Sudah Konfirmasi
									}
								}else{
									$data_pesanan[$k]['flag_konfirm'] = 0; //Belum Konfirmasi
								}
							}elseif($this->auth["jenis_pembeli"] == "SEKOLAH"){
								$cek_data_konfirmasi = $this->db->get_where("tbl_konfirmasi", array("tbl_h_pemesanan_id"=>$v["id"]) )->row_array();
								$cek_data_upload = $this->db->get_where("tbl_uploadfile", array("tbl_h_pemesanan_id"=>$v["id"]) )->row_array();
								
								if($cek_data_konfirmasi){
									$data_pesanan[$k]['flag_konfirm'] = "";
									if($cek_data_konfirmasi["flag"] == "P"){
										$data_pesanan[$k]['flag_konfirm'] = 2; //Menunggu verifikasi
									}elseif($cek_data_konfirmasi["flag"] == "F"){										
										$data_pesanan[$k]['flag_konfirm'] = 1; //Sudah Konfirmasi
									}
								}else{
									$data_pesanan[$k]['flag_konfirm'] = 0; //Belum Konfirmasi
								}
								
								if($cek_data_upload){
									$data_pesanan[$k]['flag_upload'] = 1; //Sudah Konfirmasi
								}else{
									$data_pesanan[$k]['flag_upload'] = 0; //Belum Konfirmasi
								}
							}
							$data_pesanan[$k]['total_pesanan'] = "Rp. ".number_format($v['grand_total'],0,",",".");
							
						}
						$this->nsmarty->assign('data_pesanan', $data_pesanan);
					break;
					case "katalogpaket":
						$temp = "frontend/modul/paketkatalog.html";
						$data_buku = $this->mfrontend->getdata('data_buku_paket', 'result_array', 0, 16);
						foreach($data_buku as $k=>$v){
							$total_harga = 0;
							$get_harga = $this->mfrontend->getdata("data_paket_detail", "result_array", $v["id"]);
							if($zona_pilihan){
								foreach($get_harga as $x => $y){
									$total_harga += $y['harga_zona_'.$zona_pilihan['zona_pilihan']];
								}
							}
							
							$data_buku[$k]['foto_buku'] = $this->host."__repository/paket-image.png";
							$data_buku[$k]['harga_paket'] = number_format($total_harga,0,",",".");
							
							$data_buku[$k]['id_encrpyt'] = $this->encrypt->encode($v['id']);
							$data_buku[$k]['id_url'] = $this->lib->base64url_encode($v['id']);
							$data_buku[$k]['judul_url'] = $this->lib->clean(strtolower($v['nama_paket']),"-");							
						}
												
						$total_data = $this->db->count_all("tbl_paket");
						$limit = 16;
						$total_paging = $total_data / $limit;
						if(is_int($total_paging) == true){
							$total_paging = $total_paging;
						}else{
							$total_paging = ceil($total_paging);
						}
						$array_paging = array();
						for($i=0; $i <= ($total_paging-1); $i++){
						//for($i=0; $i <= 90; $i++){
							$j = ($i + 1);
							if(isset($mulai)){
								$mulai = $mulai + $limit;
							}else{
								$mulai = 0;
							}
							
							$array_paging[$i]['angka'] = $j;
							$array_paging[$i]['limitnya'] = $mulai."-".$limit;
						}
						
						$this->nsmarty->assign('array_paging', $array_paging);
						$this->nsmarty->assign('data_buku', $data_buku);
						$this->nsmarty->assign('zona', $zona_pilihan['zona_pilihan']);						
					break;
					case "katalogpaketpaging":
						$temp = "frontend/modul/paketkatalogpaging.html";
						$type = $this->input->post('dtype');
						$limit = $this->input->post('lmt');
						$explim = explode("-",$limit);
						
						$data_buku = $this->mfrontend->getdata('data_buku_paket', 'result_array', $explim[0], $explim[1]);
						foreach($data_buku as $k=>$v){
							$total_harga = 0;
							$get_harga = $this->mfrontend->getdata("data_paket_detail", "result_array", $v["id"]);
							if($zona_pilihan){
								foreach($get_harga as $x => $y){
									$total_harga += $y['harga_zona_'.$zona_pilihan['zona_pilihan']];
								}
							}
							
							$data_buku[$k]['foto_buku'] = $this->host."__repository/paket-image.png";
							$data_buku[$k]['harga_paket'] = number_format($total_harga,0,",",".");
							
							$data_buku[$k]['id_encrpyt'] = $this->encrypt->encode($v['id']);
							$data_buku[$k]['id_url'] = $this->lib->base64url_encode($v['id']);
							$data_buku[$k]['judul_url'] = $this->lib->clean(strtolower($v['nama_paket']),"-");	
						}
						
						$this->nsmarty->assign('type', $type);
						$this->nsmarty->assign('data_buku', $data_buku);
						$this->nsmarty->assign('zona', $zona_pilihan['zona_pilihan']);	
					break;
					case "filterdtpaket":
						$temp = "frontend/modul/paketkatalogfilterdata.html";
						$data_buku = $this->mfrontend->getdata('data_buku_paket', 'result_array', 0, 16);
						foreach($data_buku as $k=>$v){
							$total_harga = 0;
							$get_harga = $this->mfrontend->getdata("data_paket_detail", "result_array", $v["id"]);
							if($zona_pilihan){
								foreach($get_harga as $x => $y){
									$total_harga += $y['harga_zona_'.$zona_pilihan['zona_pilihan']];
								}
							}
							
							$data_buku[$k]['foto_buku'] = $this->host."__repository/paket-image.png";
							$data_buku[$k]['harga_paket'] = number_format($total_harga,0,",",".");
							
							$data_buku[$k]['id_encrpyt'] = $this->encrypt->encode($v['id']);
							$data_buku[$k]['id_url'] = $this->lib->base64url_encode($v['id']);
							$data_buku[$k]['judul_url'] = $this->lib->clean(strtolower($v['nama_paket']),"-");	
						}
						
						$total_data = $this->mfrontend->getdata('hitung_data_filter_paket', 'num_rows');
						$limit = 16;
						$total_paging = $total_data / $limit;
						if(is_int($total_paging) == true){
							$total_paging = $total_paging;
						}else{
							$total_paging = ceil($total_paging);
						}
						$array_paging = array();
						for($i=0; $i <= ($total_paging-1); $i++){
							$j = ($i + 1);
							if(isset($mulai)){
								$mulai = $mulai + $limit;
							}else{
								$mulai = 0;
							}
							
							$array_paging[$i]['angka'] = $j;
							$array_paging[$i]['limitnya'] = $mulai."-".$limit;
						}
						$crfilter = $this->input->post('cr');
						
						$this->nsmarty->assign('array_paging', $array_paging);						
						$this->nsmarty->assign('data_buku', $data_buku);
						$this->nsmarty->assign('crfilter', $crfilter);			
						$this->nsmarty->assign('zona', $zona_pilihan['zona_pilihan']);							
					break;
					case "detailpaket":
						$id = $this->input->post("idix");
						$id = $this->lib->base64url_decode($id);
						
						$idx = $this->input->post("idix");
						$judulproduk = $this->input->post("jd");
						
						$temp = "frontend/modul/detail_paket.html";
						$data_buku = $this->mfrontend->getdata('data_paket_detail', 'result_array', $id);
						$data_paket = $this->db->get_where("tbl_paket", array("id"=>$id))->row_array();
						
						foreach($data_buku as $k=>$v){	
							if($zona_pilihan){
								$data_buku[$k]['harga_real'] = $v['harga_zona_'.$zona_pilihan['zona_pilihan']];
								$data_buku[$k]['harga_buku'] = "Rp. ".number_format($v['harga_zona_'.$zona_pilihan['zona_pilihan']],0,",",".");
							}else{
								$data_buku[$k]['harga_real'] = 0;
								$data_buku[$k]['harga_buku'] = "Rp. 0";
							}
						}
						
						if($zona_pilihan){
							$estimasi = $this->db->get_where('cl_zona', array('zona_code'=>$zona_pilihan['zona_pilihan']))->row_array();	
							$this->nsmarty->assign('estimasi', $estimasi['estimasi']);
							$this->nsmarty->assign('provinsi', $zona_pilihan['provinsi']);							
						}
						
						$this->nsmarty->assign('zona', $zona_pilihan['zona_pilihan']);						
						$this->nsmarty->assign('data_buku', $data_buku);
						$this->nsmarty->assign('data_paket', $data_paket);
						$this->nsmarty->assign('judulproduk', $judulproduk);
						$this->nsmarty->assign('idx', $idx);
						$this->nsmarty->assign('id', $this->encrypt->encode($id));
					break;					
					
					case "katalog":						
						$temp = "frontend/modul/katalog.html";
						$id_tingkatan = $this->db->get_where('cl_tingkatan', array('tingkatan'=>strtoupper($p2)))->row_array();
						$data_buku = $this->mfrontend->getdata('data_buku', 'result_array', $id_tingkatan['id'], 0, 16);

						foreach($data_buku as $k=>$v){
							if(isset($zona_pilihan)){
								$data_buku[$k]['harga_buku_bener'] = number_format($v['harga_zona_'.$zona_pilihan['zona_pilihan']],0,",",".");
							}else{
								$data_buku[$k]['harga_buku_bener'] = "-";
							}
							if($data_buku[$k]['foto_buku'] != null){
								$data_buku[$k]['foto_buku'] = $this->host."__repository/produk/".$v['foto_buku'];
							}else{
								$data_buku[$k]['foto_buku'] = $this->host."__repository/no-image.jpeg";
							}
							
							$data_buku[$k]['id_encrpyt'] = $this->encrypt->encode($v['id']);
							$data_buku[$k]['id_url'] = $this->lib->base64url_encode($v['id']);
							$data_buku[$k]['judul_url'] = $this->lib->clean(strtolower($v['judul_buku']),"-");
						}
						
						$arrayfiltertingkatan = array();
						if($this->auth){
							if($this->auth["bentuk_pendidikan"] == "SD"){
								$arrayfiltertingkatan = array("id"=>2);
							}elseif($this->auth["bentuk_pendidikan"] == "SMP"){
								$arrayfiltertingkatan = array("id"=>3);
							}elseif($this->auth["bentuk_pendidikan"] == "SMA"){
								$arrayfiltertingkatan = array("id"=>4);
							}elseif($this->auth["bentuk_pendidikan"] == "SMK"){
								$arrayfiltertingkatan = array("id"=>4);
							}
						}
						
						$data_tingkatan = $this->db->get_where('cl_tingkatan', $arrayfiltertingkatan )->result_array();
						
						$array_tingkatan = array();
						foreach($data_tingkatan as $k => $v){
							$array_tingkatan[$k]['tingkatan'] = $v['tingkatan'];
							$array_tingkatan[$k]['detil'] = array();
							$getkelas = $this->db->get_where('cl_kelas', array('cl_tingkatan_id'=>$v['id']))->result_array();
							foreach($getkelas as $t => $p){
								$array_tingkatan[$k]['detil'][$t]['id'] = $p['id'];
								$array_tingkatan[$k]['detil'][$t]['nama_kelas'] = "Kelas ".$p['kelas'];
							}
						}
						$data_pengguna = $this->db->get('cl_group_sekolah')->result_array();
						$data_kategori = $this->db->get('cl_kategori')->result_array();
						
						if($this->auth["bentuk_pendidikan"] != null){
							$total_data = $this->mfrontend->getdata('hitung_data_filter', 'num_rows');
						}else{
							$total_data = $this->db->count_all("tbl_buku");
						}
						
						$limit = 16;
						$total_paging = $total_data / $limit;
						if(is_int($total_paging) == true){
							$total_paging = $total_paging;
						}else{
							$total_paging = ceil($total_paging);
						}
						$array_paging = array();
						for($i=0; $i <= ($total_paging-1); $i++){
						//for($i=0; $i <= 90; $i++){
							$j = ($i + 1);
							if(isset($mulai)){
								$mulai = $mulai + $limit;
							}else{
								$mulai = 0;
							}
							
							$array_paging[$i]['angka'] = $j;
							$array_paging[$i]['limitnya'] = $mulai."-".$limit;
						}
						
						$this->nsmarty->assign('array_paging', $array_paging);
						$this->nsmarty->assign('data_tingkatan', $array_tingkatan);
						$this->nsmarty->assign('data_pengguna', $data_pengguna);
						$this->nsmarty->assign('data_kategori', $data_kategori);
						$this->nsmarty->assign('data_buku', $data_buku);
						$this->nsmarty->assign('zona', $zona_pilihan['zona_pilihan']);
					break;
					case "datapaging":
						$temp = "frontend/modul/katalogpaging.html";
						$limit = $this->input->post('lmt');
						$explim = explode("-",$limit);
						
						$data_buku = $this->mfrontend->getdata('data_buku', 'result_array', "", $explim[0], $explim[1]);
						foreach($data_buku as $k=>$v){
							if(isset($zona_pilihan)){
								$data_buku[$k]['harga_buku_bener'] = number_format($v['harga_zona_'.$zona_pilihan['zona_pilihan']],0,",",".");
							}
							if($data_buku[$k]['foto_buku'] != null){
								$data_buku[$k]['foto_buku'] = $this->host."__repository/produk/".$v['foto_buku'];
							}else{
								$data_buku[$k]['foto_buku'] = $this->host."__repository/no-image.jpeg";
							}
							$data_buku[$k]['id_encrpyt'] = $this->encrypt->encode($v['id']);
							$data_buku[$k]['id_url'] = $this->lib->base64url_encode($v['id']);
							$data_buku[$k]['judul_url'] = $this->lib->clean(strtolower($v['judul_buku']),"-");
						}
						
						$this->nsmarty->assign('data_buku', $data_buku);
					break;
					case "filterdt":
					case "crdt":					
						$temp = "frontend/modul/katalogfilterdata.html";
						$data_buku = $this->mfrontend->getdata('data_buku', 'result_array', "", 0, 16);
						
						foreach($data_buku as $k=>$v){
							if(isset($zona_pilihan)){
								$data_buku[$k]['harga_buku_bener'] = number_format($v['harga_zona_'.$zona_pilihan['zona_pilihan']],0,",",".");
							}							
							
							if($data_buku[$k]['foto_buku'] != null){
								$data_buku[$k]['foto_buku'] = $this->host."__repository/produk/".$v['foto_buku'];
							}else{
								$data_buku[$k]['foto_buku'] = $this->host."__repository/no-image.jpeg";
							}
							$data_buku[$k]['id_encrpyt'] = $this->encrypt->encode($v['id']);
							$data_buku[$k]['id_url'] = $this->lib->base64url_encode($v['id']);
							$data_buku[$k]['judul_url'] = $this->lib->clean(strtolower($v['judul_buku']),"-");
						}
						
						$total_data = $this->mfrontend->getdata('hitung_data_filter', 'num_rows');
						$limit = 16;
						$total_paging = $total_data / $limit;
						if(is_int($total_paging) == true){
							$total_paging = $total_paging;
						}else{
							$total_paging = ceil($total_paging);
						}
						$array_paging = array();
						for($i=0; $i <= ($total_paging-1); $i++){
							$j = ($i + 1);
							if(isset($mulai)){
								$mulai = $mulai + $limit;
							}else{
								$mulai = 0;
							}
							
							$array_paging[$i]['angka'] = $j;
							$array_paging[$i]['limitnya'] = $mulai."-".$limit;
						}
						
						$typefilter = $this->input->post('ty');
						$idfilter = $this->input->post('isd');
						$crfilter = $this->input->post('cr');
						
						$this->nsmarty->assign('array_paging', $array_paging);						
						$this->nsmarty->assign('data_buku', $data_buku);
						$this->nsmarty->assign('typefilter', $typefilter);
						$this->nsmarty->assign('idfilter', $idfilter);
						$this->nsmarty->assign('crfilter', $crfilter);
					break;						
					case "combo_zona":
						$temp = "frontend/modul/combozona-page.html";
						$ket_zona = $this->db->get('cl_zona')->result_array();
						$this->nsmarty->assign('ket_zona',  $ket_zona);
						$this->nsmarty->assign('combo_zona', $this->lib->fillcombo('cl_zona', 'return'));
					break;					
					case "kategori":
						$temp = "frontend/modul/kategori-page.html";
						$id_tingkatan = $this->db->get_where('cl_tingkatan', array('tingkatan'=>strtoupper($p2)))->row_array();
						$data_buku = $this->mfrontend->getdata('data_buku_tingkatan', 'result_array', $id_tingkatan['id']);
						foreach($data_buku as $k=>$v){
							$data_buku[$k]['judul_buku'] = $this->lib->cutstring($v['judul_buku'], 50);
						}
						
						$data_tingkatan = $this->db->get('cl_tingkatan')->result_array();
						$data_kategori = $this->db->get('cl_kategori')->result_array();
						$data_pengguna = $this->db->get('cl_group_sekolah')->result_array();
						
						$this->nsmarty->assign('data_tingkatan', $data_tingkatan);
						$this->nsmarty->assign('data_kategori', $data_kategori);
						$this->nsmarty->assign('data_pengguna', $data_pengguna);
						$this->nsmarty->assign('nama_kategori', strtoupper($p2));
						$this->nsmarty->assign('data_buku', $data_buku);
					break;
					case "detailproduk":
						$id = $this->input->post("idix");
						$id = $this->lib->base64url_decode($id);
						
						$idx = $this->input->post("idix");
						$judulproduk = $this->input->post("jd");
												
						$temp = "frontend/modul/detail_produk.html";
						$data_buku = $this->mfrontend->getdata('data_buku_detail', 'row_array', $id);
						$data_fotobuku = $this->db->get_where('tbl_foto_buku', array('tbl_buku_id'=>$id))->result_array();
												
						if($zona_pilihan){
							$estimasi = $this->db->get_where('cl_zona', array('zona_code'=>$zona_pilihan['zona_pilihan']))->row_array();
							$harga_buku = number_format($data_buku['harga_zona_'.$zona_pilihan['zona_pilihan']],0,",",".");
							for($i = 1; $i <= 5; $i++){
								$data_buku['harga_zona_'.$i] = "Rp. ".number_format($data_buku['harga_zona_'.$i],0,",",".");
								$data_buku['harga_pemerintah_zona_'.$i] = "Rp. ".number_format($data_buku['harga_pemerintah_zona_'.$i],0,",",".");
							}
							
							$this->nsmarty->assign('zona', $zona_pilihan['zona_pilihan']);
							$this->nsmarty->assign('estimasi', $estimasi['estimasi']);
							$this->nsmarty->assign('harga_buku', $harga_buku);	
							$this->nsmarty->assign('provinsi', $zona_pilihan['provinsi']);							
						}
						
						if($data_fotobuku){
							foreach($data_fotobuku as $k=>$v){
								if($data_fotobuku[$k]['foto_buku'] != null){
									$data_fotobuku[$k]['foto_buku'] = $this->host."__repository/produk/".$v['foto_buku'];
								}
							}
						}else{
							$data_fotobuku[0]['foto_buku'] = $this->host."__repository/no-image.jpeg";
						}
						
						//print_r($data_buku);exit;
						
						$this->nsmarty->assign('data_buku', $data_buku);
						$this->nsmarty->assign('data_fotobuku', $data_fotobuku);
						$this->nsmarty->assign('judulproduk', $judulproduk);
						$this->nsmarty->assign('idx', $idx);
						$this->nsmarty->assign('id', $this->encrypt->encode($id));
					break;
					case "zona_pengiriman":
						$zona = $this->input->post('znpn');
						if($zona == ''){
							echo "Pilih Zona Pengiriman";
							exit;
						}
						
						$id = $this->input->post('iix');
						$data_harga = $this->mfrontend->getdata('zona_pengiriman', 'row_array', $id, $zona);
						$word = "Rp. ".number_format($data_harga['harga_zona_'.$zona],0,",",".");
						
						echo $word;
						exit;
					break;
					case "keranjangnya":
						$temp = "frontend/modul/keranjangbelanja.html";
						$data_cart = $this->cart->contents();
						$tot_price = 0;
						foreach($data_cart as $key => $v){
							$datafoto = $this->db->get_where('tbl_foto_buku', array('tbl_buku_id'=>$v['id']) )->row_array();
							if($datafoto['foto_buku'] != null){
								$data_cart[$key]['foto_buku'] = $this->host."__repository/produk/".$datafoto['foto_buku'];
							}else{
								$data_cart[$key]['foto_buku'] = $this->host."__repository/no-image.jpeg";
							}
							$data_cart[$key]['price'] = number_format($v['price'],0,",",".");
							$data_cart[$key]['subtotal'] = number_format($v['subtotal'],0,",",".");
							
							$tot_price += $v['subtotal'];
						}
						$this->nsmarty->assign('data_cart', $data_cart);
						$this->nsmarty->assign('tot_price', number_format($tot_price,0,",","."));
					break;
					case "update_keranjang":
					case "hapusitem_keranjang":
						$temp = "frontend/modul/keranjangbelanjaupdate-page.html";						
						$ck = $this->input->post('ck');
						if($p1 == "update_keranjang"){
							$qty = $this->input->post('qt');
							$rowid = $this->input->post('rws');					
							$this->keranjang_belanja('update', $qty, $rowid);
						}elseif($p1 == "hapusitem_keranjang"){
							$rowid = $this->input->post('rws');
							$this->keranjang_belanja('delete', $rowid);
						}
						
						$data_cart = $this->cart->contents();
						foreach($data_cart as $key => $v){
							$datafoto = $this->db->get_where('tbl_foto_buku', array('tbl_buku_id'=>$v['id']) )->row_array();
							if($datafoto['foto_buku'] != null){
								$data_cart[$key]['foto_buku'] = $this->host."__repository/produk/".$datafoto['foto_buku'];
							}else{
								$data_cart[$key]['foto_buku'] = $this->host."__repository/no-image.jpeg";
							}						
							$data_cart[$key]['price'] = number_format($v['price'],0,",",".");
							$data_cart[$key]['subtotal'] = number_format($v['subtotal'],0,",",".");
						}
						$this->nsmarty->assign('ck', $ck);
						$this->nsmarty->assign('data_cart', $data_cart);
					break;
					case "total_item";
						$data_cart = $this->cart->contents();
						$jumlah_item = count($data_cart);
						echo $jumlah_item;
						exit;
					break;
					case "selesaibelanja": 
						$temp = "frontend/modul/selesai_belanja.html";
						$data_cart = $this->cart->contents();
						$estimasi = $this->db->get_where('cl_zona', array('zona_code'=>$zona_pilihan['zona_pilihan']))->row_array();
						$tot_price = 0;
						$tot_qty = 0;
						foreach($data_cart as $key => $v){
							$datafoto = $this->db->get_where('tbl_foto_buku', array('tbl_buku_id'=>$v['id']) )->row_array();
							if($datafoto['foto_buku'] != null){
								$data_cart[$key]['foto_buku'] = $this->host."__repository/produk/".$datafoto['foto_buku'];
							}else{
								$data_cart[$key]['foto_buku'] = $this->host."__repository/no-image.jpeg";
							}
							
							$data_cart[$key]['price_asli'] = $v['price'];
							$data_cart[$key]['price'] = number_format($v['price'],0,",",".");
							$data_cart[$key]['subtotal'] = number_format($v['subtotal'],0,",",".");
							$tot_price += $v['subtotal'];
							$tot_qty += $v['qty'];
						}
						$this->nsmarty->assign('data_cart', $data_cart);
						$this->nsmarty->assign('tot_price', number_format($tot_price,0,",","."));						
						$this->nsmarty->assign('tot_qty', number_format($tot_qty,0,",","."));	
						
						$this->nsmarty->assign('zona', $zona_pilihan['zona_pilihan']);
						$this->nsmarty->assign('estimasi', $estimasi['estimasi']);
						
						$this->nsmarty->assign('combo_jasa_pengiriman', $this->lib->fillcombo('cl_jasa_pengiriman', 'return') );
						$this->nsmarty->assign('combo_metode_pembayaran', $this->lib->fillcombo('cl_metode_pembayaran', 'return') );						
						
						if($this->auth["jenis_pembeli"] == "UMUM"){							
							$this->nsmarty->assign('combo_prov', $this->lib->fillcombo('cl_provinsi_old', 'return', (isset($this->auth) ? $this->auth['cl_provinsi_kode'] : "" ) ));
							$this->nsmarty->assign('combo_kab', $this->lib->fillcombo('cl_kab_kota_old', 'return', (isset($this->auth) ? $this->auth['cl_kab_kota_kode'] : "" ), (isset($this->auth) ? $this->auth['cl_provinsi_kode'] : "" ) ));
							$this->nsmarty->assign('combo_kec', $this->lib->fillcombo('cl_kecamatan_old', 'return', (isset($this->auth) ? $this->auth['cl_kecamatan_kode'] : "" ), (isset($this->auth) ? $this->auth['cl_kab_kota_kode'] : "" ) ));													
						}elseif($this->auth["jenis_pembeli"] == "SEKOLAH"){
							$this->nsmarty->assign('combo_prov', $this->lib->fillcombo('cl_provinsi', 'return', (isset($this->auth) ? $this->auth['cl_provinsi_kode'] : "" ) ));
							$this->nsmarty->assign('combo_kab', $this->lib->fillcombo('cl_kab_kota', 'return', (isset($this->auth) ? $this->auth['cl_kab_kota_kode'] : "" ) ));
							$this->nsmarty->assign('combo_kec', $this->lib->fillcombo('cl_kecamatan', 'return', (isset($this->auth) ? $this->auth['cl_kecamatan_kode'] : "" ), (isset($cek_data) ? $cek_data['cl_kab_kota_kode'] : "" ) ));						
						}
						
					break;
					case "combo_kab_kota_sekolah":
						$v2 = $this->input->post('v2');
						echo $this->lib->fillcombo('cl_kab_kota', 'return', '', $v2);
						exit;
					break;
					case "combo_kecamatan_sekolah":
						$v2 = $this->input->post('v2');
						echo $this->lib->fillcombo('cl_kecamatan', 'return', '', $v2);
						exit;
					break;
					case "combo_kab_kota":
						$v2 = $this->input->post('v2');
						echo $this->lib->fillcombo('cl_kab_kota_old', 'return', '', $v2);
						exit;
					break;
					case "combo_kecamatan":
						$v2 = $this->input->post('v2');
						echo $this->lib->fillcombo('cl_kecamatan_old', 'return', '', $v2);
						exit;
					break;
					case "finish_semuanya":
						$temp = "frontend/modul/finish.html";
						$type = $this->input->post('type');
						if($type == 'checkout'){
							$no_order = $this->input->post('no_order');
							$data_header_pesanan = $this->mfrontend->getdata('header_pesanan', 'row_array', $no_order);
							
							$arraynya = array();
							if($data_header_pesanan){
								$data_detail_pesanan = $this->mfrontend->getdata('detail_pesanan', 'result_array', $data_header_pesanan['idpesan']);
								$arraynya['no_order'] = $no_order;
								$arraynya['tgl_order'] = $data_header_pesanan['tgl_order'];
								$arraynya['alamat_registrasi'] = $data_header_pesanan['alamat_pengiriman_registrasi'];
								$arraynya['alamat_lain'] = $data_header_pesanan['alamat_pengiriman_lain'];
								$arraynya['grand_total'] = number_format($data_header_pesanan['grand_total'],0,",",".");
								$arraynya['detail_pesanan'] = array();
								$totalqty = 0;
								foreach($data_detail_pesanan as $k=>$v){
									$arraynya['detail_pesanan'][$k]['kelas'] = $v['kelas'];
									$arraynya['detail_pesanan'][$k]['nama_group'] = $v['nama_group'];
									$arraynya['detail_pesanan'][$k]['judul_buku'] = $v['judul_buku'];
									$arraynya['detail_pesanan'][$k]['qty'] = number_format($v['qty'],0,",",".");
									$arraynya['detail_pesanan'][$k]['harga'] = number_format($v['harga'],0,",",".");
									$arraynya['detail_pesanan'][$k]['subtotal'] = number_format($v['subtotal'],0,",",".");
									$totalqty += $v['qty'];
								}
								$arraynya['total_qty'] = number_format($totalqty,0,",",".");
								$arraynya['jasa_pengiriman'] = $data_header_pesanan["jasa_pengiriman"];
								$arraynya['metode_pembayaran'] = $data_header_pesanan["metode_pembayaran"];
							}
							
							if($zona_pilihan){
								$estimasi = $this->db->get_where('cl_zona', array('zona_code'=>$zona_pilihan['zona_pilihan']))->row_array();
								$this->nsmarty->assign('estimasi', $estimasi['estimasi']);
							}
							
							$this->nsmarty->assign('arraynya', $arraynya);
							$this->nsmarty->assign('pesan', "Terima Kasih telah berbelanja di MKS-Store : SILAHKAN CEK EMAIL ANDA.");
						}elseif($type == "konfirmasi"){
							$this->nsmarty->assign('pesan', "Terima Kasih telah melakukan konfirmasi pembayaran di MKS-Store : SILAHKAN CEK EMAIL ANDA");
						}
						$this->nsmarty->assign('type', $type);
					break;
					
					case "konfirmasi_pemb":
						$temp = "frontend/modul/konfirmasi.html";
						
						$no_order = $this->input->post("ord");
						$cek_no_order = $this->db->get_where("tbl_h_pemesanan", array("no_order"=>$no_order) )->row_array();
						if($cek_no_order){ 
							$cek_no_order["grand_total"] = number_format($cek_no_order['grand_total'],0,",",".");
							$this->nsmarty->assign( 'data_order', $cek_no_order ); 
							$this->nsmarty->assign( 'cek_order', "true" ); 
						}else{ 
							$this->nsmarty->assign( 'cek_order', "false" ); 
						}	
					break;
					case "laykomplainbro":
						$temp = "frontend/modul/komplainnya-page.html";
					break;
					case "bantuan":
						$temp = "frontend/modul/bantuan.html";
					break;
					
					case "pembatalanpesan":
						$temp = "frontend/modul/pembpesan-page.html";
					break;
					case "formpembatalancuy":
						$invno = $this->input->post('inv');
						$data_invoice = $this->mfrontend->getdata('header_pesanan', 'row_array', $invno);
						if($data_invoice){
							if($data_invoice['status'] == 'F'){
								$array_page = array(
									'loadbalancedt' => md5('Ymd'),
									'loadbalancetm' => md5('H:i:s'),
									'loadtmr' => md5('YmdHis'),
									'page' => "<center>Pesanan Sudah Diproses, Tidak Bisa Dibatalkan</center>"
								);
								
								echo json_encode($array_page);
								exit;
							}else{
								$temp = "frontend/modul/formpembatalancuy-page.html";
								$kodepemb = $this->lib->randomString(8, 'angkahuruf');
								$this->db->update('tbl_h_pemesanan', array('kode_pembatalan'=>$kodepemb) ,array('no_order'=>$invno));
								$this->lib->kirimemail('email_pembatalan', $data_invoice['email'], $kodepemb, $invno);
							}
						}else{
							$array_page = array(
								'loadbalancedt' => md5('Ymd'),
								'loadbalancetm' => md5('H:i:s'),
								'loadtmr' => md5('YmdHis'),
								'page' => "<center>Data Invoice Tidak Ditemukan</center>"
							);
							
							echo json_encode($array_page);
							exit;
						}
					break;
				}		
				
				if(isset($temp)){
					$template = $this->nsmarty->fetch($temp);
				}else{
					$template = $this->nsmarty->fetch("konstruksi.html");
				}
				
				//echo $template;exit;
				
				$array_page = array(
					'loadbalancedt' => md5('Ymd'),
					'loadbalancetm' => md5('H:i:s'),
					'loadtmr' => md5('YmdHis'),
					'page' => $template 
				);
				
				echo json_encode($array_page);
			break;
		}
	}
	
	function generatepdf($type){
		$this->load->library('mlpdf');	
		switch($type){
			case "bastnya":
				$this->load->helper('terbilang');
				$inv = $this->input->post('invo');
				if(!$inv){
					echo "tutup tab browser ini, dan generate kembali melalui tombol di web bukusekolah.mks-store.id";
					exit;
				}
				$data_invoice = $this->mfrontend->getdata('header_pesanan', 'row_array', $inv);
				if($data_invoice){
					$no_bast = $data_invoice['no_order']."/OLS-MKS/BAST/XII/".date('Y');
					$datacust = $this->mfrontend->getdata('datacustomer', 'row_array', $data_invoice['tbl_registrasi_id'], '', 'cetak_bast');
					//$datakonfirmasi = $this->db->get_where('tbl_konfirmasi', array('tbl_h_pemesanan_id'=>$data_invoice['id']) )->row_array();
					$datadetailpesanan = $this->mfrontend->getdata('detail_pesanan', 'result_array', $data_invoice['idpesan']);
					$totqty = 0;
					$tottotal = 0;
					foreach($datadetailpesanan as $k => $v){
						$totqty += $v['qty'];
						$tottotal += $v['subtotal'];
						
						$datadetailpesanan[$k]['harga'] = number_format($v['harga'],0,",",".");
						$datadetailpesanan[$k]['subtotal'] = number_format($v['subtotal'],0,",",".");
						$datadetailpesanan[$k]['nama_group'] = strtoupper(substr($v['nama_group'], 0,1));
					}
					
					$cekdatabast = $this->db->get_where('tbl_bast', array('tbl_h_pemesanan_id'=>$data_invoice['idpesan']) )->row_array();
					if(!$cekdatabast){
						$array_insert_bast = array(
							'tbl_h_pemesanan_id' => $data_invoice['idpesan'],
							'no_bast' => $no_bast,
							'create_date' => date('Y-m-d H:i:s')
						);
						$this->db->insert('tbl_bast', $array_insert_bast);
						
						$tgl = $this->lib->konversi_tgl(date('Y-m-d'));
						$time = $this->lib->konversi_jam(date('H:i:s'));
					}else{
						$tgl_create_1 = explode(" ",$cekdatabast["create_date"]);
						
						$tgl = $this->lib->konversi_tgl($tgl_create_1[0]);
						$time = $this->lib->konversi_jam($tgl_create_1[1]);
					}
										
					$this->nsmarty->assign('datainvoice', $data_invoice);
					$this->nsmarty->assign('datakonfirmasi', $datakonfirmasi);
					$this->nsmarty->assign('datacust', $datacust);
					$this->nsmarty->assign('datadetailpesanan', $datadetailpesanan);
					$this->nsmarty->assign('totqty', $totqty);
					$this->nsmarty->assign('tgl', $tgl);
					$this->nsmarty->assign('time', $time);
					$this->nsmarty->assign('no_bast', $no_bast);
					$this->nsmarty->assign('tottotal', number_format($tottotal,0,",","."));
				}
				
				$filename = str_replace('/', '_', $no_bast);
				$htmlcontent = $this->nsmarty->fetch('frontend/modul/bast_pdf.html');
				
				$pdf = $this->mlpdf->load();
				$spdf = new mPDF('', 'A4', 0, '', 12.7, 12.7, 10, 10, 5, 2, 'P');
				$spdf->ignore_invalid_utf8 = true;
				$spdf->allow_charset_conversion = true;     // which is already true by default
				$spdf->charset_in = 'iso-8859-2';  // set content encoding to iso
				$spdf->SetDisplayMode('fullpage');		
				$spdf->SetProtection(array('print'));				
				$spdf->WriteHTML($htmlcontent); // write the HTML into the PDF
				//$spdf->Output($general_path.$subgroup."/".$io_number."/"."PARTIAL-".$partial_no."/LOA/".$filename.'.pdf', 'F'); // save to file because we can
				$spdf->Output($filename.'.pdf', 'I'); // view file
			break;
			case "kwitansinya":
				$this->load->helper('terbilang');
				$inv = $this->input->post('invo');
				if(!$inv){
					echo "tutup tab browser ini, dan generate kembali melalui tombol di web bukusekolah.mks-store.id";
					exit;
				}				
				$data_invoice = $this->mfrontend->getdata('header_pesanan', 'row_array', $inv);
				if($data_invoice){
					$no_kwitansi = $data_invoice['no_order']."/OLS-MKS/K/".date('Y');
					$datacust = $this->mfrontend->getdata('datacustomer', 'row_array', $data_invoice['tbl_registrasi_id'], '', 'cetak_bast');
					$jumlah = number_to_words($data_invoice['grand_total']);
					//$datakonfirmasi = $this->db->get_where('tbl_konfirmasi', array('tbl_h_pemesanan_id'=>$data_invoice['idpesan']) )->row_array();
					
					$cekdatakwitansi = $this->db->get_where('tbl_kwitansi', array('tbl_h_pemesanan_id'=>$data_invoice['idpesan']) )->row_array();
					if(!$cekdatakwitansi){
						$array_insert_kwitansi = array(
							'tbl_h_pemesanan_id' => $data_invoice['idpesan'],
							'no_kwitansi' => $no_kwitansi,
							'create_date' => date('Y-m-d H:i:s')
						);
						$this->db->insert('tbl_kwitansi', $array_insert_kwitansi);
						
						//$dt = strtotime('06/19/2009');
						//$day = date("l", $dt);
						
					}else{
						
					}
					
					//echo "<pre>";
					//print_r($data_invoice);exit;
					
					
					//$this->nsmarty->assign('datakonfirmasi', $datakonfirmasi);
					$this->nsmarty->assign('datainvoice', $data_invoice);
					$this->nsmarty->assign('datacust', $datacust);
					$this->nsmarty->assign('jumlah', $jumlah);
					$this->nsmarty->assign('no_kwitansi', $no_kwitansi);
					$this->nsmarty->assign('grandtotal', number_format($data_invoice['grand_total'],0,",",".") );
				}
				
				$filename = str_replace('/', '_', $no_kwitansi);
				$htmlcontent = $this->nsmarty->fetch('frontend/modul/kwitansi_pdf.html');
				
				//echo $htmlcontent;exit;
				
				$pdf = $this->mlpdf->load();
				$spdf = new mPDF('', 'A4', 0, '', 12.7, 12.7, 15, 15, 5, 2, 'L');
				//$spdf = new mPDF('', 'A5-L', 0, '', 5, 5, 5, 5, 0, 0);
				$spdf->ignore_invalid_utf8 = true;
				$spdf->allow_charset_conversion = true;     // which is already true by default
				$spdf->charset_in = 'iso-8859-2';  // set content encoding to iso
				$spdf->SetDisplayMode('fullpage');		
				$spdf->SetProtection(array('print'));				
				$spdf->WriteHTML($htmlcontent); // write the HTML into the PDF
				//$spdf->Output($general_path.$subgroup."/".$io_number."/"."PARTIAL-".$partial_no."/LOA/".$filename.'.pdf', 'F'); // save to file because we can
				$spdf->Output($filename.'.pdf', 'I'); // view file
			break;
			case "tandaterima":
				//$no_tanda_terima = $data_invoice['no_order']."/ASP/TT/".date('Y');
				$inv = $this->input->post('invo');
				if(!$inv){
					echo "tutup tab browser ini, dan generate kembali melalui tombol di web bukusekolah.mks-store.id";
					exit;
				}				
				$data_invoice = $this->mfrontend->getdata('header_pesanan', 'row_array', $inv);
				if($data_invoice){
					$datadetailpesanan = $this->mfrontend->getdata('detail_pesanan', 'result_array', $data_invoice['idpesan']);
					foreach($datadetailpesanan as $k=>$v){
						$datadetailpesanan[$k]['harga'] = number_format($v['harga'],0,",",".");
						$datadetailpesanan[$k]['subtotal'] = number_format($v['subtotal'],0,",",".");
					}
					$this->nsmarty->assign('datadetailpesanan', $datadetailpesanan);
					$this->nsmarty->assign('data_invoice', $data_invoice);
					$this->nsmarty->assign('inv', $inv);
				}
				
				$filename = str_replace('/', '_', $inv);
				$htmlcontent = $this->nsmarty->fetch('frontend/modul/tanda_terima_pdf.html');
				
				$pdf = $this->mlpdf->load();
				//$spdf = new mPDF('', 'A5', 0, '', 12.7, 12.7, 15, 20, 5, 2, 'L');
				$spdf = new mPDF('', 'A4', 0, '', 12.7, 12.7, 15, 20, 5, 2, 'P');				
				$spdf->ignore_invalid_utf8 = true;
				$spdf->allow_charset_conversion = true;     // which is already true by default
				$spdf->charset_in = 'iso-8859-2';  // set content encoding to iso
				$spdf->SetDisplayMode('fullpage');		
				$spdf->SetProtection(array('print'));				
				$spdf->WriteHTML($htmlcontent); // write the HTML into the PDF
				//$spdf->Output($general_path.$subgroup."/".$io_number."/"."PARTIAL-".$partial_no."/LOA/".$filename.'.pdf', 'F'); // save to file because we can
				$spdf->Output($filename.'.pdf', 'I'); // view file
			break;
			case "suratpesanan":
				$inv = $this->input->post('invo');
				if(!$inv){
					echo "tutup tab browser ini, dan generate kembali melalui tombol di web bukusekolah.mks-store.id";
					exit;
				}				
				$data_invoice = $this->mfrontend->getdata('header_pesanan', 'row_array', $inv);
				if($data_invoice){
					$datadetailpesanan = $this->mfrontend->getdata('detail_pesanan', 'result_array', $data_invoice['idpesan']);
					foreach($datadetailpesanan as $k=>$v){
						$datadetailpesanan[$k]['harga'] = number_format($v['harga'],0,",",".");
						$datadetailpesanan[$k]['subtotal'] = number_format($v['subtotal'],0,",",".");
					}
					$this->nsmarty->assign('datadetailpesanan', $datadetailpesanan);
					$this->nsmarty->assign('data_invoice', $data_invoice);
					$this->nsmarty->assign('inv', $inv);
				}
				
				$filename = str_replace('/', '_', $inv);
				$htmlcontent = $this->nsmarty->fetch('frontend/modul/surat_pesanan_pdf.html');
				
				$pdf = $this->mlpdf->load();
				//$spdf = new mPDF('', 'A5', 0, '', 12.7, 12.7, 15, 20, 5, 2, 'L');
				$spdf = new mPDF('', 'A4', 0, '', 12.7, 12.7, 15, 20, 5, 2, 'P');				
				$spdf->ignore_invalid_utf8 = true;
				$spdf->allow_charset_conversion = true;     // which is already true by default
				$spdf->charset_in = 'iso-8859-2';  // set content encoding to iso
				$spdf->SetDisplayMode('fullpage');		
				$spdf->SetProtection(array('print'));				
				$spdf->WriteHTML($htmlcontent); // write the HTML into the PDF
				//$spdf->Output($general_path.$subgroup."/".$io_number."/"."PARTIAL-".$partial_no."/LOA/".$filename.'.pdf', 'F'); // save to file because we can
				$spdf->Output($filename.'.pdf', 'I'); // view file
			break;
		}
	}
	
	function keranjang_belanja($type, $p1="", $p2=""){
		//$zona_pilihan = $this->session->userdata("zonaxtreme");
		switch($type){
			case "add":
				$id = $this->input->post('iipx');
				$id = $this->encrypt->decode($id);
				$zona = $this->input->post('zn');
				$qty = $this->input->post('yqt');
				$harga = $this->mfrontend->getdata('zona_pengiriman', 'row_array', $id, $zona);
				$data_buku = $this->db->get_where('tbl_buku', array('id'=>$id) )->row_array();
				$data_cart = $this->cart->contents();
				$flag = true;
				
				if($data_cart){
					foreach ($data_cart as $item) {
						if ($item['id'] == $id) {
							$qtyd = $item['qty'] + $qty;
							$data_update = array(
								'rowid' => $item['rowid'],
								'qty' => $qtyd,
								'price' => $harga['harga_zona_'.$zona],
							);
							echo $this->cart->update($data_update);
							$flag = false;
						}
					}
				}
				
				if($flag){
					$data_cart = array(
						'id' => $id,
						'qty' => $qty,
						'price' => $harga['harga_zona_'.$zona],
						'name' => $data_buku['judul_buku'],
						'options' => array('jml_hal' => $data_buku['jml_hal'])
					);
					echo $this->cart->insert($data_cart);
				}
			break;
			case "add_paket":
				/*
				$id = $this->input->post('iipx');
				$qty = $this->input->post('yqt');
				$zona = $this->input->post('zn');
				$data_paket = $this->mfrontend->getdata('data_paket_detail', 'result_array', $id);
				*/
				$post = array();
				foreach($_POST as $k=>$v){
					if($this->input->post($k)!=""){
						$post[$k] = $this->db->escape_str($this->input->post($k));
					}else{
						$post[$k] = null;
					}
				}
				$data_cart = $this->cart->contents();
				
				foreach($post['iid'] as $x => $i){
					$flag = true;
					if(count($data_cart) > 0){

						foreach ($data_cart as $item ) {
							if($item['id'] == $i) {
								$qtyd = $item['qty'] + $post["qty"][$x];
								$data_update = array(
									'rowid' => $item['rowid'],
									'qty' => $qtyd,
									'price' => $post["prc"][$x],
								);
								$this->cart->update($data_update);
								$flag = false;
							}
						}
						
					}	
					
					if($flag){
						$datacart = array(
							'id' =>  $i,
							'qty' => $post["qty"][$x],
							'price' => $post["prc"][$x],
							'name' => $post["jdl"][$x],
							'options' => array('jml_hal' => 0)
						);
						$this->cart->insert($datacart);
					}	
				}
								
				echo 1;
			break;
			case "update":
				$qty = $this->input->post("xqt");
				$rowid = $this->input->post("rwid");
				
				if($qty != null || $qty != 0 ){
					$data = array(
						'rowid' => $rowid,
						'qty'   => $qty
					);
					$this->cart->update($data);
				}
				
				$kontent = $this->cart->contents();
				$tot_price = 0;
				$tot_qty = 0;
				foreach($kontent as $key => $v){
					$tot_price += $v["subtotal"];
					$tot_qty += $v["qty"];
				}
				
				$array_return = array(
					"total_harga" => number_format($tot_price,0,",","."),
					"total_qty" => number_format($tot_qty,0,",","."),
				);
				echo json_encode($array_return);
			break;
			case "deletekeranjang":
				$rowid = $this->input->post("rwid");
				$this->cart->remove($rowid);
				
				$kontent = $this->cart->contents();
				$tot_price = 0;
				$tot_qty = 0;
				foreach($kontent as $key => $v){
					$tot_price += $v["subtotal"];
					$tot_qty += $v["qty"];
				}
				
				$array_return = array(
					"total_harga" => number_format($tot_price,0,",","."),
					"total_qty" => number_format($tot_qty,0,",","."),
				);
				echo json_encode($array_return);
			break;
			case "delete":
				$rowid = $this->input->post("rwid");
				echo $this->cart->remove($rowid);
			break;
			case "view":
				$kontent = $this->cart->contents();
				
				echo "<pre>";
				print_r($kontent);exit;
			break;
			case "destroy":
				$this->cart->destroy();
			break;
			
		}
	}
	
	function cruddata($type, $p1="", $p2=""){
		$post = array();
        foreach($_POST as $k=>$v){
			if($this->input->post($k)!=""){
				$post[$k] = $this->db->escape_str($this->input->post($k));
			}else{
				$post[$k] = null;
			}
		}
		
		if($type == 'session_zona'){
			//$data_zona = $this->db->get_where('cl_provinsi', array('kode_prov'=>$post['kdprv']))->row_array();
			//$sess = array();
			//$sess['zona_pilihan'] = $post['kdprv'];
			//$sess['provinsi'] = $data_zona['provinsi'];
			//$sess['kode_provinsi'] = $data_zona['kode_prov'];
			//$this->session->set_userdata("zonaxtreme", $sess);
			//echo 1;
		}else{
			if(isset($post['editstatus'])){$editstatus = $post['editstatus'];unset($post['editstatus']);}
			else $editstatus = null;
			
			echo $this->mfrontend->simpansavedata($p1, $post, $editstatus);
		}
	}
	
	function report_kementerian($type){
		$code_secret = $this->input->get('secretkey');
		if($code_secret != $this->client_secret){
			//echo "invalid code secret";exit;
		}
		
		header('Content-Type: application/json');
		switch($type){
			case "all":
				$per_page = $this->input->get('per_page');
				$page = $this->input->get('page');
				$sort = $this->input->get('sort');
				$start_date = $this->input->get('start_date');
				$end_date = $this->input->get('end_date');
				$id = $this->input->get('id');
				
				$array_parameter = array(
					'per_page' => (isset($per_page) ? $per_page : 200 ),
					'page' => (isset($page) ? $page : 1 ),
					'sort' => $sort,
					'start_date' => $start_date,
					'end_date' => $end_date,
					'id' => $id,
				);
				
				if(isset($id) || $id != null){
					$per_page = 1;
				}
				
				$array_pesanan = array();
				$data_pesanan = $this->mfrontend->get_report_kementerian('all_pesanan', 'result_array', $array_parameter);
				$count = $this->mfrontend->get_report_kementerian('count_semua_pesanan', 'row_array');
				if( $count["total"] >0 ) { 
					$limit = (isset($per_page) ? $per_page : 200);
					$total_pages = ceil($count["total"]/$limit); 
				}else{ 
					$total_pages = 0; 
				} 
				
				$array_pesanan['total'] = $count["total"];
				$array_pesanan['current_page'] = (isset($page) ? $page : 1);
				$array_pesanan['per_page'] = (isset($per_page) ? $per_page : 200);
				$array_pesanan['total_page'] = $total_pages;
				$array_pesanan['detail_paket'] = array();
				
				$no = 1;
				foreach($data_pesanan as $k=>$v){
					$detail_pesanan = $this->mfrontend->get_report_kementerian('count_detail_pesanan', 'row_array', $v['idpesan']);
					$bentuk = explode(" ", $v["nama_sekolah"]);
					
					$array_pesanan['detail_paket'][$k]['count'] = $no;
					$array_pesanan['detail_paket'][$k]['id'] = $v['idpesan'];
					$array_pesanan['detail_paket'][$k]['id_pesanan'] = $v['id_pesanan'];
					$array_pesanan['detail_paket'][$k]['sekolah_id'] = $v['sekolah_id'];
					$array_pesanan['detail_paket'][$k]['bentuk'] = $bentuk[0];
					$array_pesanan['detail_paket'][$k]['npsn'] = $v['npsn'];
					$array_pesanan['detail_paket'][$k]['nama_sekolah'] = $v['nama_sekolah'];
					$array_pesanan['detail_paket'][$k]['kd_prop'] = $v['kd_prov'];
					$array_pesanan['detail_paket'][$k]['prop'] = $v['prov'];
					$array_pesanan['detail_paket'][$k]['kd_kab_kota'] = $v['kd_kab'];
					$array_pesanan['detail_paket'][$k]['kab_kota'] = $v['kab'];
					$array_pesanan['detail_paket'][$k]['p_tgl_pesan'] = $v['tanggal_pesan'];
					$array_pesanan['detail_paket'][$k]['p_tanggal_konfirmasi'] = $v['tanggal_konfirmasi'];
					$array_pesanan['detail_paket'][$k]['p_waktu_pelaksanaan'] = $v["jasa_pengiriman"];
					$array_pesanan['detail_paket'][$k]['p_kode_buku'] = $detail_pesanan['kode_buku'];
					$array_pesanan['detail_paket'][$k]['p_jml_buku'] = $detail_pesanan['qty'];
					$array_pesanan['detail_paket'][$k]['p_harga_konfirm'] = $detail_pesanan['harga'];
					$array_pesanan['detail_paket'][$k]['p_total_harga'] = ($detail_pesanan['qty'] * $detail_pesanan['harga']);
					$array_pesanan['detail_paket'][$k]['k_tgl_kirim'] = $v['tanggal_kirim'];
					$array_pesanan['detail_paket'][$k]['k_kode_buku'] = $detail_pesanan['kode_buku'];
					$array_pesanan['detail_paket'][$k]['k_jml_buku'] = $detail_pesanan['qty'];
					$array_pesanan['detail_paket'][$k]['s_tgl_sampai'] = $v["tanggal_sampai"]; //gak tau ambil darimana
					$array_pesanan['detail_paket'][$k]['s_kode_buku'] = $detail_pesanan['kode_buku'];
					$array_pesanan['detail_paket'][$k]['s_jml_buku'] = $detail_pesanan['quantity'];
					$array_pesanan['detail_paket'][$k]['s_nama_penerima'] = ""; //gak tau ambil darimana;
					$array_pesanan['detail_paket'][$k]['t_tgl_terima'] = $v['tanggal_terima'];
					$array_pesanan['detail_paket'][$k]['t_kode_buku'] = $detail_pesanan['kode_buku'];
					$array_pesanan['detail_paket'][$k]['t_jml_buku'] = $detail_pesanan['qty'];
					$array_pesanan['detail_paket'][$k]['t_nomor_surat'] = $v['no_bast'];
					$array_pesanan['detail_paket'][$k]['t_tanggal_bast'] = $v['tanggal_bast'];
					$array_pesanan['detail_paket'][$k]['b_tgl_bayar'] = $v['tanggal_bayar'];
					$array_pesanan['detail_paket'][$k]['b_kode_buku'] = $detail_pesanan['kode_buku'];
					$array_pesanan['detail_paket'][$k]['b_jml_buku'] = $detail_pesanan['qty'];
					$array_pesanan['detail_paket'][$k]['b_jml_bayar'] = $v['total_pembayaran'];
					$array_pesanan['detail_paket'][$k]['active'] = 1;
					$array_pesanan['detail_paket'][$k]['periode'] = 1;
					$array_pesanan['detail_paket'][$k]['updated_date'] = date('Y-m-d H:i:s');
					$no++;
				}
				
				/*
				echo "<pre>";
				print_r($array_pesanan);exit;
				echo "</pre>";//*/
				
				echo json_encode($array_pesanan);
			break;
		}
	}
	
	//fungsi login pembeli
	function loginpembeli(){
		$this->load->library('encrypt');
		$user = $this->db->escape_str($this->input->post('userpmb'));
		$pass = $this->db->escape_str($this->input->post('pwdpmb'));
		$error=false;
		//echo $user;exit;
		//echo $this->encrypt->encode($pass);exit;
		if($user && $pass){
			$cek_user = $this->mfrontend->getdata('data_login_pembeli','row_array',$user);
			//wzc1y
			//print_r($cek_user);exit;
			//print_r($this->encrypt->decode($cek_user['password']));exit;
			//echo $pass." - ".$this->encrypt->decode($cek_user['password']);exit;
			
			if(count($cek_user) > 0){
				if($pass == $this->encrypt->decode($cek_user['password'])){
					$sess = array();
					if($cek_user["jenis_pembeli"] == "UMUM"){
						$sess['zona_pilihan'] = $cek_user["zona_pengiriman"];
						$sess['bentuk_pendidikan'] = $cek_user["bentuk_pendidikan"];
						$sess['provinsi'] = $cek_user['provinsi'];
					}elseif($cek_user["jenis_pembeli"] == "SEKOLAH"){
						$zona = $this->db->get_where("cl_provinsi", array("kode_prov"=>$cek_user["cl_provinsi_kode"]))->row_array();
						$cek_user['zona_pengiriman'] = $zona["zona"];
						$sess['bentuk_pendidikan'] = $cek_user["bentuk_pendidikan"];						
						$sess['zona_pilihan'] = $zona["zona"];
						$sess['provinsi'] = $zona['provinsi'];
					}
					
					//$sess['kode_provinsi'] = $data_zona['kode_prov'];
					$this->session->set_userdata("zonaxtreme", $sess);
					$this->session->set_userdata('mkspembeli', base64_encode(serialize($cek_user)));
					//header("Location: ".$this->host);
					echo 1;
				}else{
					//$error=true;
					//$this->session->set_flashdata('error', 'Password Tidak Benar');
					echo "<font color='red'>Password Tidak Benar</font>";
				}
			}else{
				$error=true;
				//$this->session->set_flashdata('error', 'User Tidak Terdaftar');
				echo "<font color='red'>User Tidak Terdaftar</font>";
			}
		}else{
			$error=true;
			//$this->session->set_flashdata('error', 'Isi User Dan Password');
			echo "<font color='red'>Username & Password Tidak Boleh Kosong</font>";
		}
	}
	function logoutpembeli(){
		$log = $this->db->update('tbl_registrasi', array('last_login'=>date('Y-m-d H:i:s')), array('nama_user'=>$this->auth['nama_user']) );
		if($log){
			$this->session->unset_userdata('aldeaz_pembeli', 'limit');
			$this->session->sess_destroy();
			header("Location: ".$this->host);
		}
	}	
	
	function forgot_password(){
		$cek_data = $this->db->get_where('tbl_registrasi', array('email'=>$this->input->post('mailerEd'), 'jenis_pembeli' => 'UMUM') )->row_array();
		if(!$cek_data){
			echo 2;
			exit;
		}
		
		$cek_data['password'] = $this->encrypt->decode($cek_data['password']);
		$kirim_email = $this->lib->kirimemail('email_lupa_password', $this->input->post('mailerEd'), $cek_data);
		if($kirim_email){
			echo 1;
		}
		

	}
	
	function tester(){			
		//echo "<pre>";
		//print_r($this->auth);
		//print_r($this->cart->contents());
		
		//$this->cart->destroy();	
		
		//echo date('l');
		
		//$dt = strtotime('06/19/2009');
		//$day = date("l", $dt);
		//echo $day;
		//echo (int)date('Y');
	}
	
	function test(){			
		/*
		$data_registrasi = $this->db->get_where("tbl_registrasi", array("jenis_pembeli"=>"sekolah"))->result_array();
		foreach($data_registrasi as $k => $v){
			$bentuk = explode(" ", $v["nama_sekolah"]);
			$bentuknya = substr($bentuk[0], 0, 3);
			
			if($bentuknya == "SDN"){
				$fixnya = "SD";
			}else{
				$fixnya = $bentuknya; 
			}
			
			$this->db->update("tbl_registrasi", array("bentuk_pendidikan"=>$fixnya), array("id"=>$v["id"]) );
		}
		
		//echo "<pre>";
		//print_r($arraycek);exit;
		
		/*
		$curl = curl_init();
		$url = 'http://www.mks-store.id/api/detail_paket?secretkey=7c222fb2927d828af22f592134e8932480637c0d--spesifik';
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		$result_obj = json_decode($result, true);
		
		echo "<pre>";
		print_r($result_obj);exit;
		
		//$this->session->sess_destroy();
		//$sess = $this->session->userdata("zonaxtreme");
		//echo $sess['zona_pilihan'];
		//$this->session->sess_destroy();
		/*
		$data_cart = $this->cart->contents();
		$array_batch = array();
		foreach($data_cart as $s => $r){
			$array_insert = array(
				'tbl_h_pemesanan_id' => 1,
				'tbl_buku_id' => $r['id'],
				'harga' => $r['price'],
				'qty' => $r['qty'],
				'subtotal' => $r['subtotal'],
				'create_date' => date('Y-m-d H:i:s'),
			);
			array_push($array_batch, $array_insert);	
			
			$data_cart[$s]['price'] = number_format($r['price'],0,",",".");
			$data_cart[$s]['subtotal'] = number_format($r['subtotal'],0,",",".");
		}
				
		$array_email = array(
			'pemesan' => "SD XXXX",
			'no_order' => "ORD-87978SSS",
			'tot' => "999",
			'pajak' => "888",
			'grand_total' => "777",
		);
		
		$this->nsmarty->assign('data_cart', $data_cart);
		$this->nsmarty->assign('penunjang', $array_email);
		$this->nsmarty->display('frontend/modul/email_invoice.html');
		
		
		$data_cart = $this->cart->contents();
		$array_email = array(
			'pemesan' => "SD XXXX",
			'no_order' => "ORD-87978SSS",
			'tot' => "700.000.000",
			'pajak' => "70.000.000",
			'grand_total' => "770.000.000",
		);
		foreach($data_cart as $s => $r){
			$data_cart[$s]['price'] = number_format($r['price'],0,",",".");
			$data_cart[$s]['subtotal'] = number_format($r['subtotal'],0,",",".");
		}
		
		
		
		$data_registrasi = array(
						'jenis_pembeli' => 'UMUM',
						'nama_user' => "asu",
						'password' => "asu",
						'email' => "asu",
						'status' => 1,
						'nama_lengkap' => "asu",
						'no_telp_customer' => "asu",
						'no_hp_customer' => "asu",
						'cl_provinsi_kode' => "asu",
						'cl_kab_kota_kode' => "asu",
						'cl_kecamatan_kode' => "asu",
						'kode_pos' => "asu",						
						'alamat_pengiriman' => "asu",
						'reg_date' => date('Y-m-d H:i:s'),
					);	
		
		echo $this->lib->kirimemail('email_konfirmasi', "triwahyunugroho11@gmail.com", "12345");
		
		//echo $this->lib->kirimemail('email_test', "triwahyunugroho11@gmail.com");
		
		//*/
	}
	
}
