
   @if(Auth::user()->isA('gafa-saas'))
      <div class="BuqSaas-l-calendar">
         <div class="BuqSaas-l-calendar__header">
            <div class="BuqSaas-c-sectionTitle">
               <h2 style="padding-top: 1rem;"><strong>{{__('calendar.reservCalendar')}}</strong></h2>
            </div>
         </div>
         <div class="BuqSaas-l-calendar__body">
            <input hidden name="room" id="room" value="{{$room ?? 0}}">
            <div class="calendar" id="calendarRoom"></div>
         </div>
      </div>
   @else
      <div class="radius--forms">
         <h5 class="">{{__('calendar.reservCalendar')}}</h5>
         <div class="panelcombos col panelcombos_full">
            <input hidden name="room" id="room" value="{{$room ?? 0}}">
            <div class="calendar" id="calendarRoom"></div>
         </div>
      </div>
   @endif


<script type="text/javascript">
    window.Routes = {
        modal: "{{route('admin.company.brand.locations.reservations.seeMeeting',['company'=>$company,'brand'=>$brand,'location'=>$location,'meeting'=>'_|_'])}}",
    };

    $('.tooltipped').tooltip({delay: 70});


    $(document).ready(function () {
        var calendar = new CalendarSystem();
        //Eventos
        calendar.setEventUrl("{{route('admin.company.brand.locations.reservations.room.meetings',['company'=>$company,'brand'=>$brand,'location'=>$location,'room'=>$room->id])}}");
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
