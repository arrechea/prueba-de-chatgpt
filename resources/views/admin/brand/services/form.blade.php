<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
<form class="row" method="post" action="{{$urlForm}}" autocomplete="off" enctype="multipart/form-data">

    @include('admin.common.alertas')
    <input hidden name="brands_id" value="{{$brand->id}}">
    <input hidden name="companies_id" value="{{$company->id}}">
    @if(isset($service))
        <input type="hidden" name="id" value="{{$service->id}}">
    @endif
    @if(isset($parent_id))
        <input hidden name="parent_id" value="{{$parent_id}}">
    @endif
    {{csrf_field()}}

    <div class="col s12 m8">
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <h5 class="col s12 header">{{__('services.ServiceData')}}</h5>
                <div class="input-field col s12 m8">
                    <input type="text" id="name" class="input" name="name"
                           value="{{old('name',($service->name ?? ''))}}"
                           required>
                    <label for="name">{{__('administrators.Name')}}</label>
                </div>
                <div class="input-field col s12 m4">
                    <input type="text" id="category" class="input" name="category"
                           value="{{old('product',($service->category ?? ''))}}">
                    <label for="category">{{__('services.Category')}}</label>
                </div>
                <div class="{{$isSaas ? 'input-field text-area' : ''}} col s12 m8">
                    <label for="description" class="active">{{__('services.Description')}}</label>
                    <textarea type="text" id="description" class="materialize-textarea" name="description"
                    >{{old('description',($service->description ?? ''))}}</textarea>
                </div>
            </div>
            <div class="row" style="min-height: 85px">
                <div class="input-field col s12 m6 l5">
                    <input type="text" id="order" class="input" name="order"
                           value="{{old('order',($service->order ?? ''))}}">
                    <label for="order">{{__('services.Order')}}</label>
                </div>
                <div class="col s12 m6">
                    <label class="header">{{__('marketing.Show')}}</label>
                    <div class="switch">
                        <label>
                            {{__('marketing.Hide')}}
                            <input type="checkbox"
                                   @if(!!old())
                                       {!! old('show','')==='on' ? 'checked' : '' !!}
                                   @else
                                       {!! isset($service) && !$service->hide_in_home ? 'checked' : '' !!}
                                   @endif
                                   name="show">
                            <span class="lever"></span>
                            {{__('marketing.show')}}
                        </label>
                    </div>
                </div>
            </div>
        </div>

        @if($company->isGympassActive() && \App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::GYMPASS_CLASS_VIEW,$brand))
            @php
                $edit_permission=\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::GYMPASS_CLASS_EDIT,$brand);
                $locations=$brand->gympassActiveLocations;
            @endphp
            @if($locations->count()>0)
                <div class="col s12 m12 card-panel panelcombos" style="margin-bottom: 150px;">
                    <div class="row">
                        <h5 class="col s12 header">{{__('gympass.gympassSettings')}}</h5>

                        <div class="col s12 m12" style="margin-bottom: 15px;">
                            <label class="header">{{__('gympass.serviceActive')}}</label>
                            <div class="switch">
                                <label>
                                    {{__('gympass.inactive')}}
                                    <input type="checkbox"
                                           @if(!$edit_permission) disabled="disabled" @endif
                                           @if(!!old())
                                               {!! old("gympass_active",'')==='on' ? 'checked' : '' !!}
                                           @else
                                               {!! isset($service) && $service->isGympassActive() ? 'checked' : '' !!}
                                           @endif
                                           name='gympass_active'>
                                    <span class="lever"></span>
                                    {{__('gympass.active')}}
                                </label>
                            </div>
                        </div>

                        <div class="gympass--service-locations-container {!! isset($service) && $service->isGympassActive() ? 'active' : '' !!} col">
                            @foreach($locations as $location)
                                @if($location->isGympassActive())
                                    <div class="gympass--service-location card grey lighten-5 col">
                                        <h5>{{ __('gympass.locationTitle',['location'=> $location->name ]) }}</h5>
                                        <div class="col s12 m6">
                                            <label class="header">{{__('gympass.serviceActive')}}</label>
                                            <div class="switch">
                                                <label>
                                                    {{__('gympass.inactive')}}
                                                    <input type="checkbox"
                                                           @if(!$edit_permission) disabled="disabled" @endif
                                                           @if(!!old())
                                                               {!! old("gympass_active.{$location->id}",'')==='on' ? 'checked' : '' !!}
                                                           @else
                                                               {!! isset($service) && $service->isGympassActive($location) ? 'checked' : '' !!}
                                                           @endif
                                                           name="gympass_actives[{{$location->id}}]">
                                                    <span class="lever"></span>
                                                    {{__('gympass.active')}}
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col s12 m6">
                                            <label class="header">{{__('gympass.serviceBookable')}}</label>
                                            <div class="switch">
                                                <label>
                                                    {{__('gympass.inactive')}}
                                                    <input type="checkbox"
                                                           @if(!$edit_permission) disabled="disabled" @endif
                                                           @if(!!old())
                                                               {!! old("gympass_bookable.{$location->id}",'')==='on' ? 'checked' : '' !!}
                                                           @elseif(isset($service))
                                                               {!! $service->isGympassBookable($location) ? 'checked' : '' !!}
                                                           @else
                                                               checked
                                                           @endif
                                                           name="gympass_bookable[{{$location->id}}]">
                                                    <span class="lever"></span>
                                                    {{__('gympass.active')}}
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col s12 m6">
                                            <label class="header">{{__('gympass.serviceVisible')}}</label>
                                            <div class="switch">
                                                <label>
                                                    {{__('gympass.inactive')}}
                                                    <input type="checkbox"
                                                           @if(!$edit_permission) disabled="disabled" @endif
                                                           @if(!!old())
                                                               {!! old("gympass_visible.{$location->id}",'')==='on' ? 'checked' : '' !!}
                                                           @elseif(isset($service))
                                                               {!! $service->isGympassVisible($location) ? 'checked' : '' !!}
                                                           @else
                                                               checked
                                                           @endif
                                                           name="gympass_visible[{{$location->id}}]">
                                                    <span class="lever"></span>
                                                    {{__('gympass.active')}}
                                                </label>
                                            </div>
                                        </div>

                                        @php
                                            $products=\App\Librerias\Gympass\Helpers\GympassHelpers::getGympassProducts($location);
                                            $selected_product = isset($service) ?
                                                $service->getDotValue("extra_fields.gympass.info.{$location->id}.product_id") :
                                                null;
                                        @endphp
                                        @if($products)
                                            <div class="col s12 m12 l8">
                                                <div class="input-field">
                                                    <select
                                                        @if(!$edit_permission) disabled="disabled" @endif
                                                    class="col s12"
                                                        name="extra_fields[gympass][info][{{$location->id}}][product_id]"
                                                        id="gympass_product_id--{{$location->id}}"
                                                    >
                                                        <option value="">--</option>
                                                        @foreach($products as $product)
                                                            <option value="{{$product->product_id}}"
                                                                    @if($selected_product && $selected_product==$product->product_id) selected @endif>{{$product->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <label
                                                        for="gympass_product_id--{{$location->id}}">{{__('gympass.serviceProduct')}}</label>
                                                </div>
                                            </div>
                                        @endif

                                        @if(isset($service))
                                            <div class="input-field col s12 m4">
                                                <input type="text" id="gympass_class_id--{{$location->id}}"
                                                       class="input"
                                                       value="{{$service->getGympassClassId($location)}}"
                                                       readonly>
                                                <label
                                                    for="gympass_class_id--{{$location->id}}">{{__('gympass.serviceClassId')}}</label>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endif

        @if(isset($service))
            <div class="col s12 m12 card-panel panelcombos">
                <br>
                <div class="row">
                    <h5 class="header">{{__('services.ChildServices')}}</h5>
                    <div class="col s12 m12">
                        <ul class="collection" id="service-list"
                            data-url="{{route('admin.company.brand.services.save.child',['company'=>$company,'brand'=>$brand])}}">

                            @if(isset($child_services))
                                @foreach($child_services as $servicio)
                                    <li class="collection-item service-list-item" data-order="{{$servicio->order}}"
                                        data-url="{{route('admin.company.brand.services.delete.post',['company'=>$company, 'brand'=>$brand, 'service'=>$servicio])}}">
                                        <div>
                                            <label>{{$servicio->name}}</label>
                                            {!! $servicio->status!=='active' ? '<i class="material-icons">not_interested</i>' : '' !!}
                                            <div class="secondary-content">
                                                <input hidden value="{{$servicio->id}}">
                                                <a class="remove-child-service btn btn-flat">
                                                    <i class="material-icons">remove</i>
                                                </a>
                                                <a class="btn btn-flat" target="_blank"
                                                   href="{{route('admin.company.brand.services.edit',['company'=>$company,'brand'=>$brand,'service'=>$servicio])}}">
                                                    <i class="material-icons">mode_edit</i>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        <a class="waves-effect pull-right waves-light btn btn-floating" href="#child_service"><i
                                class="material-icons">add</i></a>
                    </div>

                    {{--<div class="col s12">--}}
                    {{--<ul class="collection with-header" id="text-list"--}}
                    {{--data-url="{{route('admin.company.brand.services.texts.save.new',['company'=>$company, 'brand'=>$brand, 'service'=>$service])}}">--}}
                    {{--<li class="collection-header">--}}
                    {{--<h6 class="header">{{__('services.SpecialTexts')}}</h6>--}}
                    {{--</li>--}}

                    {{--@if(isset($special_texts))--}}
                    {{--@foreach($special_texts as $text)--}}
                    {{--<li class="collection-item service-list-item"--}}
                    {{--data-url="{{route('admin.company.brand.services.texts.delete',['company'=>$company,'brand'=>$brand,'service'=>$service,'text'=>$text])}}">--}}
                    {{--<div>--}}
                    {{--<label>{{$text->title}}</label>--}}
                    {{--<div class="secondary-content">--}}
                    {{--<input class="text-id" hidden value="{{$text->id}}">--}}
                    {{--<a class="remove-child-service btn btn-flat">--}}
                    {{--<i class="material-icons">remove</i>--}}
                    {{--</a>--}}
                    {{--<a class="btn btn-flat edit-special-text"--}}
                    {{--data-url="{{route('admin.company.brand.services.texts.save',['company'=>$company,'brand'=>$brand,'service'=>$service,'text'=>$text])}}"--}}
                    {{--data-href="{{route('admin.company.brand.services.texts.edit',['company'=>$company,'brand'=>$brand,'service'=>$service,'text'=>$text])}}">--}}
                    {{--<i class="material-icons">mode_edit</i>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</li>--}}
                    {{--@endforeach--}}
                    {{--@endif--}}
                    {{----}}
                    {{--</ul>--}}
                    {{--<a class="waves-effect pull-right waves-light btn" href="#create_special_text"><i--}}
                    {{--class="material-icons">add</i></a>--}}
                    {{--</div>--}}


                </div>
                <br>
            </div>
        @endif

        @if(isset($service))
            <div class="col s12 m12 card-panel panelcombos">
                <div class="row">
                    <div id="special-texts" data-implement="services"></div>
                </div>
                <div class="col s12 m12 card-panel panelcombos">
                    <div class="row">
                        <div id="special-texts" data-implement="services"></div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div id="special-text-form"></div>

        </div>
    </div>
    <div class="col s12 m4">
        <div class="col s12 m10 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12">
                    <div class="file-field input-field">
                        <div class="uploadPhoto">
                            <img src="{{$service->pic??''}}" width="180px" alt=""
                                 class="responsive-img uploadPhoto--image"/> <br>
                            <h5 class="header"><i
                                    class="material-icons small">add_a_photo</i> {{__('services.ServicePicture')}}</h5>
                            <input type='file' class="uploadPhoto--input" name="pic"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="switch">
                <label>
                    {{__('administrators.Inactive')}}
                    <input type="checkbox"
                           @if(!!old())
                               {!! old('status','')==='on' ? 'checked' : '' !!}
                           @else
                               {!! isset($service) && $service->isActive() ? 'checked' : '' !!}
                           @endif
                           name="status">
                    <span class="lever"></span>
                    {{__('administrators.Active')}}
                </label>
            </div>
            @if(isset($service) && !$service->isActive())
                <div class="col s12 m12 l12">
                    <p style="color: var(--alert-color)">{{__('services.ActiveWarning')}}</p>
                </div>
            @endif
        </div>
        <div class="input-field">
            <div class="col s7 m7 edit-buttons">
                <button type="submit"
                        class="{{$isSaas ? 'BuqSaas-e-button is-save' : 'waves-effect waves-light btn btnguardar'}}">
                    @if($isSaas)
                        <div>
                            <i class="fal fa-save"></i>
                            <span>{{__('company.Save')}}</span>
                        </div>
                    @else
                        <i class="material-icons right small">save</i>
                        {{__('company.Save')}}
                    @endif
                </button>
            </div>
        </div>

        <div class="row">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::SERVICES_DELETE, $brand) && isset($service))
                <div class="col s7 m7 edit-buttons">
                    <a class="{{$isSaas ? 'BuqSaas-e-button is-delete' : 'waves-effect waves-light btn btnguardar'}}"
                       href="#eliminar_b" style="{{$isSaas ? '' : 'background-color: grey'}}">
                        @if($isSaas)
                            <i class="far fa-times"></i>
                            <span>{{__('company.Delete')}}</span>
                        @else
                            <i class="material-icons right small">clear</i>
                            {{__('company.Delete')}}
                        @endif
                    </a>
                </div>
            @endif
        </div>
    </div>
