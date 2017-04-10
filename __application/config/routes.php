<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'frontend';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['backoffice'] = 'backend';
$route['backoffice-masuk'] = 'login';
$route['backoffice-keluar'] = 'login/logout';
$route['backoffice-grid/(:any)'] = 'backend/get_grid/$1';
$route['backoffice-form/(:any)'] = 'backend/get_form/$1';
$route['backoffice-combo'] = 'backend/get_combo';
$route['backoffice-simpan/(:any)/(:any)'] = 'backend/simpandata/$1/$2';
$route['backoffice-delete'] = 'backend/simpandata';
$route['backoffice-upload'] = 'backend/upload';
$route['backoffice-hapusFile'] = 'backend/hapus_file';
$route['backoffice-GetDetil'] = 'backend/get_konten';
$route['backoffice-Cetak'] = 'backend/cetak';
$route['backoffice-SetFlag'] = 'backend/set_flag';
$route['backoffice-Dashboard'] = 'backend/get_konten';
$route['backoffice-GetDataChart'] = 'backend/get_chart';
$route['backoffice-laporan/(:any)'] = 'backend/get_form/$1';
$route['Backoffice-Pesan'] = 'backend/get_pesan';

$route['simpan-tandaterima'] = 'backend/simpandata';
$route['onoff-produk'] = 'backend/simpandata';
$route['mapping-add'] = 'backend/mappingpaket/add';
$route['mapping-delete'] = 'backend/mappingpaket/delete';

$route['backupform'] = 'backend/backupsystem/formbackup';
$route['backupdb'] = 'backend/backupsystem/backupdb';
$route['backup-application'] = 'backend/backupsystem/application';
$route['backup-assets'] = 'backend/backupsystem/assets';
$route['backup-repository'] = 'backend/backupsystem/repository';


/* Routes Front End Routes */
$route['beranda'] = 'frontend/getdisplay/main_page/beranda'; //kepake
$route['loading-beranda'] = 'frontend/getdisplay/loading_page/beranda'; //kepake

$route['profile'] = 'frontend/getdisplay/loading_page/profile'; //kepake
$route['loading-profile'] = 'frontend/getdisplay/loading_page/profile'; //kepake

$route['kontak'] = 'frontend/getdisplay/main_page/kontak'; //kepake
$route['loading-kontak'] = 'frontend/getdisplay/loading_page/kontak'; //kepake

$route['katalog'] = 'frontend/getdisplay/main_page/katalog'; //kepake
$route['loading-katalog'] = 'frontend/getdisplay/loading_page/katalog'; //kepake
$route['paginationdata'] = 'frontend/getdisplay/loading_page/datapaging'; //kepake
$route['filtering-data'] = 'frontend/getdisplay/loading_page/filterdt'; //kepake
$route['cari-data'] = 'frontend/getdisplay/loading_page/crdt'; //kepake

$route['katalogpaket'] = 'frontend/getdisplay/main_page/katalogpaket'; // kepake
$route['loading-katalogpaket'] = 'frontend/getdisplay/loading_page/katalogpaket'; // kepake
$route['paginationdatapaket'] = 'frontend/getdisplay/loading_page/katalogpaketpaging'; // kepake
$route['filtering-datapaket'] = 'frontend/getdisplay/loading_page/filterdtpaket'; // kepake

$route['detailpaket/(:any)/(:any)'] = 'frontend/getdisplay/main_page/detailpaket/$1/$2';
$route['loading-detailpaket'] = 'frontend/getdisplay/loading_page/detailpaket/'; //kepake

$route['kategori/(:any)'] = 'frontend/getdisplay/main_page/kategori/$1';
$route['loading-kategori/(:any)'] = 'frontend/getdisplay/loading_page/kategori/$1';

$route['detailproduk/(:any)/(:any)'] = 'frontend/getdisplay/main_page/detailproduk/$1/$2'; //kepake
$route['loading-detailproduk'] = 'frontend/getdisplay/loading_page/detailproduk/'; //kepake

$route['keranjang-belanja'] = 'frontend/getdisplay/loading_page/keranjangnya'; // kepake
$route['banyak-belanja'] = 'frontend/getdisplay/loading_page/total_item';
$route['keranjang-belanja-masuk'] = 'frontend/keranjang_belanja/add'; // kepake
$route['keranjang-belanja-masuk-paket'] = 'frontend/keranjang_belanja/add_paket'; // kepake
$route['hapus-keranjang'] = 'frontend/keranjang_belanja/delete'; //kepake
$route['perbaharui-keranjang'] = 'frontend/keranjang_belanja/update'; //kepake
$route['perbaharui-keranjang-hapus'] = 'frontend/keranjang_belanja/deletekeranjang'; //kepake

$route['selesaibelanja'] = 'frontend/getdisplay/main_page/selesaibelanja'; // kepake
$route['loading-selesaibelanja'] = 'frontend/getdisplay/loading_page/selesaibelanja'; //kepake
$route['submit-transaksi'] = 'frontend/cruddata/form/checkout'; //kepake
$route['finish'] = 'frontend/getdisplay/loading_page/finish_semuanya'; // kepake

$route['datapesanan'] = 'frontend/getdisplay/main_page/datapesanan'; // kepake
$route['loading-datapesanan'] = 'frontend/getdisplay/loading_page/datapesanan'; // kepake

