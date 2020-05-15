<table class="data-table algolia_master_table table marginNone {{index}} {{sorting}} current_column_{{sorting_title}}" id="my-orders-table">
    <colgroup>
        <col width="1">
        <col width="1">
        <col>
        <col width="1">
        <col width="1">
        <col width="1">
    </colgroup>
    <thead>
        <tr class="first last">
            <th>Order #</th>
            <th>Date</th>
            <th>Ship To</th>
            <th><span class="nobr">Order Total</span></th>
            <th><span class="nobr">Order Status</span></th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        {{#hits}}
            <tr class="first odd">
                <td>{{number}}</td>
                <td><span class="nobr">{{created_utc_converted}}</span></td>
                <td>{{#shipping_address}}{{name}}{{/shipping_address}}</td>
                <td><span class="price">{{total_converted}}</span></td>
                <td><em>{{status}}</em></td>
                <td class="a-center last">
                    <span class="nobr"><a href="/shop/order/{{number}}">View Order</a></span>
                </td>
            </tr>
        {{/hits}}
    </tbody>
</table>