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

$route['profile'] = 'frontend/getdisplay/loading_page/profile';
$route['loading-profile'] = 'frontend/getdisplay/loading_page/profile';

$route['tracking'] = 'frontend/getdisplay/main_page/tracking';
$route['loading-tracking'] = 'frontend/getdisplay/loading_page/tracking';

$route['histori'] = 'frontend/getdisplay/main_page/histori';
$route['loading-histori'] = 'frontend/getdisplay/loading_page/histori';

$route['kontak'] = 'frontend/getdisplay/main_page/kontak';
$route['loading-kontak'] = 'frontend/getdisplay/loading_page/kontak';

$route['katalog'] = 'frontend/getdisplay/main_page/katalog'; //kepake
$route['loading-katalog'] = 'frontend/getdisplay/loading_page/katalog'; //kepake
$route['paginationdata'] = 'frontend/getdisplay/loading_page/datapaging'; //kepake
$route['filtering-data'] = 'frontend/getdisplay/loading_page/filterdt'; //kepake
$route['cari-data'] = 'frontend/getdisplay/loading_page/crdt'; //kepake

$route['katalogpaket'] = 'frontend/getdisplay/main_page/katalogpaket';
$route['loading-katalogpaket'] = 'frontend/getdisplay/loading_page/katalogpaket';
$route['paginationdatapaket'] = 'frontend/getdisplay/loading_page/katalogpaketpaging';
$route['filtering-datapaket'] = 'frontend/getdisplay/loading_page/filterdtpaket';

$route['detailpaket/(:any)/(:any)'] = 'frontend/getdisplay/main_page/detailpaket/$1/$2';
$route['loading-detailpaket'] = 'frontend/getdisplay/loading_page/detailpaket/'; //kepake

$route['kategori/(:any)'] = 'frontend/getdisplay/main_page/kategori/$1';
$route['loading-kategori/(:any)'] = 'frontend/getdisplay/loading_page/kategori/$1';

$route['detailproduk/(:any)/(:any)'] = 'frontend/getdisplay/main_page/detailproduk/$1/$2'; //kepake
$route['loading-detailproduk'] = 'frontend/getdisplay/loading_page/detailproduk/'; //kepake

$route['harga-per-zona'] = 'frontend/getdisplay/loading_page/zona_pengiriman';

$route['session-zona'] = 'frontend/getdisplay/loading_page/combo_zona';
$route['pilih-zona'] = 'frontend/cruddata/session_zona';

$route['keranjang-belanja'] = 'frontend/getdisplay/loading_page/keranjangnya'; // kepake
$route['banyak-belanja'] = 'frontend/getdisplay/loading_page/total_item';
$route['keranjang-belanja-masuk'] = 'frontend/keranjang_belanja/add'; // kepake
$route['keranjang-belanja-masuk-paket'] = 'frontend/keranjang_belanja/add_paket'; // kepake
$route['perbaharui-keranjang'] = 'frontend/getdisplay/loading_page/update_keranjang';
$route['hapus-keranjang'] = 'frontend/getdisplay/loading_page/hapusitem_keranjang';

$route['selesaibelanja'] = 'frontend/getdisplay/main_page/selesaibelanja'; // kepake
$route['loading-selesaibelanja'] = 'frontend/getdisplay/loading_page/selesaibelanja'; //kepake
$route['submit-transaksi'] = 'frontend/cruddata/form/checkout'; //kepake
$route['finish'] = 'frontend/getdisplay/loading_page/finish_semuanya';

$route['datapesanan'] = 'frontend/getdisplay/main_page/datapesanan'; // kepake
$route['loading-datapesanan'] = 'frontend/getdisplay/loading_page/datapesanan'; // kepake

$route['loading-formcheckout'] = 'frontend/getdisplay/loading_page/form_isian_checkout';
$route['combo-kab-kota'] = 'frontend/getdisplay/loading_page/combo_kab_kota';
$route['combo-kecamatan'] = 'frontend/getdisplay/loading_page/combo_kecamatan';

$route['konfirmasi/(:any)'] = 'frontend/getdisplay/main_page/konfirmasi/$1'; // kepake
$route['loading-konfirmasi'] = 'frontend/getdisplay/loading_page/konfirmasi_pemb'; // kepake
$route['submit-konfirmasi'] = 'frontend/cruddata/form/konf'; //kepake

$route['uploadfile/(:any)'] = 'frontend/getdisplay/main_page/uploadfile/$1'; // kepake
$route['loading-uploadfile'] = 'frontend/getdisplay/loading_page/uploadfile'; // kepake
$route['submit-uploadfile'] = 'frontend/cruddata/form/uploadfile'; // kepake

$route['detailorder/(:any)'] = 'frontend/getdisplay/main_page/detailorder/$1'; // kepake
$route['loading-detailorder'] = 'frontend/getdisplay/loading_page/detail_order'; // kepake

$route['loading-formkonf'] = 'frontend/getdisplay/loading_page/konfrom';

$route['cetak-bast'] = 'frontend/generatepdf/bastnya'; //kepake
$route['cetak-kwitansi'] = 'frontend/generatepdf/kwitansinya'; //kepake
$route['cetak-tanda-terima'] = 'frontend/generatepdf/tandaterima'; //kepake
$route['cetak-surat-pesanan'] = 'frontend/generatepdf/suratpesanan'; //kepake

$route['lacakpesanan'] = 'frontend/getdisplay/main_page/lacakpesanan';
$route['loading-lacakpesanan'] = 'frontend/getdisplay/loading_page/lacakpesan';
$route['loading-lacakform'] = 'frontend/getdisplay/loading_page/lacakform';

$route['riwayatpesanan'] = 'frontend/getdisplay/main_page/riwayat';
$route['loading-riwayat'] = 'frontend/getdisplay/loading_page/pesananriwayat';
$route['loading-riwayatform'] = 'frontend/getdisplay/loading_page/formriwayat';


$route['komplain'] = 'frontend/getdisplay/main_page/komplain';
$route['loading-komplain'] = 'frontend/getdisplay/loading_page/laykomplainbro';
$route['submit-komplain'] = 'frontend/cruddata/form/komp';

$route['caraberbelanja'] = 'frontend/getdisplay/main_page/carabelanja';
$route['loading-carabelanja'] = 'frontend/getdisplay/loading_page/belanjanyacara';

$route['pembatalan'] = 'frontend/getdisplay/main_page/pembatalan';
$route['loading-pembatalan'] = 'frontend/getdisplay/loading_page/pembatalanpesan';
$route['loading-formpemb'] = 'frontend/getdisplay/loading_page/formpembatalancuy';
$route['submit-pembatalan'] = 'frontend/cruddata/form/pembss';

$route['registrasi'] = 'frontend/getdisplay/main_page/registrasi';
$route['loading-registrasi'] = 'frontend/getdisplay/loading_page/registrasi';
$route['submit-registrasi'] = 'frontend/cruddata/form/registrasi';
$route['finish-registrasi'] = 'frontend/getdisplay/loading_page/finish_registrasi';

$route['login'] = 'frontend/getdisplay/main_page/login';
$route['loading-login'] = 'frontend/getdisplay/loading_page/login';
$route['submit-login'] = 'frontend/loginpembeli';
$route['logoutpembeli'] = 'frontend/logoutpembeli';

$route['komentar'] = 'frontend/getdisplay/main_page/komentarpelanggan';
$route['loading-komentarpelanggan'] = 'frontend/getdisplay/loading_page/komentarpelanggan';
$route['submit-komentar'] = 'frontend/cruddata/form/komentar';


$route['data_pesanan_all'] = 'frontend/report_kementerian/all';








