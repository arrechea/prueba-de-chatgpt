<html>
<head>
    <title>
    </title>
    <link rel="stylesheet" href="{{mixGafaFit('/css/admin/printCalendar.css')}}">

</head>
<body>

<div class="row" style="border: 1px solid rgb(117,117,117)">
    <h5 class="header">{{__('reservations.ListAsist')}}</h5>
    <h5 class="ClassNo">{{__('reservations.ClassNumber')}}{{$meeting->id}}</h5>
    <table class="col s8" style="width: 100%">
        <thead>
        <tr class="row">
            <th class="dataReservation col s12 m6">{{__('reservations.Class')}}:</th>
            <th
                class="dataReservation  col s12 m6">{{$services->name}}</th>
        </tr>
        <tr class="row">
            <th class="dataReservation col s12 m12 l6">{{__('reservations.Instructor')}}:</th>
            <th
                class="dataReservation col s12 m12 l6">{{$staff->name}}</th>

        </tr>
        <tr class="row">
            <th class="dataReservation  col s12 m12 l6">{{__('reservations.schedule')}} :</th>
            <th
                class="dataReservation col s12 m12 l6">{{$start_date}}</th>

        </tr>

        </thead>
    </table>

    <div class="col s12 m4 imprimirLista">
        <a href="" target="_blank" style="display: none; float: right; color: rgb(117,117,117)"><i
                class="material-icons small">print</i></a>
    </div>

    <div class="row">
        <div class="col s12 m12">
            <?php
            $catalog = \App\Librerias\SpecialText\LibSpecialTextCatalogs::getModelCatalog(new \App\Models\User\UserProfile());
            $fields = \App\Librerias\SpecialText\LibSpecialTextCatalogs::getFieldsOnly($company, $catalog, $brand, 'reservations_list');
            ?>
            <table class="dataTable centered striped" width="100%">
                <thead>
                <tr>
                    <th>{{__('reservations.map_position')}}</th>
                    <th class="fondo">{{__('reservations.Name')}}</th>
                    <th class="fondo">{{__('reservations.Email')}}</th>
                    <th class="fondo">{{__('reservations.gender')}}</th>
                    <th class="fondo">{{__('reservations.usedCredit')}}</th>
                    @foreach($fields as $field)
                        <th class="fondo">{{$field->name}}</th>
                    @endforeach
                    <th class="fondo">{{__('reservations.Asist')}}</th>
                    <th class="imprimirLista">{{__('reservations.Actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reservations as $reservation )
                    <?php
                    $fields_ids = $fields->pluck('id')->values()->toArray();
                    $values = $reservation->user->fields_values()
                        ->selectRaw('group_concat(value) val,catalogs_fields_id')
                        ->groupBy('catalogs_fields_id')
                        ->whereIn('catalogs_fields_id', $fields_ids)->get();
                    $position = \App\Librerias\Map\LibMapFunctions::getPositionText($reservation);
                    $credit = $reservation->credit;
                    ?>
                    <tr>
                        <td>{{$position or '--'}}</td>
                        <td>{{$reservation->user->first_name}}  {{$reservation->user->last_name}}</td>
                        <td>{{$reservation->user->email}}</td>
                        <td>{{$reservation->user->gender ? __("gender.{$reservation->user->gender}") : ''}}</td>
                        <td>{{$credit->name ?? '--'}}</td>
                        @foreach($fields as $field)
                            <?php $val = $values->where('catalogs_fields_id', $field->id)->first() ?>
                            <td>{{$val->val ?? '--'}}</td>
                        @endforeach
                        <td colspan="1">{{$reservation->attendance ?  __("reservations.$reservation->attendance") : ''}}</td>
                        <td class="imprimirLista"><a href="{{--todo eliminacion de reservas--}}"
                                                     class="btn btn-floating"><i
                                    class="material-icons">delete</i></a></td>
                    </tr>
                @endforeach
                </tbody>


            </table>

        </div>
    </div>

</div>
<script>
    print();
</script>

</body>
</html>