$route['loading-formcheckout'] = 'frontend/getdisplay/loading_page/form_isian_checkout';
$route['combo-kab-kota'] = 'frontend/getdisplay/loading_page/combo_kab_kota'; // kepake
$route['combo-kecamatan'] = 'frontend/getdisplay/loading_page/combo_kecamatan'; // kepake
$route['combo-kab-kota-sekolah'] = 'frontend/getdisplay/loading_page/combo_kab_kota_sekolah'; // kepake
$route['combo-kecamatan-sekolah'] = 'frontend/getdisplay/loading_page/combo_kecamatan_sekolah'; // kepake

$route['konfirmasi/(:any)'] = 'frontend/getdisplay/main_page/konfirmasi/$1'; // kepake
$route['loading-konfirmasi'] = 'frontend/getdisplay/loading_page/konfirmasi_pemb'; // kepake
$route['submit-konfirmasi'] = 'frontend/cruddata/form/konf'; //kepake

$route['uploadfile/(:any)'] = 'frontend/getdisplay/main_page/uploadfile/$1'; // kepake
$route['loading-uploadfile'] = 'frontend/getdisplay/loading_page/uploadfile'; // kepake
$route['submit-uploadfile'] = 'frontend/cruddata/form/uploadfile'; // kepake

$route['detailorder/(:any)'] = 'frontend/getdisplay/main_page/detailorder/$1'; // kepake
$route['loading-detailorder'] = 'frontend/getdisplay/loading_page/detail_order'; // kepake

$route['cetak-bast'] = 'frontend/generatepdf/bastnya'; //kepake
$route['cetak-kwitansi'] = 'frontend/generatepdf/kwitansinya'; //kepake
$route['cetak-tanda-terima'] = 'frontend/generatepdf/tandaterima'; //kepake
$route['cetak-surat-pesanan'] = 'frontend/generatepdf/suratpesanan'; //kepake

$route['testimonial'] = 'frontend/getdisplay/main_page/testimonial'; //kepake
$route['loading-testimonial'] = 'frontend/getdisplay/loading_page/testimonial'; //kepake
$route['submit-testimonial'] = 'frontend/cruddata/form/testimonial'; //kepake

$route['login'] = 'frontend/getdisplay/main_page/login'; // kepake
$route['loading-login'] = 'frontend/getdisplay/loading_page/login'; // kepake
$route['submit-login'] = 'frontend/loginpembeli'; // kepake
$route['logoutpembeli'] = 'frontend/logoutpembeli'; // kepake

$route['submit-update-profil'] = 'frontend/cruddata/form/updateprofile';

$route['registrasi'] = 'frontend/getdisplay/main_page/registrasi';
$route['loading-registrasi'] = 'frontend/getdisplay/loading_page/registrasi';
$route['submit-registrasi'] = 'frontend/cruddata/form/registrasi';
$route['finish-registrasi'] = 'frontend/getdisplay/loading_page/finish_registrasi';

$route['ubahpassword'] = 'frontend/getdisplay/main_page/ubahpassword';
$route['loading-ubahpassword'] = 'frontend/getdisplay/loading_page/ubahpassword';
$route['submit-update-password'] = 'frontend/cruddata/form/ubahpassword';

// Utk Reporting Kementerian Pendidikan
$route['data_pesanan_all'] = 'frontend/report_kementerian/all';


// ROuting gak kepake

$route['tracking'] = 'frontend/getdisplay/main_page/tracking';
$route['loading-tracking'] = 'frontend/getdisplay/loading_page/tracking';

$route['histori'] = 'frontend/getdisplay/main_page/histori';
$route['loading-histori'] = 'frontend/getdisplay/loading_page/histori';


$route['harga-per-zona'] = 'frontend/getdisplay/loading_page/zona_pengiriman';
$route['session-zona'] = 'frontend/getdisplay/loading_page/combo_zona';
$route['pilih-zona'] = 'frontend/cruddata/session_zona';

$route['loading-formkonf'] = 'frontend/getdisplay/loading_page/konfrom';

$route['lacakpesanan'] = 'frontend/getdisplay/main_page/lacakpesanan';
$route['loading-lacakpesanan'] = 'frontend/getdisplay/loading_page/lacakpesan';
$route['loading-lacakform'] = 'frontend/getdisplay/loading_page/lacakform';

$route['riwayatpesanan'] = 'frontend/getdisplay/main_page/riwayat';
$route['loading-riwayat'] = 'frontend/getdisplay/loading_page/pesananriwayat';
$route['loading-riwayatform'] = 'frontend/getdisplay/loading_page/formriwayat';

$route['komplain'] = 'frontend/getdisplay/main_page/komplain';
$route['loading-komplain'] = 'frontend/getdisplay/loading_page/laykomplainbro';
$route['submit-komplain'] = 'frontend/cruddata/form/komp';

$route['bantuan'] = 'frontend/getdisplay/main_page/bantuan';
$route['loading-bantuan'] = 'frontend/getdisplay/loading_page/bantuan';

$route['pembatalan'] = 'frontend/getdisplay/main_page/pembatalan';
$route['loading-pembatalan'] = 'frontend/getdisplay/loading_page/pembatalanpesan';
$route['loading-formpemb'] = 'frontend/getdisplay/loading_page/formpembatalancuy';
$route['submit-pembatalan'] = 'frontend/cruddata/form/pembss';








