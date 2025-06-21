<?php
// app/Http/Controllers/RateController.php
namespace App\Http\Controllers;

use App\Models\Bathroom;
use App\Models\Bedroom;
use App\Models\CleaningType;
use App\Models\CustomOption;
use App\Models\Frequency;
use App\Models\Rate;
use App\Models\SquareFootage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class RateController extends Controller
{
    public function index()
    {

        $rates = Rate::all();
        return view('rates.create', [
            'frequencies'       => Frequency::all(),
            'cleaningTypes'     => CleaningType::all(),
            'squareFootages'    => SquareFootage::all(),
            'bedrooms'          => Bedroom::all(),
            'bathrooms'         => Bathroom::all(),
            'customOptions'     => CustomOption::all(),
        ]);
    }

    public function create()
    {
        return view('rates.create');
    }


    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'category' => 'required|in:frequencies,cleaning_types,square_footages,bedrooms,bathrooms,customOptions',
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
            'custom_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Handle image upload if this is a custom option and image was provided
            $imagePath = null;
            if ($validated['category'] === 'customOptions' && $request->hasFile('custom_image')) {
                $imagePath = $request->file('custom_image')->store('custom_options', 'public');
            }

            // Map category to model and rate column name
            $modelConfig = match ($validated['category']) {
                'frequencies' => [
                    'model' => Frequency::class,
                    'rate_column' => 'discount_rate',
                    'additional_data' => []
                ],
                'cleaning_types' => [
                    'model' => CleaningType::class,
                    'rate_column' => 'rate',
                    'additional_data' => []
                ],
                'square_footages' => [
                    'model' => SquareFootage::class,
                    'rate_column' => 'rate',
                    'additional_data' => []
                ],
                'bedrooms' => [
                    'model' => Bedroom::class,
                    'rate_column' => 'rate',
                    'additional_data' => []
                ],
                'bathrooms' => [
                    'model' => Bathroom::class,
                    'rate_column' => 'rate',
                    'additional_data' => []
                ],
                'customOptions' => [
                    'model' => CustomOption::class,
                    'rate_column' => 'rate',
                    'additional_data' => [
                        'image_path' => $imagePath
                    ]
                ],
                default => throw new \Exception("Invalid category"),
            };

            // Prepare base data
            $data = [
                'name' => $validated['name'],
                $modelConfig['rate_column'] => $validated['rate'],
            ];

            // Merge additional data if exists
            if (!empty($modelConfig['additional_data'])) {
                $data = array_merge($data, $modelConfig['additional_data']);
            }

            // Create new record
            $record = $modelConfig['model']::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rate created successfully!',
                'data' => $record
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded image if transaction failed
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to create rate: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit()
    {

        return view('rates.update', [
            'frequencies'       => Frequency::all(),
            'cleaningTypes'     => CleaningType::all(),
            'squareFootages'    => SquareFootage::all(),
            'bedrooms'          => Bedroom::all(),
            'bathrooms'         => Bathroom::all(),
            'customOptions'     => CustomOption::all(),
        ]);
    }

    public function updateFrequency(Request $request, $id)
    {
        $validated = $request->validate([
            'discount_rate' => 'required|numeric|min:0|max:100',
        ]);

        try {
            $frequency = Frequency::findOrFail($id);
            $frequency->update(['discount_rate' => $validated['discount_rate']]);

            return response()->json([
                'success' => true,
                'message' => 'Frequency updated successfully!',
                'data' => $frequency
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update frequency: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateCleaningType(Request $request, $id)
    {
        $validated = $request->validate([
            'rate' => 'required|numeric|min:0|max:100',
        ]);

        try {
            $frequency = CleaningType::findOrFail($id);
            $frequency->update(['rate' => $validated['rate']]);

            return response()->json([
                'success' => true,
                'message' => 'CleaningType updated successfully!',
                'data' => $frequency
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update CleaningType: ' . $e->getMessage()
            ], 500);
        }
    }

    // FrequencyController.php
    public function destroyFrequency(Frequency $frequency)
    {
        try {

            if ($frequency->bookings()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete frequency because it is being used by existing bookings.'
                ], 422);
            }

            $frequency->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyCleaningType(CleaningType $cleaningType)
    {
        try {

            if ($cleaningType->bookings()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete frequency because it is being used by existing bookings.'
                ], 422);
            }

            $cleaningType->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateSquareFootage(Request $request, $id)
    {
        $validated = $request->validate([
            'rate' => 'required|numeric|min:0|max:100',
        ]);

        try {
            $SquareFootage = SquareFootage::findOrFail($id);
            $SquareFootage->update(['rate' => $validated['rate']]);

            return response()->json([
                'success' => true,
                'message' => 'SquareFootage updated successfully!',
                'data' => $SquareFootage
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update CleaningType: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroySquarefootages(SquareFootage $squareFootage)
    {
        try {

            if ($squareFootage->bookings()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete SquareFootage because it is being used by existing bookings.'
                ], 422);
            }

            $squareFootage->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateBedroom(Request $request, $id)
    {
        $validated = $request->validate([
            'rate' => 'required|numeric|min:0|max:100',
        ]);

        try {
            $Bedrooms = Bedroom::findOrFail($id);
            $Bedrooms->update(['rate' => $validated['rate']]);

            return response()->json([
                'success' => true,
                'message' => 'SquareFootage updated successfully!',
                'data' => $Bedrooms
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update CleaningType: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroybedroom(Bedroom $Bedroom)
    {
        try {

            if ($Bedroom->bookings()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete Bedroom because it is being used by existing bookings.'
                ], 422);
            }

            $Bedroom->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateBathroom(Request $request, $id)
    {
        $validated = $request->validate([
            'rate' => 'required|numeric|min:0|max:100',
        ]);

        try {
            $Bathroom = Bathroom::findOrFail($id);
            $Bathroom->update(['rate' => $validated['rate']]);

            return response()->json([
                'success' => true,
                'message' => 'SquareFootage updated successfully!',
                'data' => $Bathroom
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update CleaningType: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroybathrooms(Bathroom $Bathroom)
    {
        try {

            if ($Bathroom->bookings()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete Bedroom because it is being used by existing bookings.'
                ], 422);
            }

            $Bathroom->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateCustomOption(Request $request, $id)
{
    $request->validate([
        'rate' => 'required|numeric|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    $customOption = CustomOption::findOrFail($id);
    $customOption->rate = $request->input('rate');

    if ($request->hasFile('image')) {
        if ($customOption->image_path) {
            Storage::delete($customOption->image_path);
        }
        $path = $request->file('image')->store('public/custom-options');
        $customOption->image_path = str_replace('public/', '', $path);
    }

    $customOption->save();

    return response()->json([
        'success' => true,
        'message' => 'Addon updated successfully',
        'image_url' => $customOption->image_path ? asset('storage/' . $customOption->image_path) : null
    ]);
}

public function destroycustomoptions($id)
{
    try {
        $option = CustomOption::findOrFail($id);

        if ($option->image_path && Storage::exists($option->image_path)) {
            Storage::delete($option->image_path);
        }

        $option->delete();

        return response()->json([
            'success' => true,
            'message' => 'Custom option deleted successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}
