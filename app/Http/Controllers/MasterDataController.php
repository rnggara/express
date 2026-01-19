<?php

namespace App\Http\Controllers;

use App\Models\Master_city;
use App\Models\Master_district;
use App\Models\Master_educations;
use App\Models\Master_gender;
use App\Models\Master_industry;
use App\Models\Master_job_level;
use App\Models\Master_job_type;
use App\Models\Master_language;
use App\Models\Master_marital_status;
use App\Models\Master_proficiency;
use App\Models\Master_province;
use App\Models\Master_religion;
use App\Models\Master_skill;
use App\Models\Master_specialization;
use App\Models\Master_subdistrict;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterDataController extends Controller
{

    private $mProv, $mCities, $mDist, $mSubdist, $m_religion, $m_gender, $m_industry, $m_job_level, $m_job_type, $m_language, $m_proficiency, $m_skill, $m_specialization;

    function __construct()
    {
        $this->mProv = "master_provinces";
        $this->mCities = "master_cities";
        $this->mDist = "master_districts";
        $this->mSubdist = "master_subdistricts";
        $this->m_gender = Master_gender::class;
        $this->m_industry = Master_industry::class;
        $this->m_job_level = Master_job_level::class;
        $this->m_job_type = Master_job_type::class;
        $this->m_language = Master_language::class;
        $this->m_proficiency = Master_proficiency::class;
        $this->m_religion = Master_religion::class;
        $this->m_skill = Master_skill::class;
        $this->m_specialization = Master_specialization::class;
    }

    function index(Request $request){
        $v = $request->v ?? "locations";
        $data = [];
        $data['list'] = [];
        $data['state'] = $v;
        if($v == "locations"){
            $p = $request->p;
            $data['beard'] = [];
            if(empty($p)){
                $list = Master_province::get();
                // $list = DB::table("$this->mProv")
                //     ->select("*")
                //     ->get();
                $data['url'] = route("master_data.index")."?v=locations&p=";
                $data['state'] = "province";
            } else {
                $prov = Master_province::find($p);
                $data['beard']['province'] = "?v=locations&p=$prov->id";
                $data['url'] = route("master_data.index")."?v=locations&p=$p&c=";
                $data['province'] = $prov;
                $data['id'] = $prov->id;

                $c = $request->c;
                if(empty($c)){
                    $list = Master_city::where("prov_id", $p)->get();
                    $data['state'] = "city";
                } else {
                    $city = Master_city::find($c);
                    $data['url'] = route("master_data.index")."?v=locations&p=$p&c=$c&d=";

                    $data['beard']['city'] = route("master_data.index")."?v=locations&p=$p&c=$c";
                    $data['city'] = $city;
                    $data['id'] = $city->id;

                    $d = $request->d;
                    if(empty($d)){
                        $list = Master_district::where("city_id", $city->id)->get();
                        $data['state'] = "district";
                    } else {
                        $data['url'] = "#";
                        $district = Master_district::find($d);
                        $data['beard']['district'] = route("master_data.index")."?v=locations&p=$p&c=$c&d=$d";
                        $data['district'] = $district;
                        $data['state'] = "subdistrict";
                        $data['id'] = $district->id;
                        $list = Master_subdistrict::where("dis_id", $district->id)->get();
                    }
                }
            }

            $data['list'] = $list;
        } elseif($v == "educations"){
            $list = Master_educations::get();
            $data['state'] = "master_study";
            $data['list'] = $list;
        } elseif($v == "marriege"){
            $list = Master_marital_status::get();
            $data['state'] = "master_marriege";
            $data['list'] = $list;
        } else{
            $c = "m_$v";
            $list = $this->$c::get();
            $data['list'] = $list;
        }
        return view("master_data.index", compact('v', 'data'));
    }

    function store(Request $request){
        $t = Master_province::class;
        if($request->state == "city"){
            $t = Master_city::class;
        } elseif($request->state == "district"){
            $t = Master_district::class;
        } elseif($request->state == "subdistrict"){
            $t = Master_subdistrict::class;
        } elseif($request->state == "master_study"){
            $t = Master_educations::class;
        } elseif($request->state == "master_marriege"){
            $t = Master_marital_status::class;
        } else {
            $c = "m_$request->state";
            $t = $this->$c;
        }

        $n = $t::find($request->id_edit);
        if(empty($n)){
            $n = new $t();
        }
        $n->name = $request->name;
        if(!empty($request->id)){
            if($request->state == "city"){
                $n->prov_id = $request->id;
            } elseif($request->state == "district"){
                $n->city_id = $request->id;
            } elseif($request->state == "subdistrict"){
                $n->dis_id = $request->id;
            }
        }

        $n->save();

        return redirect()->back();
    }

    function delete($state, $id){
        $t = Master_province::class;
        if($state == "city"){
            $t = Master_city::class;
        } elseif($state == "district"){
            $t = Master_district::class;
        } elseif($state == "subdistrict"){
            $t = Master_subdistrict::class;
        } elseif($state == "master_study"){
            $t = Master_educations::class;
        } elseif($state == "master_marriege"){
            $t = Master_marital_status::class;
        } else {
            $c = "m_$state";
            $t = $this->$c;
        }

        $n = $t::find($id);
        if(!empty($n)){
            $n->delete();
        }

        return redirect()->back();
    }
}
