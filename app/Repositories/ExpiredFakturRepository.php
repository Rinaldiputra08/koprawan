<?php

namespace App\Repositories;

use App\Models\MasterData\Produk;

class ExpiredFakturRepository
{
    /**
     * @param Produk $produk
     * @return Produk
     */
    public function stockFree(Produk $produk)
    {
        // get expired produkct
        $faktur_produk = $produk->expiredFaktur;

        if($faktur_produk->count() > 0){
    
            $aktif = $faktur_produk->filter(function($produk){
                return strtotime($produk->expired_at) > strtotime(date('Y-m-d H:i:s'));
            });
    
            // override value of stock free, calculate by current stock free minus faktur products
            $produk->stock_free = $produk->stock_free - $aktif->sum('qty');
        }
        return $produk;
    }
}
