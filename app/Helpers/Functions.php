<?php

namespace App\Helpers;

use App\Models\User_experience;
use App\Models\User_formal_education;
use App\Models\User_informal_education;
use App\Models\User_portofolio;
use App\Models\User_profile;
use App\Models\User_skill;
use App\Models\User_language_skill;
use App\Models\User_medsos;
use App\Models\User_add_family;
use App\Models\User_add_id_card;
use App\Models\User_add_license;
use App\Models\Hrd_employee_test_result;
use Illuminate\Support\Facades\Auth;

class Functions {

    public static function isMobile(){
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
    public static function terbilang($num){
        $digits = array(
            0 => "nol",
            1 => "satu",
            2 => "dua",
            3 => "tiga",
            4 => "empat",
            5 => "lima",
            6 => "enam",
            7 => "tujuh",
            8 => "delapan",
            9 => "sembilan");
        $orders = array(
            0 => "",
            1 => "puluh",
            2 => "ratus",
            3 => "ribu",
            6 => "juta",
            9 => "miliar",
            12 => "triliun",
            15 => "kuadriliun");

        $is_neg = $num < 0; $num = "$num";

        //// angka di kiri desimal

        $int = ""; if (preg_match("/^[+-]?(\d+)/", $num, $m)) $int = $m[1];
        $mult = 0; $wint = "";

        // ambil ribuan/jutaan/dst
        while (preg_match('/(\d{1,3})$/', $int, $m)) {

            // ambil satuan, puluhan, dan ratusan
            $s = $m[1] % 10;
            $p = ($m[1] % 100 - $s)/10;
            $r = ($m[1] - $p*10 - $s)/100;

            // konversi ratusan
            if ($r==0) $g = "";
            elseif ($r==1) $g = "se$orders[2]";
            else $g = $digits[$r]." $orders[2]";

            // konversi puluhan dan satuan
            if ($p==0) {
                if ($s==0);
                elseif ($s==1) $g = ($g ? "$g ".$digits[$s] :
                    ($mult==0 ? $digits[1] : "se"));
                else $g = ($g ? "$g ":"") . $digits[$s];
            } elseif ($p==1) {
                if ($s==0) $g = ($g ? "$g ":"") . "se$orders[1]";
                elseif ($s==1) $g = ($g ? "$g ":"") . "sebelas";
                else $g = ($g ? "$g ":"") . $digits[$s] . " belas";
            } else {
                $g = ($g ? "$g ":"").$digits[$p]." puluh".
                    ($s > 0 ? " ".$digits[$s] : "");
            }

            // gabungkan dengan hasil sebelumnya
            $wint = ($g ? $g.($g=="se" ? "":" ").$orders[$mult]:"").
                ($wint ? " $wint":"");

            // pangkas ribuan/jutaan/dsb yang sudah dikonversi
            $int = preg_replace('/\d{1,3}$/', '', $int);
            $mult+=3;
        }
        if (!$wint) $wint = $digits[0];

        // angka di kanan desimal

        $frac = ""; if (preg_match("/\.(\d+)/", $num, $m)) $frac = $m[1];
        $wfrac = "";
        for ($i=0; $i<strlen($frac); $i++) {
            $wfrac .= ($wfrac ? " ":"").$digits[substr($frac,$i,1)];
        }
        $wfrac = 0;

        return ($is_neg ? "minus ":"").$wint.($wfrac ? "koma $wfrac":"");
    }

    public static function ordinal($num){
        if (!in_array(($num % 100),array(11,12,13))){
            switch ($num % 10) {
              // Handle 1st, 2nd, 3rd
              case 1:  return $num.'st';
              case 2:  return $num.'nd';
              case 3:  return $num.'rd';
            }
        }
        return $num.'th';
    }

    function send_mail($subject, $sent_to, $content){
        $body = [];
        $body["sender"]["email"] = "cypher@vesselholding.com";
        $body["sender"]["name"] = "CYPHER";

        $body["subject"] = $subject;
        // return $view;
        $body["htmlContent"] = "<!DOCTYPE html><html><body><h1>Hello</h1></body></html>";

        $to = [];

        // $receipent["email"] = "kepaladukun@gmail.com";
        // $receipent["name"] = "Rangga Anggara";
        // $to[] = $receipent;

        // $receipent["email"] = "vdg.indonesia@gmail.com";
        // $receipent["name"] = "Vessel";
        // $to[] = $receipent;

        foreach ($sent_to as $key => $value) {
            $receipent["email"] = $value['email'];
            $receipent["name"] = $value['name'];
            $to[] = $receipent;
        }

        $message["to"] = $to;
        $message["htmlContent"] = $content;
        $message["subject"] = $subject;

        $body["messageVersions"][] = $message;
        $body["tags"] = ["myTransactional"];

        $url = "https://api.sendinblue.com/v3/smtp/email";
        $ch = curl_init($url);

        $post = json_encode($body);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'accept: application/json', 'api-key: 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'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
        try {
            $exec = curl_exec($ch);
            $js = json_decode($exec, true);
            if(isset($js['messageIds'])){
                return $response = [
                    'success' => 1,
                ];
            } else {
                return $response = [
                    'success' => 0,
                    'messages' => "error"
                ];
            }
        } catch (\Throwable $th) {
            return $response = [
                'success' => 0,
                'messages' => $th->getMessage()
            ];
        }
    }

    public static function instance()
     {
         return new Functions();
     }

     public static function getDetailProfile(){
        $id = Auth::id();
        $profile = User_profile::where("user_id", $id)->first();
        $exp = User_experience::where("user_id", $id)->first();
        $edufo = User_formal_education::where("user_id", $id)->first();
        $eduin = User_informal_education::where("user_id", $id)->first();
        $port = User_portofolio::where("user_id", $id)->first();
        $skill = User_skill::where("user_id", $id)->first();
        $skill_language = User_language_skill::where("user_id", $id)->first();
        $medsos = User_medsos::where("user_id", $id)->first();
        $test_result = Hrd_employee_test_result::where("user_id", $id)->first();

        $fam = User_add_family::where("user_id", $id)->first();
        $id_card = User_add_id_card::where("user_id", $id)->first();
        $license = User_add_license::where("user_id", $id)->first();

        $myProfile = [];
        $edu = 0;
        $edu += empty($edufo) ? 0 : .5;
        $edu += empty($eduin) ? 0 : .5;
        $myProfile['data_personal']['link'] = "personal_data";
        $myProfile['data_personal']['check'] = empty($profile) ? 0 : 1;
        $myProfile['pendidikan']['link'] = "education";
        $myProfile['pendidikan']['check'] = empty($edufo) && empty($eduin) ? 0 : 1;
        $myProfile['pengalaman_kerja']['link'] = "experience";
        $myProfile['pengalaman_kerja']['check'] = empty($exp) ? 0 : 1;
        $myProfile['kemampuan']['link'] = "skill";
        $myProfile['kemampuan']['check'] = empty($skill) && empty($skill_language) ? 0 : 1;
        $myProfile['portofolio']['link'] = "portofolio";
        $myProfile['portofolio']['check'] = empty($port) ? 0 : 1;
        $myProfile['media_social']['link'] = "media_sosial";
        $myProfile['media_social']['check'] = empty($medsos) ? 0 : 1;
        $myProfile['hasil_tes']['link'] = "test_result";
        $myProfile['hasil_tes']['check'] = empty($test_result) ? 0 : 1;
        $myProfile['tambahan']['link'] = "additional_information";
        $myProfile['tambahan']['check'] = empty($fam) && empty($id_card) && empty($license) ? 0 : 1;

        return $myProfile;
     }

     public static function getProfile(){
        $point = 0;
        $id = Auth::id();
        $profile = User_profile::where("user_id", $id)->first();
        $exp = User_experience::where("user_id", $id)->first();
        $edufo = User_formal_education::where("user_id", $id)->first();
        $eduin = User_informal_education::where("user_id", $id)->first();
        $port = User_portofolio::where("user_id", $id)->first();
        $skill = User_skill::where("user_id", $id)->first();
        $skill_language = User_language_skill::where("user_id", $id)->first();
        $medsos = User_medsos::where("user_id", $id)->first();

        $point += empty($profile) ? 0 : 1;
        $point += empty($exp) ? 0 : 1;
        $point += empty($edufo) ? 0 : .5;
        $point += empty($eduin) ? 0 : .5;
        $point += empty($port) ? 0 : 1;
        $point += empty($skill) ? 0 : 1;

        $pct = ($point / 5) * 100;

        if($point > 5){
            $pct = 100;
        }

        return $pct;
     }

     public static function http_request($url, $type, $data = null, $headers = null){
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
            if(!empty($headers)){
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            if(!empty($data)){
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
            $result = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($result, true);
            $res = [
                'success' => 1,
                'data' => $response
            ];
        } catch (\Throwable $th) {
            $res = [
                'success' => 0,
                'message' => $th->getMessage()
            ];
        }

        return $res;
     }
}


?>
