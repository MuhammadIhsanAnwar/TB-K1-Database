<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pesanan</title>
</head>
<body>

<h2>Laporan Pesanan</h2>

@foreach($orders as $order)
<p>
    #{{ $order->id }}
    -
    {{ $order->status }}
</p>
@endforeach

</body>
</html>