<?php

namespace App\Imports;

use App\Models\Kjk_crm_leads_contact;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
class KjkCrmLeadContacts implements ToModel, WithHeadingRow, WithValidation, WithCalculatedFormulas
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(isset($row['nama_lengkap'])){
            return new Kjk_crm_leads_contact([
                "name" => $row['nama_lengkap'],
                "position" => $row['jabatan'],
                "religion_id" => $row['agama_id'],
                "birth_date" => $row['tanggal_lahir_format'],
                "role" => $row['role'],
                "no_telp" => $row['phone_number'],
                "email" => $row['email'],
                "address" => $row['address'],
                "company_id" => Session::get('company_id'),
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => Auth::id()
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'tanggal_lahir_format' => function($attribute, $value, $onFailure){
                if (!empty($value) && DateTime::createFromFormat('Y-m-d', $value) == false) {
                    $onFailure("Date Format is wrong");
                }
            }
        ];
    }
}
