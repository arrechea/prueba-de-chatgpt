<form action="{{$urlForm}}" class="row" autocomplete="off" method="post" enctype="multipart/form-data">
    @include('admin.common.alertas')
    @if(isset($credit))
        <input type="hidden" name="id" value="{{$credit->id}}">
    @endif
    {{csrf_field()}}
    <input type="hidden" name="companies_id" value="{{$company->id}}">

    <div class="col s12 m8">

        <br>
        <div class="row">
            <div class="col s12 m12 l8">
                <div class="input-field">
                    <input type="text" class="input" name="name" id="name"
                           value="{{old('name', isset($credit) ? $credit->name: '')}}" required>
                    <label for="name">{{__('credits.Name')}}</label>
                </div>
            </div>
        </div>


        @if(isset($credit))
            <div class="row">
                <div class="col s12 m12 l8">
                    {{--todo comparar brands_c con brand company--}}
                    @foreach($company->brands as $brandCompany)
                        <?php
                        $brand_c = $brands_c->where('brands_id', $brandCompany->id)->first();
                        $selected = in_array($brandCompany->id, $brands_c->pluck('brands_id')->toArray())
                        ?>

                        <div class="input-field">
                            <p>
                                <input type="checkbox" name="brandCompanies[]" id="{{$brandCompany->id}}"
                                       value="{{$brandCompany}}" {!! $selected ? 'checked="checked"' : '' !!}/>
                                <label for="{{$brandCompany->id}}">{{$brandCompany->name}}</label>
                            </p>

                        </div>
                    @endforeach

                </div>
            </div>
        @endif
    </div>

    <div class="col s12 m4">
        <div class="col s12 m10 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l12">
                    <div class="file-field input-field">
                        <div class="uploadPhoto">
                            <img src="{{$credit->picture??''}}" width="180px" alt=""
                                 class="responsive-img uploadPhoto--image"/> <br>
                            <h5 class="header"><i
                                    class="material-icons small">add_a_photo</i> {{__('credits.Image')}}
                                de {{__('credits.Credits')}}</h5>
                            <input type='file' class="uploadPhoto--input" name="picture"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12 l12">
                <div class="switch">
                    <label>
                        {{__('credits.Inactive')}}
                        <input type="checkbox"
                               @if(!!old()) {!! old('status','')==='on' ? 'checked' : '' !!}
                               @else {!! isset($credit) && $credit->isActive() ? 'checked' : '' !!}
                               @endif
                               name="status">
                        <span class="lever"></span>
                        {{__('credits.Active')}}
                    </label>
                </div>
            </div>
            @if(isset($credit) && !$credit->isActive())
                <div class="col s12 m12 l12">
                    <p style="color: var(--alert-color)">{{__('credits.ActiveWarning')}}</p>
                </div>
            @endif
        </div>

        @if(isset($credit) && $brands_c->count() >0)
            <div class="input-field col s12">
                <a class="waves-effect waves-light btn deep-purple lighten-3 btnasignacionr"
                   {{--devuelve vista parcial de asignacion de roles para editar--}}
                   href="#applicable_services_company"
                   style="margin: 0;">{{__('marketing.ApplicableServices')}}</a>
            </div>
        @endif

        <div class="col s12 m12 l12">
            <button type="submit" class="waves-effect waves-light btn btnguardar"><i
                    class="material-icons right small">save</i>{{__('credits.Save')}}</button>
        </div>

        @if (isset($credit))
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::CREDITS_DELETE,$company))
                <div class="col s12 m12 l6">
                    <a class="waves-effect waves-light btn btnguardar" href="#eliminar_credits"
                       style="background-color: grey"><i
                            class="material-icons right small">clear</i>{{__('credits.Delete')}}</a>
                </div>
            @endif
        @endif
    </div>

</form>

@if (isset($credit))
    <div id="eliminar_credits" class="modal modal - fixed - footer modaldelete model--border-radius" data-method="get"
         data-href="{{route('admin.company.credits.delete', ['company'=>$company, 'credit' => $credit->id])}}">
        <div class="modal-content"></div>
    </div>

    <div id="applicable_services_company"
         class="User--assignmentRoles modal modal-fixed-footer modalserviciosApp model--border-radius"
         data-method="get"
         data-href="{{route('admin.company.credits.services.company',[
    'company'=>$company,
    'credit'=>$credit
    ])}}"
    >
        <div class="modal-content">@cargando</div>
        <div class="modal-footer">
            <a type="submit" id="services_form_button"
               class="modal-action modal-close waves-effect waves-green btn">{{__('administrators.Save')}}</a>
        </div>

    </div>
@endif
