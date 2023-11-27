<input hidden name="staff" id="staff" value="{{$staff->id}}">
<input hidden name="staff" id="staff" value="{{$location->id}}">
<h5 class="">{{__('calendar.reservCalendar')}}</h5>
<div class="calendar" id="calendarRoom"></div>

<script type="text/javascript">
    window.Routes = {
        modal: "{{route('admin.company.brand.locations.reservations.seeMeeting',['company'=>$company,'brand'=>$brand,'location'=>$location,'meeting'=>'_|_'])}}",
    };

    $('.tooltipped').tooltip({delay: 70});

    $(document).ready(function () {
        var calendar = new CalendarSystem();
        //Eventos
        calendar.setEventUrl("{{route('admin.company.brand.locations.reservations.staff.meetings',['company'=>$company,'brand'=>$brand, 'location'=> $location, 'staff'=>$staff]) ?? ''}}");
        calendar.setLocale("{{session('language','es')}}");
        calendar.setDbClick(function (event, element) {
            let touchtime = 0;
            element.bind('click', function () {
                //DoubleCLick function
                if (touchtime == 0) {
                    touchtime = new Date().getTime();
                } else {
                    if (((new Date().getTime()) - touchtime) < 800) {
                        let modal_url = typeof window.Routes.modal !== 'undefined' ? window.Routes.modal : '';
                        let mod = $('#seeReservations');

                        let newHref = modal_url.replace('_|_', event.id);
                        mod.attr('data-href', newHref);
                        mod.data('href', newHref);

                        mod.modal('open');
                        touchtime = 0;
                    } else {
                        touchtime = new Date().getTime();
                    }
                }
                //DoubleCLick function
                //https://stackoverflow.com/users/643482/jrulle - Credit JRulle
            });
            let info = "{{__('calendar.timeslot').':'}} " + event.start.format('hh:mm') + ' - ' +
                event.end.format('hh:mm') + ",<br/> {{__('calendar.Staff').':'}} " + event.title +
                ",<br/> {{__('calendar.classType').':'}} " + event.type + ",<br/> {{__('calendar.availability')}}" +
                ': ' + event.available;
            element.find('.fc-title').append('<br/>' + event.type + '<br/>' + "{{__('calendar.availability')}}" + ': ' +
                event.available + '<br/>' + (event.not_attendance_list ? "*{{__('reservations.MissingList')}}" : ''));
            element.attr('data-tooltip', info);
            element.tooltip({delay: 50});
            let tooltip_id = element.data('tooltip-id');
            $('#' + tooltip_id).find('span').html('').html(info);
            if (event.passed) {
                element.addClass('past-meeting')
            }
        });
        calendar.setStart("{{$location->start_time}}");
        calendar.setEnd("{{$location->end_time}}");
        calendar.setDataForSource({
            room: $('#rooms_id').val(),
        });
        //Render
        calendar.render();
    });
</script>