</form>

@if (isset($service))
    <div id="eliminar_b" class="modal modal - fixed - footer modaldelete model--border-radius" data-method="get"
         data-href="{{route('admin.company.brand.services.delete', ['company'=>$company,'brand' => $brand,'service'=>$service])}}">
        <div class="modal-content"></div>
    </div>

    <div id="child_service" class="modal modal-fixed-footer create-modal" data-method="get" style=""
         data-href="{{route('admin.company.brand.services.create.child',
         ['company'=>$company, 'brand'=>$brand, 'parent'=>$service])}}" data-list="service-list"
         data-url="{{route('admin.company.brand.services.save.child',['company'=>$company, 'brand'=>$brand])}}">
        <div class="row" id="NewChildService">
            <div class="modal-content">@cargando</div>
            <div class="modal-footer">
                <button type="submit" class="modal-action modal-close waves-effect waves-green btn save-button"><i
                        class="material-icons right small">save</i>{{__('company.Save')}}</button>
            </div>
        </div>
    </div>

    {{--<div id="create_special_text" class="modal modal-fixed-footer create-modal" data-method="get"--}}
    {{--style="min-height: 400px; height: 60% !important; width: 65% !important;"--}}
    {{--data-href="{{route('admin.company.brand.services.texts.create',--}}
    {{--['company'=>$company, 'brand'=>$brand, 'service'=>$service])}}" data-list="text-list"--}}
    {{--data-url="{{route('admin.company.brand.services.texts.save.new',['company'=>$company, 'brand'=>$brand, 'service'=>$service])}}">--}}
    {{--<div class="row">--}}
    {{--<div class="modal-content">@cargando</div>--}}
    {{--<div class="modal-footer">--}}
    {{--<button type="submit" class="modal-action modal-close waves-effect waves-green btn save-button"><i--}}
    {{--class="material-icons right small">save</i>{{__('company.Save')}}</button>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    <div id="edit_special_text" class="modal modal-fixed-footer create-modal" data-method="get"
         style="min-height: 400px" data-list="text-list" data-href="" data-url="">
        <div class="row">
            <div class="modal-content">@cargando</div>
            <div class="modal-footer">
                <button type="submit" class="modal-action modal-close waves-effect waves-green btn save-button"><i
                        class="material-icons right small">save</i>{{__('company.Save')}}</button>
            </div>
        </div>
    </div>
@endif


