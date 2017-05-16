<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	LIBRARY CIPTAAN JINGGA LINTAS IMAJI
	KONTEN LIBRARY :
	- Upload File
	- Upload File Multiple
	- RandomString
	- CutString
	- Kirim Email
	- Konversi Bulan
	- Fillcombo
	- Json Datagrid
	
*/
class Lib {
	public function __construct(){
		
	}
	
	//class Upload File Version 1.0 - Beta
	function uploadnong($upload_path="", $object="", $file=""){
		//$upload_path = "./__repository/".$folder."/";
		
		$ext = explode('.',$_FILES[$object]['name']);
		$exttemp = sizeof($ext) - 1;
		$extension = $ext[$exttemp];
		
		$filename =  $file.'.'.$extension;
		
		$files = $_FILES[$object]['name'];
		$tmp  = $_FILES[$object]['tmp_name'];
		if(file_exists($upload_path.$filename)){
			unlink($upload_path.$filename);
			$uploadfile = $upload_path.$filename;
		}else{
			$uploadfile = $upload_path.$filename;
		} 
		
		move_uploaded_file($tmp, $uploadfile);
		if (!chmod($uploadfile, 0775)) {
			echo "Gagal mengupload file";
			exit;
		}
		
		return $filename;
	}
	// end class Upload File
	
	//class Upload File Multiple Version 1.0 - Beta
	function uploadmultiplenong($upload_path="", $object="", $file="", $idx=""){
		$ext = explode('.',$_FILES[$object]['name'][$idx]);
		$exttemp = sizeof($ext) - 1;
		$extension = $ext[$exttemp];
		
		$filename =  $file.'.'.$extension;
		
		$files = $_FILES[$object]['name'][$idx];
		$tmp  = $_FILES[$object]['tmp_name'][$idx];
		if(file_exists($upload_path.$filename)){
			unlink($upload_path.$filename);
			$uploadfile = $upload_path.$filename;
		}else{
			$uploadfile = $upload_path.$filename;
		} 
		
		move_uploaded_file($tmp, $uploadfile);
		if (!chmod($uploadfile, 0775)) {
			echo "Gagal mengupload file";
			exit;
		}
		
		return $filename;
	}
	//end Class Upload File
	
	//class Random String Version 1.0
	function randomString($length,$parameter="") {
        $str = "";
		$rangehuruf = range('A','Z');
		$rangeangka = range('0','9');
		if($parameter == 'angka'){
			$characters = array_merge($rangeangka);
		}elseif($parameter == 'huruf'){
			$characters = array_merge($rangehuruf);
		}else{
			$characters = array_merge($rangehuruf, $rangeangka);
		}
         $max = count($characters) - 1;
         for ($i = 0; $i < $length; $i++) {
              $rand = mt_rand(0, $max);
              $str .= $characters[$rand];
         }
         return $str;
    }
	//end Class Random String
	
	//Class CutString
	function cutstring($text, $length) {
		$isi_teks = htmlentities(strip_tags($text));
		$isi = substr($isi_teks, 0,$length);
		//$isi = substr($isi_teks, 0,strrpos($isi," "));
		$isi = $isi.' ...';
		return $isi;
	}
	//end Class CutString
	
