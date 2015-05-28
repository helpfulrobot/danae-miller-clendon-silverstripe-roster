<table>
    <tbody>

    <tr>
        <% loop $HeaderItems %>
            <% if $First %>
                <th>$Date.Format('F Y')</th>
            <% else %>
                <th colspan="2" class="$EvenOdd $FirstLast $HolidayClass">$Date.Format('D jS')</th>
            <% end_if %>
        <% end_loop %>
    </tr>

    <tr>
        <% loop $HeaderItems %>
            <% if $First %>
                <td>Time slot</td>
            <% else %>
                <td>AM</td>
                <td>PM</td>
            <% end_if %>
        <% end_loop %>
    </tr>

        <% loop $Rows %>
        <tr class="$EvenOdd $FirstLast">
            <% loop $Items %>

                <td>$Item</td>
            <% end_loop %>
        </tr>
        <% end_loop %>

    </tbody>

</table>