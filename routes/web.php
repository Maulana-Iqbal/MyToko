<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckOngkirController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HariKerjaController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PresentsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\XenditController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);





Auth::routes(['register' => false]);
Route::get('/forgot-password', function () {
    return view('auth.passwords.email');
})->middleware('guest')->name('password.request');
Route::get('/password/reset/{token}', function ($token) {
    return view('auth.passwords.reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');


// Route::group(['middleware' => ['auth']], function() {
//     Route::resource('roles', RoleController::class);
//     Route::resource('users', UserController::class);
//     Route::resource('products', ProductController::class);
// });

Route::group(['middleware' => ['auth', 'ceklevel:COSTUMER']], function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index']);
});

Route::group(['middleware' => ['auth', 'ceklevel:SUPERADMIN,SUPERCEO,ADMIN,CEO,STAFF,PEGAWAI']], function () {
    // Route::get('/galeri', [App\Http\Controllers\GaleriController::class, 'index']);
    Route::post('/upload-gambar', [App\Http\Controllers\GaleriController::class, 'store']);
    Route::post('/aksi-galeri', [App\Http\Controllers\GaleriController::class, 'aksiGaleri']);
    Route::delete('/hapus-galeri/{id}', [App\Http\Controllers\GaleriController::class, 'destroy']);

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index']);
    //Produk
    Route::get('/produk', [App\Http\Controllers\ProdukController::class, 'index']);
    Route::get('/produk/kode', [App\Http\Controllers\ProdukController::class, 'kode']);
    Route::post('/produkTable', [App\Http\Controllers\ProdukController::class, 'index']);
    Route::get('/produk/pilih', [App\Http\Controllers\ProdukController::class, 'produkPilih']);
    Route::post('/produk/pilih/table', [App\Http\Controllers\ProdukController::class, 'produkPilih']);
    Route::get('/produk/get-one/{id}', [App\Http\Controllers\ProdukController::class, 'getOne']);
    Route::post('/produkTrashTable', [App\Http\Controllers\ProdukController::class, 'trashTable']);
    Route::post('/produk/simpan', [App\Http\Controllers\ProdukController::class, 'store']);
    Route::post('/produk/update', [App\Http\Controllers\ProdukController::class, 'update']);
    Route::get('/produk/{id}/edit', [App\Http\Controllers\ProdukController::class, 'edit']);
    Route::get('/produk-trash/{id}', [App\Http\Controllers\ProdukController::class, 'trash']);
    Route::get('/produk-delete/{id}', [App\Http\Controllers\ProdukController::class, 'delete']);
    Route::get('/produk-restore/{id}', [App\Http\Controllers\ProdukController::class, 'restore']);
    Route::post('/produk/update-stock', [App\Http\Controllers\ProdukController::class, 'updateStock']);
    Route::get('/copy-produk/{id}', [App\Http\Controllers\ProdukController::class, 'copyProduk']);
    Route::post('/simpan-gambar', [App\Http\Controllers\ProdukController::class, 'simpanGambar']);
    Route::get('/get-gambar-produk/{id}', [App\Http\Controllers\ProdukController::class, 'getGambarProduk']);
    Route::get('/produk/id/{id}', [App\Http\Controllers\ProdukController::class, 'getId']);
    Route::post('/produk/select', [App\Http\Controllers\ProdukController::class, 'select']);

    Route::get('/barcode', [App\Http\Controllers\BarcodeController::class, 'index']);

    //Kategori
    Route::get('/kategori', [App\Http\Controllers\KategoriController::class, 'index']);
    Route::post('/kategoriTable', [App\Http\Controllers\KategoriController::class, 'index']);
    Route::post('/kategori/simpan', [App\Http\Controllers\KategoriController::class, 'store']);
    Route::get('/kategori/{id}/edit', [App\Http\Controllers\KategoriController::class, 'edit']);
    Route::post('/kategoriTableTrash', [App\Http\Controllers\KategoriController::class, 'trashTable']);
    Route::get('/kategori-trash/{id}', [App\Http\Controllers\KategoriController::class, 'trash']);
    Route::get('/kategori-delete/{id}', [App\Http\Controllers\KategoriController::class, 'delete']);
    Route::get('/kategori-restore/{id}', [App\Http\Controllers\KategoriController::class, 'restore']);
    Route::get('/kategori/bulk-delete', [App\Http\Controllers\KategoriController::class, 'bulkDelete']);
    Route::post('/kategori/select', [App\Http\Controllers\KategoriController::class, 'select']);


    Route::get('/permission', [App\Http\Controllers\PermissionController::class, 'index']);
    Route::post('/permissionTable', [App\Http\Controllers\PermissionController::class, 'index']);
    Route::post('/permission/simpan', [App\Http\Controllers\PermissionController::class, 'store']);
    Route::get('/permission/{id}/edit', [App\Http\Controllers\PermissionController::class, 'edit']);
    Route::get('/permission-trash/{id}', [App\Http\Controllers\PermissionController::class, 'delete']);
    Route::get('/permission/bulk-delete', [App\Http\Controllers\PermissionController::class, 'bulkDelete']);
    Route::post('/permission/select', [App\Http\Controllers\PermissionController::class, 'select']);


    //Satuan
    Route::get('/satuan', [App\Http\Controllers\SatuanController::class, 'index']);
    Route::post('/satuanTable', [App\Http\Controllers\SatuanController::class, 'index']);
    Route::post('/satuan/simpan', [App\Http\Controllers\SatuanController::class, 'store']);
    Route::get('/satuan/{id}/edit', [App\Http\Controllers\SatuanController::class, 'edit']);
    Route::post('/satuanTableTrash', [App\Http\Controllers\SatuanController::class, 'trashTable']);
    Route::get('/satuan-trash/{id}', [App\Http\Controllers\SatuanController::class, 'trash']);
    Route::get('/satuan-delete/{id}', [App\Http\Controllers\SatuanController::class, 'delete']);
    Route::get('/satuan-restore/{id}', [App\Http\Controllers\SatuanController::class, 'restore']);
    Route::get('/satuan/bulk-delete', [App\Http\Controllers\SatuanController::class, 'bulkDelete']);
    Route::post('/satuan/select', [App\Http\Controllers\SatuanController::class, 'select']);

    Route::get('/akun', [App\Http\Controllers\AkunController::class, 'index']);
    Route::post('/akunTable', [App\Http\Controllers\AkunController::class, 'index']);
    Route::post('/akun/simpan', [App\Http\Controllers\AkunController::class, 'store']);
    Route::get('/akun/{id}/edit', [App\Http\Controllers\AkunController::class, 'edit']);
    Route::post('/akunTableTrash', [App\Http\Controllers\AkunController::class, 'trashTable']);
    Route::get('/akun-trash/{id}', [App\Http\Controllers\AkunController::class, 'trash']);
    Route::get('/akun-delete/{id}', [App\Http\Controllers\AkunController::class, 'delete']);
    Route::get('/akun-restore/{id}', [App\Http\Controllers\AkunController::class, 'restore']);
    Route::get('/akun/bulk-delete', [App\Http\Controllers\AkunController::class, 'bulkDelete']);
    Route::post('/akun/select', [App\Http\Controllers\AkunController::class, 'select']);
    Route::post('/akun/select-kategori', [App\Http\Controllers\AkunController::class, 'selectKategori']);
    Route::post('/akun/table-akun', [App\Http\Controllers\AkunController::class, 'tableAkun']);



    Route::get('/kategoriAkun', [App\Http\Controllers\KategoriAkunController::class, 'index']);
    Route::post('/kategoriAkunTable', [App\Http\Controllers\KategoriAkunController::class, 'index']);
    Route::post('/kategoriAkun/simpan', [App\Http\Controllers\KategoriAkunController::class, 'store']);
    Route::get('/kategoriAkun/{id}/edit', [App\Http\Controllers\KategoriAkunController::class, 'edit']);
    Route::post('/kategoriAkunTableTrash', [App\Http\Controllers\KategoriAkunController::class, 'trashTable']);
    Route::get('/kategoriAkun-trash/{id}', [App\Http\Controllers\KategoriAkunController::class, 'trash']);
    Route::get('/kategoriAkun-delete/{id}', [App\Http\Controllers\KategoriAkunController::class, 'delete']);
    Route::get('/kategoriAkun-restore/{id}', [App\Http\Controllers\KategoriAkunController::class, 'restore']);
    Route::get('/kategoriAkun/bulk-delete', [App\Http\Controllers\KategoriAkunController::class, 'bulkDelete']);
    Route::post('/kategoriAkun/select', [App\Http\Controllers\KategoriAkunController::class, 'select']);

    Route::get('/kasBank', [App\Http\Controllers\KasBankController::class, 'index']);
    Route::post('/kasBankTable', [App\Http\Controllers\KasBankController::class, 'index']);
    Route::post('/kasBank/simpan', [App\Http\Controllers\KasBankController::class, 'store']);
    Route::get('/kasBank/{id}/edit', [App\Http\Controllers\KasBankController::class, 'edit']);
    Route::post('/kasBankTableTrash', [App\Http\Controllers\KasBankController::class, 'trashTable']);
    Route::get('/kasBank-trash/{id}', [App\Http\Controllers\KasBankController::class, 'trash']);
    Route::get('/kasBank-delete/{id}', [App\Http\Controllers\KasBankController::class, 'delete']);
    Route::get('/kasBank-restore/{id}', [App\Http\Controllers\KasBankController::class, 'restore']);
    Route::get('/kasBank/bulk-delete', [App\Http\Controllers\KasBankController::class, 'bulkDelete']);
    Route::post('/kasBank/select', [App\Http\Controllers\KasBankController::class, 'select']);

   
    //Rekening
    Route::get('/rekening', [App\Http\Controllers\RekeningController::class, 'index']);
    Route::post('/rekeningTable', [App\Http\Controllers\RekeningController::class, 'index']);
    Route::post('/rekening/simpan', [App\Http\Controllers\RekeningController::class, 'store']);
    Route::get('/rekening/{id}/edit', [App\Http\Controllers\RekeningController::class, 'edit']);
    Route::post('/rekeningTableTrash', [App\Http\Controllers\RekeningController::class, 'trashTable']);
    Route::get('/rekening-trash/{id}', [App\Http\Controllers\RekeningController::class, 'trash']);
    Route::get('/rekening-delete/{id}', [App\Http\Controllers\RekeningController::class, 'delete']);
    Route::get('/rekening-restore/{id}', [App\Http\Controllers\RekeningController::class, 'restore']);
    Route::get('/rekening/bulk-delete', [App\Http\Controllers\RekeningController::class, 'bulkDelete']);
    Route::post('/rekening/select', [App\Http\Controllers\RekeningController::class, 'select']);

    //Pemasok
    Route::get('/supplier', [App\Http\Controllers\PemasokController::class, 'index']);
    Route::post('/pemasokTable', [App\Http\Controllers\PemasokController::class, 'index']);
    Route::post('/pemasok/simpan', [App\Http\Controllers\PemasokController::class, 'store']);
    Route::get('/pemasok/{id}/edit', [App\Http\Controllers\PemasokController::class, 'edit']);
    Route::post('/pemasokTableTrash', [App\Http\Controllers\PemasokController::class, 'trashTable']);
    Route::get('/pemasok-trash/{id}', [App\Http\Controllers\PemasokController::class, 'trash']);
    Route::get('/pemasok-delete/{id}', [App\Http\Controllers\PemasokController::class, 'delete']);
    Route::get('/pemasok-restore/{id}', [App\Http\Controllers\PemasokController::class, 'restore']);
    Route::post('/pemasok/bulk-delete', [App\Http\Controllers\PemasokController::class, 'bulkDelete']);
    Route::post('/supplier/select', [App\Http\Controllers\PemasokController::class, 'select']);
    Route::get('/supplier/kode', [App\Http\Controllers\PemasokController::class, 'kode']);

    Route::get('/sales', [App\Http\Controllers\SalesController::class, 'index']);
    Route::post('/salesTable', [App\Http\Controllers\SalesController::class, 'index']);
    Route::post('/sales/simpan', [App\Http\Controllers\SalesController::class, 'store']);
    Route::get('/sales/{id}/edit', [App\Http\Controllers\SalesController::class, 'edit']);
    Route::post('/salesTableTrash', [App\Http\Controllers\SalesController::class, 'trashTable']);
    Route::get('/sales-trash/{id}', [App\Http\Controllers\SalesController::class, 'trash']);
    Route::get('/sales-delete/{id}', [App\Http\Controllers\SalesController::class, 'delete']);
    Route::get('/sales-restore/{id}', [App\Http\Controllers\SalesController::class, 'restore']);
    Route::post('/sales/bulk-delete', [App\Http\Controllers\SalesController::class, 'bulkDelete']);
    Route::post('/sales/select', [App\Http\Controllers\SalesController::class, 'select']);
    Route::get('/sales/kode', [App\Http\Controllers\SalesController::class, 'kode']);


    Route::get('/gudang', [App\Http\Controllers\GudangController::class, 'index']);
    Route::post('/gudangTable', [App\Http\Controllers\GudangController::class, 'index']);
    Route::post('/gudang/simpan', [App\Http\Controllers\GudangController::class, 'store']);
    Route::get('/gudang/{id}/edit', [App\Http\Controllers\GudangController::class, 'edit']);
    Route::post('/gudangTableTrash', [App\Http\Controllers\GudangController::class, 'trashTable']);
    Route::get('/gudang-trash/{id}', [App\Http\Controllers\GudangController::class, 'trash']);
    Route::get('/gudang-delete/{id}', [App\Http\Controllers\GudangController::class, 'delete']);
    Route::get('/gudang-restore/{id}', [App\Http\Controllers\GudangController::class, 'restore']);
    Route::post('/gudang/bulk-delete', [App\Http\Controllers\GudangController::class, 'bulkDelete']);
    Route::post('/gudang/select', [App\Http\Controllers\GudangController::class, 'select']);
    Route::get('/gudang/kode', [App\Http\Controllers\GudangController::class, 'kode']);

    Route::get('/customer', [App\Http\Controllers\PelangganController::class, 'index']);
    Route::post('/pelangganTable', [App\Http\Controllers\PelangganController::class, 'index']);
    Route::post('/pelanggan/simpan', [App\Http\Controllers\PelangganController::class, 'store']);
    Route::get('/pelanggan/{id}/edit', [App\Http\Controllers\PelangganController::class, 'edit']);
    Route::get('/pelanggan-delete/{id}', [App\Http\Controllers\PelangganController::class, 'delete']);
    Route::get('/pelanggan/bulk-delete', [App\Http\Controllers\PelangganController::class, 'bulkDelete']);
    Route::post('/pelanggan/select', [App\Http\Controllers\PelangganController::class, 'select']);

    //transaksi
    Route::get('/transaksi', [App\Http\Controllers\TransaksiController::class, 'index']);
    Route::get('/transaksi/status/{status}', [App\Http\Controllers\TransaksiController::class, 'index']);
    Route::post('/transaksi/table', [App\Http\Controllers\TransaksiController::class, 'index']);
    Route::post('/transaksi/table/{status}', [App\Http\Controllers\TransaksiController::class, 'index']);
    Route::get('/transaksi/detail/{nomor}', [App\Http\Controllers\TransaksiController::class, 'show']);
    Route::post('/transaksi/simpan', [App\Http\Controllers\TransaksiController::class, 'store']);
    Route::get('/transaksi/{id}/edit', [App\Http\Controllers\TransaksiController::class, 'edit']);
    Route::get('/transaksi/delete/{id}', [App\Http\Controllers\TransaksiController::class, 'destroy']);
    Route::post('/transaksi/simpan-pengiriman', [App\Http\Controllers\TransaksiController::class, 'savePengiriman']);
    Route::post('/transaksi/update-pengiriman', [App\Http\Controllers\TransaksiController::class, 'updatePengiriman']);
    Route::get('/transaksi/ubah-pengiriman/{transaksi_id}', [App\Http\Controllers\TransaksiController::class, 'getPengiriman']);
    Route::get('/transaksi/create-invoice/{id}', [App\Http\Controllers\TransaksiController::class, 'createInvoiceOffline']);
    Route::get('/transaksi/baru', [App\Http\Controllers\TransaksiController::class, 'baru']);
    Route::get('/transaksi/ubah/{id}', [App\Http\Controllers\TransaksiController::class, 'ubah']);
    Route::post('/transaksi/update', [App\Http\Controllers\TransaksiController::class, 'update']);

    Route::get('/addcart/{id}', [App\Http\Controllers\TransaksiController::class, 'addToCart']);
    Route::post('/cartremove/{id}', [App\Http\Controllers\CartOfflineController::class, 'removeCart']);
    Route::get('/getcarttable', [App\Http\Controllers\CartOfflineController::class, 'getCartTable']);
    Route::post('/transaksi/verifikasi-transaksi/{id}', [App\Http\Controllers\TransaksiController::class, 'verifikasiTrans']);

    Route::post('/cart-offline/add-to-cart', [App\Http\Controllers\CartOfflineController::class, 'add']);
    Route::post('/cart-offline/cart-update-biaya', [App\Http\Controllers\CartOfflineController::class, 'updateCartBiaya']);
    Route::post('/cart-offline/cart-update-harga-jual', [App\Http\Controllers\CartOfflineController::class, 'updateCartHargaJual']);
    Route::post('/cart-offline/cart-update-jumlah', [App\Http\Controllers\CartOfflineController::class, 'updateCartJumlah']);
    Route::get('/cart-offline/dengan-ppn', [App\Http\Controllers\CartOfflineController::class, 'enablePpn']);
    Route::get('/cart-offline/hapus-ppn', [App\Http\Controllers\CartOfflineController::class, 'removePpn']);
    Route::get('/cart-offline/dengan-pph', [App\Http\Controllers\CartOfflineController::class, 'enablePph']);
    Route::get('/cart-offline/hapus-pph', [App\Http\Controllers\CartOfflineController::class, 'removePph']);
    Route::post('/cart-offline/cart-biaya-lain', [App\Http\Controllers\CartOfflineController::class, 'biayaLain']);
    Route::post('/cart-offline/cart-biaya-pengiriman', [App\Http\Controllers\CartOfflineController::class, 'biayaPengiriman']);

    Route::get('/pelanggan/list-pelanggan', [App\Http\Controllers\PelangganController::class, 'listPelanggan']);
    Route::get('/cari-pelanggan/{name}', [App\Http\Controllers\PelangganController::class, 'cariPelanggan']);
  
    //Stock
    Route::get('/stock', [App\Http\Controllers\StockController::class, 'index']);
    Route::get('/stock/masuk', [App\Http\Controllers\StockController::class, 'stockJenis']);
    Route::post('/stock/masuk/laporan', [App\Http\Controllers\StockController::class, 'stockJenis']);
    Route::post('/stock/masuk/table', [App\Http\Controllers\StockController::class, 'stockJenis']);
    Route::get('/stock/keluar', [App\Http\Controllers\StockController::class, 'stockJenis']);
    Route::post('/stock/keluar/laporan', [App\Http\Controllers\StockController::class, 'stockJenis']);
    Route::post('/stock/keluar/table', [App\Http\Controllers\StockController::class, 'stockJenis']);
    Route::get('/stock/pengurangan-stock', [App\Http\Controllers\StockController::class, 'stockJenis']);
    Route::post('/stock/pengurangan-stock/laporan', [App\Http\Controllers\StockController::class, 'stockJenis']);
    Route::post('/stock/hapus/table', [App\Http\Controllers\StockController::class, 'stockJenis']);
    Route::post('/stock/no/{jenis}/select', [App\Http\Controllers\StockController::class, 'noSelect']);
    Route::post('/stockTable', [App\Http\Controllers\StockController::class, 'index']);
    Route::post('/history-stock/historyStockTable', [App\Http\Controllers\HistoryStockController::class, 'historyStock']);
    Route::get('/history', [App\Http\Controllers\HistoryStockController::class, 'historyStock']);
    Route::get('/mutasi-stock', [App\Http\Controllers\HistoryStockController::class, 'index']);
    Route::post('/stock/simpan', [App\Http\Controllers\StockController::class, 'store']);
    Route::post('/stock/simpan-keluar', [App\Http\Controllers\StockController::class, 'storeKeluar']);
    Route::get('/stock/transfer', [App\Http\Controllers\StockController::class, 'stockTransfer']);
    Route::post('/stock/transfer/table', [App\Http\Controllers\StockController::class, 'stockTransfer']);
    Route::post('/stock/transfer/simpan', [App\Http\Controllers\StockController::class, 'storeTransfer']);
    Route::post('/stock/update', [App\Http\Controllers\StockController::class, 'update']);
    Route::post('/stock/pengurangan', [App\Http\Controllers\StockController::class, 'pengurangan']);
    Route::post('/stock/ubah-harga', [App\Http\Controllers\StockController::class, 'ubahHarga']);
    Route::get('/stock/{id}/edit', [App\Http\Controllers\StockController::class, 'edit']);
    Route::delete('/stock/{id}', [App\Http\Controllers\StockController::class, 'destroy']);
    Route::post('/stock/laporan', [App\Http\Controllers\StockController::class, 'index']);
    Route::post('/stock/hampir-habis/table', [App\Http\Controllers\StockController::class, 'hampirHabis']);
    Route::post('/stock/habis/table', [App\Http\Controllers\StockController::class, 'habis']);

    Route::post('/stock/transfer/laporan', [App\Http\Controllers\StockController::class, 'stockTransfer']);


    Route::post('/mutasi/laporan', [App\Http\Controllers\HistoryStockController::class, 'historyStock']);


    Route::get('/stock-gudang', [App\Http\Controllers\GudangTransController::class, 'index']);
    Route::post('/stock-gudang/table', [App\Http\Controllers\GudangTransController::class, 'index']);
    Route::post('/stock-gudang/laporan-stock-gudang', [App\Http\Controllers\GudangTransController::class, 'index']);

    //pembelian
    Route::get('/pembelian', [App\Http\Controllers\PembelianController::class, 'index']);
    Route::post('/pembelian/table', [App\Http\Controllers\PembelianController::class, 'index']);
    Route::post('/pembelian/laporan', [App\Http\Controllers\PembelianController::class, 'index']);
    Route::get('/pembelian/create', [App\Http\Controllers\PembelianController::class, 'create']);
    Route::post('/pembelian/store', [App\Http\Controllers\PembelianController::class, 'store']);
    Route::post('/pembelian/update', [App\Http\Controllers\PembelianController::class, 'update']);
    Route::get('/pembelian/kode', [App\Http\Controllers\PembelianController::class, 'kode']);
    Route::get('/pembelian/{id}/edit', [App\Http\Controllers\PembelianController::class, 'edit']);
    Route::get('/pembelian/delete/{id}', [App\Http\Controllers\PembelianController::class, 'destroy']);
    Route::get('/pembelian/detail/{nomor}', [App\Http\Controllers\PembelianController::class, 'show']);
    Route::post('/pembelian/no/select', [App\Http\Controllers\PembelianController::class, 'noSelect']);
    //cart stock order
    Route::get('/pembelian/cart-table', [App\Http\Controllers\CartPembelianController::class, 'getCartTable']);
    Route::post('/pembelian/cart-add', [App\Http\Controllers\CartPembelianController::class, 'add']);
    Route::post('/pembelian/cart-update-jumlah', [App\Http\Controllers\CartPembelianController::class, 'updateCartJumlah']);
    Route::post('/pembelian/cart-remove/{id}', [App\Http\Controllers\CartPembelianController::class, 'removeCart']);

    //preorder
    Route::get('/preorder', [App\Http\Controllers\PreorderController::class, 'index']);
    Route::post('/preorder/table', [App\Http\Controllers\PreorderController::class, 'index']);
    Route::post('/preorder/laporan', [App\Http\Controllers\PreorderController::class, 'index']);
    Route::get('/preorder/create', [App\Http\Controllers\PreorderController::class, 'create']);
    Route::post('/preorder/store', [App\Http\Controllers\PreorderController::class, 'store']);
    Route::post('/preorder/update', [App\Http\Controllers\PreorderController::class, 'update']);
    Route::get('/preorder/kode', [App\Http\Controllers\PreorderController::class, 'kode']);
    Route::get('/preorder/{id}/edit', [App\Http\Controllers\PreorderController::class, 'edit']);
    Route::get('/preorder/delete/{id}', [App\Http\Controllers\PreorderController::class, 'destroy']);
    Route::get('/preorder/detail/{nomor}', [App\Http\Controllers\PreorderController::class, 'show']);
    Route::post('/preorder/no/select', [App\Http\Controllers\PreorderController::class, 'noSelect']);
    Route::post('/preorder/kirim/{id}', [App\Http\Controllers\PreorderController::class, 'kirimEmail']);
    Route::get('/preorder/kirim-po/{id}', [App\Http\Controllers\PreorderController::class, 'kirimEmail']);
    //cart stock order
    Route::get('/preorder/cart-table', [App\Http\Controllers\CartPreorderController::class, 'getCartTable']);
    Route::post('/preorder/cart-add', [App\Http\Controllers\CartPreorderController::class, 'add']);
    Route::post('/preorder/cart-update-jumlah', [App\Http\Controllers\CartPreorderController::class, 'updateCartJumlah']);
    Route::post('/preorder/cart-remove/{id}', [App\Http\Controllers\CartPreorderController::class, 'removeCart']);



    //penjualan
    Route::get('/penjualan', [App\Http\Controllers\PenjualanController::class, 'index']);
    Route::post('/penjualan/table', [App\Http\Controllers\PenjualanController::class, 'index']);
    Route::post('/penjualan/laporan', [App\Http\Controllers\PenjualanController::class, 'index']);
    Route::get('/penjualan/create', [App\Http\Controllers\PenjualanController::class, 'create']);
    Route::post('/penjualan/store', [App\Http\Controllers\PenjualanController::class, 'store']);
    Route::post('/penjualan/update', [App\Http\Controllers\PenjualanController::class, 'update']);
    Route::get('/penjualan/kode', [App\Http\Controllers\PenjualanController::class, 'kode']);
    Route::get('/penjualan/{id}/edit', [App\Http\Controllers\PenjualanController::class, 'edit']);
    Route::get('/penjualan/delete/{id}', [App\Http\Controllers\PenjualanController::class, 'destroy']);
    Route::get('/penjualan/detail/{nomor}', [App\Http\Controllers\PenjualanController::class, 'show']);
    Route::post('/penjualan/no/select', [App\Http\Controllers\PenjualanController::class, 'noSelect']);
    //cart stock order
    Route::get('/penjualan/cart-table', [App\Http\Controllers\CartPenjualanController::class, 'getCartTable']);
    Route::post('/penjualan/cart-add', [App\Http\Controllers\CartPenjualanController::class, 'add']);
    Route::post('/penjualan/cart-update-jumlah', [App\Http\Controllers\CartPenjualanController::class, 'updateCartJumlah']);
    Route::post('/penjualan/cart-remove/{id}', [App\Http\Controllers\CartPenjualanController::class, 'removeCart']);

    //pengurangan
    Route::get('/pengurangan', [App\Http\Controllers\PenguranganController::class, 'index']);
    Route::post('/pengurangan/table', [App\Http\Controllers\PenguranganController::class, 'index']);
    Route::post('/pengurangan/laporan', [App\Http\Controllers\PenguranganController::class, 'index']);
    Route::get('/pengurangan/create', [App\Http\Controllers\PenguranganController::class, 'create']);
    Route::post('/pengurangan/store', [App\Http\Controllers\PenguranganController::class, 'store']);
    Route::post('/pengurangan/update', [App\Http\Controllers\PenguranganController::class, 'update']);
    Route::get('/pengurangan/kode', [App\Http\Controllers\PenguranganController::class, 'kode']);
    Route::get('/pengurangan/{id}/edit', [App\Http\Controllers\PenguranganController::class, 'edit']);
    Route::get('/pengurangan/delete/{id}', [App\Http\Controllers\PenguranganController::class, 'destroy']);
    Route::get('/pengurangan/detail/{nomor}', [App\Http\Controllers\PenguranganController::class, 'show']);
    Route::post('/pengurangan/no/select', [App\Http\Controllers\PenguranganController::class, 'noSelect']);
    //cart stock order
    Route::get('/pengurangan/cart-table', [App\Http\Controllers\CartPenguranganController::class, 'getCartTable']);
    Route::post('/pengurangan/cart-add', [App\Http\Controllers\CartPenguranganController::class, 'add']);
    Route::post('/pengurangan/cart-update-jumlah', [App\Http\Controllers\CartPenguranganController::class, 'updateCartJumlah']);
    Route::post('/pengurangan/cart-remove/{id}', [App\Http\Controllers\CartPenguranganController::class, 'removeCart']);
    Route::post('/pengurangan/cart-update-kondisi', [App\Http\Controllers\CartPenguranganController::class, 'updateKondisi']);

    //stock-transfer
    Route::get('/stock-transfer', [App\Http\Controllers\StockTransferController::class, 'index']);
    Route::post('/stock-transfer/table', [App\Http\Controllers\StockTransferController::class, 'index']);
    Route::post('/stock-transfer/laporan', [App\Http\Controllers\StockTransferController::class, 'index']);
    Route::get('/stock-transfer/create', [App\Http\Controllers\StockTransferController::class, 'create']);
    Route::post('/stock-transfer/store', [App\Http\Controllers\StockTransferController::class, 'store']);
    Route::post('/stock-transfer/update', [App\Http\Controllers\StockTransferController::class, 'update']);
    Route::get('/stock-transfer/kode', [App\Http\Controllers\StockTransferController::class, 'kode']);
    Route::get('/stock-transfer/{id}/edit', [App\Http\Controllers\StockTransferController::class, 'edit']);
    Route::get('/stock-transfer/delete/{id}', [App\Http\Controllers\StockTransferController::class, 'destroy']);
    Route::post('/stock-transfer/terima', [App\Http\Controllers\StockTransferController::class, 'terima']);
    Route::get('/stock-transfer/detail/{nomor}', [App\Http\Controllers\StockTransferController::class, 'show']);
    Route::post('/stock-transfer/no/select', [App\Http\Controllers\StockTransferController::class, 'noSelect']);
    //cart stock order
    Route::get('/stock-transfer/cart-table', [App\Http\Controllers\CartStockTransferController::class, 'getCartTable']);
    Route::post('/stock-transfer/cart-add', [App\Http\Controllers\CartStockTransferController::class, 'add']);
    Route::post('/stock-transfer/cart-update-jumlah', [App\Http\Controllers\CartStockTransferController::class, 'updateCartJumlah']);
    Route::post('/stock-transfer/cart-remove/{id}', [App\Http\Controllers\CartStockTransferController::class, 'removeCart']);



    Route::get('/pengeluaran', [App\Http\Controllers\PengeluaranController::class, 'index']);
    Route::post('/pengeluaranTable', [App\Http\Controllers\PengeluaranController::class, 'index']);
    Route::post('/pengeluaran/simpan', [App\Http\Controllers\PengeluaranController::class, 'store']);
    Route::get('/pengeluaran/{id}/edit', [App\Http\Controllers\PengeluaranController::class, 'edit']);
    Route::delete('/pengeluaran/{id}', [App\Http\Controllers\PengeluaranController::class, 'destroy']);
    Route::post('/pengeluaran/bulk-verifikasi/{aksi}', [App\Http\Controllers\PengeluaranController::class, 'bulkVerifikasi']);


    Route::get('/transfer', [App\Http\Controllers\TransferController::class, 'index']);
    Route::post('/transferTable', [App\Http\Controllers\TransferController::class, 'index']);
    Route::post('/transfer/simpan', [App\Http\Controllers\TransferController::class, 'store']);
    Route::get('/transfer/{id}/edit', [App\Http\Controllers\TransferController::class, 'edit']);
    Route::delete('/transfer/{id}', [App\Http\Controllers\TransferController::class, 'destroy']);
    Route::post('/transfer/bulk-verifikasi/{aksi}', [App\Http\Controllers\TransferController::class, 'bulkVerifikasi']);


    Route::get('/penerimaan', [App\Http\Controllers\PenerimaanController::class, 'index']);
    Route::post('/penerimaanTable', [App\Http\Controllers\PenerimaanController::class, 'index']);
    Route::post('/penerimaan/simpan', [App\Http\Controllers\PenerimaanController::class, 'store']);
    Route::get('/penerimaan/{id}/edit', [App\Http\Controllers\PenerimaanController::class, 'edit']);
    Route::delete('/penerimaan/{id}', [App\Http\Controllers\PenerimaanController::class, 'destroy']);
    Route::post('/penerimaan/bulk-verifikasi/{aksi}', [App\Http\Controllers\PenerimaanController::class, 'bulkVerifikasi']);

    Route::get('/jurnal', [App\Http\Controllers\JurnalController::class, 'index']);
    Route::post('/jurnalTable', [App\Http\Controllers\JurnalController::class, 'index']);
    Route::post('/jurnal/simpan', [App\Http\Controllers\JurnalController::class, 'store']);
    Route::get('/jurnal/{id}/edit', [App\Http\Controllers\JurnalController::class, 'edit']);
    Route::delete('/jurnal/{id}', [App\Http\Controllers\JurnalController::class, 'destroy']);
    Route::post('/jurnal/bulk-verifikasi/{aksi}', [App\Http\Controllers\JurnalController::class, 'bulkVerifikasi']);

    Route::get('/quotation', [App\Http\Controllers\QuotationController::class, 'index']);
    Route::post('/quotationTable', [App\Http\Controllers\QuotationController::class, 'index']);
    Route::post('/quotation/simpan', [App\Http\Controllers\QuotationController::class, 'store']);
    Route::get('/quotation/{id}/edit', [App\Http\Controllers\QuotationController::class, 'edit']);
    Route::delete('/quotation/{id}', [App\Http\Controllers\QuotationController::class, 'destroy']);
    Route::get('/quotation/detail/{no}', [App\Http\Controllers\QuotationController::class, 'show']);
    Route::post('/quotation/kirim/{id}', [App\Http\Controllers\QuotationController::class, 'kirimEmail']);

    //laporan
    Route::get('/laporan-transaksi', [App\Http\Controllers\LaporanController::class, 'view_laporan_transaksi']);
    Route::get('/print-laporan-transaksi', [App\Http\Controllers\LaporanController::class, 'print_laporan_transaksi']);
    Route::get('/laporan-pengeluaran', [App\Http\Controllers\LaporanController::class, 'view_laporan_pengeluaran']);
    Route::get('/print-laporan-pengeluaran', [App\Http\Controllers\LaporanController::class, 'print_laporan_pengeluaran']);

    Route::get('/laporan-penerimaan', [App\Http\Controllers\LaporanController::class, 'view_laporan_penerimaan']);
    Route::get('/print-laporan-penerimaan', [App\Http\Controllers\LaporanController::class, 'print_laporan_penerimaan']);

    Route::get('/laporan-aset', [App\Http\Controllers\LaporanController::class, 'view_laporan_aset']);
    Route::post('/print-laporan-aset', [App\Http\Controllers\LaporanController::class, 'print_laporan_aset']);
    Route::get('/print-laporan-history-stock', [App\Http\Controllers\LaporanController::class, 'laporanHistoryStock']);
    Route::get('/laporan/print-laporan-stock', [App\Http\Controllers\LaporanController::class, 'laporanStock']);
    Route::get('/laporan/pajak', [App\Http\Controllers\LaporanController::class, 'laporanPajak']);
    Route::post('/laporan/pajak/table', [App\Http\Controllers\LaporanController::class, 'laporanPajak']);
    Route::get('/print-laporan-pajak', [App\Http\Controllers\LaporanController::class, 'print_laporan_pajak']);


    Route::get('/laporan/neraca', [App\Http\Controllers\AkunController::class, 'neraca']);
    Route::get('/laporan/laba-rugi', [App\Http\Controllers\AkunController::class, 'labaRugi']);
    Route::get('/laporan/buku-besar', [App\Http\Controllers\AkunController::class, 'bukuBesar']);


    Route::get('/notifikasi', [App\Http\Controllers\NotifikasiController::class, 'index']);
    Route::get('/notif-read/{id}', [App\Http\Controllers\NotifikasiController::class, 'update']);

    Route::post('/verifikasi-pengeluaran/{id}', [App\Http\Controllers\PengeluaranController::class, 'verifikasiPengeluaran']);

    Route::post('/verifikasi-pengurangan/{id}', [App\Http\Controllers\HistoryStockController::class, 'verifikasiPengurangan']);

    //Kas
    Route::get('/kas', [App\Http\Controllers\KasController::class, 'index']);
    Route::post('/kasTable', [App\Http\Controllers\KasController::class, 'index']);
    Route::post('/kas/simpan', [App\Http\Controllers\KasController::class, 'store']);
    Route::get('/kas/{id}/edit', [App\Http\Controllers\KasController::class, 'edit']);
    Route::get('/laporan-kas', [App\Http\Controllers\LaporanController::class, 'view_laporan_kas']);
    Route::get('/print-laporan-kas', [App\Http\Controllers\LaporanController::class, 'print_laporan_kas']);



    Route::resource('roles', RoleController::class);


    Route::get('/website/delete/{id}', [App\Http\Controllers\WebsiteController::class, 'destroy']);
    Route::get('/toko', [App\Http\Controllers\WebsiteController::class, 'index']);
    Route::post('/website/table', [App\Http\Controllers\WebsiteController::class, 'index']);
    Route::get('/toko/baru', [App\Http\Controllers\WebsiteController::class, 'baru']);
    Route::post('/prefix/store', [App\Http\Controllers\WebsiteController::class, 'updatePrefix']);
    Route::post('/default-data/store', [App\Http\Controllers\WebsiteController::class, 'updateDefault']);
    Route::get('down', function () {
        \Artisan::call('down', array('--secret' => '131'));
    });

    Route::get('up', function () {
        \Artisan::call('up');
    });


    //User
    Route::get('/user', [App\Http\Controllers\UserController::class, 'index']);
    Route::post('/userTable', [App\Http\Controllers\UserController::class, 'index']);
    Route::post('/user/simpan', [App\Http\Controllers\UserController::class, 'store']);
    Route::get('/user/{id}/edit', [App\Http\Controllers\UserController::class, 'edit']);
    Route::delete('/user/{id}', [App\Http\Controllers\UserController::class, 'destroy']);
    Route::get('/user/ubah/{username?}', [App\Http\Controllers\UserController::class, 'ubahProfil']);
    Route::get('/user/{username?}', [App\Http\Controllers\UserController::class, 'profil']);
    Route::post('/update-profil', [App\Http\Controllers\UserController::class, 'updateProfil']);
    Route::post('/user/select', [App\Http\Controllers\UserController::class, 'select']);


    Route::get('/toko/ubah/{username?}', [App\Http\Controllers\WebsiteController::class, 'ubahProfil']);
    Route::get('/toko/{username?}', [App\Http\Controllers\WebsiteController::class, 'profil']);
    Route::post('/update-pengaturan-website', [App\Http\Controllers\WebsiteController::class, 'updateWebsite']);
    Route::post('/update-pengaturan-transaksi', [App\Http\Controllers\WebsiteController::class, 'updatePengaturan']);
});


Route::get('{username}/produk/detail/{id}', [App\Http\Controllers\ProdukController::class, 'show']);
Route::get('/produk-page', [App\Http\Controllers\ProdukController::class, 'produkPage']);
Route::get('/produk-kategori/{id}', [App\Http\Controllers\ProdukController::class, 'produkKategori']);
Route::get('/produk-cari/{id}', [App\Http\Controllers\ProdukController::class, 'produkCari']);

Route::post('send', [PushNotificationController::class, 'bulksend'])->name('bulksend');
Route::get('all-notifications', [PushNotificationController::class, 'index']);
Route::get('get-notification-form', [PushNotificationController::class, 'create']);
Route::post('/save-token', [App\Http\Controllers\PushNotificationController::class, 'saveToken'])->name('save-token');
Route::get('/send-notification', [App\Http\Controllers\PushNotificationController::class, 'sendNotification'])->name('send.notification');

Route::get('/ongkir', [CheckOngkirController::class, 'index']);
Route::post('/ongkir', [CheckOngkirController::class, 'check_ongkir']);
Route::get('/cities/{province_id}', [CheckOngkirController::class, 'getCities']);
Route::get('/ongkir/get-all-provinsi', [CheckOngkirController::class, 'getAllProvinsi']);
Route::get('/ongkir/get-all-kota/{id}', [CheckOngkirController::class, 'getAllKota']);
Route::get('/ongkir/get-all-kecamatan/{id}', [CheckOngkirController::class, 'getAllKecamatan']);
Route::post('/ongkir/provinsi/select', [CheckOngkirController::class, 'provinsiSelect']);
Route::post('/ongkir/kabupaten/select', [CheckOngkirController::class, 'kabupatenSelect']);
Route::post('/ongkir/kecamatan/select', [CheckOngkirController::class, 'kecamatanSelect']);
Route::get('/ongkir/get-expedisi', [CheckOngkirController::class, 'getExpedisi']);
Route::post('/ongkir/costs', [CheckOngkirController::class, 'costs']);


Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