	//Class Kirim Email
	function kirimemail($type="", $email="", $p1="", $p2="", $p3=""){
		$ci =& get_instance();
		
		$ci->load->library('email');
		$html = "";
		$subject = "";
		switch($type){
			case "email_invoice":
				$ci->nsmarty->assign('data_cart', $p1);
				$ci->nsmarty->assign('penunjang', $p2);
				$html = $ci->nsmarty->fetch('frontend/modul/email_invoice.html');
				$subject = "SURAT PEMESANAN BUKU BARU - ".$p2['no_order'];
			break;
			case "email_konfirmasi":	
				$ci->nsmarty->assign('no_order', $p1);
				$subject = "EMAIL KONFIRMASI PEMBAYARAN ".$p1;
				$html = $ci->nsmarty->fetch('frontend/modul/email_konfirmasi.html');
			break;
			case "email_pembatalan":
				$ci->nsmarty->assign('kode_pembatalan', $p1);
				$ci->nsmarty->assign('no_order', $p2);
				$html = $ci->nsmarty->fetch('frontend/modul/email_pembatalan.html');
				$subject = "EMAIL PEMBATALAN PESANAN";
			break;
			case "email_registrasi":
				$ci->nsmarty->assign('data_registrasi', $p1);
				$ci->nsmarty->assign('password', $p2);
				$ci->nsmarty->assign('type_registrasi', $p3);				
				$html = $ci->nsmarty->fetch('frontend/modul/email_registrasi.html');
				$subject = "EMAIL REGISTRASI WEBSTORE MKS-Store.ID";
			break;	
			case "email_register_pic":
				$ci->nsmarty->assign('email', $email);
				$ci->nsmarty->assign('password', $p1);
				$ci->nsmarty->assign('namalengkap', $p2);
				$html = $ci->nsmarty->fetch('backend/email/email_registrasi_sales.html');
				$subject = "REGISTRASI PIC MARKETING MKS-Store";
			break;	
			case "email_lupa_password":
				$ci->nsmarty->assign('email', $p1['email']);
				$ci->nsmarty->assign('password', $p1['password']);
				$html = $ci->nsmarty->fetch('frontend/modul/email_password.html');
				$subject = "EMAIL LUPA PASSWORD MKS-Store";
			break;			
			case "email_test":
				$html = $ci->nsmarty->fetch('frontend/modul/email_invoice.html');
				$subject = "Test Mail Aja";
			break;		
			
		}
		
		$config = array(
			"protocol"	=>"smtp"
			,"mailtype" => "html"
			,"smtp_host" => "ssl://mail.mks-store.id"
			,"smtp_user" => "notification@mks-store.id"
			,"smtp_pass" => "zi8quv2x8vfo"
			,"smtp_port" => "465",
			'charset' => 'utf-8',
            'wordwrap' => TRUE,
		);
		
		//*/
		/*
		$config = array(
			"protocol"	=>"smtp"
			,"mailtype" => "html"
			,"smtp_host" => "ssl://smtp.gmail.com"
			,"smtp_user" => "none.id@gmail.com"
			,"smtp_pass" => ""
			,"smtp_port" => "465",
			'charset' => 'utf-8',
            'wordwrap' => TRUE,
		);
		*/
		
		//,"smtp_user" => "none.id@gmail.com","smtp_pass" => "wonogiri100km" */
		
		$ci->email->initialize($config);
		//$ci->email->from("none.id@gmail.com", "MKS Store Notifikasi");
		$ci->email->from("notification@mks-store.id", "MKS-Store Notifikasi");
		$ci->email->to($email);
		$ci->email->subject($subject);
		$ci->email->message($html);
		$ci->email->set_newline("\r\n");
		if($ci->email->send())
			//echo "<h3> SUKSES EMAIL ke $email </h3>";
			return 1;
		else
			//echo $this->email->print_debugger();
			return $ci->email->print_debugger();
	}	
	//End Class KirimEmail
	
	//Class Konversi Bulan
	function konversi_bulan($bln){
		switch($bln){
			case 1:$bulan='Januari';break;
			case 2:$bulan='Februari';break;
			case 3:$bulan='Maret';break;
			case 4:$bulan='April';break;
			case 5:$bulan='Mei';break;
			case 6:$bulan='Juni';break;
			case 7:$bulan='Juli';break;
			case 8:$bulan='Agustus';break;
			case 9:$bulan='September';break;
			case 10:$bulan='Oktober';break;
			case 11:$bulan='November';break;
			case 12:$bulan='Desember';break;
		}
		return $bulan;
	}
	//End Class Konversi Bulan
	
