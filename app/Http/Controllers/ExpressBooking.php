<?php

namespace App\Http\Controllers;

use App\Models\ConfigCompany;
use App\Models\Express_book_order;
use App\Models\Express_book_search;
use App\Models\Express_from;
use App\Models\Express_negara_tujuan;
use App\Models\Express_produk_kategori;
use App\Models\Express_produk_tipe;
use App\Models\Express_vendor;
use App\Models\Express_deposit;
use App\Models\Express_pickup_request;
use App\Models\Express_promo;
use App\Models\Express_refund_request;
use App\Models\Express_zone_demand;
use App\Models\Express_zone_demand_price;
use App\Models\Express_zone_green;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpressBooking extends Controller
{

    private $dir;
    public function __construct(){
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $this->dir = str_replace("\\", "/", $dir);
    }

    function index(Request $request){
        $a = $request->a ?? "outstanding";
        $orders = Express_book_order::where("created_by", Auth::id())
            ->where(function($q) use ($a) {
                if($a == "archive"){
                    $q->where("received", 1);
                } else {
                    $q->where("received", "!=", 1);
                }
            })->orderBy("created_at", "desc")
            ->get();

        $bs = Express_book_search::whereIn("id", $orders->pluck("book_id"))->get()->keyBy("id");

        $tujuan = Express_negara_tujuan::whereIn('id', $bs->pluck("tujuan_id"))->get()->keyBy("id");

        $saldoDeposit = \App\Http\Controllers\ExpresDeposit::getDepositBalance();

        return view("_express.booking.index", compact("orders", 'bs', 'tujuan', "saldoDeposit", "a"));
    }

    function cek_resi($nomor = null){

        if(empty($nomor)){
            return view("_express.booking.resi");
        }

        $book_order = Express_book_order::where("nomor_resi", $nomor)->first();

        if(empty($book_order)){
            return view("errors.resinotfound", compact("nomor"));
        }

        $book = $book_order->book ?? [];
        $tujuan = Express_negara_tujuan::find($book->tujuan_id);
        $vendor = Express_vendor::find($book->vendor_id);
        $dari = Express_from::find($book->dari_id);
        $produk = Express_produk_tipe::find($book->produk_id);
        $kategori = Express_produk_kategori::whereIn("id",$book->kategori_id)->get();
        $content = $book->content['request'] ?? [];
        $res = $book->content['vendor'] ?? [];


        return view("_express.booking.cek_resi", compact("book_order", "book", "tujuan", "vendor", "dari", "produk", "kategori", "content", "res"));
    }

    function confirm_payment(Request $request){
        $order = Express_book_order::find($request->deposit_id);

        if($request->status == 0){
            $order->status = 1;
            $order->order_payment_at = date("Y-m-d H:i:s");
            $order->order_payment_by = Auth::id();
        } else {
            $order->status = 3;
            $order->reweight_payment_at = date("Y-m-d H:i:s");
            $order->reweight_payment_by = Auth::id();
        }
        $order->payment_confirm_at = date("Y-m-d H:i:s");
        $order->save();

        if(!empty($request->use_saldo) && $request->use_saldo > 0){
            $_url = route("booking.views", $order->book_id);
            $desc = "Pembayaran Order";
            $desc .= " <a href='$_url'>#$order->kode_book</a>";

            $saldo = $request->saldo;

            $deposit = Express_deposit::where("user_id", Auth::id())
                ->whereNotNull('confirm_at')
                ->where("used", "!=", 1)
                ->update([
                    "used" => 1
                ]);


            $deposit = new Express_deposit();
            $deposit->user_id = Auth::id();
            $deposit->amount = $saldo * -1;
            $deposit->status = "confirmed";
            $deposit->remarks = $desc;
            $deposit->confirm_at = date("Y-m-d H:i:s");
            $deposit->type = "Payment";
            $deposit->used = 1;
            $deposit->save();
        }

        return redirect()->route("booking.index");
    }

    function roundUpToNearestHalf($number) {
        return ceil($number * 2) / 2;
    }

    function cari(Request $request){
        $kategori_id = $request->kategori[$request->produk_id] ?? [];

        $produk = Express_produk_tipe::find($request->produk_id);

        if($produk->tipe_kategori != "w"){
            $lmt = \Config::get("constants.limit");
            foreach($request->data as $i => $item){
                $berat = $item['berat-'.$produk->id];
                $panjang = $item['panjang-'.$produk->id];
                $lebar = $item['lebar-'.$produk->id];
                $tinggi = $item['tinggi-'.$produk->id];

                if($berat > $lmt['berat']){
                    return redirect()->back()->withInput($request->all())
                        ->withErrors([
                            "berat-$produk->id" => "Berat melebihi batas maksimal"
                        ]);
                }

                if($panjang > $lmt['panjang']){
                    return redirect()->back()->withInput($request->all())
                        ->withErrors([
                            "panjang-$produk->id" => "Panjang melebihi batas maksimal"
                        ]);
                }

                if($lebar > $lmt['lebar']){
                    return redirect()->back()->withInput($request->all())
                        ->withErrors([
                            "lebar-$produk->id" => "Lebar melebihi batas maksimal"
                        ]);
                }

                if($tinggi > $lmt['tinggi']){
                    return redirect()->back()->withInput($request->all())
                        ->withErrors([
                            "tinggi-$produk->id" => "Tinggi melebihi batas maksimal"
                        ]);
                }
            }
        }

        $tujuan = Express_negara_tujuan::find($request->tujuan);
        $zones = [];
        if(!empty($tujuan) && is_array($tujuan->zones ?? [])){
            $zones = $tujuan->zones ?? [];
        }
        $vendors = Express_vendor::where(function($q) use($zones){
            $q->whereIn("id", array_keys($zones));
            $q->orWhere('type', 2);
        })->where(function($q) use($tujuan){
            $q->whereNull("specific_countries");
            $q->orWhere("specific_countries", "like", '%"'.$tujuan->id.'"%');
        })->get()->keyBy("id");

        $vendor_id = $vendors->pluck("id")->toArray();

        $zone_ids = array_values($zones);

        $mrate = \App\Models\Master_rate::whereIn("vendor_id", $vendor_id)->get();

        $zonePrice = $mrate->whereNull("tipe_sku")->groupBy("vendor_id");
        $zoneSku = $mrate->where("tipe_sku", $produk->id)->groupBy("vendor_id");

        $multiplierRate = \App\Models\Master_rate_multiplier::whereIn("vendor_id", $vendor_id)->get()->groupBy("vendor_id");

        $mr = [];

        // foreach($zonePrice as $item){
        //     $zp[$item->weight][] = $item;
        // }

        $dari = Express_from::find($request->dari);

        $data = $request->data;
        // dd($data, $request->all);

        $vendor = Express_vendor::whereIn("id", $vendor_id)->orderBy('nama')->get();

        $demand = Express_zone_demand::where('countries', "like", '%"'.$tujuan->id.'"%')
            ->get();
        $demand_vendor = [];
        foreach($demand as $item){
            $dv = $item->vendors ?? [];
            foreach($dv as $v){
                $demand_vendor[$v] = $item;
            }
        }

        $demand_price = Express_zone_demand_price::whereIn("demand_id", $demand->pluck("id"))
            ->get()->groupBy("demand_id");

        $greens = Express_zone_green::whereIn('vendor_id',  $vendor->pluck('id'))->get()
            ->groupBy("vendor_id");

        $row = [];

        $details = [];

        $total_berat = 0;
        $total_paket = 0;
        $total_volume = 0;
        $kid = [];

        if($produk->tipe_kategori == "w"){
            $_wg = $request->document_weight;
            if($kategori_id == "_new"){
                $kategori = new Express_produk_kategori();
                $kategori->nama = $request->kategori_name;
                $kategori->produk_id = $produk->id;
                $kategori->save();
                $kategori_id = $kategori->id;
            } else {
                $kategori = Express_produk_kategori::find($kategori_id);
            }
            $col = [];
            $col['berat'] = floatval($_wg);
            $col['v'] = 0;
            $col['panjang'] = 0;
            $col['lebar'] = 0;
            $col['tinggi'] = 0;
            $col['total_paket'] = 1;
            $col['volumetric'] = 0;
            $col['multiplier'] = (string) $this->roundUpToNearestHalf(number_format($col['berat'], 2, ".", ""));
            $col['divisor'] = 5000;
            $row[] = $col;
            $details[] = $col;
            $total_berat += $_wg;
            $total_paket = 1;
            $kid[] = $kategori->id;
        } else {
            $kategori = Express_produk_kategori::whereIn("id", $kategori_id)->get();
            foreach($kategori as $item){
                $kid[] = $item->id;
            }
            foreach($data as $item){
                $col = [];
                if(isset($item['total_paket-'.$produk->id])){
                    $kg = $item['berat-'.$produk->id] * $item['total_paket-'.$produk->id];
                    $volume = $item['panjang-'.$produk->id] * $item['lebar-'.$produk->id] * $item['tinggi-'.$produk->id];
                    $colv = round(($volume * $item['total_paket-'.$produk->id]) / 5000,1);
                    // $row[] = $col;
                    $total_berat += $kg;
                    $total_volume += $colv;
                    $total_paket += $item['total_paket-'.$produk->id];
                    $details[] = [
                        'berat' => $item['berat-'.$produk->id],
                        'panjang' => $item['panjang-'.$produk->id],
                        'lebar' => $item['lebar-'.$produk->id],
                        'tinggi' => $item['tinggi-'.$produk->id],
                        'total_paket' => $item['total_paket-'.$produk->id],
                        'volumetric' => $colv,
                        'multiplier' => $this->roundUpToNearestHalf(strval(number_format($colv > $kg ? $colv : $kg, 2, ".", ""))),
                        'divisor' => 5000
                    ];
                }
            }
        }
        $kode = $request->add_token;

        $fuel_charges = \App\Models\Express_fuel_charge::whereIn("vendor_id", $vendor->pluck("id"))
            ->whereNull("end_date")
            ->get();

        foreach($vendor as $i => $item){
            $col = [];
            $pr = 0;
            $price_pk = $item->harga;
            $fuel_charge = $fuel_charges->where("vendor_id", $item->id)
                ->sortByDesc("start_date")
                ->first();
            $price_pd = $item->harga;
            $col = [];
            $additional_prices = [];

            if($item->type == 1){
                $zoneVendor = $zones[$item->id];
                $zp = collect($zonePrice[$item->id] ?? [])->where('zone_id', $zoneVendor)->whereNotNull("weight")->values();
                $zpSku = collect($zoneSku[$item->id] ?? [])->where('zone_id', $zoneVendor)->whereNotNull("weight");
                $mpRate = collect($multiplierRate[$item->id] ?? [])->where("zone_id", $zoneVendor);
            } else {
                $zp = collect($zonePrice[$item->id] ?? [])->whereNotNull("weight")->values();
                $zpSku = collect($zoneSku[$item->id] ?? [])->whereNotNull("weight");
                $mpRate = collect($multiplierRate[$item->id] ?? []);
            }

            $_greens = collect($greens[$item->id] ?? [])->keyBy("weight");
            $green_total = 0;

            $price = 0;
            $multiplier = 0;
            $volumetric = 0;
            $berat = 0;

            foreach($details as $val){
                $multiplier += $val['multiplier'];
                $volumetric += $val['volumetric'] * $val['total_paket'];
                $berat += $val['berat'] * $val['total_paket'];
            }

            $item->show = false;

            $data_total_paket = collect($details)->sum("total_paket");

            $exclude_sku = $item->exclude_sku ?? [];

            if((empty($item->weight_limit) || (!empty($item->weight_limit) && $multiplier < $item->weight_limit)) && (empty($item->packet_limit) || $data_total_paket <= $item->packet_limit) && ($zpSku->count() > 0 || $zp->count() > 0) && (!in_array($produk->id, $exclude_sku))){
                // dd($zp, $multiplier);
                if($zpSku->count() > 0){
                    if($item->type == 1){
                        $mtp = collect($zpSku->where("weight", $multiplier))->first();
                    } else {
                        $mtp = collect($zpSku->where("weight", "<=", $multiplier))->first();
                    }
                    if(empty($mtp)){
                        // $mtp = collect($zp[$multiplier] ?? []);
                        if($item->type == 1){
                            $mtp = $zp->where("weight", $multiplier)->first();
                        } else {
                            $mtp = $zp->where("weight", "<=", $multiplier)->first();
                        }
                    }
                } else {
                    if($item->type == 1){
                        $mtp = $zp->where("weight", $multiplier)->first();
                    } else {
                        $mtp = $zp->where("weight", "<=", $multiplier)->first();
                    }
                }

                $gtp = $_greens[$multiplier] ?? [];
                if(!empty($gtp)){
                    // $green_total += $gtp->price;
                    $price += $gtp->price;
                }


                if(empty($mtp)){
                    $mtp = $zp->last();
                    if(!empty($mtp)){
                        $price = $mtp['price'];

                        $mrData = $mpRate->where("weight_min", "<=", $multiplier)->where("weight_max", ">=", $multiplier)->first();
                        if(empty($mrData)){
                            $mrData = $mpRate->last();
                        }
                        if(!empty($mrData)){
                            $selisih = round(floatval($multiplier) - $mtp['weight'], 2);
                            // if($item->id == 2){
                            //     dd($selisih, $multiplier, $mtp, $mrData);
                            // }
                            $price = $multiplier * $mrData['price'];
                            if(count($_greens) > 0){
                                $green_total = $multiplier * $mrData['green'];
                                // $price += $green_total;
                            }
                            // $price += $selisih * $mrData['price'];
                        }
                    }
                } else {
                    $price = $mtp['price'] ?? 0;
                }
                if($item->id == 2){
                    // dd($berat, $multiplier, $mtp, $zp, $zpSku);
                }

                $ppk = $tujuan->hpk * $multiplier;
                $ppd = $tujuan->hpd * $volumetric;
                $col['val'] = $val;
                $col['kg'] = $berat;
                $col['v'] = $volumetric;
                $col['price'] = $price;
                $price_pk += $ppk;
                $price_pd += $ppd;

                if($green_total > 0){
                    $additional_prices[] = [
                        "key" => "green",
                        "label" => "Go Green",
                        "value" => $green_total
                    ];
                }

                $dv = $demand_vendor[$item->id] ?? [];
                if(!empty($dv)){
                    $dp = collect($demand_price[$dv->id] ?? [])->where("vendor_id", $item->id)->first();
                    if(!empty($dp)){
                        $dv = $multiplier * $dp->price;
                        if($multiplier >= 30){
                            $dv = round($multiplier) * $dp->price;
                        }
                        $additional_prices[] = [
                            "key" => "demand_surcharges",
                            "label" => "Demand Surcharges",
                            'price' => $dp->price,
                            "value" => $dv,
                            'multiplier' => $multiplier
                        ];
                    }
                }

                $fcharge = 0;
                // if($item->id == 2){
                //     dd($fuel_charge);
                // }
                if(!empty($fuel_charge)){
                    if(!empty($fuel_charge->fuel_surcharge)){
                        $fcharge = $price * $fuel_charge->fuel_surcharge;
                    }
                }
                $item->additional_prices = $additional_prices;
                $item->prices = $col;
                $item->price_type = "kg";
                $item->price = $price_pk;
                $item->pr = $price;
                $item->fuel_charge = $fcharge;
                $item->zone = $mtp;
                $item->details = $details;
                if($price_pd > $price_pk){
                    $item->price_type = "v";
                    $item->price = $price_pd;
                }
                $item->show = true;
                $item->gtp = $green_total;
                $_kode = $kode.$item->id;
                $item->url = route('booking.add', base64_encode($_kode));
                $vendor[$i] = $item;

                $book_search = Express_book_search::where([
                    "book_kode" => $_kode,
                ])->first();

                $content = [
                    "request" => $details,
                    "vendor" => $item
                ];

                if(empty($book_search)){
                    $book_search = new Express_book_search();
                    $book_search->book_kode = $_kode;
                    $book_search->produk_id = $produk->id;
                    $book_search->dari_id = $dari->id;
                    $book_search->tujuan_id = $tujuan->id;
                    $book_search->kategori_id = $kid;
                    $book_search->vendor_id = $item->id;
                    $book_search->content = $content;
                    $book_search->save();
                } else {
                    $book_search->content = $content;
                    $book_search->save();
                }
            }
        }
        // dd($vendor);

        return view("_express.booking.cari", compact("vendor", "produk", "kategori", "tujuan", "dari", "details", "total_berat", "total_paket", "total_volume"));
    }

    function invoice($type, $id){
        $order = Express_book_order::find($id);

        $book = \App\Models\Express_book_search::find($order->book_id);

        $produk = Express_produk_tipe::find($book->produk_id);

        $items = $book->content ?? [];
        $content = $items['request'] ?? [];

        $total_berat = 0;
        $total_parcel = 0;
        foreach($content as $item){
            $volume = $item['panjang'] * $item['lebar'] * $item['tinggi'];
            $colv = round(($volume * $item['total_paket']) / 5000,1);
            $total_berat += $colv > ($item['berat'] * $item['total_paket']) ? $colv : ($item['berat'] * $item['total_paket']);
            $total_parcel += $item['total_paket'];
        }

        $negara = \App\Models\Express_negara_tujuan::find($book->tujuan_id);

        $vendor = \App\Models\Express_vendor::find($book->vendor_id);

        $listItem = $order['items'];

        $comp = ConfigCompany::find(1);

        $saldoDeposit = \App\Http\Controllers\ExpresDeposit::getDepositBalance();


        return view("_express.booking.invoice", compact("order", "negara", "book", "vendor", "total_berat", "total_parcel", "content", "listItem", "comp", 'type', 'saldoDeposit', 'produk'));
    }

    function delete($id){
        $book = Express_book_order::find($id);
        if(!empty($book)) $book->delete();

        return redirect()->back();
    }

    function numberValue($value){
        $nominal = str_replace('.', '', $value);
        $nominal = str_replace(',', '.', $nominal);
        return $nominal;
    }

    function refund(Request $request){
        $book = Express_book_order::find($request->id);
        if(!empty($book)){
            $book->status = -1;
            $book->save();

            $refund = new Express_refund_request();
            $refund->order_id = $book->id;
            $refund->amount = $this->numberValue($request->amount);
            $refund->bank_name = $request->bank_name;
            $refund->account_name = $request->account_name;
            $refund->no_rekening = $request->no_rekening;
            $refund->save();
        }

        return redirect()->back();
    }

    function add($id){
        $kode = base64_decode($id);

        $book = Express_book_search::where("book_kode", $kode)->first();

        $dataPage = \App\Models\Pref_page::get()->keyBy("page_name");

        if(empty($book)){
            return abort('404');
        }

        $tujuan = Express_negara_tujuan::find($book->tujuan_id);
        $vendor = Express_vendor::find($book->vendor_id);
        $dari = Express_from::find($book->dari_id);
        $produk = Express_produk_tipe::find($book->produk_id);
        $kategori = Express_produk_kategori::whereIn("id",$book->kategori_id)->get();
        $content = $book->content['request'] ?? [];
        $res = $book->content['vendor'] ?? [];
        $surcharge = \App\Models\Express_fuel_charge::where("vendor_id", $vendor->id)
            ->whereNull("end_date")
            ->first();
            
        $totalPaket = 0;
        foreach($content as $item){
            $totalPaket += $item['total_paket'];
        }

        $oversizePacket = [];
        $overweightPacket = [];
        $ncpPacket = [];

        $surchargePacket = [];

        $sc['overweight'] = 0;
        $sc['oversize'] = 0;
        $sc['ncp'] = 0;
        $sc['insurance'] = 0;
        $sc['delivery_duty'] = 0;
        $sc['export_declare'] = 0;
        $sc['overweight_tooltip'] = "";
        $sc['oversize_tooltip'] = "";
        $sc['fuel_charge'] = 0;

        if(!empty($surcharge)){
            $sc['insurance'] = $surcharge->insurance_price;
            $sc['delivery_duty'] = $surcharge->delivery_duty_price;
            $sc['export_declare'] = $surcharge->export_declaration_price;
            $sc['fuel_charge'] = $surcharge->surcharge_type == 0 ? $surcharge->fuel_surcharge : $surcharge->price;

            foreach($content as $i => $item){
                $sur = [];

                if($item['berat'] >= floatval($surcharge->overweight_limit)){
                    $sur = [
                        "label" => "Overweight",
                        "price" => $surcharge->overweight_price
                    ];
                    $sc['overweight'] += $surcharge->overweight_price * $item['total_paket'];
                }

                $overSizeLimit = [100,80];

                if(empty($sur)){
                    // Buat array dan urutkan untuk mendapatkan nilai terpanjang, menengah, dan terpendek
                    $dimensi = [
                        'panjang' => $item['panjang'],
                        'lebar' => $item['lebar'],
                        'tinggi' => $item['tinggi'],
                    ];
                    arsort($dimensi); // urutkan dari besar ke kecil
                    $dimensi_urut = array_values($dimensi); // [terpanjang, tengah, terpendek]
                    // cek apakah terpanjang > 100, jika tidak, cek apakah tengah lebih dari 80
                    if($dimensi_urut[0] > 100){
                        $sur = [
                            "label" => "Oversize > 100cm",
                            "price" => $surcharge->oversize_price
                        ];
                        $sc['oversize'] += $surcharge->oversize_price * $item['total_paket'];
                    } elseif($dimensi_urut[1] > 80){
                        $sur = [
                            "label" => "Oversize > 80cm",
                            "price" => $surcharge->oversize_price
                        ];
                        $sc['oversize'] += $surcharge->oversize_price * $item['total_paket'];
                    }
                    // if($item['panjang'] >= floatval($surcharge->oversize_limit) || $item['lebar'] >= floatval($surcharge->oversize_limit) || $item['tinggi'] >= floatval($surcharge->oversize_limit)){
                    //     $sur = [
                    //         "label" => "Oversize",
                    //         "price" => $surcharge->oversize_price
                    //     ];
                    //     $sc['oversize'] += $surcharge->oversize_price * $item['total_paket'];
                    // }
                }

                if(empty($sur)){
                    if(!empty($surcharge->ncp_price)){
                        if($item['berat'] >= $surcharge->ncp_min && $item['berat'] <= $surcharge->ncp_max){
                            $sur = [
                                "label" => "NCP",
                                "price" => $surcharge->ncp_price
                            ];
                            $sc['ncp'] += $surcharge->ncp_price * $item['total_paket'];
                        }
                    }
                }

                $item['surcharge'] = $sur;
                $content[$i] = $item;
            }
        }
        $ct = $book->content ?? [];
        $ct['request'] = $content;
        $book->content = $ct;
        $book->save();
        $last_book = Express_book_order::where("created_by", Auth::id())
            ->orderBy('created_at', "desc")
            ->get();
        // dd($content);

        return view("_express.booking.add", compact("book", "tujuan", "vendor", "dari", "produk", "kategori", "content", "res", "surcharge", "sc", "totalPaket", "dataPage", 'last_book'));
    }

    function confirm(Request $request){

        $bk = Express_book_search::find($request->book_id);

        $book_order = new Express_book_order();
        foreach($request->all() as $key => $item){
            if(!in_array($key, ["_token", "submit", "barang", "country", "peb_invoice", "peb_packing_list", 'nib_file', 'npwp_file'])){
                $book_order[$key] = $request->{$key};
            }
        }

        $book_order->with_insurance = $request->with_insurance ?? 0;

        $promo = Express_promo::whereIn("id", $request->promo_id ?? [])->get();
        if($promo->count() > 0){
            $book_order->promo_code = $promo->pluck("code")->toArray();
        }

        $peb_invoice = $request->file('peb_invoice');
        if(!empty($peb_invoice)){
            $newName = date("YmdHis")."_peb_invoice_$bk->book_code"."_".$item->getClientOriginalName();
            if($peb_invoice->move($this->dir, $newName)){
                $book_order->peb_invoice = "media/attachments/$newName";
            }
        }

        $peb_packing_list = $request->file('peb_packing_list');
        if(!empty($peb_packing_list)){
            $newName = date("YmdHis")."_peb_packing_list_$bk->book_code"."_".$item->getClientOriginalName();
            if($peb_packing_list->move($this->dir, $newName)){
                $book_order->peb_packing_list = "media/attachments/$newName";
            }
        }

        $nib_file = $request->file("nib_file");
        if(!empty($nib_file)){
            $newName = date("YmdHis")."_nib_file_$bk->book_code"."_".$item->getClientOriginalName();
            if($nib_file->move($this->dir, $newName)){
                $book_order->nib_file = "media/attachments/$newName";
            }
        }

        $npwp_file = $request->file("npwp_file");
        if(!empty($npwp_file)){
            $newName = date("YmdHis")."_npwp_file_$bk->book_code"."_".$item->getClientOriginalName();
            if($npwp_file->move($this->dir, $newName)){
                $book_order->npwp_file = "media/attachments/$newName";
            }
        }

        $barang = $request->barang ?? [];
        $total_harga_usd = 0;
        foreach($barang as $item){
            $total_harga_usd += $item['jumlah'] * $item['harga'];
        }

        $book_order->total_harga_usd = $total_harga_usd;
        $book_order->items = $barang;
        $book_order->kode_book = str_replace("0", rand(1,9), strtoupper(\Str::random(8)));

        $book_order->save();


        return redirect()->route('booking.views', base64_encode($bk->book_kode))->with([
            "notif" => 'Booking sudah disimpan. Untuk instruksi lebih lanjut, silakan lihat "Free Pickup" di halaman dashboard.',
            "type" => "success"
        ]);
    }

    function getviews($id, Request $request){
        $kode = base64_decode($id);

        $book = Express_book_search::where("book_kode", $kode)->first();

        $book_order = Express_book_order::where("book_id", $book->id)->first();

        $tujuan = Express_negara_tujuan::find($book->tujuan_id);
        $vendor = Express_vendor::find($book->vendor_id);
        $dari = Express_from::find($book->dari_id);
        $produk = Express_produk_tipe::find($book->produk_id);
        $kategori = Express_produk_kategori::whereIn("id",$book->kategori_id)->get();
        $content = $book->content['request'] ?? [];
        $res = $book->content['vendor'] ?? [];

        $pickup_request = Express_pickup_request::where("order_id", $book_order->id)->first();

        $cmp = \App\Models\ConfigCompany::find(\Session::get("company_id"));

        if($request->act == "jam"){
            $dayOfWeek = date("w", strtotime($request->date));
            $list = [];
            if ($dayOfWeek == 0 || $dayOfWeek == 6) {
                $list[] = "10:00 - 11:00";
                $list[] = "11:00 - 12:00";
            } else {
                $list[] = "11:00 - 12:00";
                $list[] = "12:00 - 13:00";
                $list[] = "13:00 - 14:00";
                $list[] = "14:00 - 15:00";
                $list[] = "15:00 - 16:00";
            }

            $options = [];
            foreach($list as $item){
                $options[] = [
                    "value" => $item,
                    "text" => $item
                ];
            }

            return response()->json(['results' => $options]);

        }

        return view("_express.booking.views", compact("book", "book_order", "cmp", "pickup_request", "tujuan", "vendor", "dari", "produk", "kategori", "content", "res"));
    }

    function get_promo(Request $request){
        $promo = Express_promo::where("code", $request->promo)->first();
        $promo_amount = 0;
        $message = "Kode Promo tidak ditemukan";
        if(!empty($promo)){
            if(in_array($promo->id, $request->exist ?? [])){
                $message = "Kode promo sudah digunakan";
            } else {
                if($promo->source == 0){
                    $promo_amount = $promo->amount;
                } else {
                    $biaya_kirim = $request->biaya_kirim;
                    $pickup_amount = $request->pickup_amount;
                    $am = eval("return $promo->formula_text;");
                    $promo_amount = $am;
                    if(!empty($promo->amount_limit)){
                        if($promo_amount > $promo->amount_limit){
                            $promo_amount = $promo->amount_limit * 1;
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => $promo_amount > 0 ? true : false,
            "message" => $message,
            "promo" => $promo_amount,
            'promo_id' => $promo->id ?? null,
            "label" => $promo->description ?? ""
        ]);
    }

    function pickup_request(Request $request){

        $pickup_request = new Express_pickup_request();
        $pickup_request->order_id = $request->order_id;
        $pickup_request->sender_name = $request->sender_name;
        $pickup_request->sender_phone = "0".$request->sender_phone;
        $pickup_request->sender_address = $request->sender_address;
        $pickup_request->full_address = $request->full_address;
        $pickup_request->tanggal = $request->tanggal;
        $pickup_request->pickup_jam = $request->jam;
        $pickup_request->latitude = $request->latitude;
        $pickup_request->longitude = $request->longitude;
        $pickup_request->save();

        return redirect()->back()->with([
            "notif" => "Pickup request berhasil dikirim",
            "type" => "success"
        ]);
    }

    function confirm_order(Request $request){
        $order = Express_book_order::find($request->id);
        $order->status = 3;
        $order->reweight_payment_at = date("Y-m-d H:i:s");
        $order->reweight_payment_by = Auth::id();
        $order->save();

        if($order->outstanding_payment < 0){
            $deposit = new \App\Models\Express_deposit();
            $deposit->user_id = Auth::id();
            $deposit->amount = abs($order->outstanding_payment);
            $deposit->type = ucwords("Deposit");
            $deposit->status = "confirmed";
            $deposit->confirm_at = date("Y-m-d H:i:s");
            $deposit->remarks = "Kelebihan pembayaran order #$order->kode_book";
            $deposit->save();
        }

        return redirect()->back();
    }
}
