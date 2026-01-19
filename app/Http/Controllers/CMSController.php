<?php

namespace App\Http\Controllers;

use App\Models\Artikel_category_model;
use App\Models\Artikel_model;
use App\Models\Hrd_employee_test;
use App\Models\Pref_landing_applicant;
use App\Models\Pref_landing_employer;
use App\Models\Pref_page;
use App\Models\Express_faq as Faq;
use App\Models\Express_review as Review;
use App\Models\User_stories_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CMSController extends Controller
{
    private $dir;
    public function __construct(){
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $this->dir = str_replace("\\", "/", $dir);
    }

    private function setEnv($key, $value)
    {
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key . '=' . \Config::get("constants.$key"),
            $key . '=' . $value,
            file_get_contents(app()->environmentFilePath())
        ));
    }

    function applicant(Request $request){
        $list_menu = ["main_section", "second_section", "partners", "artikel"];
        $v = $request->v ?? "main_section";

        $lp = Pref_landing_applicant::where('company_id', Session::get('company_id'))->first();

        $list_test = Hrd_employee_test::where('company_id', Session::get('company_id'))->get();

        $data = [];
        if($v == "artikel"){
            $artikel = Artikel_model::where("company_id", Session::get("company_id"))->orderBy("created_at", "desc")->get();
            $artikel_category = Artikel_category_model::where("company_id", Session::get("company_id"))->get();
            $data['artikel'] = $artikel;
            $data['artikel_category'] = $artikel_category;
        }

        return view("cms.applicant.index", compact("v", "list_menu", "lp", "list_test", "data"));
    }

    function applicant_delete($type, $id){
        if($type == "artikel"){
            $artikel = Artikel_model::find($id);
            $artikel->delete();
        }

        return redirect()->back();
    }

    function applicant_update(Request $request){
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", "public_html\kerjaku\assets", $_dir);
        $dir = str_replace("\\", "/", $dir);
        if($request->type == "artikel"){
            $artikel = Artikel_model::findOrNew($request->id);
            $artikel->company_id = Session::get("company_id");
            $artikel->category = $request->category;
            $artikel->subject = $request->title;
            $artikel->description = $request->content;
            $artikel->created_by = $request->created_by;

            $images = $request->file('img') ?? [];
            if(count($images) > 0){
                foreach($images as $i => $item){
                    $key = $i == "banner" ? "drawing" : "thumbnail";
                    $d = date("YmdHis");
                    $newName = $key."_$d-$artikel->company_id-".$item->getClientOriginalName();
                    if($item->move($this->dir, $newName)){
                        $artikel[$key] = "media/attachments/$newName";
                    }
                }
            }
            $artikel->save();
        } else {
            $lp = Pref_landing_applicant::firstOrNew(["company_id" => Session::get("company_id")]);
            if($request->type == "mk"){
                $lp->mk_title = $request->mk_title;
                $lp->mk_subtitle = $request->mk_subtitle;
                $title = $request->title;
                $content = $request->content;
                $img = $request->file("img");
                foreach($title as $i => $item){
                    $_title = "mk_title$i";
                    $_content = "mk_content$i";
                    $_img = "mk_img$i";
                    $lp[$_title] = $item;
                    $lp[$_content] = $content[$i];
                    if(isset($img[$i])){
                        $file = $img[$i];
                        if(!empty($file)){
                            $newName = $_img."_"."$lp->company_id-".$file->getClientOriginalName();
                            if($file->move($this->dir, $newName)){
                                $lp[$_img] = "media/attachments/$newName";
                            }
                        }
                    }
                }

            } elseif($request->type == "partner"){
                $img = $request->file("img") ?? [];
                $img_remove = $request->img_remove;
                if(count($img) > 0){
                    foreach($img as $i => $item){
                        $_img = "partner$i";
                        $file = $img[$i];
                        if(!empty($file)){
                            $newName = $_img."_"."$lp->company_id-".$file->getClientOriginalName();
                            if($file->move($this->dir, $newName)){
                                $lp[$_img] = "media/attachments/$newName";
                            }
                        }
                    }
                }
                foreach($img_remove as $i => $item){
                    $_img = "partner$i";
                    if($item == 1){
                        $lp[$_img] = null;
                    }
                }
            } elseif($request->type == "um"){
                $file = $request->file("img");
                $_img = "um_img";
                if(!empty($file)){
                    $newName = $_img."_"."$lp->company_id-".$file->getClientOriginalName();
                    if($file->move($this->dir, $newName)){
                        $lp[$_img] = "media/attachments/$newName";
                    }
                }
                $test_id = $request->test_id;
                $test_desc = $request->test_desc;
                foreach($test_id as $i => $item){
                    $_test_id = "um_test_id$i";
                    $_test_desc = "um_test_desc$i";
                    $lp[$_test_id] = $item;
                    $lp[$_test_desc] = $test_desc[$i];
                }
            } elseif($request->type == "sec"){
                $file = $request->file("img");
                $_img = "sec_img";
                if(!empty($file)){
                    $newName = $_img."_"."$lp->company_id-".$file->getClientOriginalName();
                    if($file->move($this->dir, $newName)){
                        $lp[$_img] = "media/attachments/$newName";
                    }
                }
                $lp->sec_title = $request->sec_title;
                $lp->sec_desc = $request->sec_desc;
            }

            $lp->save();
        }

        return redirect()->back();
    }

    function employer(Request $request){
        $list_menu = ["general"];
        $v = $request->v ?? "general";

        $lp = Pref_landing_employer::where('company_id', Session::get('company_id'))->first();

        $data = [];

        if($v == "user_stories"){
            $data['stories'] = User_stories_model::where("company_id", Session::get("company_id"))->orderBy("created_at", "desc")->get();
        }

        return view("cms.employer.index", compact("v", "list_menu", "lp", "data"));
    }

    function employer_delete($type, $id){
        if($type == "stories"){
            $stories = User_stories_model::find($id);
            $stories->delete();
        }

        return redirect()->back();
    }

    function employer_update(Request $request){
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", "public_html\kerjaku\assets", $_dir);
        $dir = str_replace("\\", "/", $dir);
        if($request->type == "stories"){
            $stories = User_stories_model::findOrNew($request->id);
            $stories->company_id = Session::get("company_id");
            $stories->name = $request->nama;
            $stories->specification = $request->perusahaan;
            $stories->notes = $request->content;
            $stories->created_by = Auth::user()->username;

            $images = $request->file('img');
            if(!empty($images)){
                $key = "picture";
                $d = date("YmdHis");
                $newName = $key."_$d-$stories->company_id-".$images->getClientOriginalName();
                if($images->move($this->dir, $newName)){
                    $stories[$key] = "media/attachments/$newName";
                }
            }
            $stories->save();
        } else {
            $lp = Pref_landing_employer::firstOrNew(["company_id" => Session::get("company_id")]);
            if($request->type == "general"){

                $logo = $request->file("logo");
                $_img = "logo";
                if(!empty($logo)){
                    $newName = $_img."_"."$lp->company_id-".$logo->getClientOriginalName();
                    if($logo->move($this->dir, $newName)){
                        $lp[$_img] = "media/attachments/$newName";
                    }
                }

                $favicon = $request->file("favicon");
                $_img = "favicon";
                if(!empty($favicon)){
                    $newName = $_img."_"."$lp->company_id-".$favicon->getClientOriginalName();
                    if($favicon->move($this->dir, $newName)){
                        $lp[$_img] = "media/attachments/$newName";
                    }
                }

                $lp->app_name = $request->app_name;
                $lp->company_name = $request->company_name;
                $lp->branding_color = $request->branding_color_primary_hex;
                $lp->branding_color_accent = $request->branding_color_accent_hex;

                // $lp->wa_no = str_replace("-", "", $request->wa_no);
                // $this->setEnv("WA_NO",$lp->wa_no);
            } elseif($request->type == "partner"){
                $img = $request->file("img") ?? [];
                if(count($img) > 0){
                    foreach($img as $i => $item){
                        $_img = "partner$i";
                        $file = $img[$i];
                        if(!empty($file)){
                            $newName = $_img."_"."$lp->company_id-".$file->getClientOriginalName();
                            if($file->move($this->dir, $newName)){
                                $lp[$_img] = "media/attachments/$newName";
                            }
                        }
                    }
                }
                $img_remove = $request->img_remove;
                foreach($img_remove as $i => $item){
                    $_img = "partner$i";
                    if($item == 1){
                        $lp[$_img] = null;
                    }
                }
            } elseif($request->type == "hs"){
                $file = $request->file("img");
                $_img = "hs_img";
                if(!empty($file)){
                    $newName = $_img."_"."$lp->company_id-".$file->getClientOriginalName();
                    if($file->move($this->dir, $newName)){
                        $lp[$_img] = "media/attachments/$newName";
                    }
                }
                $title = $request->title;
                $desc = $request->desc;
                foreach($title as $i => $item){
                    $_title = "hs_title$i";
                    $_desc = "hs_desc$i";
                    $lp[$_title] = $item;
                    $lp[$_desc] = $desc[$i];
                }
            } elseif($request->type == "wah"){
                $title = $request->title;
                $content = $request->content;
                $img = $request->file("img");
                foreach($title as $i => $item){
                    $_title = "wah_title$i";
                    $_content = "wah_content$i";
                    $_img = "wah_img$i";
                    $lp[$_title] = $item;
                    $lp[$_content] = $content[$i];
                    if(isset($img[$i])){
                        $file = $img[$i];
                        if(!empty($file)){
                            $newName = $_img."_"."$lp->company_id-".$file->getClientOriginalName();
                            if($file->move($this->dir, $newName)){
                                $lp[$_img] = "media/attachments/$newName";
                            }
                        }
                    }
                }

            }

            $lp->save();
        }

        return redirect()->back();
    }

    function pages_index(Request $request){
        $list_menu = ["FAQ", "About Us", "Review", "Informasi Pengiriman", "Bea Cukai dan Pajak", "Syarat dan Ketentuan", "Tambahan", "Fumigasi", "Non Stackable"];
        $v = $request->v ?? "faq";

        if($v == "faq"){
            $data = Faq::orderBy("order")->get();
        } elseif($v == "review"){
            $data = Review::orderBy("created_at", "desc")->get();
        } else {
            $data = Pref_page::where("page_name", $v)->first();
        }

        return view("cms.pages.index", compact("v", "list_menu", 'data'));
    }

    function pages_update(Request $request){
        if($request->type == "faq"){
            $faq = Faq::findOrNew($request->id);
            $faq->title = $request->title;
            $faq->description = $request->content;
            if(empty($faq->id)){
                $lastFaq = Faq::orderBy("order", "desc")->first();
                $order = ($lastFaq->order ?? 0) + 1;
                $faq->order = $order;
            }
            $faq->save();

            return redirect()->back();
        } elseif($request->type == "review"){
            $review = Review::findOrNew($request->id);
            $review->name = $request->name;
            $review->occupation = $request->occupation;
            $review->rating = $request->rating;
            $review->description = $request->content;

            $images = $request->file('img');
            if(!empty($images)){
                $key = "avatar";
                $d = date("YmdHis");
                $newName = $key."_$d-".$images->getClientOriginalName();
                if($images->move($this->dir, $newName)){
                    $review->avatars = "media/attachments/$newName";
                }
            }
            $review->save();

            return redirect()->back();
        } else {
            $data = Pref_page::firstOrNew(["page_name" => $request->type]);
            $data->content = $request->content;
            if($request->type == "fumigasi"){
                $data->fumigasi_base_price = str_replace(",", "", $request->base_price);
                $data->fumigasi_additional_price = str_replace(",", "", $request->additional_price);
            } elseif($request->type == "non_stackable"){
                // $data->non_stackable_base_price = str_replace(",", "", $request->base_price);
            }
            $data->save();
        }

        return redirect()->back();
    }

    function pages_order($type, $direction, $id){
        if($type == "faq"){
            $faq = Faq::find($id);
            if($direction == "up"){
                $swap = Faq::where("order", "<", $faq->order)->orderBy("order", "desc")->first();
            } else {
                $swap = Faq::where("order", ">", $faq->order)->orderBy("order", "asc")->first();
            }
            if($swap){
                $temp = $faq->order;
                $faq->order = $swap->order;
                $swap->order = $temp;
                $faq->save();
                $swap->save();
            }
        }

        return redirect()->back();
    }

    function pages_delete($type, $id){
        if($type == "faq"){
            $faq = Faq::find($id);
            if($faq){
                Faq::where("order", ">", $faq->order)->decrement("order");
                $faq->delete();
            }
        } elseif($type == "review"){
            $review = Review::find($id);
            if($review){
                $review->delete();
            }
        }

        return redirect()->back();
    }

    function faq(){
        $data = Pref_page::where("page_name", "faq")->first();
        $data = Faq::orderBy("order")->get();
        return view("cms.pages.faq", compact("data"));
    }

    function about_us(){
        $data = Pref_page::where("page_name", "about_us")->first();
        return view("cms.pages.about_us", compact("data"));
    }

    function review(){
        $data = Pref_page::where("page_name", "review")->first();
        return view("cms.pages.review", compact("data"));
    }
}