	//Class Konversi Tanggal
	function konversi_tgl($date){
		$ci =& get_instance();
		$ci->load->helper('terbilang');
		$data=array();
		$timestamp = strtotime($date);
		$day = date('D', $timestamp);
		$day_angka = (int)date('d', $timestamp);
		$month = date('m', $timestamp);
		$years = date('Y', $timestamp);
		switch($day){
			case "Mon":$data['hari']='Senin';break;
			case "Tue":$data['hari']='Selasa';break;
			case "Wed":$data['hari']='Rabu';break;
			case "Thu":$data['hari']='Kamis';break;
			case "Fri":$data['hari']='Jumat';break;
			case "Sat":$data['hari']='Sabtu';break;
			case "Sun":$data['hari']='Minggu';break;
		}
		switch($month){
			case "01":$data['bulan']='Januari';break;	
			case "02":$data['bulan']='Februari';break;	
			case "03":$data['bulan']='Maret';break;	
			case "04":$data['bulan']='April';break;	
			case "05":$data['bulan']='Mei';break;	
			case "06":$data['bulan']='Juni';break;	
			case "07":$data['bulan']='Juli';break;	
			case "08":$data['bulan']='Agustus';break;	
			case "09":$data['bulan']='September';break;	
			case "10":$data['bulan']='Oktober';break;	
			case "11":$data['bulan']='November';break;	
			case "12":$data['bulan']='Desember';break;	
		}
		$data['tahun']=ucwords(number_to_words($years));
		$data['tgl_text']=ucwords(number_to_words($day_angka));
		return $data;
	}
	//End Class Konversi Tanggal
	
	//Start Class Konversi Jam
	function konversi_jam($time){
		$data = array();
		$times = explode(":", $time);
		$data["jam"] = ucwords(number_to_words((int)$times[0]));
		$data["menit"] = ucwords(number_to_words((int)$times[1]));
		$data["detik"] = ucwords(number_to_words((int)$times[2]));
		return $data;
	}
	//End Class Konversi Jam
	
	//konversi hari
	function convert_day_name($day){
		switch($day){
			case "Monday":
				return "Senin";
			break;
			case "Tuesday":
				return "Selasa";
			break;
			case "Wednesday":
				return "Rabu";
			break;
			case "Thursday":
				return "Kamis";
			break;
			case "Friday":
				return "Jumat";
			break;
			case "Saturday":
				return "Sabtu";
			break;
			case "Sunday":
				return "Minggu";
			break;
		}
	}	
	//end konversi hari
	
	//Class Fillcombo
	function fillcombo($type="", $balikan="", $p1="", $p2="", $p3=""){
		$ci =& get_instance();
		$ci->load->model('mfrontend');
		
		$v = $ci->input->post('v');
		if($v != ""){
			$selTxt = $v;
		}else{
			$selTxt = $p1;
		}
		
		
		$optTemp = '<option value=""> -- Pilih -- </option>';
		switch($type){
			case "jenis_pembayaran":
				$data = array(
					'0' => array('id'=>'CASH','txt'=>'CASH'),
					'1' => array('id'=>'DEBIT','txt'=>'KARTU DEBIT'),
					'2' => array('id'=>'KREDIT','txt'=>'KARTU KREDIT'),
				);
			break;
			case "jenis_kelamin":
				$data = array(
					'0' => array('id'=>'L','txt'=>'Laki-Laki'),
					'1' => array('id'=>'P','txt'=>'Perempuan'),
				);
			break;
			case "status":
				$data = array(
					'0' => array('id'=>'1','txt'=>'Active'),
					'1' => array('id'=>'0','txt'=>'Inactive'),
				);
			break;
			case "zona_pengiriman":
				$data = array(
					'0' => array('id'=>'1','txt'=>'Zona Pengiriman 1'),
					'1' => array('id'=>'2','txt'=>'Zona Pengiriman 2'),
					'2' => array('id'=>'3','txt'=>'Zona Pengiriman 3'),
					'3' => array('id'=>'4','txt'=>'Zona Pengiriman 4'),
					'4' => array('id'=>'5','txt'=>'Zona Pengiriman 5'),
				);
			break;
			default:
				$data = $ci->mfrontend->get_combo($type, $p1, $p2);
			break;
		}
		
		if($data){
			foreach($data as $k=>$v){
				if($selTxt == $v['id']){
					$optTemp .= '<option selected value="'.$v['id'].'">'.$v['txt'].'</option>';
				}else{ 
					$optTemp .= '<option value="'.$v['id'].'">'.$v['txt'].'</option>';	
				}
			}
		}
		
		if($balikan == 'return'){
			return $optTemp;
		}elseif($balikan == 'echo'){
			echo $optTemp;
		}
		
	}
	//End Class Fillcombo
	
