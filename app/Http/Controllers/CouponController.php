<?php

// app/Http/Controllers/CouponController.php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function index()
{
    // Get coupon statistics
    $totalCoupons = Coupon::count();
    $activeCoupons = Coupon::where('is_active', true)->count();
    $expiredCoupons = Coupon::where('expiry_date', '<', now())->count();

    // Get all coupons for the table
    $coupons = Coupon::latest()->paginate(10);

    return view('coupons.index', compact(
        'totalCoupons',
        'activeCoupons',
        'expiredCoupons',
        'coupons'
    ));
}
    public function list()
    {
        try {
            $coupons = Coupon::all();
            return response()->json($coupons);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

   public function store(Request $request)
{
   $validated = $request->validate([
    'code' => 'required|string|unique:coupons',
    'discount_type' => 'required|in:percentage,fixed',
    'discount_value' => [
        'required',
        'numeric',
        $request->discount_type === 'percentage'
            ? 'max:100'  // Percentage can't be over 100
            : 'min:0'    // Fixed amount just needs to be positive
    ],
    'expiry_date' => 'nullable|date|after:today',
    'is_active' => 'boolean'
]);

    $coupon = Coupon::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Coupon created successfully',
        'coupon' => $coupon
    ]);
}

public function destroy(Coupon $coupon)
{
    try {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error deleting coupon: ' . $e->getMessage());
    }
}
public function validateCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
            'subtotal' => 'required|numeric|min:0'
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>=', Carbon::today());
            })
            ->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or expired coupon code'
            ], 404);
        }

        // Calculate discount amount
        $discountAmount = 0;
        $discountText = '';

        if ($coupon->discount_type === 'percentage') {
            $discountAmount = $request->subtotal * ($coupon->discount_value / 100);
            $discountText = $coupon->discount_value.'%';
        } else {
            $discountAmount = min($coupon->discount_value, $request->subtotal);
            $discountText = '$'.number_format($coupon->discount_value, 2);
        }

        return response()->json([
            'valid' => true,
            'discount_value' => $coupon->discount_value,
            'discount_type' => $coupon->discount_type,
            'discount_amount' => $discountAmount,
            'discount_text' => $discountText,
            'message' => 'Coupon applied successfully!'
        ]);
    }
}
