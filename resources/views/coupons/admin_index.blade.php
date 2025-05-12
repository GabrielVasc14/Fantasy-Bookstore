@extends('books.layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Dashboard - Coupons</h2>

    <div class="table-responsive">
        <table class="table table_bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Usage</th>
                    <th>Limit</th>
                    <th>Total</th>
                    <th>Expires At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->code }}</td>
                        <td>€ {{ $coupon->discount }}</td>
                        <td>{{ $coupon->times_used }}</td>
                        <td>{{ $coupon->usage_limit }}</td>
                        <td>
                            € {{ number_format($coupon->users->sum(function ($u) {
                                return $u->pivot->discount ?? 0;
                            }), 2, ',', '.') }}
                        </td>
                        <td>
                            {{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Ilimitado'}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
