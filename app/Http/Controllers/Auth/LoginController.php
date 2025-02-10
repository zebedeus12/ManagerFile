<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input login
    $request->validate([
        'login' => 'required|string',
        'password' => 'required|string',
    ]);

    // Dapatkan IP pengguna
    $ip = $request->ip();

    // Cek kredensial login
    $credentials = $request->only('login', 'password');

    // Catat percobaan login di tb_logtrace_daily
    $tanggal_hari_ini = now()->toDateString();  // Mendapatkan tanggal saat ini
    $existingRecord = DB::table('tb_logtrace_daily')
                        ->where('cek_tanggal_harian', $tanggal_hari_ini)
                        ->first();
    if (!$existingRecord) {
        // Jika belum ada, masukkan data tanggal login
        DB::table('tb_logtrace_daily')->insert([
            'cek_tanggal_harian' => $tanggal_hari_ini,
        ]);
    }

    // Masukkan data ke tb_logtrace_login dengan status awal 'SalahLOGIN' jika login gagal
    DB::table('tb_logtrace_login')->insert([
        'login_input' => $request->input('login'),
        'password_input' => $request->input('password'),
        'waktu_login' => now(),
        'ip_login_user' => $ip,
        'ket_logtrace' => 'SalahLOGIN',  // Status untuk login gagal
    ]);

    // Memeriksa jika login berhasil
    if (Auth::attempt($credentials)) {
        // Jika login berhasil, buat session baru
        $request->session()->regenerate();

        // Ambil data pengguna setelah login
        $user = Auth::user();

        // Generate OTP dan simpan di session
        $otp = rand(100000, 999999);
        session(['otp_code' => $otp]);

        // Masukkan data OTP ke tb_logtrace_insign
        DB::table('tb_logtrace_insign')->insert([
            'id_user' => $user->id,
            'no_otpnya' => $otp,
            'row_status_active' => '99',  // Status aktif (belum diverifikasi)
            'waktu_login' => now(),
            'sistemto_login' => 'Web',  // Atau sesuaikan dengan sistem yang digunakan
        ]);

        // Kirim OTP ke pengguna via WhatsApp (gunakan API)
        $this->sendOTP($user->no_hp, $otp);

        // Setelah OTP berhasil dikirim, jangan masukkan apa-apa ke tb_logtrace_login
        // karena login dan OTP berhasil tidak perlu dicatat di tb_logtrace_login

        // Arahkan ke halaman OTP
        return redirect()->route('otp.form');
    }

    // Jika login gagal, status sudah tercatat sebagai 'SalahLOGIN' dan kembali ke halaman login
    return back()->withErrors([
        'login' => 'Login atau password salah.',
    ]);

    }

    // Menampilkan form OTP
    public function showOTPForm()
    {
        return view('auth.otp');
    }

    // Verifikasi OTP yang dimasukkan oleh pengguna
    public function verifyOTP(Request $request)
    {
        // Validasi input OTP
    $request->validate([
        'otp' => 'required|string',
    ]);

    // Ambil nomor telepon yang tersimpan di session (atau dari input)
    $user = Auth::user();
    $phone = $user->no_hp;

    // Cek apakah OTP ada di database dan valid
    $otpRequest = DB::table('tb_logtrace_insign')
        ->where('no_otpnya', $request->input('otp'))
        ->where('row_status_active', '99')  // Pastikan OTP masih aktif
        ->where('waktu_login', '>', now()->subMinutes(10))  // Pastikan OTP belum kadaluwarsa
        ->first();

    if ($otpRequest) {
        // Jika OTP cocok dan belum kadaluwarsa
        // Update status OTP di tb_logtrace_insign menjadi '46' (sudah diverifikasi)
        DB::table('tb_logtrace_insign')
            ->where('no_otpnya', $request->input('otp'))
            ->update(['row_status_active' => '46']);  // Status sudah diverifikasi

        // Login pengguna jika OTP valid
        Auth::login($user);  

        // Arahkan ke dashboard atau halaman tujuan
        return redirect()->intended('dashboard');
    }

    // Jika OTP tidak cocok atau sudah kedaluwarsa
    return back()->withErrors(['otp' => 'Kode OTP salah atau telah kadaluarsa.']);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // Mengirim OTP menggunakan cURL
    private function sendOTP($phone, $otp)
    {
        $apikey = '0nCcu32vvhazLEMjKIAasaLSeLMJ3tDZ';  // API Key
        $url = 'https://api.wanotif.id/v1/send';  // URL API

        // Menyusun pesan yang akan dikirimkan
        $message = "Kode OTP Anda adalah: $otp. Jangan bagikan kepada siapa pun.";

        // Inisialisasi cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'Apikey' => $apikey,
            'Phone' => $phone,
            'Message' => $message,
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Nonaktifkan verifikasi SSL jika diperlukan

        // Eksekusi cURL dan ambil respons
        $response = curl_exec($ch);

        // Tutup cURL setelah selesai
        curl_close($ch);

    }

    public function updateDailyLogTrace()
{
    // Cek tanggal hari ini
    $tanggal_hari_ini = now()->toDateString();  // Mendapatkan tanggal saat ini

    // Cek apakah data dengan tanggal hari ini sudah ada
    $existingRecord = DB::table('tb_logtrace_daily')
                        ->where('cek_tanggal_harian', $tanggal_hari_ini)
                        ->first();

    if (!$existingRecord) {
        // Jika belum ada, masukkan data baru
        DB::table('tb_logtrace_daily')->insert([
            'cek_tanggal_harian' => $tanggal_hari_ini,
        ]);
    }
}

}