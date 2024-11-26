<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        // Ambil notifikasi terbaru dari database untuk user yang sedang login
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->take(5) // Batas hanya 5 notifikasi terbaru
            ->get();

        // Kirim data sebagai JSON untuk digunakan di frontend
        return response()->json($notifications);
    }
}
