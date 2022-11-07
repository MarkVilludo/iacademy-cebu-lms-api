<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('payment_modes')->insert([
            [
                "id" => 7,
                "name" => "GCASH",
                "pchannel" => "gc",
                "pmethod" => "wallet",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/gcash.jpg",
                "type" => "percentage",
                "charge" => "2.00",
                "is_nonbank" => false
            ],
            [
                "id" => 8,
                "name" => "COINS",
                "pchannel" => "coins_ph",
                "pmethod" => "wallet",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/coinsph.jpg",
                "type" => "fixed",
                "charge" => "25.00",
                "is_nonbank" => false
            ],
            [
                "id" => 13,
                "name" => "ECPAY",
                "pchannel" => "ecpay_ph",
                "pmethod" => "nonbank_otc",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/ecpay.jpg",
                "type" => "fixed",
                "charge" => "25.00",
                "is_nonbank" => true
            ],
            [
                "id" => 14,
                "name" => "CLIQQ",
                "pchannel" => "cliqq_ph",
                "pmethod" => "nonbank_otc",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/cliqq.jpg",
                "type" => "fixed",
                "charge" => "25.00",
                "is_nonbank" => true
            ],
            [
                "id" => 15,
                "name" => "DA5",
                "pchannel" => "da5_ph",
                "pmethod" => "nonbank_otc",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/da5.jpg",
                "type" => "fixed",
                "charge" => "25.00",
                "is_nonbank" => true
            ],
            [
                "id" => 16,
                "name" => "EXPRESSPAY",
                "pchannel" => "expresspay_ph",
                "pmethod" => "nonbank_otc",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/expresspay.jpg",
                "type" => "fixed",
                "charge" => "25.00",
                "is_nonbank" => true
            ],
            [
                "id" => 17,
                "name" => "DP",
                "pchannel" => "dp_ph",
                "pmethod" => "nonbank_otc",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/dp.jpg",
                "type" => "fixed",
                "charge" => "25.00",
                "is_nonbank" => true
            ],
            [
                "id" => 18,
                "name" => "7\/11",
                "pchannel" => "711_ph",
                "pmethod" => "nonbank_otc",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/711.jpg",
                "type" => "fixed",
                "charge" => "25.00",
                "is_nonbank" => true
            ],
            [
                "id" => 20,
                "name" => "CEBUANA",
                "pchannel" => "cebuana_ph",
                "pmethod" => "nonbank_otc",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/cebuana.jpg",
                "type" => "fixed",
                "charge" => "25.00",
                "is_nonbank" => true
            ],
            [
                "id" => 21,
                "name" => "SBILLS",
                "pchannel" => "smbills_ph",
                "pmethod" => "nonbank_otc",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/sm.jpg",
                "type" => "fixed",
                "charge" => "25.00",
                "is_nonbank" => true
            ],
            [
                "id" => 23,
                "name" => "TRUEMONEY",
                "pchannel" => "truemoney_ph",
                "pmethod" => "nonbank_otc",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/truemoney.jpg",
                "type" => "fixed",
                "charge" => "25.00",
                "is_nonbank" => true
            ],
            [
                "id" => 24,
                "name" => "POSIBLE",
                "pchannel" => "posible_ph",
                "pmethod" => "nonbank_otc",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/posible.jpg",
                "type" => "fixed",
                "charge" => "25.00",
                "is_nonbank" => true
            ],
            [
                "id" => 25,
                "name" => "ETAP",
                "pchannel" => "etap_ph",
                "pmethod" => "nonbank_otc",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/etap.jpg",
                "type" => "fixed",
                "charge" => "25.00",
                "is_nonbank" => true
            ],
            [
                "id" => 5,
                "name" => "RCBC",
                "pchannel" => "br_rcbc_ph",
                "pmethod" => "onlinebanktransfer",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/rcbc.jpg",
                "type" => "percentage",
                "charge" => "2.00",
                "is_nonbank" => false
            ],
            [
                "id" => 4,
                "name" => "PNB",
                "pchannel" => "br_pnb_ph",
                "pmethod" => "onlinebanktransfer",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/pnb.jpg",
                "type" => "percentage",
                "charge" => "2.00",
                "is_nonbank" => false
            ],
            [
                "id" => 3,
                "name" => "BDO",
                "pchannel" => "br_bdo_ph",
                "pmethod" => "onlinebanktransfer",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/bdo.jpg",
                "type" => "percentage",
                "charge" => "2.00",
                "is_nonbank" => false
            ],
            [
                "id" => 2,
                "name" => "UBP",
                "pchannel" => "ubp_online",
                "pmethod" => "onlinebanktransfer",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/unionbank.jpg",
                "type" => "percentage",
                "charge" => "2.00",
                "is_nonbank" => false
            ],
            [
                "id" => 1,
                "name" => "BPI",
                "pchannel" => "bpi_online",
                "pmethod" => "onlinebanktransfer",
                "image_url" => "http:/103.225.39.201:8081/images/finance_online_payment/bpi.jpg",
                "type" => "percentage",
                "charge" => "2.00",
                "is_nonbank" => false
            ]
        ]);
    }
}
