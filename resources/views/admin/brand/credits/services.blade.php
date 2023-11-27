<form method="post" enctype="multipart/form-data" id="services_form">
    {{csrf_field()}}
    <h5 class="header">{{__('marketing.ApplicableServices')}}</h5>
    <div id="ApplicableServices">
        <ul>
            @foreach($services as $service)
                {!! \App\Librerias\Servicies\LibServices::printServicesInCredits($service, $credits_services )!!}
            @endforeach
        </ul>
    </div>
</form>


<script>
    $('.service_checkbox').on('change', function () {
        if ($(this).prop('checked')) {
            $("input[name='services[" + $(this).data('id') + "][credits]']").prop('disabled', false);
        }
        else {
            $("input[name='services[" + $(this).data('id') + "][credits]']").prop('disabled', true);
        }
    })
</script>
