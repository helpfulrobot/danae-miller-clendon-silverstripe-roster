I'm the roster!

<div class="container">

    <div class="row">
        <% loop $HeaderItems %>
            <div class="col-sm-2 $EvenOdd $FirstLast $HolidayClass">
                <% if $First %>
                    <span class="year">$Date.Format('Y')</span>
                <% else %>
                    <span class="day">$Date.Format('l')</span>
                    <span class="date">$Date.Format('j')</span>
                <% end_if %>
            </div>
        <% end_loop %>
    </div>

    <div class="row">
        <% loop $HeaderItems %>
            <% if $First %>
                <div class="col-sm-2 $EvenOdd $FirstLast $HolidayClass">
                    <span class="month">$Date.Format('M')</span>
                </div>
            <% else %>
                <div class="col-sm-1 $EvenOdd $FirstLast $HolidayClass">
                    <span class="period">AM</span>
                </div>
                <div class="col-sm-1 $EvenOdd $FirstLast $HolidayClass">
                    <span class="period">PM</span>
                </div>
            <% end_if %>
        <% end_loop %>
    </div>

    <% loop $Rows %>
        <div class="row $EvenOdd $FirstLast">
            <% loop $Items %>

                <% if $First %>
                    <div class="col-sm-2 $EvenOdd $FirstLast">
                        $Item
                    </div>
                <% else %>
                    <div class="col-sm-1 $EvenOdd $FirstLast">
                        $Item
                    </div>
                <% end_if %>

            <% end_loop %>
        </div>

    <% end_loop %>

</div>