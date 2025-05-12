<?php

namespace App\Http\Controllers;

use App\Models\Books;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function checkoutForm(): View
    {
        return view('orders.checkout');
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string',
            'city' => 'required|string',
            'cep' => 'required|string',
        ], [
            'address.required' => 'User Address is mandatory.',
            'city.required' => 'User City is mandatory.',
            'cep.required' => 'User CEP is mandatory.',
        ], [
            'address' => 'User address',
            'city' => 'User city',
            'cep' => 'User CEP',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator->errors())
                ->withInput();
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->withErrors(['message' => 'Your cart is empty']);
        }

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->withErrors(['message' => 'You must be logged in for this.']);
        }

        // $order = Order::create([
        //     'user_id' => Auth::user()->id,
        //     'address' => $request->address,
        //     'city' => $request->city,
        //     'cep' => $request->cep,
        //     'total' => session('cart_total') ?? 0,
        //     'coupon_id' => session('coupon')?->id,
        // ]);

        $order = new Order;
        $order->user_id = Auth::user()->id;
        $order->address = $request->address;
        $order->city = $request->city;
        $order->cep = $request->cep;
        $order->total = (float) session('cart_total') ?? 0;
        $order->coupon_id = session('coupon')?->id ?? null;
        $order->save();

        $user->increment('points', 20);

        foreach ($cart as $item) {
            $order->items()->create([
                'book_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        session()->forget(['cart', 'cart_total', 'coupon']);

        foreach ($order->items as $item) {
            $book = Books::findOrFail($item->book_id);
            if ($book->stock >= $item->quantity) {
                $book->stock -= $item->quantity;
                $book->save();
            } else {
                return back()->withErrors(['message' => 'Not enough stock for book: ' . $book->name]);
            }
        }

        return redirect()->route('orders.index')
            ->with('success', 'Your request was successfully made.');
    }

    public function index(): View
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.book', 'coupon')
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function pay(Order $order)
    {
        $order->is_paid = true;
        $order->save();

        return redirect()->back()->with('success', 'Payment was successfully made it.');
    }
}
