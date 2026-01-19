@extends('layouts.templatePrint')

@section('content')
<div class="d-flex flex-column gap-3">
    <h1 class="title text-center">Salary Slip</h1>
    <h2 class="company-name">{{ $company->company_name }}</h2>
    <div class="row border border-start-0 border-end-0 border-3 border-dark fw-semibold">
        <div class="col-6">
            <table class="personel-details table table-sm">
                <tr>
                    <td>Name</td>
                    <td>:</td>
                    <td>{{ $personel->emp_name }}</td>
                </tr>
                <tr>
                    <td>Employee ID</td>
                    <td>:</td>
                    <td>{{ $personel->emp_id }}</td>
                </tr>
                <tr>
                    <td>Department</td>
                    <td>:</td>
                    <td>{{ $user->uacdepartement->name ?? "-" }}</td>
                </tr>
                <tr>
                    <td>Title</td>
                    <td>:</td>
                    <td>{{ $personel->position->name ?? "-"}}</td>
                </tr>
                <tr>
                    <td>Location</td>
                    <td>:</td>
                    <td>{{ $user->uaclocation->name ?? "-" }}</td>
                </tr>
            </table>
        </div>
        <div class="col-6">
            <table class="personel-details table table-sm">
                <tr>
                    <td>Period</td>
                    <td>:</td>
                    <td>{{ date("m - Y", strtotime($periode)) }}</td>
                </tr>
                <tr>
                    <td>NPWP</td>
                    <td>:</td>
                    <td>{{ $profile->npwp }}</td>
                </tr>
                <tr>
                    <td>Tax Status</td>
                    <td>:</td>
                    <td>{{ $tax_status->code ?? "-" }}</td>
                </tr>
                <tr>
                    <td>Account Name</td>
                    <td>:</td>
                    <td>{{ $profile->personal_bank_name ?? "-" }}</td>
                </tr>
                <tr>
                    <td>Bank Account</td>
                    <td>:</td>
                    <td>{{ $profile->personal_bank_number ?? "-" }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row fw-semibold">
        <div class="col-6 d-flex flex-column gap-3">
            <h3 class="title">Income</h3>
            <table class="personel-details table table-sm">
                <tr>
                    <td>Gaji Pokok</td>
                    <td>:</td>
                    <td align="right">0</td>
                </tr>
                <tr>
                    <td>Overtime</td>
                    <td>:</td>
                    <td align="right">0</td>
                </tr>
                <tr>
                    <td>Uang Makan</td>
                    <td>:</td>
                    <td align="right">0</td>
                </tr>
                <tr>
                    <td>Uang Transport</td>
                    <td>:</td>
                    <td align="right">0</td>
                </tr>
                <tr>
                    <td>Tunj. Hp</td>
                    <td>:</td>
                    <td align="right">0</td>
                </tr>
                <tr>
                    <td>THR</td>
                    <td>:</td>
                    <td align="right">0</td>
                </tr>
                <tr>
                    <td>Tunj. Lain lain</td>
                    <td>:</td>
                    <td align="right">0</td>
                </tr>
            </table>
        </div>
        <div class="col-6 d-flex flex-column gap-3">
            <h3 class="title">Deduction</h3>
            <table class="personel-details table table-sm">
                <tr>
                    <td>JHT Karyawan 2%</td>
                    <td>:</td>
                    <td align="right">{{ number_format($profile->jht_karyawan, 2) }}</td>
                </tr>
                <tr>
                    <td>BPJS Karyawan 1%</td>
                    <td>:</td>
                    <td align="right">{{ number_format($profile->bpjs_karyawan, 2) }}</td>
                </tr>
                <tr>
                    <td>Pot. Loan</td>
                    <td>:</td>
                    <td align="right">{{ number_format($profile->pot_loan, 2) }}</td>
                </tr>
                <tr>
                    <td>JP 1%</td>
                    <td>:</td>
                    <td align="right">{{ number_format($profile->jpk, 2) }}</td>
                </tr>
                <tr>
                    <td>Pot. Lain lain</td>
                    <td>:</td>
                    <td align="right">{{ number_format($profile->pot_lain, 2) }}</td>
                </tr>
                <tr>
                    <td>Tax PPh21</td>
                    <td>:</td>
                    <td align="right">{{ number_format($profile->tax_pph21, 2) }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
        <div class="col-6"></div>
        <div class="col-6 border border-start-0 border-end-0 border-bottom-0 border-3 border-dark">
            <table class="table table-sm mt-">
                <tr>
                    <td class="fw-bold">Total Deduction</td>
                    <td></td>
                    <td align="right">0</td>
                </tr>
            </table>
        </div>
        <div class="col-12 border border-start-0 border-end-0 border-3 border-dark">
            <div class="row">
                <div class="col-6">
                    <table class="table table-sm mt-">
                        <tr>
                            <td class="fw-bold">Total Income</td>
                            <td></td>
                            <td align="right">0</td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-sm mt-">
                        <tr>
                            <td class="fw-bold">Take Home Pay</td>
                            <td></td>
                            <td align="right">0</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 d-flex justify-content-between">
            <span class="w-100">
                <i>* Sign not requirement</i>
            </span>
            <span class="text-center w-100">
                -
            </span>
        </div>
    </div>
</div>
<style>

    .title {
        text-decoration: underline;
    }

</style>

@endsection