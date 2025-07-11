<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TransaksiController extends BaseController
{
        protected $cart;

    function __construct()
    {
        // Helper number digunakan untuk format harga barang (Rupiah).
        helper('number'); 
        helper('form');
        $this->cart = \Config\Services::cart();
    }

    // Function index akan menampilkan isi data keranjang, dengan menggunakan fungsi contents bawaan dari modul.
    public function index()
    {
        $data['items'] = $this->cart->contents();
        $data['total'] = $this->cart->total();
        return view('v_keranjang', $data);
    }

    // Function cart_add() digunakan untuk menambahkan data produk ke keranjang, dengan menggunakan fungsi insert bawaan dari modul.
    public function cart_add()
    {
        $this->cart->insert(array(
            'id'        => $this->request->getPost('id'),
            'qty'       => 1,
            'price'     => $this->request->getPost('harga'),
            'name'      => $this->request->getPost('nama'),
            'options'   => array('foto' => $this->request->getPost('foto'))
        ));
        session()->setflashdata('success', 'Produk berhasil ditambahkan ke keranjang. (<a href="' . base_url() . 'keranjang">Lihat</a>)');
        return redirect()->to(base_url('/'));
    }

    // Function cart_clear() digunakan untuk mengosongkan keranjang
    public function cart_clear()
    {
        $this->cart->destroy();
        session()->setflashdata('success', 'Keranjang Berhasil Dikosongkan');
        return redirect()->to(base_url('keranjang'));
    }

    // Function cart_edit() digunakan untuk mengubah jumlah data produk di keranjang
    public function cart_edit()
    {
        $i = 1;
        foreach ($this->cart->contents() as $value) {
            $this->cart->update(array(
                'rowid' => $value['rowid'],
                'qty'   => $this->request->getPost('qty' . $i++)
            ));
        }

        session()->setflashdata('success', 'Keranjang Berhasil Diedit');
        return redirect()->to(base_url('keranjang'));
    }

    // Function cart_delete() digunakan untuk menghapus data produk dari keranjang
    public function cart_delete($rowid)
    {
        $this->cart->remove($rowid);
        session()->setflashdata('success', 'Keranjang Berhasil Dihapus');
        return redirect()->to(base_url('keranjang'));
    }
}