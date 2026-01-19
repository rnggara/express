@extends('layouts.template')

@section('css')
    <style>
        ul {
        margin: 0;
        }
        ul.dashed {
        list-style-type: none;
        }
        ul.dashed > li {
        text-indent: -5px;
        }
        ul.dashed > li:before {
        content: "-";
        text-indent: -5px;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h1 class="fw-bold text-center mb-10">
                SYARAT &amp; KETENTUAN SISTEM KERJAKU
            </h1>
            {{-- <p>
                Efektif per tanggal 10 Oktober 2023
            </p> --}}
            <p>
                Selamat datang di KerjaKu, platform Sistem Informasi Sumber Daya Manusia
                yang disediakan oleh PT Global Infotech Solution. Dengan mengakses
                atau  menggunakan platform ini, Anda secara tegas menyetujui dan tunduk pada
                syarat  dan ketentuan yang tercantum di bawah ini. Pastikan untuk membaca
                dengan  seksama sebelum menggunakan KerjaKu.
            </p>
            <ol>
                <li>
                    <span class="fw-bold">Penggunaan Platform</span>
                    <ol start="1" type="a">
                        <li>
                            Kami       tidak bertanggung jawab atas alasan apapun yangmembuat
                            Platform Kami tidak       tersedia pada waktu atau periode tertentu.
                        </li>
                        <li>
                            Penggunaan platform ini       hanya untuk tujuan bisnis internal
                            perusahaan dan tidak boleh digunakan       untuk tujuan komersial atau
                            ilegal.
                        </li>
                    </ol>
                </li>
                <li>
                    <span class="fw-bold">
                        Akses dan Keamanan
                    </span>
                    <ol start="1" type="a">
                        <li>
                            Anda bertanggung jawab       untuk menjaga kerahasiaan informasi login
                            dan kata sandi Anda.
                        </li>
                        <li>
                            Setiap aktivitas yang       terjadi di bawah akun Anda dianggap sebagai
                            aktivitas yang dilakukan oleh       Anda sendiri.
                        </li>
                        <li>
                            Akun Anda       hanya dapat digunakan oleh Anda, sehingga Anda tidak
                            dapat mengalihkannya       kepada orang lain dengan alasan apapun. Kami
                            berhak menolak untuk memfasilitasi       Layanan jika Kami mengetahui
                            atau mempunyai alasan yang cukup untuk       menduga bahwa Anda telah
                            mengalihkan atau membiarkan Akun Anda digunakan       oleh orang lain.
                        </li>
                    </ol>
                </li>
                <li>
                    <span class="fw-bold">
                        Kebijakan Privasi
                    </span>
                    <ol start="1" type="a">
                        <li>
                            Informasi pribadi yang       Anda berikan akan diatur sesuai dengan
                            Kebijakan &amp; Privasi KerjaKu.
                        </li>
                        <li>
                            PT Global Infotech       Solution berkomitmen untuk melindungi informasi
                            pribadi Anda dan       menerapkan langkah-langkah keamanan yang sesuai.
                        </li>
                    </ol>
                </li>
                <li>
                    <span class="fw-bold">
                        Konten dan Informasi
                    </span>
                    <ol start="1" type="a">
                        <li>
                            Anda       setuju untuk tidak mengunggah, membagikan, atau menyimpan
                            konten yang :
                            <s>
                            </s>
                            <ul class="dashed">
                                <li>
                                    Mengandung materi yang  mencemarkan nama baik seseorang;
                                </li>
                                <li>
                                    Mengandung materi yang tidak  senonoh, menyinggung, bersifat
                                    kebencian, ataumenghasut;
                                </li>
                                <li>
                                    Mempromosikan konten yang  berkaitan dengan perjudian, undian,
                                    dan/atautaruhan;
                                </li>
                                <li>
                                    Mempromosikan materi yang  eksplisit secara seksual;
                                </li>
                                <li>
                                    Mempromosikan kekerasan;
                                </li>
                                <li>
                                    Mempromosikan diskriminasi  berdasarkan ras, jenis kelamin,
                                    agama, kebangsaan, kecacatan, orientasi  seksual, atau usia;
                                </li>
                                <li>
                                    Mempromosikan layanan meretas  (hacking) dan/atau membobol
                                    (cracking);
                                </li>
                                <li>
                                    Mempromosikan akses terhadap  perdagangan manusia dan organ
                                    tubuh manusia;
                                </li>
                                <li>
                                    Mempromosikan akses terhadap  zat-zat terlarang dan narkotika;
                                </li>
                                <li>
                                    Mempromosikan penjualan tidak  sah terhadap produk-produk yang
                                    membutuhkan lisensi (misalnya obat-obatan,  bahan peledak, senjata api,
                                    dll.);
                                </li>
                                <li>
                                    Melanggar hak cipta, hak  pangkalan data (database right), atau
                                    pun merek dagang orang lain;
                                </li>
                            </ul>
                        </li>
                        <li>
                            Anda       menjamin bahwa konten tersebut telah mematuhi standar yang
                            telah       disebutkan, dan bahwa anda akan bertanggung jawab secara
                            penuh kepada kami       dan mengganti kerugian kami atas pelanggaran
                            terhadap jaminan tersebut
                        </li>
                    </ol>
                </li>
                <li>
                    <span class="fw-bold">
                        Penghentian Akses
                    </span>
                    <p>
                        PT Global Infotech Solution berhak, atas kebijakannya sendiri, untuk
                        menghentikan atau memblokir akses Anda ke KerjaKu jika melanggar syarat
                        dan ketentuan ini.
                    </p>
                </li>
                <li>
                    <span class="fw-bold">
                        Perubahan Syarat &amp; Ketentuan
                    </span>
                    <p>
                        PT Global Infotech Solution dapat memperbarui atau mengubah syarat dan
                        ketentuan ini dari waktu ke waktu. Perubahan akan diumumkan melalui platform
                    . Dengan melanjutkan penggunaan setelah perubahan, Anda menyetujui
                        syarat  dan ketentuan yang diperbarui.
                    </p>
                </li>
                <li>
                    <span class="fw-bold">
                        Dukungan Pelanggan
                    </span>
                    <p>
                        Untuk bantuan atau pertanyaan terkait penggunaan KerjaKu, Anda dapat
                        menghubungi tim dukungan pelanggan kami melalui kontak yang tercantum di
                        platform.
                    </p>
                </li>
                <li>
                    <span class="fw-bold">
                        Tidak Ada Jaminan
                    </span>
                    <p>
                        Platform ini disediakan "apa adanya" dan PT Global Infotech  Solution tidak
                        memberikan jaminan, baik tersurat maupun tersirat, mengenai  kualitas,
                        keandalan, atau kesesuaian untuk tujuan tertentu.
                    </p>
                </li>
                <li>
                    <span class="fw-bold">
                        Batasan Tanggung Jawab
                    </span>
                    <ol start="1" type="a">
                        <li>
                            PT Global Infotech       Solution tidak bertanggung jawab atas kerugian
                            atau kerusakan yang timbul       akibat penggunaan platform ini.
                        </li>
                        <li>
                            Kami       tidak bertanggung jawab kepada pengguna mana pun atas
                            kerugian atau       kerusakan, baik dalam bentuk kontrak, perbuatan
                            melawan hukum, pelanggaran       kewajiban berdasarkan undang-undang,
                            atau sebaliknya.
                        </li>
                        <li>
                            Kami       tidak bertanggung jawab atas kerugian atau kerusakan yang
                            disebabkan oleh       virus, serangan Penolakan Layanan secara
                            Terdistribusi (DDoS), maupun       materi teknologi berbahaya lainnya
                            yang dapat menginfeksi peralatan       komputer, programkomputer, data,
                            atau materi kepemilikan lainnya karena       penggunaan maupun
                            pengunduhan konten apapundari Platform Kami maupun situs       web lain
                            yang terkait dengannya oleh Anda.
                        </li>
                        <li>
                            Kami       tidak bertanggung jawab atas konten situs web yang terhubung
                            dengan       Platform Kami. Tautan semacam itu seharusnya tidak
                            ditafsirkan sebagai       bentuk dukungan Kami terhadap situs-situs
                            tersebut.
                        </li>
                        <li>
                            Kami       tidak bertanggung jawab atas kerugian atau kerusakan
                            apapunyang timbul       dari penggunaan Anda terhadap situs-situs web
                            tersebut.
                        </li>
                    </ol>
                </li>
                <li>
                    <span class="fw-bold">
                        Keadaan Kahar (Force Majeure)
                    </span>
                    <p>
                        Platform Kami dapat diinterupsi oleh  kejadian di luar kewenangan atau
                        kontrol Kami (“Keadaan Kahar”), termasuk namun  tidak terbatas pada bencana
                        alam, gangguan listrik, gangguan telekomunikasi,  kebijakan pemerintah, dan
                        lain-lain. Anda setuju untuk membebaskan Kami dari  setiap tuntutan dan
                        tanggung jawab, jika Kami tidak dapat memfasilitasi  Layanan, termasuk
                        memenuhi instruksi yang Anda berikan melalui Platform, baik  sebagian maupun
                        seluruhnya, karena suatu Keadaan Kahar
                    </p>
                </li>
                <li>
                    <span class="fw-bold">
                        Hukum yang Berlaku
                    </span>
                    <p>
                        Syarat dan ketentuan ini diatur oleh hukum yang berlaku di Indonesia.
                        Setiap perselisihan yang timbul Penggunaan KerjaKu akan diselesaikan melalui
                        mekanisme penyelesaian sengketa yang disepakati.
                    </p>
                </li>
                <li>
                    <span class="fw-bold">
                        Ketentuan Lain-Lain
                    </span>
                    <ol start="1" type="a">
                        <li>
                            Dengan mengakses atau       menggunakan KerjaKu, Anda mengakui
                            bahwa Anda telah membaca,       memahami, dan setuju untuk mematuhi
                            syarat dan ketentuan ini. Jika Anda       tidak setuju dengan syarat dan
                            ketentuan ini, harap tidak menggunakan       platform ini.
                        </li>
                        <li>
                            Harap diingat bahwa       syarat dan ketentuan ini dapat berubah dari
                            waktu ke waktu, dan Anda       diwajibkan untuk memeriksa kembali saat
                            ada perubahan.
                        </li>
                    </ol>
                </li>
            </ol>
            <p>
                <em>
                    Saya telah membaca dan mengerti  seluruh Syarat dan Ketentuan ini dan
                    konsekuensinya dan dengan ini menerima setiap hak, kewajiban, dan
                    ketentuan yang diatur di dalamnya.
                </em>
                <em></em>
            </p>
        </div>
    </div>
@endsection
