<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Auth;

class FileManagement
{
    public static function save_file_management($hash, $file, $file_name, $target_dir){
        $fM = new \App\Models\File_Management();
        $fM->hash_code = $hash;
        $fM->file_name = $target_dir."/".$file_name;
        if (isset(Auth::user()->username)) {
            $username = Auth::user()->username;
        } else {
            $username = "System";
        }
        $fM->created_by = $username;

        $_dir = str_replace("/", "\\", public_path($target_dir));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $fdir = str_replace("\\", "/", $dir);
        if($file->move($fdir, $file_name)){
            if($fM->save()){
                return 1;
            } return 0;
        } else {
            return 2;
        }
    }

    public static function get_child($id){
        $comp = \App\Models\ConfigCompany::where('id_parent', $id)->get();
        $childs = array();
        foreach ($comp as $item){
            $childs[] = $item->id;
        }

        return $childs;
    }
}
