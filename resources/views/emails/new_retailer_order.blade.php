
<h3 class="card">Emrad New Order Notification</h3>
<p><b>Hello Niyi</b>, </p>
<p> A retailer just place a new order. </p>
<p>Order Details</p>
<table>
    <th>Retailer Info</th><th>Product Info</th><th>Unit Price</th><th>Quantity</th><th>Total</th>
    <tr>
        <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
        <td>{{ $order->product_id }}</td>
        <td>{{ $order->unit_price }}</td>
        <td>{{ $order->quantity }}</td>
        <td>{{ $order->order_amount }}</td>
    </tr>
</table>

<p>Kind regards</p><br><br>
<p>Emrad Team</p>
