<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Coupon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return response()
     */
    public function index(): View
    {
        $coupons = Coupon::with(['users' => function ($q) {
            $q->withPivot('discount');
        }])->latest()->paginate(5);

        return view('coupons.index', compact('coupons'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'discount' => 'required|numeric',
            'expires_at' => 'required|date',
            'min_cart_value' => 'required|numeric',
        ], [
            'code.required' => 'Coupon code is mandatory',
            'discount.required' => 'Coupon discount is mandatory',
            'expires_at.required' => 'Coupon expire date is mandatory',
            'min_cart_value.required' => 'Min value coupon is mandatory',
        ], [
            'code' => 'Coupon code',
            'discount' => 'Coupon discount',
            'expires_at' => 'Coupon expire date',
            'min_cart_value' => 'Coupon min value',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $coupons = new Coupon;
        $coupons->code = $request->code;
        $coupons->discount = $request->discount;
        $coupons->expires_at = $request->expires_at;
        $coupons->min_cart_value = $request->min_cart_value;
        $coupons->usage_limit = 5;
        $coupons->save();

        if (!$coupons) {
            return response()->json(['error' => 'Error inserting data.']);
        } else {
            return response()->json(['success' => true, 'message' => 'Coupon added successfully']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $coupons = Coupon::findOrFail($id);

        return view('coupons.show', compact('coupons'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $coupons = Coupon::findOrFail($id);

        return view('coupons.edit', compact('coupons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'discount' => 'required|numeric',
            'expires_at' => 'required|date',
            'min_cart_value' => 'required|numeric',
        ], [
            'code.required' => 'Coupon code is mandatory',
            'discount.required' => 'Coupon discount is mandatory',
            'expires_at.required' => 'Coupon expire date is mandatory',
            'min_cart_value.required' => 'Min value coupon is mandatory',
        ], [
            'code' => 'Coupon code',
            'discount' => 'Coupon discount',
            'expires_at' => 'Coupon expire date',
            'min_cart_value' => 'Coupon min value',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $coupons = Coupon::findOrFail($id);
        $coupons->code = $request->code;
        $coupons->discount = $request->discount;
        $coupons->expires_at = $request->expires_at;
        $coupons->min_cart_value = $request->min_cart_value;
        $coupons->save();

        if (!$coupons) {
            return response()->json(['error' => 'Error inserting data.']);
        } else {
            return response()->json(['success' => true, 'message' => 'Coupon added successfully']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $coupons = Coupon::findOrFail($id);

        $coupons->delete();

        return redirect()->route('coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|exists:coupons,code', //Valida se o codigo existe na tabela
        ]);

        $coupons = Coupon::where('code', $request->coupon_code)->first();

        // Verifique se o cupom foi encontrado
        if (!$coupons) {
            return back()->withErrors(['coupon_code' => 'Coupon not found']);
        }

        //Verifica se o cupom é valido
        if (!$coupons->isValid()) {
            return back()->withErrors(['coupon_code' => 'This coupon expire or is not available anymore.']);
        }

        //Se o cupom for valido, aplica o desconto
        session(['coupon' => $coupons]);

        //Calcular o novo total do carrinho com desconto
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        //Verifica se o cupom tem um valor minimo e se o total cumpre
        if ($coupons->min_cart_value && $total < $coupons->min_cart_value) {
            return back()->withErrors(['coupon_code' => 'This coupon is only valid for purchases of at least €' . $coupons->min_cart_value]);
        }

        //Aumenta o uso
        $coupons->increment('times_used');

        //Calcula o desconto (pode ser um valor fixo ou uma porcentagem)
        $discountAmount = 0;
        if ($coupons->type == 'percentage') {
            //Se o cupom for uma porcentagem de desconto
            $discountAmount = max(0, $total * $coupons->discount) / 100;
        } else {
            $discountAmount = max(0, $coupons->discount);
        }

        $totalAfterDiscount = max(0, $total - $discountAmount);

        //Impedir que o valor do desconto seja maior que o valor do cart
        if ($discountAmount > $total) {
            $discountAmount = $total;
            $totalAfterDiscount = 0;
        }

        //Registra o uso de cupons pelo user logado
        if (Auth::check()) {
            $user = Auth::user();
            $user = User::find($user->id);

            $user->coupons()->syncWithoutDetaching([
                $coupons->id => ['discount' => $discountAmount]
            ]);

            //Armazena o total com desconto na sessao
            session(['cart_total' => $totalAfterDiscount]);

            return back()->with('success', 'Coupon used successfully');
        }
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove()
    {
        session()->forget(['coupon', 'cart_total']);
        return back()->with('success', 'Coupon removed successfully.');
    }

    public function index_coupon()
    {
        $coupons = Coupon::withCount('users')
            ->with(['users' => function ($q) {
                $q->withPivot('discount');
            }])
            ->get();

        return view('coupons.admin_index', compact('coupons'));
    }
}
