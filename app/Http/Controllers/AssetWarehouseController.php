<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset_wh;
use App\Models\Hrd_employee;
use App\Models\Kjk_employee_location;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mpdf\Tag\Tr;

class AssetWarehouseController extends Controller
{
    public function index(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }

        $all = Asset_wh::whereIn('company_id', $id_companies)->get();

        return view('wh.index',[
            'whs' => $all,
        ]);
    }

    function view_page($id){
        $wh = Asset_wh::find($id);

        $list_emp = Kjk_employee_location::where("wh_id", $id)->get();

        $emp = Hrd_employee::where("company_id", Session::get("company_id"))
            ->whereNotIn("id", $list_emp->pluck("emp_id"))
            ->get();

        $emp_all = Hrd_employee::where("company_id", Session::get("company_id"))
            ->get();

        $emp_name = $emp_all->pluck("emp_name", "id");

        return view("wh.view_page", compact("wh", "emp", "list_emp", "emp_name"));
    }

    function delete_user($id){
        try {
            Kjk_employee_location::find($id)->delete();
        } catch (\Throwable $th) {
            //throw $th;
        }

        return redirect()->back();
    }

    function add_user(Request $request){
        $data = [];

        $table = json_decode($request->emp_id_table, true);

        foreach($request->emp_id ?? [] as $item){
            $col = [];
            $col["wh_id"] = $request->wh_id;
            $col['emp_id'] = $item;
            $col['company_id'] = Session::get("company_id");
            $col['created_at'] = date("Y-m-d H:i:s");
            $col['updated_at'] = date("Y-m-d H:i:s");
            $col['created_by'] = Auth::id();
            $data[] = $col;
        }

        foreach($table as $item){
            $filtered_array = array_filter($data, function ($obj) use ($item) {
                return $obj['emp_id'] == $item;
            });
            if(count($filtered_array) == 0){
                $col = [];
                $col["wh_id"] = $request->wh_id;
                $col['emp_id'] = $item;
                $col['company_id'] = Session::get("company_id");
                $col['created_at'] = date("Y-m-d H:i:s");
                $col['updated_at'] = date("Y-m-d H:i:s");
                $col['created_by'] = Auth::id();
                $data[] = $col;
            }
        }

        Kjk_employee_location::insert($data);

        return redirect()->back();
    }

    public function delete($id){
        Asset_wh::where('id',$id)->update([
            'deleted_by' => Auth::user()->username,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
        Asset_wh::where('id',$id)->delete();

        return redirect()->route('wh.index');
    }

    public function store(Request $request){
        $wh = new Asset_wh();
        $wh->name = $request->name;
        $wh->address = $request->address;
        $wh->telephone = $request->telephone;
        $wh->pic = $request->pic;
        $wh->created_at = date('Y-m-d H:i:s');
        $wh->company_id = \Session::get('company_id');
        if(!empty($request->_type)){
            $wh->office = $request->_type;
        }
        $wh->longitude = $request->longitude;
        $wh->latitude = $request->latitude;
        $wh->longitude2 = $request->longitude2;
        $wh->latitude2 = $request->latitude2;
        $wh->btn_class = $request->btn_class;
        $wh->save();
        return redirect()->route('wh.index');
    }

    public function update(Request $request){
        $wh = Asset_wh::find($request->id);
        $wh->name = $request->name;
        $wh->address = $request->address;
        $wh->telephone = $request->telephone;
        $wh->pic = $request->pic;
        if (!empty($request->_type)) {
            $wh->office = $request->_type;
        } else {
            $wh->office = null;
        }
        $wh->longitude = $request->longitude;
        $wh->latitude = $request->latitude;
        $wh->longitude2 = $request->longitude2;
        $wh->latitude2 = $request->latitude2;
        $wh->btn_class = $request->btn_class;
        $wh->save();
        return redirect()->route('wh.index');
    }

    function rack($id){
        return view("wh.rack", compact("id"));
    }


    function qr($id){
        $wh = Asset_wh::find($id);

        return view("wh.qr", compact("wh"));
    }

    function qr_view($id){
        $wh = Asset_wh::find($id);
        $wh_type = "Others";
        switch ($wh->office) {
            case 1:
                $wh_type = "Office";
                break;
            case 2:
                $wh_type = "Warehouse";
                break;
            case 3:
                $wh_type = "Project";
                break;
        }

        return view("wh.qr_view", compact("wh", "wh_type"));
    }

    function upload_image(Request $request){
        $wh = Asset_wh::find($request->id);
        $file = $request->file("featured_image");
        $now = date("YmdHis");
        if(!empty($file)){
            $fName = "[WH-$wh->id-$now]_".$file->getClientOriginalName();
            if($file->move(public_path('media/building_gallery/'), $fName)){
                $wh->featured_image = "media/building_gallery/$fName";
                $wh->save();
            }
        }

        return redirect()->back();
    }
}
