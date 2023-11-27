<div class="model--border-radius">
    <input hidden name="profile" value="{{$profile->id}}">
    <div>
        <ul class="tabs tabsWithLinks">
            <li class="tab"><a href="#credits">{{__('credits.Credits')}}</a></li>
            <li class="tab"><a href="#memberships">{{__('credits.Memberships')}}</a></li>
        </ul>
    </div>


    <div id="credits">
        <div class="">
            <ul class="tabs tabsWithLinks">
                {{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::ROOMS_CREATE, $location))--}}
                <li class="tab col s3"><a
                        href="#User--creditsA">{{__('company.ActiveCredits')}}</a>
                </li>
                {{--@endif--}}
                <li class="tab col s3"><a
                        href="#User--creditsP">{{__('company.PastCredits')}}</a>
                </li>
                <li class="tab col s3"><a
                        href="#User--creditsU">{{__('credits.UsedCredits')}}</a>
                </li>
                <li class="tab col s3"><a
                            href="#User--creditsD">{{__('company.DeleteCredits')}}</a>
                </li>

            </ul>
        </div>
        <br>


        <div id="User--creditsA" class="tab-panel">
            <h5 class="">{{__('company.ActiveCredits').': '.$profile->first_name.' '.$profile->last_name}}</h5>
            <div class="panelcombos col panelcombos_full">
                @include('admin.catalog.table',[
                'ajaxDatatable' => $ajaxDatatable,
                'catalogo' => $catalogo,
                //'micro' => $micro,
                ])
                @include('admin.catalog.datatable-script')
                @include('admin.location.users.Creditos.edit')
                @include('admin.location.users.Creditos.delete')
            </div>
        </div>

        <div id="User--creditsP" class="tab-panel">
            <h5 class="">{{__('company.PastCredits').': '.$profile->first_name.' '.$profile->last_name}}</h5>
            <div class="panelcombos col panelcombos_full">
                <?php $micro = \App\Librerias\Catalog\LibDatatable::GetTableId()?>
                @include('admin.catalog.table',[
                'ajaxDatatable' => $ajaxDatatable1,
                'catalogo' => $catalogo1,
                //'micro' => $micro2,
                ])
                @include('admin.catalog.datatable-script')
            </div>
        </div>

        <div id="User--creditsU" class="tab-panel">
            <h5 class="">{{__('credits.UsedCredits').': '.$profile->first_name.' '.$profile->last_name}}</h5>
            <div class="panelcombos col panelcombos_full">
                <?php $micro = \App\Librerias\Catalog\LibDatatable::GetTableId()?>
                @include('admin.catalog.table',[
                'ajaxDatatable' => $ajaxDatatable2,
                'catalogo' => $catalogo2,
                //'micro' => $micro2,
                ])
                @include('admin.catalog.datatable-script')
            </div>
        </div>
        <div id="User--creditsD" class="tab-panel">
            <h5 class="">{{__('company.DeleteCredits').': '.$profile->first_name.' '.$profile->last_name}}</h5>
            <div class="panelcombos col panelcombos_full">
                <?php $micro = \App\Librerias\Catalog\LibDatatable::GetTableId()?>
                @include('admin.catalog.table',[
                'ajaxDatatable' => $ajaxDatatable3,
                'catalogo' => $catalogo3,
                //'micro' => $micro2,
                ])
                @include('admin.catalog.datatable-script')
            </div>
        </div>
    </div>


    <div id="memberships">
        <div class="">
            <ul class="tabs tabsWithLinks">
                <li class="tab col s3"><a
                        href="#User--membershipsA">{{__('company.ActiveMemberships')}}</a>
                </li>
                <li class="tab col s3"><a
                        href="#User--membershipsP">{{__('company.ExpiredMemberships')}}</a>
                </li>
            </ul>
        </div>
        <br>

        <div id="User--membershipsA" class="tab-panel">
            <h5 class="">{{__('company.ActiveMemberships').': '.$profile->first_name.' '.$profile->last_name}}</h5>
            <div class="panelcombos col panelcombos_full">
                <?php $micro = \App\Librerias\Catalog\LibDatatable::GetTableId()?>
                @include('admin.catalog.table',[
                'ajaxDatatable' => $ajaxDatatableActiveMemberships,
                'catalogo' => $catalogActiveMemberships,
                //'micro' => $micro2,
                ])
                @include('admin.catalog.datatable-script')
                @include('admin.location.users.memberships.delete')
                @include('admin.location.users.memberships.edit')
            </div>
        </div>

        <div id="User--membershipsP" class="tab-panel">
            <h5 class="">{{__('company.ExpiredMemberships').': '.$profile->first_name.' '.$profile->last_name}}</h5>
            <div class="panelcombos col panelcombos_full">
                <?php $micro = \App\Librerias\Catalog\LibDatatable::GetTableId()?>
                @include('admin.catalog.table',[
                'ajaxDatatable' => $ajaxDatatableExpiredMemberships,
                'catalogo' => $catalogExpiredMemberships,
                //'micro' => $micro2,
                ])
                @include('admin.catalog.datatable-script')
            </div>
        </div>
    </div>

    @include('admin.location.users.Creditos.subscription-delete')
</div>


<script>

    $(document).ready(function () {
        $('ul.tabs').tabs();
    });

</script>
