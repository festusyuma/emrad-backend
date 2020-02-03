
<h3>Emrad New Order Notification</h3>
<p><b>Hello Niyi</b>, </p>
<p> A retailer just place a new order. </p>
<p>Order Details</p>
<hr>
<p>{{ ucfirst($user->first_name) . ' ' . ucfirst($user->last_name) }}</p>
@foreach ($retailerOrders as $retailerOrder)
    <table>
        <th>Product Id</th><th>Unit Price (NGN)</th><th>Quantity</th><th>Amount (NGN)</th>
        <tr>
            <td style="text-align:center;">{{ $retailerOrder->product_id }}</td>
            <td style="text-align:center;">{{ $retailerOrder->unit_price }}</td>
            <td style="text-align:center;">{{ $retailerOrder->quantity }}</td>
            <td style="text-align:center;">{{ $retailerOrder->order_amount }}</td>
        </tr>
    </table>
    <hr>
@endforeach


<p>Kind regards</p>
<p>Emrad Team</p>
