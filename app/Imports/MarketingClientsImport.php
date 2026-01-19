<?php

namespace App\Imports;

use App\Models\Marketing_clients;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MarketingClientsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(isset($row['company_name'])){
            return new Marketing_clients([
                'company_name' => $row['company_name'],
                "category" => $row['business_entity'],
                "type" => $row['type'],
                "jumlah_karyawan" => $row['number_of_employees'],
                "pic_number" => $row['phone_number'],
                "email" => $row['email'],
                "address" => $row['address'],
                "company_id" => Session::get('company_id'),
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => Auth::id()
            ]);
        }
    }
}
