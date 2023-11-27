<link rel="stylesheet" href="{{asset('sdk/assets/widget-buq.css')}}">
<link rel="stylesheet" href="{{asset('sdk/assets/widget-map_meeting.css')}}">
<link rel="stylesheet" href="{{asset('sdk/assets/fonts/awesomefont-pro/css/all.min.css')}}">
<script>
    window.WidgetBUQUid = '{{$uid}}';
</script>
<div id="WidgetBUQ--{{$uid}}">
    <div class="WidgetBUQ--uid" style="display: none">{{$uid}}</div>
    <div class="WidgetBUQ--locations" style="display: none">{{$locations}}</div>
    <div class="WidgetBUQ--color" style="display: none">{{$color}}</div>
    <div class="WidgetBUQ--images" style="display: none">{{$images}}</div>
    <div class="WidgetBUQ--meetings_id" style="display: none">{{$meetings_id}}</div>
    <div class="WidgetBUQ--current_location" style="display: none">{{$current_location}}</div>
    <div class="WidgetBUQ--current_brand" style="display: none">{{$current_brand}}</div>
    <div class="WidgetBUQ--lang"
         style="display: none">{{$langFile}}</div>
</div>
@include('reservation.scripts-reservations')
<script>
    loadScript("{{mixGafaFit('js/widget/build.js')}}");
</script>
