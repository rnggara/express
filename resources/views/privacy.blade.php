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
                Kebijakan &amp; Privasi  KerjaKu
            </h1>
            {{-- <p>
                Efektif per tanggal [10  Oktober 2023]
            </p> --}}
            <p>
                KerjaKu berkomitmen  untuk melindungi informasi pribadi Anda dan
                menjaga kerahasiaannya dengan  mengikuti kebijakan dan praktik privasi yang
                ketat. Kami menjelaskan bagaimana  kami mengumpulkan, menggunakan, dan
                melindungi data pribadi Anda saat menggunakan  platform kami. Dengan
                mengakses atau menggunakan platform ini, Anda secara  tegas menyetujui
                praktik yang dijelaskan dalam kebijakan privasi ini.
            </p>
            <ol>
                <li>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">
                            Jenis Informasi yang Kami  Kumpulkan
                        </span>
                        <span>
                            Kami dapat mengumpulkan  berbagai jenis informasi pribadi dari Anda,
                            termasuk tetapi tidak terbatas  pada:
                        </span>
                    </div>
                    <ul class="dashed">
                        <li>
                            Nama, alamat, tanggal  lahir, dan informasi kontak lainnya.
                        </li>
                        <li>
                            Foto wajah, lokasi koordinat, file-file yang di-unggah
                        </li>
                        <li>
                            Informasi pekerjaan,  seperti posisi, gaji, jadwal, dan riwayat pekerjaan.
                        </li>
                        <li>
                            Informasi perbankan atau  finansial untuk penggajian.
                        </li>
                        <li>
                            Informasi tentang cuti,  izin, atau absensi lainnya.
                        </li>
                        <li>
                            Informasi penggunaan  platform, termasuk aktivitas log, alamat IP,
                            dan perangkat yang digunakan.
                        </li>
                        <li>
                            Informasi proses  perekrutan karyawan.
                        </li>
                    </ul>
                </li>
                <li>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">
                            Penggunaan Informasi
                        </span>
                        <span>
                            Informasi pribadi yang kami  kumpulkan akan digunakan untuk:
                        </span>
                    </div>
                    <ul class="dashed">
                        <li>
                            Menyediakan akses dan  fungsionalitas platform.
                        </li>
                        <li>
                            Mengelola data calon  karyawan/ pelamar.
                        </li>
                        <li>
                            Mengelola data karyawan  dan administrasi perusahaan.
                        </li>
                        <li>
                            Memproses penggajian dan  manajemen tunjangan.
                        </li>
                        <li>
                            Mengirimkan pemberitahuan  dan komunikasi penting terkait pekerjaan.
                        </li>
                        <li>
                            Menyediakan dukungan  teknis dan layanan pelanggan.
                        </li>
                        <li>
                            Melakukan analisis untuk  meningkatkan kinerja dan pengalaman pengguna
                            platform.
                        </li>
                    </ul>
                </li>
                <li>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">
                            Keamanan Data
                        </span>
                        <span>
                            Kami mengambil  langkah-langkah teknis dan organisasi yang wajar untuk
                            melindungi data pribadi  Anda dari akses, penggunaan, atau pengungkapan yang
                            tidak sah. Hanya karyawan  yang membutuhkan akses untuk melaksanakan tugas
                            pekerjaan yang akan memiliki  izin untuk mengakses data pribadi Anda.
                        </span>
                    </div>
                </li>
                <li>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">
                            Pengungkapan Informasi
                        </span>
                        <span>
                            Kami tidak akan menjual,  menyewakan, atau menukar informasi pribadi Anda
                            kepada pihak ketiga tanpa izin  Anda, kecuali dalam situasi yang diwajibkan
                            oleh hukum atau ketentuan peraturan  yang berlaku.
                        </span>
                    </div>
                </li>
                <li>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">
                            Akses dan Perubahan Data  Pribadi
                        </span>
                        <span>
                            Anda memiliki hak untuk  mengakses, memperbarui, dan mengoreksi data pribadi
                            Anda yang kami simpan di  platform kami. Anda dapat melakukannya
                            melalui pengaturan akun Anda atau  menghubungi tim dukungan pelanggan kami.
                        </span>
                    </div>
                </li>
                <li>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">
                            Privasi Data Karyawan
                        </span>
                        <span>
                            Kami akan memperlakukan  informasi pribadi karyawan dengan kerahasiaan yang
                            tepat dan tidak akan  mengakses atau mengungkapkannya kecuali untuk tujuan
                            bisnis yang sah atau jika  diwajibkan oleh hukum.
                        </span>
                    </div>
                </li>
                <li>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">
                            Cookies dan Teknologi  Pelacak Lainnya
                        </span>
                        <span>
                            Kami dapat menggunakan  teknologi pelacak seperti cookies untuk mengumpulkan
                            informasi tentang  penggunaan platform dan meningkatkan pengalaman
                            pengguna. Anda dapat  mengelola preferensi cookie Anda melalui pengaturan
                            peramban Anda.
                        </span>
                    </div>
                </li>
                <li>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">
                            Perubahan pada Kebijakan  Privasi
                        </span>
                        <span>
                            Kebijakan privasi ini dapat  diperbarui dari waktu ke waktu. Setiap
                            perubahan akan diumumkan melalui  platform kami. Dengan terus
                            menggunakan platform ini setelah perubahan,  Anda menyetujui kebijakan
                            privasi yang diperbarui. Terima kasih telah  menggunakan platform
                            KerjaKu.
                        </span>
                    </div>
                </li>
            </ol>
        </div>
    </div>
@endsection
