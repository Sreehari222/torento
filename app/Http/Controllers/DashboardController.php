<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Coupon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get coupon statistics
        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::where('is_active', true)->count();
        $expiredCoupons = Coupon::where('expiry_date', '<', now())->count();

        // Get booking statistics
        $totalBookings = Booking::count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();

        // Get recent bookings (last 5)
        $recentBookings = Booking::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.auth.dashboard', compact(
            'totalCoupons',
            'activeCoupons',
            'expiredCoupons',
            'totalBookings',
            'completedBookings',
            'pendingBookings',
            'cancelledBookings',
            'recentBookings'
        ));
    }
}
