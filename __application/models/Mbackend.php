<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Mbackend extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->auth = unserialize(base64_decode($this->session->userdata('4ld33334zzzzzzt')));
		$this->db_remote='';
	}
	
	function getdata($type="", $balikan="", $p1="", $p2="",$p3="",$p4=""){
		$where = " WHERE 1=1 ";
		if($this->input->post('key')){
				$where .=" AND ".$this->input->post('kat')." like '%".$this->db->escape_str($this->input->post('key'))."%'";
		}
		switch($type){
			case "cl_provinsi_indo":
				$sql="SELECT kode_prov as id,provinsi as text 
				FROM cl_provinsi_indo ORDER BY provinsi ASC";
			break;
			case "cl_kab_kota_indo":
				$sql="SELECT kode_kab_kota as id, kab_kota as txt  
				FROM cl_kab_kota_indo 
				WHERE cl_provinsi_kode='".$this->input->post('v2')."' ORDER BY kab_kota ASC";
			break;
			case "cl_kecamatan_indo":
				$sql="SELECT kode_kecamatan as id, kecamatan as txt  
				FROM cl_kecamatan_indo 
				WHERE cl_kab_kota_kode='".$this->input->post('v2')."' ORDER BY kecamatan ASC";
			break;
			case "tbl_registration":
				if($balikan=='row_array'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				$sql = "SELECT A.*,CONCAT(A.alamat,', ',B.provinsi,' ',C.kab_kota,' ',D.kecamatan)as lokasi
						FROM tbl_registration A
						LEFT JOIN cl_provinsi_indo B ON A.cl_provinsi_id=B.kode_prov
						LEFT JOIN cl_kab_kota_indo C ON A.cl_kab_kota_id=C.kode_kab_kota
						LEFT JOIN cl_kecamatan_indo D ON A.cl_kecamatan_id=D.kode_kecamatan 
						".$where;	
			break;
			case "kabkota":
				$sql="SELECT A.* 
					  FROM cl_kab_kota A 
					  ".$where." ORDER BY A.kab_kota ASC ";
			break;
			case "get_distribusi_kabkota":
				$tgl_mulai=$this->input->post('tgl_mulai');
				$tgl_akhir=$this->input->post('tgl_akhir');
				$sql="SELECT B.npsn,A.no_order,B.nama_sekolah,C.kab_kota,
						A.flag_ver,SUM(A.grand_total)AS grand_total
						FROM tbl_h_pemesanan A
						LEFT JOIN tbl_registrasi B ON A.tbl_registrasi_id=B.id
						LEFT JOIN cl_kab_kota C ON B.cl_kab_kota_kode=C.kode_kab_kota
						WHERE A.tgl_order BETWEEN '".$tgl_mulai."' AND '".$tgl_akhir." 23:59:00'
						GROUP BY B.npsn,A.no_order,B.nama_sekolah,C.kab_kota,A.flag_ver ";
				//echo $sql;exit;
			break;
			case "get_buku_kabkota":
				$tgl_mulai=$this->input->post('tgl_mulai');
				$tgl_akhir=$this->input->post('tgl_akhir');
				$sql="SELECT G.tingkatan,E.judul_buku,SUM(A.qty)as qty 
						FROM tbl_d_pemesanan A
						LEFT JOIN tbl_h_pemesanan B ON A.tbl_h_pemesanan_id=B.id
						LEFT JOIN tbl_registrasi C ON B.tbl_registrasi_id=C.id
						LEFT JOIN cl_kab_kota D ON C.cl_kab_kota_kode=D.kode_kab_kota
						LEFT JOIN tbl_buku E ON A.tbl_buku_id=E.id
						LEFT JOIN cl_kelas F ON E.cl_kelas_id=F.id
						LEFT JOIN cl_tingkatan G ON F.cl_tingkatan_id=G.id
						WHERE B.tgl_order BETWEEN '".$tgl_mulai."' AND '".$tgl_akhir." 23:59:00'
						GROUP BY G.tingkatan,E.judul_buku ";
						//echo $sql;exit;
			break;
			case "mapping_paket_belum":
				$id_paket = $this->input->post('id_paket');
				$sqlmapping = "
					SELECT tbl_buku_id
					FROM tbl_paket_mapping
					WHERE tbl_paket_id = '".$id_paket."'
				";
				$querymapping = $this->db->query($sqlmapping)->result_array();
				$arraycdm = array();
				foreach($querymapping as $k=>$v){
					$arraycdm[$k] = $v['tbl_buku_id'];
				}
				if($arraycdm){
					$join_array = join("','",$arraycdm);
					$where .= "
						AND A.id NOT IN ('".$join_array."') 
					";
				}
				
				$sql = "
					SELECT A.judul_buku, A.id,
						B.kelas, 
						C.nama_group, 
						D.nama_kategori, 
						E.id as id_tingkatan,
						E.tingkatan 
					FROM tbl_buku A
					LEFT JOIN cl_kelas B ON A.cl_kelas_id=B.id
					LEFT JOIN cl_group_sekolah C ON A.cl_group_sekolah=C.id
					LEFT JOIN cl_kategori D ON A.cl_kategori_id=D.id 
					LEFT JOIN cl_tingkatan E ON B.cl_tingkatan_id=E.id 
					$where
				";
			break;
			case "mapping_paket_sudah":
				$id_paket = $this->input->post('id_paket');
				$sql = "
					SELECT B.judul_buku, A.id,
						C.kelas, 
						D.nama_group, 
						E.nama_kategori, 
						F.id as id_tingkatan,
						F.tingkatan 
					FROM tbl_paket_mapping A
					LEFT JOIN tbl_buku B ON B.id = A.tbl_buku_id
					LEFT JOIN cl_kelas C ON C.id = B.cl_group_sekolah
					LEFT JOIN cl_group_sekolah D ON D.id = B.cl_group_sekolah
					LEFT JOIN cl_kategori E ON E.id = B.cl_kategori_id
					LEFT JOIN cl_tingkatan F ON F.id = C.cl_tingkatan_id
					$where AND A.tbl_paket_id = '".$id_paket."'
				";
			break;
			case "tbl_paket":
				if($balikan=='row_array'){
					$where .=" AND id=".$this->input->post('id');
				}
				$sql = "
					SELECT *
					FROM tbl_paket
				".$where;
				//echo $sql;
			break;
		
			case "reg_marketing":
				$sql="SELECT A.*,
					CASE WHEN B.member_user IS NULL THEN 'P' ELSE B.member_user END AS sts_member
					FROM tbl_registration A
					LEFT JOIN tbl_member B ON B.tbl_registration_id=A.id ".$where ;
					
				$this->db_remote=$this->load->database('marketing',true);
				$data=$this->db_remote->query($sql)->result_array();
				if($data){
				   $responce = new stdClass();
				   $responce->rows= $data;
				   $responce->total =count($data);
				}else{ 
				   $responce = new stdClass();
				   $responce->rows = 0;
				   $responce->total = 0;
				} 
				$this->db_remote->close();
				return json_encode($responce);
				
			break;
			case "pesan":
				$data=array();
				$sql="SELECT A.*,B.jenis_pembeli,
						CASE 
							WHEN B.jenis_pembeli = 'SEKOLAH' THEN B.nama_sekolah
							ELSE B.nama_lengkap
						END AS nama
						FROM tbl_h_pemesanan A 
						LEFT JOIN tbl_registrasi B ON A.tbl_registrasi_id=B.id
						WHERE (A.flag_read <> 'F' OR A.flag_read IS NULL)";
				$data['inv']=$this->db->query($sql)->result_array();
				$sql="SELECT A.*
						FROM tbl_komplain A
						WHERE (A.flag_read <> 'F' OR A.flag_read IS NULL)";
				$data['komp']=$this->db->query($sql)->result_array();
				return $data;
				
				//echo $sql;
			break;
			case "detil_invoice":
				$data=array();
				$sql="SELECT A.*,B.zona 
						FROM tbl_d_pemesanan A
						LEFT JOIN tbl_h_pemesanan B ON A.tbl_h_pemesanan_id=B.id 
						WHERE A.id=".$this->input->post('id');
				$data['detil']=$this->db->query($sql)->row_array();
				$sql="SELECT A.*,A.harga_zona_".$data['detil']['zona']." as harga,A.judul_buku as text 
						FROM tbl_buku A ";
				$data['buku']=$this->db->query($sql)->result_array();
				return $data;
				
			break;
			case "tbl_komplain":
				$sql="SELECT A.*,B.no_order 
					  FROM tbl_komplain A 
					  LEFT JOIN tbl_h_pemesanan B ON A.tbl_h_pemesanan_id=B.id ".$where;
			break;
			case "buku_laris":
				$sql="SELECT B.judul_buku,sum(A.qty)as jml 
						FROM tbl_d_pemesanan A
						LEFT JOIN tbl_buku B ON A.tbl_buku_id=B.id
						GROUP BY B.judul_buku
						ORDER BY sum(A.qty) DESC
						limit 0,10";
			break;
			case "admin":
				if($balikan=='row_array'){
					$where .=" AND id=".$this->input->post('id');
				}
				$sql = "
					SELECT *
					FROM admin ".$where;				
			break;
			
			case "get_lap_rekap":
				$tgl_mulai=$this->input->post('tgl_mulai');
				$tgl_akhir=$this->input->post('tgl_akhir');
				$kat=$this->input->post('kat');
				$mod=$this->input->post('mod');
				if($mod=='rekap_penjualan_SEKOLAH' || $mod=='rekap_penjualan_UMUM'){
					$sql="SELECT A.*,B.nama_sekolah,B.nama_kepala_sekolah as pic,B.npsn,B.nama_lengkap,
							B.alamat_pengiriman,B.no_telp_sekolah,B.no_hp_kepsek,B.email,E.kab_kota,
							C.provinsi,F.jml_buku,G.total_pembayaran
							FROM tbl_h_pemesanan A
							LEFT JOIN tbl_registrasi B ON A.tbl_registrasi_id=B.id
							LEFT JOIN cl_provinsi C ON B.cl_provinsi_kode=C.kode_prov
							LEFT JOIN cl_kab_kota E ON B.cl_kab_kota_kode=E.kode_kab_kota
							LEFT JOIN (
								SELECT A.tbl_h_pemesanan_id,SUM(A.qty)as jml_buku 
								from tbl_d_pemesanan A 
								LEFT JOIN tbl_h_pemesanan B ON A.tbl_h_pemesanan_id=B.id
								LEFT JOIN tbl_registrasi C ON B.tbl_registrasi_id=C.id
								WHERE C.jenis_pembeli='".$kat."' 
								AND B.tgl_order BETWEEN '".$tgl_mulai."' AND '".$tgl_akhir." 23:59:00'
								GROUP BY A.tbl_h_pemesanan_id
							)AS F ON F.tbl_h_pemesanan_id=A.id
							LEFT JOIN tbl_konfirmasi G ON G.tbl_h_pemesanan_id=A.id
							WHERE B.jenis_pembeli='".$kat."' 
							AND A.tgl_order BETWEEN '".$tgl_mulai."' AND '".$tgl_akhir." 23:59:00'";
					//echo $sql;
				}else if($mod=='detil_penjualan_SEKOLAH' || $mod=='detil_penjualan_UMUM'){
					$sql="SELECT A.tbl_h_pemesanan_id,SUM(A.qty)as jml_buku,
							CONCAT(C.nama_sekolah,' [',C.npsn,']')as sekolah,C.nama_lengkap,
							B.no_order,B.sub_total,B.pajak,B.grand_total,B.id as id_header  
							from tbl_d_pemesanan A 
							LEFT JOIN tbl_h_pemesanan B ON A.tbl_h_pemesanan_id=B.id 
							LEFT JOIN tbl_registrasi C ON B.tbl_registrasi_id=C.id 
							WHERE C.jenis_pembeli='".$kat."' 
							AND B.tgl_order BETWEEN '".$tgl_mulai."' AND '".$tgl_akhir." 23:59:00'
							GROUP BY A.tbl_h_pemesanan_id ";
							
					$data=array();
					$res=$this->db->query($sql)->result_array();
					if(count($res)>0){
						foreach($res as $x=>$v){
							$data[$x]=array();
							$data[$x]['no_order']=$v['no_order'];
							$data[$x]['sekolah']=$v['sekolah'];
							$data[$x]['nama_lengkap']=$v['nama_lengkap'];
							$data[$x]['sub_total']=$v['sub_total'];
							$data[$x]['pajak']=$v['pajak'];
							$data[$x]['grand_total']=$v['grand_total'];
							$data[$x]['jml_buku']=$v['jml_buku'];
							$sql="SELECT A.*,B.no_order,CONCAT(D.nama_sekolah,' (',D.npsn,')')as sekolah,D.nama_lengkap,C.judul_buku
									FROM tbl_d_pemesanan A
									LEFT JOIN tbl_h_pemesanan B ON A.tbl_h_pemesanan_id=B.id
									LEFT JOIN tbl_buku C ON A.tbl_buku_id=C.id
									LEFT JOIN tbl_registrasi D ON B.tbl_registrasi_id=D.id
									WHERE D.jenis_pembeli='".$kat."' AND A.tbl_h_pemesanan_id=".$v['id_header'];
							$det=$this->db->query($sql)->result_array();
							//print_r($det);exit;
							if(count($det)>0){
								$data[$x]['detil']=$det;
							}
							
						}
					}
					//echo "<pre>";print_r($data);exit;
					return $data;
				}else if($mod=='lap_bast_SEKOLAH' || $mod=='lap_bast_UMUM'){
					$sql="SELECT A.*,D.nama_sekolah,D.nama_lengkap,D.npsn,B.no_konfirmasi,
							B.total_pembayaran,C.no_order,C.grand_total
							FROM tbl_bast A
							
							LEFT JOIN tbl_h_pemesanan C ON A.tbl_h_pemesanan_id=C.id
							LEFT JOIN tbl_konfirmasi B ON B.tbl_h_pemesanan_id=C.id
							LEFT JOIN tbl_registrasi D ON C.tbl_registrasi_id=D.id
							WHERE D.jenis_pembeli='".$kat."' 
							AND A.create_date BETWEEN '".$tgl_mulai."' AND '".$tgl_akhir." 23:59:00'";
					
				}else if($mod=='lap_kwitansi_SEKOLAH' || $mod=='lap_kwitansi_UMUM'){
					$sql="SELECT A.*,D.nama_sekolah,D.nama_lengkap,D.npsn,B.no_konfirmasi,B.total_pembayaran,C.no_order,C.grand_total
							FROM tbl_kwitansi A
							LEFT JOIN tbl_h_pemesanan C ON A.tbl_h_pemesanan_id=C.id
							LEFT JOIN tbl_konfirmasi B ON B.tbl_h_pemesanan_id=C.id
							LEFT JOIN tbl_registrasi D ON C.tbl_registrasi_id=D.id
							WHERE D.jenis_pembeli='".$kat."' 
							AND A.create_date BETWEEN '".$tgl_mulai."' AND '".$tgl_akhir." 23:59:00'";
				}else if($mod=='dashboard_penjualan'){
					$sql="SELECT A.*,B.nama_sekolah 
						  FROM tbl_h_pemesanan A 
						  LEFT JOIN tbl_registrasi B ON A.tbl_registrasi_id=B.id
						  WHERE B.jenis_pembeli='SEKOLAH' 
						  ORDER BY A.tgl_order DESC
						  LIMIT 0,10
					";
				}else if($mod=='dashboard_penjualan_umum'){
					$sql="SELECT A.*,B.nama_lengkap 
						  FROM tbl_h_pemesanan A 
						  LEFT JOIN tbl_registrasi B ON A.tbl_registrasi_id=B.id
						  WHERE B.jenis_pembeli='UMUM' 
						  ORDER BY A.tgl_order DESC
						  LIMIT 0,10
					";
				}else if($mod=='dashboard_penjualan_zona'){
					$sql='SELECT CONCAT("Zona ",A.zona)as zona,SUM(A.grand_total)as total
							FROM tbl_h_pemesanan A 
							WHERE A.zona='.$p1.'
							GROUP BY A.zona
					';
				}
			break;
			/*case "tbl_monitor":
				$where .=" AND (J.alamat_pengiriman <> '')";
				$sql="SELECT A.id,A.no_order,A.`status` as status_order,B.flag as status_konfirmasi,
						C.flag as status_gudang,D.`status` as status_kirim,D.no_resi,
						DATE_FORMAT(B.tgl_konfirmasi,'%d %b %y') as tanggal_konfirmasi,
						DATE_FORMAT(B.tanggal_transfer,'%d %b %y') as tanggal_transfer,
						DATE_FORMAT(F.tgl_masuk,'%d %b %y') as tanggal_masuk_gudang,
						DATE_FORMAT(G.create_date,'%d %b %y') as tanggal_kirim,
						DATE_FORMAT(H.create_date,'%d %b %y') as tanggal_upload, H.file_bast, H.file_tanda_terima,
						DATE_FORMAT(I.tanggal_terima,'%d %b %y') as tanggal_terimanya, I.jam_terima, I.petugas
						FROM tbl_h_pemesanan A
						LEFT JOIN (
							SELECT A.tbl_h_pemesanan_id,A.flag,A.id,A.tgl_konfirmasi,A.tanggal_transfer FROM tbl_konfirmasi A
						)AS B ON B.tbl_h_pemesanan_id=A.id
						LEFT JOIN (
							SELECT A.tbl_h_pemesanan_id,A.tbl_konfirmasi_id,A.flag 
							FROM tbl_gudang A
						)AS C ON (C.tbl_h_pemesanan_id=A.id AND C.tbl_konfirmasi_id=B.id)
						LEFT JOIN (
							SELECT A.tbl_h_pemesanan_id,A.`status`,A.no_resi 
							FROM tbl_tracking_pengiriman A
						)AS D ON D.tbl_h_pemesanan_id=A.id 
						LEFT JOIN tbl_gudang F ON F.tbl_h_pemesanan_id = A.id
						LEFT JOIN tbl_tracking_pengiriman G ON G.tbl_h_pemesanan_id = A.id
						LEFT JOIN tbl_uploadfile H ON H.tbl_h_pemesanan_id = A.id
						LEFT JOIN tbl_terima_barang I ON I.tbl_h_pemesanan_id = A.id
						LEFT JOIN tbl_registrasi J ON A.tbl_registrasi_id=J.id
				".$where." ORDER BY A.id DESC";
				return $this->lib->json_grid($sql,"tbl_monitor");
			break;
			*/
			case "tbl_monitor":
				$sql=" SELECT A.*,C.jenis_pembeli,B.no_order 
							FROM tbl_monitoring_order A  
							LEFT JOIN tbl_h_pemesanan B ON A.tbl_h_pemesanan_id=B.id
							LEFT JOIN tbl_registrasi C ON B.tbl_registrasi_id=C.id
							WHERE B.status <> 'C'
				
				";
			break;
			case "tbl_registrasi":
			case "tbl_registrasi_umum":
				if($type=="tbl_registrasi")$where .=" AND A.jenis_pembeli='SEKOLAH'";
				else $where .=" AND A.jenis_pembeli='UMUM'";
				$sql="SELECT A.*,CONCAT('PROV. ',B.provinsi,', ',C.kab_kota,', KEC. ',D.kecamatan)as prov_kota
					FROM tbl_registrasi A
					LEFT JOIN cl_provinsi B ON A.cl_provinsi_kode=B.kode_prov
					LEFT JOIN cl_kab_kota C ON A.cl_kab_kota_kode=C.kode_kab_kota
					LEFT JOIN cl_kecamatan D ON A.cl_kecamatan_kode=D.kode_kecamatan 
					".$where;
			break;
			case "get_no_gudang":
				$sql=" SELECT *  FROM tbl_gudang ";
				$res=$this->db->query($sql)->result_array();
				if(count($res)>0){
					$sql=" SELECT max(no_gudang)+1 as nogud_baru  FROM tbl_gudang ";
					$qry=$this->db->query($sql)->row();
					$id=$qry->nogud_baru;
				}else{
					$id=1;
				}
				if($id<10){$id_baru='0000'.$id;}
				if($id<100 && $id >=10){$id_baru='000'.$id;}
				if($id<1000 && $id >=100){$id_baru='00'.$id;}
				if($id<10000 && $id >=1000){$id_baru='0'.$id;}
				return $id_baru;
			break;
			case "tbl_gudang":
				//$where .=" AND (D.alamat_pengiriman <> '')";
				$sql="SELECT A.*,B.no_konfirmasi,B.tgl_konfirmasi,B.total_pembayaran,
						C.no_order,C.tgl_order,C.zona,D.nama_sekolah,D.nama_lengkap,D.jenis_pembeli,
						C.id as id_pemesanan,D.nama_kepala_sekolah,D.alamat_pengiriman,E.jasa_pengiriman  
						FROM tbl_gudang A 
						LEFT JOIN tbl_konfirmasi B ON A.tbl_konfirmasi_id=B.id
						LEFT JOIN tbl_h_pemesanan C ON (A.tbl_h_pemesanan_id=C.id AND B.tbl_h_pemesanan_id=C.id)
						LEFT JOIN tbl_registrasi D ON C.tbl_registrasi_id=D.id
						LEFT JOIN cl_jasa_pengiriman E ON C.cl_jasa_pengiriman_id=E.id
						".$where." 
						 ORDER BY A.tgl_masuk DESC"; 
					//echo $sql;
			break;
			case "manajemen_gudang_sekolah":
				$where .=" AND D.jenis_pembeli = 'SEKOLAH' ";
				$sql="SELECT A.*,C.grand_total,
						C.no_order,C.tgl_order,C.zona,D.nama_sekolah,D.nama_lengkap,D.jenis_pembeli,
						C.id as id_pemesanan,D.nama_kepala_sekolah,D.alamat_pengiriman,E.jasa_pengiriman  
						FROM tbl_gudang A 
						LEFT JOIN tbl_h_pemesanan C ON A.tbl_h_pemesanan_id=C.id
						LEFT JOIN tbl_registrasi D ON C.tbl_registrasi_id=D.id
						LEFT JOIN cl_jasa_pengiriman E ON C.cl_jasa_pengiriman_id=E.id
						".$where." 
						 ORDER BY A.tgl_masuk DESC"; 
					//echo $sql;
			break;
			case "manajemen_gudang_umum":
				$where .=" AND D.jenis_pembeli = 'UMUM'";
				$sql="SELECT A.*,B.no_konfirmasi,B.tgl_konfirmasi,B.total_pembayaran,
						C.no_order,C.tgl_order,C.zona,D.nama_sekolah,D.nama_lengkap,D.jenis_pembeli,
						C.id as id_pemesanan,D.nama_kepala_sekolah,D.alamat_pengiriman,E.jasa_pengiriman  
						FROM tbl_gudang A 
						LEFT JOIN tbl_konfirmasi B ON A.tbl_konfirmasi_id=B.id
						LEFT JOIN tbl_h_pemesanan C ON (A.tbl_h_pemesanan_id=C.id AND B.tbl_h_pemesanan_id=C.id)
						LEFT JOIN tbl_registrasi D ON C.tbl_registrasi_id=D.id
						LEFT JOIN cl_jasa_pengiriman E ON C.cl_jasa_pengiriman_id=E.id
						".$where." 
						 ORDER BY A.tgl_masuk DESC"; 
					//echo $sql;
			break;
			case "tbl_konfirmasi":
			case "konfirmasi_umum":
			case "konfirmasi_sekolah":
				if($type=="konfirmasi_sekolah")$where .=" AND C.jenis_pembeli='SEKOLAH'";
				else $where .=" AND C.jenis_pembeli='UMUM'";
				$sql="SELECT A.*,B.no_order,B.tgl_order,B.zona,C.nama_sekolah,C.nama_lengkap,
						B.id as id_pemesanan,C.jenis_pembeli,C.nama_kepala_sekolah 
						FROM tbl_konfirmasi A 
						LEFT JOIN tbl_h_pemesanan B ON A.tbl_h_pemesanan_id=B.id
						LEFT JOIN tbl_registrasi C ON B.tbl_registrasi_id=C.id
						".$where." 
					  ORDER BY A.id DESC";
			
			break;
			case "get_bast":
				$data=array();
				$id=$this->input->post('id');
				if($id)$where .=" AND A.id=".$id;
				$sql="SELECT A.*,B.no_order,B.tgl_order,B.zona,C.nama_sekolah,
						C.nama_kepala_sekolah,C.nip,C.npsn,C.alamat_pengiriman,
						C.no_telp_sekolah,C.email,C.no_hp_kepsek,D.no_bast, B.id as idpesan
						FROM tbl_konfirmasi A 
						LEFT JOIN tbl_h_pemesanan B ON A.tbl_h_pemesanan_id=B.id
						LEFT JOIN tbl_registrasi C ON B.tbl_registrasi_id=C.id
						LEFT JOIN tbl_bast D ON D.tbl_h_pemesanan_id=B.id
					  ".$where;
				$data['header']=$this->db->query($sql)->row_array();
				$sql="SELECT A.*,B.judul_buku,(A.qty*A.harga)as total,E.kelas,F.nama_group
					  FROM tbl_d_pemesanan A 
					  LEFT JOin tbl_buku B ON A.tbl_buku_id=B.id
					  LEFT JOIN tbl_h_pemesanan C ON A.tbl_h_pemesanan_id=C.id
					  LEFT JOIN tbl_konfirmasi D ON D.tbl_h_pemesanan_id=C.id
					  LEFT JOIN cl_kelas E ON B.cl_kelas_id=E.id
					  LEFT JOIN cl_group_sekolah F ON B.cl_group_sekolah=F.id
					  WHERE  D.id=".$id;
				$data['detil']=$this->db->query($sql)->result_array();
				return $data;
			break;
			case "get_pemesanan":
				$data=array();
				$id=$this->input->post('id');
				if($id)$where .=" AND A.id=".$id;
				$sql="SELECT A.*,B.nama_sekolah,B.nama_lengkap,B.jenis_pembeli 
					  FROM tbl_h_pemesanan A 
					  LEFT JOIN tbl_registrasi B ON A.tbl_registrasi_id=B.id ".$where;
				$data['header']=$this->db->query($sql)->row_array();
				$sql="SELECT A.*,B.judul_buku,(A.qty*A.harga)as total,B.berat
					  FROM tbl_d_pemesanan A 
					  LEFT JOin tbl_buku B ON A.tbl_buku_id=B.id
					  WHERE A.tbl_h_pemesanan_id=".$id;
				$data['detil']=$this->db->query($sql)->result_array();
				return $data;
			break;
			case "get_pemesanan_konfirmasi":
				$data=array();
				$id=$this->input->post('id');
				if($id)$where .=" AND A.id=".$id;
				$sql="SELECT A.*,B.nama_sekolah,B.nama_lengkap,B.jenis_pembeli,
					  C.id as id_konf,C.flag as flag_konf,
					  C.no_konfirmasi,C.tgl_konfirmasi,C.total_pembayaran,C.nama_bank_pengirim,C.atas_nama_pengirim,
					  C.tanggal_transfer,C.nama_bank_penerima,C.file_bukti_bayar
					  FROM tbl_h_pemesanan A 
					  LEFT JOIN tbl_konfirmasi C ON C.tbl_h_pemesanan_id=A.id
					  LEFT JOIN tbl_registrasi B ON A.tbl_registrasi_id=B.id ".$where;
				$data['header']=$this->db->query($sql)->row_array();
				$sql="SELECT A.*,B.judul_buku,(A.qty*A.harga)as total,B.berat
					  FROM tbl_d_pemesanan A 
					  LEFT JOin tbl_buku B ON A.tbl_buku_id=B.id
					  WHERE A.tbl_h_pemesanan_id=".$id;
				$data['detil']=$this->db->query($sql)->result_array();
				return $data;
			break;
			case "data_login":
				$sql = "
					SELECT *
					FROM admin
					WHERE username = '".$p1."'
				";				
			break;
			case "tbl_buku":
				if($balikan=='row_array'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				/*if($this->input->post('key')){
					$where .=" AND ".$this->input->post('kat')." like '%".$this->db->escape_str($this->input->post('key'))."%'";
				}*/
				$sql = "
					SELECT A.*,B.kelas,C.nama_group,D.nama_kategori,E.id as id_tingkatan,E.tingkatan 
						FROM tbl_buku A
					LEFT JOIN cl_kelas B ON A.cl_kelas_id=B.id
					LEFT JOIN cl_group_sekolah C ON A.cl_group_sekolah=C.id
					LEFT JOIN cl_kategori D ON A.cl_kategori_id=D.id 
					LEFT JOIN cl_tingkatan E ON B.cl_tingkatan_id=E.id ".$where;
					
				//echo $sql;
			break;
			case "tbl_h_pemesanan":
			case "tbl_h_pemesanan_umum":
				if($type=='tbl_h_pemesanan')$where .=" AND B.jenis_pembeli='SEKOLAH' AND (A.status='T' OR A.status='F')";
				if($type=='tbl_h_pemesanan_umum')$where .=" AND B.jenis_pembeli='UMUM' AND (A.flag_ver='F' AND A.flag_ver_gudang='F') ";
				
				$sql="SELECT A.*,B.nama_sekolah,B.nama_lengkap 
					  FROM tbl_h_pemesanan A 
					  LEFT JOIN tbl_registrasi B ON A.tbl_registrasi_id=B.id 
					  ".$where."
					  ORDER BY A.tgl_order DESC";
			
			break;
			case "ver_sekolah":
			case "ver_umum":
				if($type=='ver_sekolah'){
					$where .=" AND B.jenis_pembeli='SEKOLAH'";
					$where .=" AND A.flag_ver <> 'F'";
					$where .=" AND A.status <> 'C'";
				}
				if($type=='ver_umum'){ 
					$where .=" AND B.jenis_pembeli='UMUM'";
					$where .=" AND A.flag_ver <> 'F'";
					$where .=" AND A.status <> 'C'";
				}
				
				$sql="SELECT A.*,B.nama_sekolah,B.nama_lengkap 
					  FROM tbl_h_pemesanan A 
					  LEFT JOIN tbl_registrasi B ON A.tbl_registrasi_id=B.id 
					  ".$where."
					  ORDER BY A.tgl_order DESC";
			
			break;
			case "ver_gudang_sekolah":
			case "ver_gudang_umum":
				if($type=='ver_gudang_sekolah'){
					$where .=" AND B.jenis_pembeli='SEKOLAH'";
					$where .=" AND A.flag_ver_gudang <> 'F' AND A.flag_ver <> 'P' ";
				}
				if($type=='ver_gudang_umum'){
					$where .=" AND B.jenis_pembeli='UMUM'";
					$where .=" AND A.flag_ver_gudang <> 'F' AND A.flag_ver <> 'P'";
				}
				
				$sql="SELECT A.*,B.nama_sekolah,B.nama_lengkap 
					  FROM tbl_h_pemesanan A 
					  LEFT JOIN tbl_registrasi B ON A.tbl_registrasi_id=B.id 
					  ".$where."
					  ORDER BY A.tgl_order DESC";
			
			break;
			case "cl_tingkatan":
				if($balikan=='row_array'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				/*if($this->input->post('key')){
					$where .=" AND ".$this->input->post('kat')." like '%".$this->db->escape_str($this->input->post('key'))."%'";
				}*/
				$sql="SELECT A.*,A.tingkatan as text FROM cl_tingkatan A  ".$where;
				//echo $sql;
			break;
			case "cl_group_sekolah":
				if($balikan=='row_array'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				/*if($this->input->post('key')){
					$where .=" AND ".$this->input->post('kat')." like '%".$this->db->escape_str($this->input->post('key'))."%'";
				}*/
				$sql="SELECT A.*,A.nama_group as text 
					  FROM cl_group_sekolah A ".$where;
			break;
			case "cl_kategori":
				if($balikan=='row_array'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				/*if($this->input->post('key')){
					$where .=" AND ".$this->input->post('kat')." like '%".$this->db->escape_str($this->input->post('key'))."%'";
				}*/
				$sql="SELECT A.*,A.nama_kategori as text
					  FROM cl_kategori A ".$where;
			break;
			case "cl_edisi":
				if($balikan=='row_array'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				/*if($this->input->post('key')){
					$where .=" AND ".$this->input->post('kat')." like '%".$this->db->escape_str($this->input->post('key'))."%'";
				}*/
				$sql="SELECT A.*,A.nama_edisi as text
					  FROM cl_edisi A ".$where;
			break;
			case "cl_kelas":
				if($balikan=='row_array'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				if($balikan=='result_array'){
					$where .=" AND A.cl_tingkatan_id=".$this->input->post('v2');
				}
				/*if($this->input->post('key')){
					$where .=" AND ".$this->input->post('kat')." like '%".$this->db->escape_str($this->input->post('key'))."%'";
				}*/
				$sql="SELECT A.*, B.tingkatan,kelas as txt 
					  FROM cl_kelas A 
					  LEFT JOIN cl_tingkatan B ON A.cl_tingkatan_id=B.id ".$where;
			break;
			case "tbl_foto_buku":
				if($balikan=='row_array'){
					 $where .=" AND A.id=".$this->input->post('id');
				}else{
					 $where .=" AND A.tbl_buku_id=".$this->input->post('id');
				}
				$sql="SELECT A.*,C.kelas,D.nama_group,E.nama_kategori,F.tingkatan 
					FROM tbl_foto_buku A 
					LEFT JOIN tbl_buku B ON A.tbl_buku_id=B.id
					LEFT JOIN cl_kelas C ON B.cl_kelas_id=C.id
					LEFT JOIN cl_group_sekolah D ON B.cl_group_sekolah = D.id
					LEFT JOIN cl_kategori E ON B.cl_kategori_id=E.id 
					LEFT JOIN cl_tingkatan F ON C.cl_tingkatan_id=F.id ".$where;
			break;
			case "l_kelas":
				$filter=$this->input->post('v2');
				$sql="SELECT id as id,kelas as txt FROM cl_kelas ".$where;
				if($filter){$sql .=" AND cl_tingkatan_id=".$filter;}
				else{ $sql .=" AND cl_tingkatan_id=-1";}
				//return $this->result_query($sql);
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
			case "cl_kategori":
			break;
			case "c_kelas":
				$sql = "
					SELECT id, kelas as txt
					FROM 
				";
			break;
		}
		
		return $this->db->query($sql)->result_array();
	}
	
	function simpandata($table,$data,$sts_crud){ //$sts_crud --> STATUS NYEE INSERT, UPDATE, DELETE
		$this->db->trans_begin();
		if(isset($data['id'])){
			$id = $data['id'];
			unset($data['id']);
		}
		
		switch($table){
			case "tbl_registration":
				//print_r($data);exit;
				if($sts_crud=='add'){
					$data['registration_date']=date('Y-m-d H:i:s');
					$data['create_by']=$this->auth['username'];
					$data['password']=$this->encrypt->encode($data['password']);
				}
				if(!isset($data['status'])){$data['status']=0;}
			break;
			case "admin":
				//print_r($data);exit;
				if($sts_crud=='add')$data['password']=$this->encrypt->encode($data['password']);
				if(!isset($data['status'])){$data['status']=0;}
			break;
			case "tbl_buku":
				//print_r($data);exit;
				unset($data['tingkatan']);
				if($sts_crud=='delete'){
					$data_foto=$this->getdata('tbl_foto_buku','result_array');
					if(count($data_foto)>0){
						foreach($data_foto as $v){
							if(isset($v['foto_buku'])){
								$path='__repository/produk/'.$v['tingkatan'].'/'.$v['kelas'].'/'.$v['nama_group'].'/'.$v['nama_kategori'].'/';
								chmod($path.$v['foto_buku'],0777);
								unlink($path.$v['foto_buku']);
								$this->mbackend->simpandata('tbl_foto_buku',$v,'delete');
							}
						}
					}
				}
			break;
			case "cl_group_sekolah":
			case "cl_kelas":
			case "cl_edisi":
			
				$data['create_date']=date('Y-m-d H:i:s');
				$data['create_by']=$this->auth['username'];
			break;
			case "cl_kategori":
				if($sts_crud=='add'){
					$data['tgl_buat']=date('Y-m-d H:i:s');
					$data['user_create']=$this->auth['username'];
				}else if($sts_crud=='edit'){
					$data['tgl_update']=date('Y-m-d H:i:s');
					$data['user_update']=$this->auth['username'];
				}
			break;
			case "tbl_gudang":
				
				if($this->input->post('mod')=='kirim_gudang' || $this->input->post('mod')=='kirim_gudang_umum'){
					$sql="UPDATE tbl_konfirmasi set flag='F' WHERE id=".$data['tbl_konfirmasi_id'];
					$this->db->query($sql);
					if($this->input->post('mod')=='kirim_gudang_umum'){
						$sql="UPDATE tbl_h_pemesanan set status='F' WHERE id=".$data['tbl_h_pemesanan_id'];
						$this->db->query($sql);
						$sql="UPDATE tbl_monitoring_order SET konfirmasi='F',produksi='P' WHERE tbl_h_pemesanan_id=".$data['tbl_h_pemesanan_id'];
						$this->db->query($sql);
					}
				}else if($this->input->post('mod')=='set_kirim' || $this->input->post('mod')=='set_kirim_sekolah' || $this->input->post('mod')=='set_kirim_umum'){
					$sql="SELECT * FROM tbl_gudang where id=".$id;
					$id_pemesanan=$this->db->query($sql)->row('tbl_h_pemesanan_id');
					$data_kirim=array('tbl_h_pemesanan_id'=>$id_pemesanan,
									  'no_resi'=>$this->input->post('no_resi'),
									  'status'=>'F',
									  'create_date'=>date('Y-m-d H:i:s'),
									  'create_by'=>$this->auth['username']
					);
					$this->db->insert('tbl_tracking_pengiriman',$data_kirim);
					if($this->input->post('mod')=='set_kirim_sekolah'){
						$sql="UPDATE tbl_h_pemesanan SET status='T' WHERE id=".$id_pemesanan;
						$this->db->query($sql);
					}
				}
			break;
			case "tbl_konfirmasi":
				$sql="SELECT * FROM tbl_konfirmasi WHERE id=".$id;
				$id_pemesanan=$this->db->query($sql)->row('tbl_h_pemesanan_id');
				$sql="UPDATE tbl_h_pemesanan SET status='C' WHERE id=".$id_pemesanan;
				$this->db->query($sql);
			break;
			case "tbl_konfirmasi_sekolah":
				$table='tbl_konfirmasi';
				//print_r($_FILES);exit;
				/*
				$sql=" SELECT *  FROM tbl_konfirmasi ";
				$res=$this->db->query($sql)->result_array();
				if(count($res)>0){
					$sql=" SELECT max(id)+1 as no_baru  FROM tbl_konfirmasi ";
					$qry=$this->db->query($sql)->row();
					$id=$qry->no_baru;
				}else{
					$id=1;
				}
				if($id<10){$id_baru='0000'.$id;}
				if($id<100 && $id >=10){$id_baru='000'.$id;}
				if($id<1000 && $id >=100){$id_baru='00'.$id;}
				if($id<10000 && $id >=1000){$id_baru='0'.$id;}
				
				$data['no_konfirmasi']='KONF-'.$id_baru;
				$data['create_date']=date('Y-m-d H:i:s');
				$data['create_by']=$this->auth['username'];
				$data['tgl_konfirmasi']=date('Y-m-d');
				
				if($_FILES['file_bukti_bayar']['name']!=''){
					$path='__repository/konfirmasi/';
					$data['file_bukti_bayar']=$this->lib->uploadnong($path, 'file_bukti_bayar', date('Ymdhis'));
				}
				$sql="UPDATE tbl_h_pemesanan SET status='F' WHERE id=".$data['tbl_h_pemesanan_id'];
				$this->db->query($sql);
				
				$sql="UPDATE tbl_monitoring_order SET konfirmasi='F' WHERE tbl_h_pemesanan_id=".$data['tbl_h_pemesanan_id'];
				$this->db->query($sql);
				*/
				$sql="UPDATE tbl_monitoring_order SET konfirmasi='F' WHERE tbl_h_pemesanan_id=".$this->input->post('id');
				$this->db->query($sql);
			break;
		}
		
		switch ($sts_crud){
			case "add":
				$this->db->insert($table,$data);
				if($table=="tbl_buku"){
					$id=$this->db->insert_id();
				}
			break;
			case "edit":
				if($table=='tbl_h_pemesanan'){
					$gd=$this->input->post('gd');
					if($gd){
						$mod_na=$this->input->post('mod_na');
						if($mod_na=='ver_sekolah' || $mod_na=='ver_gudang_sekolah'){
							$no_gudang=$this->getdata('get_no_gudang');
							$data_gd=array('tbl_h_pemesanan_id'=>$id,
										'no_gudang'=>$no_gudang,
										'tgl_masuk'=>date('Y-m-d H:i:s'),
										//'remark'=>$this->input->post('remark'),
										'flag'=>'P',
										'create_date'=>date('Y-m-d H:i:s'),
										'create_by'=>$this->auth['username']
							);
							$this->db->insert('tbl_gudang',$data_gd);
							$sql="UPDATE tbl_monitoring_order SET verifikasi='F',produksi='P' WHERE tbl_h_pemesanan_id=".$id;
							$this->db->query($sql);
						}else{
							$data['status']='T';
							$sql="UPDATE tbl_monitoring_order SET verifikasi='F' WHERE tbl_h_pemesanan_id=".$id;
							$this->db->query($sql);
						}
						
						
					}
					unset($data['gd']);
					unset($data['mod_na']);
					$this->db->update($table, $data, array('id' => $id) );
				}else{
					$this->db->update($table, $data, array('id' => $id) );
				}
			break;
			case "delete":
				$this->db->delete($table, array('id' => $id));
			break;
			case "disable_enable":
				if(!isset($data['flag_disable_enable'])){
					$array = array(
						'flag_disable_enable' => 0
					);
				}elseif($data['flag_disable_enable'] == 0){
					$array = array(
						'flag_disable_enable' => null
					);
				}
				$this->db->update($table, $array, array('id' => $id) );
			break;
		}
		
		if($this->db->trans_status() == false){
			$this->db->trans_rollback();
			return 0;
		} else{
			if($table=="tbl_buku"){
				if($sts_crud !='delete'){
					$this->db->trans_commit();
					$js=array('msg'=>1,'data'=>$id);
					return json_encode($js);
				}else{
					return $this->db->trans_commit();
				}
			}elseif($table=="tbl_paket"){
				if($sts_crud =='disable_enable'){
					$this->db->trans_commit();
					$js=array('msg'=>1,'data'=>$id);
					return json_encode($js);
				}else{
					return $this->db->trans_commit();
				}				
			}else{
				return $this->db->trans_commit();
			}
		}
	
	}
	
}
