<?php

namespace App\Http\Controllers;

use App\Models\Bathroom;
use App\Models\Bedroom;
use App\Models\Booking;
use App\Models\CleaningType;
use App\Models\Coupon;
use App\Models\CustomOption;
use App\Models\Frequency;
use App\Models\SquareFootage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::all();
        return view('user.list', compact('bookings'));
    }

    // Service areas constants
    const SERVICE_AREAS = [
        'Downtown Toronto',
        'Midtown Toronto',
        'North York',
        'Scarborough',
        'Etobicoke',
        'Mississauga',
        'Brampton',
        'Vaughan',
        'Markham',
        'Richmond Hill',
        'Newmarket',
        'Aurora',
        'Pickering',
        'Whitby',
        'Oshawa',
        'Clarington',
        'Oakville',
        'Burlington'
    ];

    // Service times constants
    const SERVICE_TIMES = [
        '9:00 AM',
        '10:00 AM',
        '11:00 AM',
        '12:00 PM',
        '1:00 PM',
        '2:00 PM',
        '3:00 PM',
        '4:00 PM',
        '5:00 PM'
    ];

    public function showform(Booking $booking)
    {
        return view('user.form', [
            'frequencies'       => Frequency::all(),
            'cleaningTypes'     => CleaningType::all(),
            'squareFootages'    => SquareFootage::all(),
            'bedrooms'          => Bedroom::all(),
            'bathrooms'         => Bathroom::all(),
            'customOptions'     => CustomOption::all(),
            'booking'           => $booking,
            'serviceAreas'      => self::SERVICE_AREAS,
            'serviceTimes'      => self::SERVICE_TIMES,
        ]);
    }

    public function validateCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string',
            'subtotal' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid request'
            ], 422);
        }

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            })
            ->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or expired coupon code'
            ]);
        }

        // Remove the max_uses check completely
        // if ($coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses) {
        //     return response()->json([
        //         'valid' => false,
        //         'message' => 'This coupon has reached its maximum usage limit'
        //     ]);
        // }

        // Check minimum order amount
        if ($coupon->min_order_amount !== null && $request->subtotal < $coupon->min_order_amount) {
            return response()->json([
                'valid' => false,
                'message' => 'This coupon requires a minimum order amount of ' . $coupon->min_order_amount
            ]);
        }

        return response()->json([
            'valid' => true,
            'discount_amount' => $coupon->discount_value,
            'discount_type' => $coupon->discount_type,
            'max_discount_amount' => $coupon->max_discount_amount
        ]);
    }

    public function store(Request $request)
    {
        $errors = [];

        // Validate honeypot field
        if (!empty($request->honeypot)) {
            $errors['honeypot'] = ['Bot detected'];
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $errors
            ], 422);
        }



        // Main validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'suite' => 'nullable|string|max:50',
            'city' => 'required|string|max:100',
            'area' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10|regex:/^[A-Za-z]\d[A-Za-z]$/',
            'service_date' => 'required|date|after_or_equal:today',
            'service_time' => 'required|string',
            'frequency_id' => 'required|exists:frequencies,id',
            'cleaning_type_id' => 'required|exists:cleaning_types,id',
            'square_footage_id' => [
                Rule::requiredIf(function () use ($request) {
                    $cleaningType = CleaningType::find($request->cleaning_type_id);
                    return !$cleaningType || !$cleaningType->is_package;
                }),
                'exists:square_footages,id'
            ],
            'bedrooms_id' => [
                Rule::requiredIf(function () use ($request) {
                    $cleaningType = CleaningType::find($request->cleaning_type_id);
                    return !$cleaningType || !$cleaningType->is_package;
                }),
                'exists:bedrooms,id'
            ],
            'bathrooms_id' => [
                Rule::requiredIf(function () use ($request) {
                    $cleaningType = CleaningType::find($request->cleaning_type_id);
                    return !$cleaningType || !$cleaningType->is_package;
                }),
                'exists:bathrooms,id'
            ],
            'custom_options' => 'nullable|array',
            'custom_options.*' => 'exists:custom_options,id',
            'cleaning_instructions' => 'nullable|string',
            'access_info' => 'required|string',
            'parking' => 'nullable|string',
            'property-type' => 'required|in:house,apartment,condo,office',
            'coupon_code' => 'nullable|string',
            'payment_method' => 'required|in:credit_card,debit,cash',
            'terms_accepted' => 'required|accepted',
            'subtotal' => 'required|numeric|min:0',
            'original_subtotal' => 'required|numeric|min:0',
        ]);
        $discountAmount = 0;
        $couponDiscount = 0;
        $coupon = null;
        $originalSubtotal = $request->original_subtotal;
        $subtotal = $request->subtotal;

        // Validate and apply coupon if provided
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->whereNull('expiry_date')
                        ->orWhere('expiry_date', '>=', now());
                })
                ->first();

            if ($coupon) {
                // Calculate discount based on coupon type
                if ($coupon->discount_type === 'percentage') {
                    $couponDiscount = $coupon->discount_value;
                    $discountAmount = $originalSubtotal * ($couponDiscount / 100);

                    // Apply maximum discount if set
                    if ($coupon->max_discount_amount !== null) {
                        $discountAmount = min($discountAmount, $coupon->max_discount_amount);
                    }
                } elseif ($coupon->discount_type === 'fixed') {
                    $couponDiscount = $coupon->discount_value;
                    $discountAmount = min($couponDiscount, $originalSubtotal);
                }
            }
        }

        // Calculate final total
        $total = $originalSubtotal - $discountAmount;
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Initialize discount variables
        $discountAmount = 0;
        $couponDiscount = 0;
        $coupon = null;
        $originalSubtotal = $request->original_subtotal;
        $subtotal = $request->subtotal;

        // Validate and apply coupon if provided
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->whereNull('expiry_date')
                        ->orWhere('expiry_date', '>=', now());
                })
                ->first();

            if ($coupon) {
                // Calculate discount based on coupon type
                if ($coupon->discount_type === 'percentage') {
                    $couponDiscount = $coupon->discount_value;
                    $discountAmount = $originalSubtotal * ($couponDiscount / 100);

                    // Apply maximum discount if set
                    if ($coupon->max_discount_amount !== null) {
                        $discountAmount = min($discountAmount, $coupon->max_discount_amount);
                    }
                } elseif ($coupon->discount_type === 'fixed') {
                    $couponDiscount = $coupon->discount_value;
                    $discountAmount = min($couponDiscount, $originalSubtotal);
                }
            }
        }

        // Calculate final total
        $total = $originalSubtotal - $discountAmount;

        // Create booking
        $booking = Booking::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'suite' => $request->suite,
            'city' => $request->city,
            'area' => $request->area,
            'postal_code' => $request->postal_code,
            'service_date' => $request->service_date,
            'service_time' => $request->service_time,
            'frequency_id' => $request->frequency_id,
            'cleaning_type_id' => $request->cleaning_type_id,
            'square_footage_id' => $request->square_footage_id,
            'bedrooms_id' => $request->bedrooms_id,
            'bathrooms_id' => $request->bathrooms_id,
            'cleaning_instructions' => $request->cleaning_instructions,
            'access_info' => $request->access_info,
            'parking' => $request->parking,
            'property_type' => $request->input('property-type'),
            'coupon_code' => $request->coupon_code,
            'coupon_discount' => $couponDiscount,
            'payment_method' => $request->payment_method,
            'original_subtotal' => $originalSubtotal,
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'total' => $total,
            'terms_accepted' => true,
            'status' => 'pending',
        ]);

        // Attach custom options if any
        if ($request->custom_options) {
            $booking->customOptions()->attach($request->custom_options);
        }


        return response()->json([
            'message' => 'Booking created successfully',
            'booking_id' => $booking->id,
            'redirect' => route('booking.confirmation', $booking->id)
        ], 201);
    }

    public function confirmation($id)
    {
        $booking = Booking::findOrFail($id);
        return view('user.confirmation', compact('booking'));
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->json(['message' => 'Booking deleted successfully']);
    }

    // Handles web form submissions
    public function delete(Booking $booking)
    {
        $booking->delete();
        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Booking #' . $booking->id . ' deleted successfully');
    }

public function showdetails(Booking $booking)
    {

        return view('user.show', compact('booking'));
    }
}
