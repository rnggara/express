<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Webhook_sendinblue;

class MailController extends Controller
{
    function webhook(Request $request){
        $webhook = new Webhook_sendinblue();
        $webhook->response = json_encode($request->all());
        $webhook->save();
    }

    function test_mail(Request $request){
        $body = [];
        $body["sender"]["email"] = "admin@psi.com";
        $body["sender"]["name"] = "ADMIN ERP";

        $body["subject"] = "Covid Protocol Access";
        $username = $request->username;
        $view = $request->username." accessed covid-protocol";
        // return $view;
        $body["htmlContent"] = $view;

        $to = [];

        $receipent["email"] = "ranggaanggara8@gmail.com";
        $receipent["name"] = "Rangga Anggara";
        $to[] = $receipent;

        $message["to"] = $to;
        $message["htmlContent"] = $view;
        $message["subject"] = "Covid Protocol Access";

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

            return $exec;
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}
