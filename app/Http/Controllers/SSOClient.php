<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use GuzzleHttp\Client as Http;

class SSOClient extends Controller
{
    function index(Request $request){

        $sso = Session::get("sso") ?? null;


        $data = [];

        if(empty($sso)){
            $request->session()->put("state", $state = Str::random(40));

            $query = http_build_query([
                "client_id" => "9b0c9367-1b6a-4631-a9ae-96cd0a732429",
                "redirect_uri" => "https://backend.kerjaku.cloud/sso-client/callback",
                "response_type" => "code",
                "scope" => "",
                "state" => $state
            ]);

            return redirect("http://sso.kerjaku.cloud/oauth/authorize?$query");
        }

        if(!empty($sso)){
            $http = new Http();

            $response = $http->request("GET","sso.kerjaku.cloud/api/clients", [
                "headers" => [
                    "Authorization" => "Bearer ".$sso['access_token'],
                    "Accept" => "application/json"
                ]
            ]);

            if($response->getStatusCode() == 200){
                $data = collect(json_decode((string) $response->getBody(), true));
            }
        }

        return view("_bp.sso.index", compact('data', "sso"));
    }

    function callback(Request $request){
        $state = $request->session()->pull("state");

        // throw_unless(strlen($state) > 0 && $state == $request->state, InvalidArgumentException::class);

        $query = [
            "grant_type" => "authorization_code",
            "client_id" => "9b0c9367-1b6a-4631-a9ae-96cd0a732429",
            "client_secret" => "GemAbvaGzHCpnhDB1KKbIvlcGziodQQWvg9MskCj",
            "redirect_uri" => "https://backend.kerjaku.cloud/sso-client/callback",
            "code" => urlencode($request->code)
        ];

        $http = new Http();

        $response = $http->post("http://sso.kerjaku.cloud/api/token", [
            "form_params" => $query
        ]);

        Session::put("sso", json_decode((string) $response->getBody(), true));

        return redirect()->route("sso_client.index");
    }

    function store($type, Request $request){
        $sso = Session::get("sso");
        $http = new Http();
        if($type == "store"){
            $validator = Validator::make($request->all(), [
                "client_name" => "required",
                "client_redirect_url" => "required|website"
            ]);

            if($validator->fails()){
                return redirect()->back()->with([
                    "modal" => empty($request->id) ? "modal_add" : "modal_edit_".$request->id
                ])->withErrors($validator)->withInput($request->all());
            }

            $data = [
                "name" => $request->client_name,
                "redirect" => $request->client_redirect_url
            ];

            $uri = empty($request->id) ? "http://sso.kerjaku.cloud/api/clients/store" : "http://sso.kerjaku.cloud/api/clients/update/$request->id";

            $response = $http->request(empty($request->id) ? "POST" : "PUT", $uri, [
                "form_params" => $data,
                "headers" => [
                    "Authorization" => "Bearer ".$sso['access_token'],
                    "Accept" => "application/json"
                ]
            ]);

            if($response->getStatusCode() == 201 || $response->getStatusCode() == 200){
                return redirect()->back()->with([
                    "toast" => [
                        "message" => empty($request->id) ? "Client successfully added" : "Client successfully updated",
                        "bg" => "bg-success"
                    ]
                ]);
            } else {
                return redirect()->back()->with([
                    "toast" => [
                        "message" => "Error occured",
                        "bg" => "bg-danger"
                    ]
                ]);
            }
        } else {
            $response = $http->request("DELETE", "http://sso.kerjaku.cloud/api/clients/delete/$request->id", [
                "headers" => [
                    "Authorization" => "Bearer ".$sso['access_token'],
                    "Accept" => "application/json"
                ]
            ]);

            return redirect()->back()->with([
                "toast" => [
                    "message" => "Client successfully deleted",
                    "bg" => "bg-success"
                ]
            ]);
        }
    }
}
