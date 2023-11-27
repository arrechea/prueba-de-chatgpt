<form method="post" enctype="multipart/form-data" id="services_form">
    {{csrf_field()}}
    <h5 class="header">{{ __('marketing.ApplicableServices')}}</h5>
    <div id="ApplicableServices">
        <ul>
            <?php
                $currentBrand = null;
            ?>
            @foreach($services as $service)
                @if ($currentBrand != $service->brands_id)
                    <?php
                        $currentBrand = $service->brands_id;
                    ?>
                    <h5> {{ $service->company->name }}</h5>
                    <h5 class="margen-gafa">
                        {{ $service->brand->name }}
                    </h5>
                @endif

                {!! \App\Librerias\Servicies\LibServices::printServicesInCreditsCompany($service, $credits_services )!!}
            @endforeach
        </ul>
    </div>
</form>


<script>
    $('.service_checkbox').on('change', function () {
        if ($(this).prop('checked')) {
            $("input[name='services[" + $(this).data('id') + "][credits]']").prop('disabled', false);
        } else {
            $("input[name='services[" + $(this).data('id') + "][credits]']").prop('disabled', true);
        }
    })
</script>
