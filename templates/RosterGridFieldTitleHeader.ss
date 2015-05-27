<tr class="ss-gridfield-title-header weekdays">
    <th class="main"></th>
    <th colspan="2" class="main">Monday</th>
    <th colspan="2" class="main">Tuesday</th>
    <th colspan="2" class="main">Wednesday</th>
    <th colspan="2" class="main">Thursday</th>
    <th colspan="2" class="main">Friday</th>
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
