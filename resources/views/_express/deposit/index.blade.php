@extends('_express.layout')

@section('view_content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Deposit</h3>
        </div>
        <div class="card-body">
            <div class="d-flex flex-column gap-5">
                @if (\Session::has("notif"))
                    <div class="alert alert-success">
                        <div class="d-flex flex-column gap-5">
                            <div class="d-flex justify-content-between">
                                <span class="fs-3">Pesan</span>
                            </div>
                            <span>{{ \Session::get("notif") }}</span>
                        </div>
                    </div>
                @endif
                <span class="fw-bold fs-3">Saldo : IDR {{ number_format($saldo, 0, ",", ".") }}</span>
                <div class="separator separator-solid"></div>
                <span class="fw-bold fs-3">Buat Order Transaksi</span>
                <div class="w-md-50">
                    <ul class="nav nav-tabs nav-line-tabs fs-6">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">Deposit</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">Withdraw</a>
                        </li>
                    </ul>
                    <div class="tab-content border p-5"  id="myTabContent">
                        <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                            <form action="{{ route("deposit.deposit") }}" method="post">
                                @csrf
                                <div class="d-flex flex-column gap-3">
                                    <div class="input-group">
                                        <input type="text" data-min="200000" required class="form-control number" placeholder="Nominal" name="nominal">
                                        <button class="btn btn-success" name="submit" value="deposit" type="submit">
                                            <i class="fa fa-plus"></i>
                                            Deposit
                                        </button>
                                    </div>
                                    <span>Minimal deposit sebesar IDR 200.000 (Dua Ratus Ribu Rupiah)</span>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                            @if (empty(Auth::user()->bank_name) || empty(Auth::user()->rek_no))
                                <span>Untuk dapat melakukan withdraw, silakan lengkapi data Bank dan No. Rekening <a href="{{ route("account.setting") }}">disini</a></span>
                            @else
                                <form action="{{ route("deposit.deposit") }}" method="post">
                                    @csrf
                                    <div class="d-flex flex-column gap-3">
                                        <div class="input-group">
                                            <input type="text" data-min="50000" required class="form-control number" placeholder="Nominal" name="nominal">
                                            <button class="btn btn-warning" name="submit" value="withdraw" type="submit">
                                                <i class="fa fa-minus"></i>
                                                Withdraw
                                            </button>
                                        </div>
                                        <span>Minimal withdraw sebesar IDR 50.000 (Lima Puluh Ribu Rupiah)</span>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="separator separator-solid"></div>
                <span class="fw-bold fs-3">Mutasi</span>
                {{-- <div class="d-flex gap-3 w-md-50">
                    <div class="input-group">
                        <input type="date" class="form-control" placeholder="Nominal" name="start">
                        <span class="badge border input-group-addon bg-secondary">Hingga</span>
                        <input type="date" class="form-control" placeholder="Nominal" name="end">
                    </div>
                    <div class="input-group">
                        <input type="number" min="200000" required class="form-control" placeholder="Nominal" name="nominal">
                        <button class="btn btn-info" type="submit">
                            <i class="fa fa-search"></i>
                            Cari
                        </button>
                    </div>
                </div>
                <span class="fw-bold fs-3">Total Deposit : IDR {{ number_format($saldo, 0, ",", ".") }}</span>
                <span class="fw-bold fs-3">Total Pembayaran : IDR 0</span>
                <span class="fw-bold fs-3">Total Withdraw : IDR (0)</span>
                <div class="separator separator-solid"></div> --}}
                <table class="table table-bordered display" data-ordering="false">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Nominal (IDR)</th>
                            <th>Status</th>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deposit as $item)
                            <tr>
                                <td>{{ date("d M Y H:i", strtotime($item->created_at)) }}</td>
                                <td>{{ $item->type }}</td>
                                <td>{{ number_format($item->amount, 0, ",", ".") }}</td>
                                <td>
                                    @if ($item->type == "Payment")
                                        {!! $item->remarks !!}
                                    @elseif($item->type == "Withdraw")
                                        <span class="badge badge-lg badge-success">
                                            Withdraw berhasil
                                        </span>
                                    @else
                                        <span class="badge badge-lg badge-{{ $item->status == "created" ? "danger" : "success" }}">
                                            {{ $item->status == "created" ? "Belum transfer" : "Sudah transfer" }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->type == "Deposit")
                                        @if ($item->status == "created")
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#modalPay{{ $item->id }}" class="btn btn-success btn-sm">
                                                Transfer Sekarang
                                            </button>
                                            <a href="{{ route("deposit.delete", $item->id) }}" class="btn btn-danger btn-sm">
                                                <i class="fa fa-times"></i>
                                                Batal
                                            </a>
                                            <div class="modal fade" tabindex="-1" id="modalPay{{ $item->id }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content px-10">
                                                        <form action="{{ route("deposit.confirm") }}" method="post">
                                                            <div class="modal-header border-0">
                                                                <div class="d-flex w-100 justify-content-end">
                                                                    <a href="javascript:;" data-bs-dismiss="modal">
                                                                        <i class="fa fa-times"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="d-flex flex-column gap-5">
                                                                    <span class="fs-3 fw-bold">Total {{ number_format($item->amount, 0, ",", ".") }}</span>
                                                                    <div class="d-flex flex-column" data-virual>
                                                                        <span class="fs-3 mb-5">Virtual Account</span>
                                                                        <div data-toggle="virtual" data-target="bca" class="p-5 border-bottom d-flex align-items-center bg-hover-light gap-5">
                                                                            <img src="https://d2f3dnusg0rbp7.cloudfront.net/snap/v4/assets/bca-906e4db60303060666c5a10498c5a749962311037cf45e4f73866e9138dd9805.svg" class="w-50px" alt="">
                                                                            <span class="fs-3">BCA</span>
                                                                        </div>
                                                                        <div data-toggle="virtual" data-target="bni" class="p-5 border-bottom d-flex align-items-center bg-hover-light gap-5">
                                                                            <img src="https://d2f3dnusg0rbp7.cloudfront.net/snap/v4/assets/bni-163d98085f5fe9df4068b91d64c50f5e5b347ca2ee306d27954e37b424ec4863.svg" class="w-50px" alt="">
                                                                            <span class="fs-3">BNI</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex flex-column gap-5 d-none" data-virual-detail="bca">
                                                                        <div class="d-flex justify-content-between">
                                                                            <span>Bank BCA</span>
                                                                            <img src="https://d2f3dnusg0rbp7.cloudfront.net/snap/v4/assets/bca-906e4db60303060666c5a10498c5a749962311037cf45e4f73866e9138dd9805.svg" class="w-50px" alt="">
                                                                        </div>
                                                                        <span>Lakukan pembayaran dari rekening bank BCA ke nomor virtual account berikut.</span>
                                                                        <div class="fv-row">
                                                                            <label class="col-form-label">Nomor Virual Account</label>
                                                                            <input type="text" class="form-control" readonly value="1234567890">
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex flex-column gap-5 d-none" data-virual-detail="bni">
                                                                        <div class="d-flex justify-content-between">
                                                                            <span>Bank BNI</span>
                                                                            <img src="https://d2f3dnusg0rbp7.cloudfront.net/snap/v4/assets/bni-163d98085f5fe9df4068b91d64c50f5e5b347ca2ee306d27954e37b424ec4863.svg" class="w-50px" alt="">
                                                                        </div>
                                                                        <span>Lakukan pembayaran dari rekening bank BNI ke nomor virtual account berikut.</span>
                                                                        <div class="fv-row">
                                                                            <label class="col-form-label">Nomor Virual Account</label>
                                                                            <input type="text" class="form-control" readonly value="1234567890">
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="deposit_id" value="{{ $item->id }}">
                                                                    @csrf
                                                                    <button type="submit" data-virual-button class="btn btn-warning d-none">
                                                                        Bayar
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="border-0 modal-footer">

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('view_script')
    <script>
        $("table.display").DataTable()

        $(document).ready(function(){

            $("input.number").number(true, 2, ",", ".").change(function(){
                var min = $(this).attr("data-min")
                if($(this).val() < min){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Minimal deposit sebesar IDR '+min
                    })
                    $(this).val(min)
                }
            })

            $('[data-toggle="virtual"]').click(function(){
                var modal = $(this).parents("div.modal").eq(0)
                var target = $(this).attr("data-target")
                $(modal).find('[data-virual]').addClass('d-none')
                modal.find('[data-virual-detail="'+target+'"]').removeClass("d-none")
                modal.find('[data-virual-button]').removeClass("d-none")
            })
        })

    </script>
@endsection

