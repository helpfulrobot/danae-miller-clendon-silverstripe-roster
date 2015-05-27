<tr class="ss-gridfield-title-header weekdays">
    <th class="main">$StartDate.Format('M Y')</th>

    <% loop $Days %>
        <th colspan="2" class="main">
            $Day
            <% if $IsHoliday %>
                 (holiday)
            <% end_if %>
        </th>
    <% end_loop %>
</tr>

<tr class="ss-gridfield-title-header">
    <% loop $Me %>
        <% if $First %>
            <th class="main col-$Name">$Title</th>
        <% else %>
            <% if $Even %>
                <th class="main col-$Name">AM</th>
            <% else %>
                <th class="main col-$Name">PM</th>
            <% end_if %>
        <% end_if %>
    <% end_loop %>
</tr>
