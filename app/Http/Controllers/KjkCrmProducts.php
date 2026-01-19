<?php

namespace App\Http\Controllers;

use App\Models\Kjk_crm_product;
use App\Models\Kjk_crm_tag;
use App\Models\Marketing_clients;
use App\Models\Marketing_leads;
use App\Models\Kjk_crm_property;
use App\Models\Kjk_crm_property_value;
use App\Models\Kjk_crm_leads_contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class KjkCrmProducts extends Controller
{
    private $dir;
    public function __construct(){
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $this->dir = str_replace("\\", "/", $dir);
    }

    function index(Request $request){
        $m_products = Kjk_crm_tag::where("company_id", Session::get('company_id'))->get()->toArray();
        $m_menu = array(
            "PRODUCT" => $m_products
        );
        $products = Kjk_crm_product::where("company_id", Session::get('company_id'))->get();

        $users = User::whereIn("id", $products->pluck("created_by"))->get()->pluck("name", "id");

        $leads = Marketing_leads::where("company_id", Session::get('company_id'))->whereNotNull("products")->get();

        $pl = [];

        foreach($leads as $item){
            $pr = json_decode($item->products ?? "[]", true);
            if(is_array($pr)){
                foreach($pr as $pr_id){
                    $pl[$pr_id][] = $item->id;
                }
            }
        }

        if(!empty($request->a)){
            if($request->a == "tags"){
                $tags = Kjk_crm_tag::where("company_id", Session::get("company_id"))
                    ->where("category", "product")
                    ->get();

                $arr = [
                    "tags" => $tags->pluck("label")->toArray()
                ];

                return json_encode($arr);
            }
        }

        return view("_crm.products.index", compact("products", "users", "m_menu", "pl"));
    }

    function archive($id){
        $products = Kjk_crm_product::find($id);
        $products->archive_at = date("Y-m-d H:i:s");
        $products->archive_by = Auth::id();
        $products->save();

        return redirect()->back();
    }

    function delete($id){
        $products = Kjk_crm_product::find($id);
        $products->delete();

        return redirect()->back();
    }

    function add(Request $request){
        $harga = str_replace(".", "", $request->harga);
        $_harga = str_replace(",", ".", $harga);
        $products = Kjk_crm_product::findOrNew($request->id);
        $products->label = $request->sku;
        $products->tags = $request->tag;
        $products->brand = $request->brand;
        $products->harga = $_harga;
        $products->deskripsi = $request->deskripsi;
        $products->uom = $request->uom;
        $products->kategori = $request->kategori;
        if(empty($request->id)){
            $products->created_by = Auth::id();
            $products->company_id = Session::get('company_id');
        }
        $file = $request->file("attachment");
        if(!empty($file)){
            $dir = str_replace("/", "\\", public_path("media/attacments"));
            $_dir = str_replace("prototype/public_html", "public_html/kerjaku/assets", $dir);
            $tdir = str_replace("\\", "/", $_dir);
            $name = date("YmdHis")."-".$products->company_id."-".$file->getClientOriginalName();
            if($file->move($this->dir, $name)){
                $products->file_name = $file->getClientOriginalName();
                $products->file_address = "media/attachments/$name";
            }
        }

        $products->comps = empty($request->id_client) ? null : json_encode($request->id_client);

        $products->save();

        $property = $request->property ?? [];
        foreach($property as $idPro => $proVal){
            if(!empty($proVal)){
                $prop = Kjk_crm_property_value::firstOrCreate([
                    "property_id" => $idPro,
                    "type" => "product",
                    "target_id" => $products->id,
                    'company_id' => Session::get('company_id')
                ]);

                if(is_array($proVal)){
                    $prop->property_value = json_encode($proVal);
                    $prop->format = "array";
                } else {
                    $prop->property_value = $proVal;
                    $prop->format = "text";
                }
                $prop->save();
            }
        }

        $tags = $request->tag ?? "[]";

        foreach(json_decode($tags, true) as $tag){
            $t = Kjk_crm_tag::firstOrNew(['label' => $tag['value'], "category" => "contact"]);
            $t->label = $tag['value'];
            $t->category = "product";
            $t->company_id = Session::get('company_id');
            $t->save();
        }

        return redirect()->back()->with(["list_id" => $products->id]);
    }

    function detail($id){
        $product = Kjk_crm_product::withoutGlobalScopes()->find($id);

        $company = Marketing_clients::where('company_id', Session::get("company_id"))->get();

        $properties = Kjk_crm_property::where("type", "product")
            ->whereNull("table_column")
            ->get();

        $propVal = Kjk_crm_property_value::whereIn("property_id", $properties->pluck("id"))
            ->where("target_id", $product->id)
            ->where("type", "product")
            ->get();
        $prop = [];
        foreach($propVal as $item){
            $prop[$item->property_id] = $item;
        }
        $contacts = Kjk_crm_leads_contact::where("company_id", Session::get('company_id'))->pluck("name", "id");
        $clients = Marketing_clients::where("company_id", Session::get('company_id'))->pluck("company_name", "id");

        // $opportunity

        $view = view("_crm.products._detail", compact("product", 'company', 'properties', 'prop', 'contacts', 'clients'))->render();

        return json_encode([
            "view" => $view
        ]);
    }
}