	//Function Json Grid
	function json_grid($sql,$type="",$table=""){
		$ci =& get_instance();
		$ci->load->database();
		
		$page = (integer) (($ci->input->post('page')) ? $ci->input->post('page') : "1");
		$limit = (integer) (($ci->input->post('rows')) ? $ci->input->post('rows') : "10");
		if($type=="tbl_monitor"){
			$sql_t="SELECT A.id FROM tbl_h_pemesanan A LEFT JOIN tbl_registrasi J ON A.tbl_registrasi_id=J.id WHERE J.alamat_pengiriman <> ''";
			$count=$ci->db->query($sql_t)->num_rows();
		}else{
			$count = $ci->db->query($sql)->num_rows();
		}
		
		if( $count >0 ) { $total_pages = ceil($count/$limit); } else { $total_pages = 0; } 
		if ($page > $total_pages) $page=$total_pages; 
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		if($start<0) $start=0;
		 		
		$sql = $sql . " LIMIT $start,$limit";
		//echo $sql;exit;			
		$data = $ci->db->query($sql)->result_array();  
				
		if($data){
		   $responce = new stdClass();
		   $responce->rows= $data;
		   $responce->total =$count;
		   return json_encode($responce);
		}else{ 
		   $responce = new stdClass();
		   $responce->rows = 0;
		   $responce->total = 0;
		   return json_encode($responce);
		} 
	}
	//end Json Grid
	
	//fungsi oauthtoken
	function oauthtoken($client_id, $client_secret, $code, $redirect_uri, $token_endpoint){
		$params = array(
			'grant_type' => 'authorization_code',
			'client_id' => $client_id,
			'client_secret' => $client_secret,
			'code' => $code,
			'redirect_uri' => $redirect_uri,
		);
		//print_r($params);exit;
		//$url_params = http_build_query($params);
		$url = $token_endpoint;
		/*$url_params = http_build_query($params);
		
		$url = $token_endpoint.$url_params;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
		*/
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
		$result = curl_exec($curl);
		curl_close($curl);
		$result_obj = json_decode($result, true);
		
		return $result_obj;
		
		
	}
	//end fungsi oauthtoken
	
	//fungsi get identity
	function oauthidentity($access_token, $url_user){
		$params = array(
			'access_token' => $access_token, // PROVIDER SPECIFIC: the access_token is passed to Google via POST param
		);
		
		/*$url_params = http_build_query($params);
		$url = $url_user . $url_params; // TODO: we probably want to send this using a curl_setopt...
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		$result_obj = json_decode($result, true);
		
		return $result_obj;
		*/
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url_user);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
		$result = curl_exec($curl);
		curl_close($curl);
		$result_obj = json_decode($result, true);
				
		return $result_obj;
	}
	function oauthsekolah($sekolah_token, $url_user){
		$params = array(
			'access_token' => $sekolah_token, // PROVIDER SPECIFIC: the access_token is passed to Google via POST param
		);
		
		/*$url_params = http_build_query($params);
		$url = $url_user . $url_params; // TODO: we probably want to send this using a curl_setopt...
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		$result_obj = json_decode($result, true);
		
		return $result_obj;
		*/
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url_user);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
		$result = curl_exec($curl);
		curl_close($curl);
		$result_obj = json_decode($result, true);
		
		return $result_obj;
	}
	//end fungsi get identity
	
	//Class String Sanitizer
	function clean($string,$pengganti) {
		$string = str_replace(' ', $pengganti, $string); // Replaces all spaces with hyphens.
		return preg_replace('/[^A-Za-z0-9\-]/', $pengganti, $string); // Removes special chars.
	}	
	
	// Encode Decode URL
	function base64url_encode($data) { 
	  return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
	} 

	function base64url_decode($data) { 
	  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
	} 	
	// End Encode Decode URL
}