<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

/*
	Models Buatan Wahyu Jinggomen
*/

class Mfrontend extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->auth = unserialize(base64_decode($this->session->userdata('mkspembeli')));
	}
	
	function getdata($type="", $balikan="", $p1="", $p2="",$p3="",$p4=""){
		$where = " WHERE 1=1 ";
		$join = "";
		$select = "";
		$order = "";
		
		switch($type){
			case "data_login_pembeli":
				$sql = "
					SELECT A.*, B.provinsi, C.kab_kota, D.kecamatan, B.zona as zona_pengiriman
					FROM tbl_registrasi A
					LEFT JOIN cl_provinsi_indo B ON B.kode_prov = A.cl_provinsi_kode
					LEFT JOIN cl_kab_kota_indo C ON C.kode_kab_kota = A.cl_kab_kota_kode
					LEFT JOIN cl_kecamatan_indo D ON D.kode_kecamatan = A.cl_kecamatan_kode
					WHERE A.nama_user = '".$p1."'
				";
			break;
			case "data_pesanan_user":
				$sql = "
					SELECT A.*, DATE_FORMAT(A.tgl_order,'%d %b %y') as tgl_orderr
					FROM tbl_h_pemesanan A
					WHERE A.tbl_registrasi_id = '".$this->auth["id"]."'
					ORDER BY A.tgl_order DESC
				";
			break;
			case "data_buku_paket":
				$crfilter = $this->input->post('cr');
				$order = "
					ORDER BY A.id ASC
					LIMIT $p1, $p2
				";
				
				if($crfilter){
					$where .= " AND A.nama_paket like '%".$crfilter."%' ";
				}
				
				$sql = "
					SELECT A.*
					FROM tbl_paket A
					$where AND A.flag_disable_enable IS NULL 
					$order
				";
			break;
			case 'hitung_data_filter_paket':
				$crfilter = $this->input->post('cr');
				
				if($crfilter){
					$where .= " AND A.nama_paket like '%".$crfilter."%' ";
				}
				
				$sql = "
					SELECT A.*
					FROM tbl_paket A
					$where AND A.flag_disable_enable IS NULL 
				";
				
				$query = $this->db->query($sql);
				return $query->num_rows();
				exit;
			break;
			case "data_paket_detail":
				$sql = "
					SELECT B.*
					FROM tbl_paket_mapping A
					LEFT JOIN tbl_buku B ON B.id = A.tbl_buku_id
					$where AND A.tbl_paket_id = '".$p1."'
				";
			break;
			
			case "data_buku":
			case "data_buku_tingkatan":
			case "data_buku_detail":
				if($type == 'data_buku'){
					$select .= " ,E.foto_buku"; 
					$where .= "";
					$join .= " LEFT JOIN (SELECT tbl_buku_id, foto_buku FROM tbl_foto_buku GROUP BY tbl_buku_id) E ON E.tbl_buku_id = A.id ";					
					$typefilter = $this->input->post('ty');
					$idfilter = $this->input->post('isd');
					$crfilter = $this->input->post('cr');
					
					if($typefilter){
						if($typefilter == 'tingk'){
							$where .= " AND A.cl_kelas_id = ".$idfilter." ";
						}elseif($typefilter == 'kate'){
							$where .= " AND A.cl_kategori_id = ".$idfilter." ";
						}elseif($typefilter == 'grp'){
							$where .= " AND A.cl_group_sekolah = ".$idfilter." ";
						}
					}
					
					if($crfilter){
						$where .= " AND A.judul_buku like '%".$crfilter."%' ";
					}
					
					$order = "
						ORDER BY A.id ASC
						LIMIT $p2, $p3
					";
				}elseif($type == 'data_buku_tingkatan'){
					$where .= " AND C.id = '".$p1."' ";
				}elseif($type == 'data_buku_detail'){
					$where .= " AND A.id = '".$p1."' ";
				}
				
				$sql = "
					SELECT A.*, D.nama_kategori, C.tingkatan, B.kelas, F.nama_group, G.nama_edisi 
						$select
					FROM tbl_buku A
					LEFT JOIN cl_kelas B ON B.id = A.cl_kelas_id
					LEFT JOIN cl_tingkatan C ON C.id = B.cl_tingkatan_id
					LEFT JOIN cl_kategori D ON D.id = A.cl_kategori_id
					LEFT JOIN cl_group_sekolah F ON F.id = A.cl_group_sekolah
					LEFT JOIN cl_edisi G ON G.id = A.cl_edisi_id
					$join
					$where AND A.flag_disable_enable IS NULL 
					$order
				";
				
				//echo $sql;exit;
			break;
			case 'hitung_data_filter':
				$typefilter = $this->input->post('ty');
				$idfilter = $this->input->post('isd');
				$crfilter = $this->input->post('cr');
				
				if($typefilter){
					if($typefilter == 'tingk'){
						$where .= " AND A.cl_kelas_id = ".$idfilter." ";
					}elseif($typefilter == 'kate'){
						$where .= " AND A.cl_kategori_id = ".$idfilter." ";
					}elseif($typefilter == 'grp'){
						$where .= " AND A.cl_group_sekolah = ".$idfilter." ";
					}
				}
				
				if($crfilter){
					$where .= " AND A.judul_buku like '%".$crfilter."%' ";
				}
				
				$sql = "
					SELECT A.*
					FROM tbl_buku A
					$where AND A.flag_disable_enable IS NULL 
				";
				
				$query = $this->db->query($sql);
				return $query->num_rows();
				exit;
			break;
			case "cl_tingkatan":
				$sql = "
					SELECT id, tingkatan
					FROM cl_tingkatan
				";
			break;
			case "zona_pengiriman":
				$sql = "
					SELECT harga_zona_".$p2."
					FROM tbl_buku
					WHERE id = '".$p1."'
				";
			break;
			case "header_pesanan":
				$sql = "
					SELECT A.*, A.id as idpesan, A.status as sts_pesan,
						B.jasa_pengiriman, C.metode_pembayaran, D.*, E.provinsi, F.kab_kota, G.kecamatan
					FROM tbl_h_pemesanan A
					LEFT JOIN cl_jasa_pengiriman B ON B.id = A.cl_jasa_pengiriman_id
					LEFT JOIN cl_metode_pembayaran C ON C.id = A.cl_metode_pembayaran_id
					LEFT JOIN tbl_registrasi D ON D.id = A.tbl_registrasi_id
					LEFT JOIN cl_provinsi E ON E.kode_prov = D.cl_provinsi_kode
					LEFT JOIN cl_kab_kota F ON F.kode_kab_kota = D.cl_kab_kota_kode
					LEFT JOIN cl_kecamatan G ON G.kode_kecamatan = D.cl_kecamatan_kode					
					WHERE A.no_order = '".$p1."'
				";
			break;
			case "detail_pesanan":
				$sql = "
					SELECT A.*,
						B.judul_buku, C.kelas, D.nama_group
					FROM tbl_d_pemesanan A
					LEFT JOIN tbl_buku B ON B.id = A.tbl_buku_id
					LEFT JOIN cl_kelas C ON C.id = B.cl_kelas_id
					LEFT JOIN cl_group_sekolah D ON D.id = B.cl_group_sekolah
					WHERE A.tbl_h_pemesanan_id = '".$p1."'
				";
			break;
			case "getemail_cust":
				$sql = "
					SELECT B.email
					FROM tbl_h_pemesanan A 
					LEFT JOIN tbl_registrasi B ON B.id = A.tbl_registrasi_id
					WHERE A.no_order = '".$p1."'
				";
			break;
			
			case "tracking_pesanan":
				$sql = "
					SELECT CASE A.status 
							WHEN 'P' THEN 'BELUM DIBAYAR'
							WHEN 'F' THEN 'SUDAH DIBAYAR'
							END
						as status_pembayaran,
						CASE B.flag 
							WHEN 'P' THEN 'MENUNGGU VERIFIKASI'
							WHEN 'F' THEN 'PEMBAYARAN TERKONFIRMASI'
							END
						as status_konfirmasi,
						CASE C.status 
							WHEN 'PK' THEN 'PROSES PACKING'
							WHEN 'F' THEN 'DALAM PENGIRIMAN'
							END
						as status_tracking,
						C.no_resi
					FROM tbl_h_pemesanan A
					LEFT JOIN  tbl_konfirmasi B ON B.tbl_h_pemesanan_id = A.id 
					LEFT JOIN  tbl_tracking_pengiriman C ON C.tbl_h_pemesanan_id = A.id 
					WHERE A.no_order = '".$p1."'
				";
			break;
			
			case "riwayat_pesanan":
				$sql = "
					SELECT A.*, 
						CASE A.status 
							WHEN 'P' THEN 'BELUM DIBAYAR'
							WHEN 'F' THEN 'SUDAH DIBAYAR'
							END
						as status_pembayaran,
						CASE B.flag 
							WHEN 'P' THEN 'MENUNGGU VERIFIKASI'
							WHEN 'F' THEN 'PEMBAYARAN TERKONFIRMASI'
							END
						as status_konfirmasi,
						CASE C.status 
							WHEN 'P' THEN 'PROSES PACKING'
							WHEN 'F' THEN 'DALAM PENGIRIMAN'
							END
						as status_tracking,
						C.no_resi
					FROM tbl_h_pemesanan A
					LEFT JOIN tbl_konfirmasi B ON B.tbl_h_pemesanan_id = A.id 
					LEFT JOIN tbl_tracking_pengiriman C ON C.tbl_h_pemesanan_id = A.id 
					WHERE A.tbl_registrasi_id = '".$p1."'
				";
			break;
			case "datacustomer":
				if($p3 == 'riwayat'){
					if($p2 == 'SEKOLAH'){
						$where = " AND npsn = '".$p1."' ";
					}else{
						$where = " AND email = '".$p1."' ";
					}
					
					$where .= " AND jenis_pembeli = '".$p2."' ";
				}elseif($p3 == 'cetak_bast'){
					$where = " AND A.id = '".$p1."' ";
				}
				$sql = "
					SELECT A.*,
						B.provinsi, C.kab_kota, D.kecamatan
					FROM tbl_registrasi A
					LEFT JOIN cl_provinsi B ON B.kode_prov = A.cl_provinsi_kode
					LEFT JOIN cl_kab_kota C ON C.kode_kab_kota = A.cl_kab_kota_kode
					LEFT JOIN cl_kecamatan D ON D.kode_kecamatan = A.cl_kecamatan_kode
					WHERE 1=1 $where
				";
			break;
		}
		
		if($balikan == 'json'){
			return $this->lib->json_grid($sql);
		}elseif($balikan == 'row_array'){
			return $this->db->query($sql)->row_array();
		}elseif($balikan == 'result'){
			return $this->db->query($sql)->result();
		}elseif($balikan == 'result_array'){
			return $this->db->query($sql)->result_array();
		}
		
	}
	
	function get_combo($type="", $p1="", $p2=""){
		switch($type){
			case "cl_provinsi":
				$sql = "
					SELECT kode_prov as id, provinsi as txt
					FROM cl_provinsi
				";
			break;
			case "cl_kab_kota":
				$sql = "
					SELECT kode_kab_kota as id, kab_kota as txt
					FROM cl_kab_kota
					WHERE cl_provinsi_kode = '".$p2."'
				";
			break;
			case "cl_kecamatan":
				$sql = "
					SELECT kode_kecamatan as id, kecamatan as txt
					FROM cl_kecamatan
					WHERE cl_kab_kota_kode = '".$p2."'
				";
			break;
			case "cl_provinsi_old":
				$sql = "
					SELECT kode_prov as id, provinsi as txt
					FROM cl_provinsi_indo
				";
			break;
			case "cl_kab_kota_old":
				$sql = "
					SELECT kode_kab_kota as id, kab_kota as txt
					FROM cl_kab_kota_indo
					WHERE cl_provinsi_kode = '".$p2."'
				";
			break;
			case "cl_kecamatan_old":
				$sql = "
					SELECT kode_kecamatan as id, kecamatan as txt
					FROM cl_kecamatan_indo
					WHERE cl_kab_kota_kode = '".$p2."'
				";
			break;
			case "cl_jasa_pengiriman":
				$sql = "
					SELECT id, jasa_pengiriman as txt
					FROM cl_jasa_pengiriman
				";
			break;
			case "cl_metode_pembayaran":
				$sql = "
					SELECT id, metode_pembayaran as txt
					FROM cl_metode_pembayaran
				";
			break;
			case "cl_zona":
				$sql = "
					SELECT zona_code as id, nama_zona as txt
					FROM cl_zona
				";
			break;
			
		}
		
		return $this->db->query($sql)->result_array();
	}
	
	function get_report_kementerian($type, $balikan, $p1=""){
		switch($type){
			case "all_pesanan":
				$sql = "
					SELECT A.id,
						D.npsn, D.nama_sekolah, D.alamat_pengiriman, D.provinsi, D.kab_kota, D.no_hp_kepsek, D.email,
						DATE_FORMAT(A.create_date,'%d %b %y %T') as tanggal_pesan, A.grand_total,
						DATE_FORMAT(E.create_date,'%d %b %y %T') as tanggal_kirim,
						DATE_FORMAT(F.tanggal_terima,'%d %b %y') as tanggal_terima, D.nama_kepala_sekolah, D.no_hp_kepsek, F.jam_terima,
						DATE_FORMAT(G.tanggal_transfer,'%d %b %y') as tanggal_bayar, G.total_pembayaran, G.no_bast, G.no_kwitansi,
						C.metode_pembayaran
					FROM tbl_h_pemesanan A
					LEFT JOIN cl_metode_pembayaran C ON C.id = A.cl_metode_pembayaran_id
					LEFT JOIN (
						SELECT X.*, PR.provinsi, KB.kab_kota, KC.kecamatan
						FROM tbl_registrasi X
						LEFT JOIN cl_provinsi PR ON PR.kode_prov = X.cl_provinsi_kode
						LEFT JOIN cl_kab_kota KB ON KB.kode_kab_kota = X.cl_kab_kota_kode
						LEFT JOIN cl_kecamatan KC ON KC.kode_kecamatan = X.cl_kecamatan_kode
						WHERE X.jenis_pembeli = 'SEKOLAH'
					) as D ON D.id = A.tbl_registrasi_id
					LEFT JOIN tbl_tracking_pengiriman E ON E.tbl_h_pemesanan_id = A.id
					LEFT JOIN tbl_terima_barang F ON F.tbl_h_pemesanan_id = A.id
					LEFT JOIN (
						SELECT Y.id, Y.tbl_h_pemesanan_id, Y.tanggal_transfer, Y.total_pembayaran, OY.no_bast, YE.no_kwitansi
						FROM tbl_konfirmasi Y
						LEFT JOIN tbl_bast OY ON OY.tbl_konfirmasi_id = Y.id
						LEFT JOIN tbl_kwitansi YE ON YE.tbl_konfirmasi_id = Y.id
					) as G ON G.tbl_h_pemesanan_id = A.id
					ORDER BY D.provinsi, D.kab_kota DESC
				";
			break;
			case "count_detail_pesanan":
				$sql = "
					SELECT SUM(qty) as quantity
					FROM tbl_d_pemesanan
					WHERE tbl_h_pemesanan_id = '".$p1."'
				";
			break;
		}
		
		if($balikan == 'json'){
			return $this->lib->json_grid($sql);
		}elseif($balikan == 'row_array'){
			return $this->db->query($sql)->row_array();
		}elseif($balikan == 'result'){
			return $this->db->query($sql)->result();
		}elseif($balikan == 'result_array'){
			return $this->db->query($sql)->result_array();
		}
	}
	
	function simpansavedata($table,$data,$sts_crud){  //$sts_crud --> STATUS NYEE INSERT, UPDATE, DELETE
		$this->db->trans_begin();
		if(isset($data['id'])){
			$id = $data['id'];
			unset($data['id']);
		}
		
		switch($table){
			case "uploadfile":
				$array_cek_invoice = array( 'no_order' => $data['inv'] );
				$cek_invoice = $this->db->get_where('tbl_h_pemesanan', $array_cek_invoice)->row_array();
				if($cek_invoice){
					$tbl_h_pemesanan_id = $cek_invoice['id'];
				}else{
					$tbl_h_pemesanan_id = null;
					echo 2; exit; //cek data invoice exist;
				}
				
				$cek_dir = "__repository/bast_tandaterima/";
				if(!is_dir($cek_dir)) {
					mkdir($cek_dir, 0777);         
				}			
				
				$upload_path = "./__repository/bast_tandaterima/";
				if(!empty($_FILES['bast']['name'])){					
					$file_bast = "BAST-".$cek_invoice['no_order'];
					$filename_bast =  $this->lib->uploadnong($upload_path, 'bast', $file_bast); //$file.'.'.$extension;
				}else{
					$filename_bast = null;
				}
				
				if(!empty($_FILES['tanda_terima']['name'])){					
					$file_tandaterima = "TANDATERIMA-".$cek_invoice['no_order'];
					$filename_tandaterima =  $this->lib->uploadnong($upload_path, 'tanda_terima', $file_tandaterima); //$file.'.'.$extension;
				}else{
					$filename_tandaterima = null;
				}
				
				$data_upload = array(
					'tbl_h_pemesanan_id' => $cek_invoice['id'],
					'file_bast' => $filename_bast,
					'file_tanda_terima' => $filename_tandaterima,
					'create_date' => date('Y-m-d H:i:s'),
				);
				$this->db->insert('tbl_uploadfile', $data_upload);
			break;
			case "registrasi":
				$this->load->library('encrypt');
				$array_cek_registrasi1 = array( 'email' => $data['email']);
				$cek_registrasi1 = $this->db->get_where('tbl_registrasi', $array_cek_registrasi1)->row_array();
				if($cek_registrasi1){
					echo 2; exit; //cek data email exist;
				}
				
				if($data["domtype"] == "S"){
					$array_cek_registrasi2 = array( 'npsn' => $data['npsn'] );
					$cek_registrasi2 = $this->db->get_where('tbl_registrasi', $array_cek_registrasi2)->row_array();
					if($cek_registrasi2){
						echo 3; exit; //cek data npsn exist;
					}
				}
				
				$password_asli = strtolower($this->lib->randomString(5, 'angkahuruf'));
				$password = $this->encrypt->encode($password_asli);
				
				if($data["domtype"] == "S"){
					$data_registrasi = array(
						'jenis_pembeli' => 'SEKOLAH',
						'nama_user' => $data['npsn'],
						'password' => $password,
						'email' => $data['email'],
						'status' => 1,
						'npsn' => $data['npsn'],
						'nama_sekolah' => $data['nm_seko'],
						'nip' => $data['nip_kepsek'],
						'nama_kepala_sekolah' => $data['kpl_sekolah'],
						'nama_bendahara' => $data['nmbend'],
						'no_hp_kepsek' => $data['no_hp_kepsek'],
						'no_hp_bendahara' => $data['no_hp_bendahara'],
						'no_telp_sekolah' => $data['telp_sekolah'],
						'cl_provinsi_kode' => $data['provinsi'],
						'cl_kab_kota_kode' => $data['kabupaten'],
						'cl_kecamatan_kode' => $data['kecamatan'],
						'kode_pos' => $data['kd_pos'],
						'alamat_pengiriman' => $data['alamat_pengiriman'],
						'reg_date' => date('Y-m-d H:i:s'),
					);					
				}elseif($data["domtype"] == "U"){
					$data_registrasi = array(
						'jenis_pembeli' => 'UMUM',
						'nama_user' => $data['email'],
						'password' => $password,
						'email' => $data['email'],
						'status' => 1,
						'nama_lengkap' => $data['nm_lengkap'],
						'no_telp_customer' => $data['no_telp'],
						'no_hp_customer' => $data['no_handphone'],
						'cl_provinsi_kode' => $data['provinsi'],
						'cl_kab_kota_kode' => $data['kabupaten'],
						'cl_kecamatan_kode' => $data['kecamatan'],
						'kode_pos' => $data['kd_pos'],						
						'alamat_pengiriman' => $data['alamat_pengiriman'],
						'reg_date' => date('Y-m-d H:i:s'),
					);					
				}
				
				$insert_registrasi = $this->db->insert('tbl_registrasi', $data_registrasi);
				if($insert_registrasi){
					$this->lib->kirimemail('email_registrasi', $data['email'], $data_registrasi, $password_asli, $data["domtype"]);
				}
			break;
			case "komentar":
				$array_cek_registrasi = array( 'email' => $data['emsek'], 'npsn' => $data['npsn']);
				$cek_registrasi = $this->db->get_where('tbl_registrasi', $array_cek_registrasi)->row_array();
				if($cek_registrasi){
					$tbl_registrasi_id = $cek_registrasi['id'];
				}else{
					$tbl_registrasi_id = null;
					echo 2; exit; //cek data email exist;			
				}
				
				$array_cek_invoice = array( 'no_order' => $data['inv'] );
				$cek_invoice = $this->db->get_where('tbl_h_pemesanan', $array_cek_invoice)->row_array();
				if($cek_invoice){
					$tbl_h_pemesanan_id = $cek_invoice['id'];
				}else{
					$tbl_h_pemesanan_id = null;
					echo 3; exit; //cek data invoice exist;
				}
				
				$data_komentar = array(
					'tbl_registrasi_id' => $tbl_registrasi_id,
					'tbl_h_pemesanan_id' => $tbl_h_pemesanan_id,
					'rating' => $data['rating'],
					'komentar' => $data['kmntr'],
					'create_date' => date('Y-m-d H:i:s'),
				);
				$insert_registrasi = $this->db->insert('tbl_komentar', $data_komentar);
			break;
			case "checkout":				
				$kdmarketing = null;
				/*
				if(isset($data['kdmar'])){
					$dbmarketing = $this->load->database('marketing',true);
					$array_cek_kdmarketing = array( 'member_user' => $data['kdmar'] );
					$cek_kdmarketing = $dbmarketing->get_where('tbl_member', $array_cek_kdmarketing)->row_array();
					if(!$cek_kdmarketing){
						$array = array("msg"=>2);
						//echo 2; exit;
						echo json_encode($array);
						exit;
					}
					$kdmarketing = $data['kdmar'];
				}else{
					$kdmarketing = null;
				}
				*/
				
				$acak_no_order = "";
				
				if($this->auth["jenis_pembeli"] == "UMUM"){
					$email = $this->auth['email'];
					$nama_pemesan = $this->auth["nama_lengkap"];
					$flag = "P";
				}elseif($this->auth["jenis_pembeli"] == "SEKOLAH"){	
					$flag = "B";
					$nama_pemesan = $this->auth["nama_sekolah"];
					$email = $this->auth['email'];
				}
				
				$data_cart = $this->cart->contents();
				$zona_pilihan = $this->session->userdata("zonaxtreme");
				if($data_cart){
					$tot = 0;
					foreach($data_cart as $k => $v){
						$tot += $v['subtotal'];
					}
					
					$sql_maxord = "
						SELECT no_order as ordered_no
						FROM tbl_h_pemesanan
						WHERE id = (select max(id) from tbl_h_pemesanan)			
					";
					$maxord = $this->db->query($sql_maxord)->row_array();
					if($maxord['ordered_no'] != null){
						$order_urut 	= explode("-", $maxord['ordered_no']);
						$order_urutnya 	= ( $order_urut[1] + 1 ); 
						$acak_string 	= strtoupper($this->lib->randomString(4,"angkahuruf"));
						$acak_no_order 	= "ORD".$acak_string."-".$order_urutnya;
					}else{
						$order_urutnya = 100000;
						$acak_string 	= strtoupper($this->lib->randomString(4,"angkahuruf"));
						$acak_no_order 	= "ORD".$acak_string."-".$order_urutnya;
					}
					
					$data_header_pesanan = array(
						'no_order' => $acak_no_order,
						'tgl_order' => date('Y-m-d H:i:s'),
						'cl_jasa_pengiriman_id' => $data['jasa_pengiriman'],
						'cl_metode_pembayaran_id' => $data['metode_pembayaran'],
						'tbl_registrasi_id' => $this->auth["id"],
						'sub_total' => $tot,
						'grand_total' => $tot,
						'status' => $flag,
						'create_date' => date('Y-m-d H:i:s'),
						'zona' => $zona_pilihan['zona_pilihan'],
						'kode_marketing' => $kdmarketing,
						'alamat_pengiriman_registrasi' => $data['alamat_pengiriman'],
						'alamat_pengiriman_lain' =>$data['alamat_pengiriman_lain']
					);
					$insert_header = $this->db->insert('tbl_h_pemesanan', $data_header_pesanan);
					if($insert_header){
						$cekdt_header = $this->db->get_where('tbl_h_pemesanan', array('no_order'=>$acak_no_order) )->row_array();
						$array_batch = array();
						foreach($data_cart as $s => $r){
							$array_insert = array(
								'tbl_h_pemesanan_id' => $cekdt_header['id'],
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
						$this->db->insert_batch('tbl_d_pemesanan', $array_batch);
						
						if($this->auth["jenis_pembeli"] == "SEKOLAH"){
							$sql_maxkonf = "
								SELECT MAX(no_konfirmasi) as konfirmasi_no
								FROM tbl_konfirmasi
							";
							$maxkonf = $this->db->query($sql_maxkonf)->row_array();
							if($maxkonf['konfirmasi_no'] != null){
								$acak_no_konf = ($maxkonf['konfirmasi_no'] + 1); 
							}else{
								$acak_no_konf = 1;
							}
							$array_konfirmasi = array(
								'tbl_h_pemesanan_id' => $cekdt_header['id'],
								'no_konfirmasi' => $acak_no_konf,
								'flag' => "B"
							);
							$this->db->insert('tbl_konfirmasi', $array_konfirmasi);
						}
						
						$array_email = array(
							'pemesan' => $nama_pemesan,
							'no_order' => $acak_no_order,
							'tot' => number_format($tot,0,",","."),
							'pajak' => 0, //number_format($pajak,0,",","."),
							'grand_total' => number_format($tot,0,",","."),
						);
						$this->lib->kirimemail('email_invoice', $email, $data_cart, $array_email);
						
						$this->cart->destroy();
					}
					
				}
				
			break;
			case "konf":
				$sts_crud = 'falseto';
				$data_inv = $this->db->get_where('tbl_h_pemesanan', array('no_order'=>$data['inv']) )->row_array();
				if($data_inv){
					$sql_maxkonf = "
						SELECT MAX(no_konfirmasi) as konfirmasi_no
						FROM tbl_konfirmasi
					";
					$maxkonf = $this->db->query($sql_maxkonf)->row_array();
					if($maxkonf['konfirmasi_no'] != null){
						$acak_no_konf = ($maxkonf['konfirmasi_no'] + 1); 
					}else{
						$acak_no_konf = 1;
					}
					
					$cek_dir = "__repository/konfirmasi/";
					if(!is_dir($cek_dir)) {
						mkdir($cek_dir, 0777);         
					}			
					
					if(!empty($_FILES['bkt_byr']['name'])){					
						$upload_path = "./__repository/konfirmasi/";
						$file = "FILEBUKTIBAYAR-".$data_inv['no_order'];
						$filename =  $this->lib->uploadnong($upload_path, 'bkt_byr', $file); //$file.'.'.$extension;
					}else{
						$filename = null;
					}
					
					$total_pembayaran = trim($data['jml_trf']);
					$total_pembayaran = str_replace(".", "", $total_pembayaran);
					if($data_inv['status'] == 'P'){ // konfirmasi untuk pembeli umum
						$array_insert = array(
							'no_konfirmasi' => $acak_no_konf,
							'tgl_konfirmasi' => date('Y-m-d'),
							'tbl_h_pemesanan_id' => $data_inv['id'],
							'total_pembayaran' => (int)$total_pembayaran,
							'nama_bank_pengirim' => $data['bank_pengirim'],
							'atas_nama_pengirim' => $data['atas_nama'],
							'tanggal_transfer' => $data['tgl_trf'],
							'nama_bank_penerima' => $data['bank_tujuan'],
							'flag' => 'P',
							'create_date' => date('Y-m-d H:i:s'),
							'file_bukti_bayar' => $filename,
						);
						$crud = $this->db->insert('tbl_konfirmasi', $array_insert);
						if($crud){
							$this->db->update('tbl_h_pemesanan', array('status'=>'F'), array('no_order'=>$data['inv']) );
							$email = $this->getdata('getemail_cust', 'row_array', $data['inv']);
							$this->lib->kirimemail('email_konfirmasi', $email['email'], $data['inv']);
						}

					}elseif($data_inv['status'] == 'B'){ // konfirmasi untuk pembeli sekolah
						$array_update = array(
							'no_konfirmasi' => $acak_no_konf,
							'tgl_konfirmasi' => date('Y-m-d'),
							'total_pembayaran' => (int)$total_pembayaran,
							'nama_bank_pengirim' => $data['bank_pengirim'],
							'atas_nama_pengirim' => $data['atas_nama'],
							'tanggal_transfer' => $data['tgl_trf'],
							'nama_bank_penerima' => $data['bank_tujuan'],
							'flag' => 'F',
							'create_date' => date('Y-m-d H:i:s'),
							'file_bukti_bayar' => $filename,
						);
						$crud = $this->db->update('tbl_konfirmasi', $array_update, array('tbl_h_pemesanan_id' => $data_inv['id']) );
						if($crud){
							$this->db->update('tbl_h_pemesanan', array('status'=>'F'), array('no_order'=>$data['inv']) );
							$email = $this->getdata('getemail_cust', 'row_array', $data['inv']);
							$this->lib->kirimemail('email_konfirmasi', $email['email'], $data['inv']);
						}
						
					}
					
				}
			break;
			case "komp":
				$table = 'tbl_komplain';
				$sts_crud = 'add';
				$datainv = $this->db->get_where('tbl_h_pemesanan', array('no_order'=>$data['noinv']) )->row_array();
				
				$data['tbl_h_pemesanan_id'] = $datainv['id'];
				$data['no_komplain'] = "KMP-".strtoupper($this->lib->randomString(4,"angkahuruf"));
				$data['komplain'] = $data['komp'];
				
				$cek_dir = "__repository/komplain/";
				if(!is_dir($cek_dir)) {
					mkdir($cek_dir, 0777);         
				}			
				
				if(!empty($_FILES['filepend']['name'])){					
					$upload_path = "./__repository/komplain/";
					$file = "FILEKOMPLAIN-".$data['no_komplain'];
					$filename =  $this->lib->uploadnong($upload_path, 'filepend', $file); //$file.'.'.$extension;
					$data['file_foto'] = $filename;
				}
				$data['create_date'] = date("Y-m-d H:i:s");
				
				unset($data['komp']);
				unset($data['noinv']);
			break;
			case "pembss":
				$cek_data = $this->db->get_where('tbl_h_pemesanan', array('no_order'=>$data['inv'], 'kode_pembatalan'=>$data['kdpemb']) )->row_array();
				if($cek_data){
					$arraypemb = array(
						"status" => 'C',
						'tanggal_pembatalan' => date('Y-m-d H:i:s'),
					);
					$this->db->update('tbl_h_pemesanan', $arraypemb, array('no_order'=>$data['inv']) );
				}else{
					return 0;
					exit;
				}
			break;
		}
		
		switch ($sts_crud){
			case "add":
				$this->db->insert($table,$data);
			break;
			case "edit":
				$this->db->update($table, $data, array('id' => $id) );
			break;
			case "delete":
				$this->db->delete($table, array('id' => $id));
			break;
		}
		
		if($this->db->trans_status() == false){
			if($table == 'checkout'){
				$this->db->trans_rollback();
				$array = array("msg"=>0);
				return json_encode($array);
			}else{
				$this->db->trans_rollback();
				return 0;
			}
		} else{
			if($table == 'checkout'){
				$this->db->trans_commit();
				$array = array("msg"=>1, "no_order"=>$acak_no_order);
				return json_encode($array);
			}else{
				return $this->db->trans_commit();
			}
		}
	
	}
	
}
