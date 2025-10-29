<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CouponController extends Controller
{
    /**
     * Display a listing of coupons
     */
    public function index()
    {
        $coupons = Coupon::with(['specificCourse', 'specificDiploma'])->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new coupon
     */
    public function create()
    {
        $courses = \App\Models\Course::where('end_date', '>', now())
            ->whereNull('diploma_id')
            ->orderBy('name')->get();
        $diplomas = \App\Models\Diploma::orderBy('name')->get();
        return view('admin.coupons.create', compact('courses', 'diplomas'));
    }

    /**
     * Store a newly created coupon
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'applicable_to' => 'required|in:courses,diplomas,both',
            'specific_course_id' => 'nullable|exists:courses,id',
            'specific_diploma_id' => 'nullable|exists:diploma,id',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'description' => 'nullable|string|max:500',
            'quantity' => 'required|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $quantity = $request->quantity;
        $createdCoupons = [];

        for ($i = 0; $i < $quantity; $i++) {
            $coupon = Coupon::create([
                'code' => Coupon::generateCode(),
                'type' => $request->type,
                'value' => $request->value,
                'applicable_to' => $request->applicable_to,
                'specific_course_id' => $request->specific_course_id,
                'specific_diploma_id' => $request->specific_diploma_id,
                'usage_limit' => $request->usage_limit,
                'valid_from' => $request->valid_from,
                'valid_until' => $request->valid_until,
                'description' => $request->description,
                'is_active' => true
            ]);
            $createdCoupons[] = $coupon;
        }

        // Store coupon IDs in session and redirect to print page
        session(['coupon_ids' => collect($createdCoupons)->pluck('id')->toArray()]);
        
        return redirect()->route('admin.coupons.print_multiple')
            ->with('success', "تم إنشاء {$quantity} كوبون بنجاح");
    }

    /**
     * Display the specified coupon
     */
    public function show(Coupon $coupon)
    {
        return view('admin.coupons.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified coupon
     */
    public function edit(Coupon $coupon)
    {
        // Get active courses (excluding diploma courses)
        $courses = \App\Models\Course::where('end_date', '>', now())
            ->whereNull('diploma_id')
            ->orderBy('name')->get();
        
        // If coupon has a specific course that's expired, include it
        if ($coupon->specific_course_id) {
            $specificCourse = \App\Models\Course::find($coupon->specific_course_id);
            if ($specificCourse && !$courses->contains('id', $coupon->specific_course_id)) {
                $courses->prepend($specificCourse);
            }
        }
        
        $diplomas = \App\Models\Diploma::orderBy('name')->get();
        
        return view('admin.coupons.edit', compact('coupon', 'courses', 'diplomas'));
    }

    /**
     * Update the specified coupon
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'applicable_to' => 'required|in:courses,diplomas,both',
            'specific_course_id' => 'nullable|exists:courses,id',
            'specific_diploma_id' => 'nullable|exists:diploma,id',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $coupon->update($request->all());

        return redirect()->route('admin.coupons.index')
            ->with('success', 'تم تحديث الكوبون بنجاح');
    }

    /**
     * Remove the specified coupon
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')
            ->with('success', 'تم حذف الكوبون بنجاح');
    }

    /**
     * Print coupons
     */
    public function print(Coupon $coupon)
    {
        return view('admin.coupons.print', compact('coupon'));
    }

    /**
     * Toggle coupon status
     */
    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);
        
        $status = $coupon->is_active ? 'تفعيل' : 'إلغاء تفعيل';
        return back()->with('success', "تم {$status} الكوبون بنجاح");
    }

    /**
     * Print multiple coupons
     */
    public function printMultiple(Request $request)
    {
        // Get coupon IDs from request or session
        $couponIds = $request->input('coupon_ids', session('coupon_ids', []));
        
        if (empty($couponIds)) {
            return redirect()->route('admin.coupons.index')
                ->with('error', 'لم يتم تحديد أي كوبونات للطباعة');
        }

        $coupons = Coupon::whereIn('id', $couponIds)->get();
        
        if ($coupons->isEmpty()) {
            return redirect()->route('admin.coupons.index')
                ->with('error', 'لم يتم العثور على الكوبونات المحددة');
        }

        // Clear the session data if it exists
        if (session()->has('coupon_ids')) {
            session()->forget('coupon_ids');
        }

        return view('admin.coupons.print_multiple', compact('coupons'));
    }
}
