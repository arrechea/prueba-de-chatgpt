<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
<form class="row" method="post" action="{{$urlForm}}" autocomplete="off" enctype="multipart/form-data">
    @include('admin.common.alertas')
    <input hidden id="companies_id" name="companies_id" value="{{$company->id}}">
    <input hidden id="brands_id" name="brands_id" value="{{$brand->id}}">
    @if(isset($staff))
        <input type="hidden" name="id" value="{{$staff->id}}">
    @endif
    {{csrf_field()}}
    <div class="col s12 m8">
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <h5 class="header">{{__('staff.StaffData')}}</h5>
                <div class="input-field col s12 m12 l3">
                    <input type="text" id="name" class="input" name="name"
                           value="{{old('name',($staff->name ?? ''))}}"
                           required>
                    <label for="name">{{__('staff.Name')}}</label>
                </div>

                <div class="input-field col s12 m12 l3">
                    <input type="text" id="lastname" class="input" name="lastname"
                           value="{{old('lastname',($staff->lastname ?? ''))}}" required>
                    <label for="lastname">{{__('staff.Lastname')}}</label>
                </div>

                <div class=" input-field col s12 m12 l3">
                    <label for="birthday">{{__('company.Birthday')}}</label>
                    <input type="text" id="birthday" class="calendar-date pck-pink" name="birth_date"
                           value="{{old('birth_date',($staff->birth_date ?? ''))}}"/>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m3">
                    <div class="input-field">
                        <input type="text" id="job" name="job" class="input"
                               value="{{old('job',($staff->job ?? ''))}}">
                        <label for="job">{{__('staff.job')}}</label>
                    </div>
                </div>

                <div class="col s12 m6">
                    <div class="input-field">
                        <input type="text" id="quote" name="quote" class="input"
                               value="{{old('quote',($staff->quote ?? ''))}}">
                        <label for="quote">{{__('staff.quote')}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l3">
                    <div class="input-field">
                        <input type="text" id="order" name="order" class="input"
                               value="{{old('order', ($staff->order ?? ''))}}">
                        <label for="order">{{__('staff.Order')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l5">
                    <label class="header">{{__('marketing.Show')}}</label>
                    <div class="switch">
                        <label>
                            {{__('marketing.Hide')}}
                            <input type="checkbox"
                                   @if(!!old()) {!! old('show','')==='on' ? 'checked' : '' !!}
                                   @else {!! isset($staff) && !$staff->hide_in_home ? 'checked' : '' !!}
                                   @endif
                                   name="show">
                            <span class="lever"></span>
                            {{__('marketing.show')}}
                        </label>
                    </div>
                </div>
            </div>
            <br>
        </div>

        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <h5 class="header">{{__('marketing.Description')}}</h5>
                <div class="{{$isSaas ? 'input-field text-area' : ''}} col s12 m8">
                  <label for="description">{{__('staff.Description')}}</label>
                  <textarea type="text" id="description" class="materialize-textarea" name="description"
                  >{{old('description',($staff->description ?? ''))}}</textarea>
                </div>
            </div>
        </div>

        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12">
                    <h5 class="header">{{__('staff.addressStaff')}}</h5>
                </div>
                <div class="col s12 m12 l6">
                    <div class="input-field ">
                        <input type="text" id="street" class="input" name="address"
                               value="{{old('address',($staff->address ?? ''))}}">
                        <label for="street">  {{__('staff.Street')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l3">
                    <div class="input-field ">
                        <input type="text" id="number" class="input" name="external_number"
                               value="{{old('external_number',($staff->external_number ?? ''))}}">
                        <label for="number">{{__('staff.ExteriorNumber')}}</label>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field ">
                        <input type="text" id="sub" class="input" name="municipality"
                               value="{{old('municipality',($staff->municipality ?? ''))}}">
                        <label for="sub">{{__('staff.Suburb')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l3">
                    <div class="input-field ">
                        <input type="text" id="post" class="input" name="postal_code"
                               value="{{old('postal_code',($staff->postal_code ?? ''))}}">
                        <label for="post">{{__('staff.Postcode')}}</label>
                    </div>
                </div>
            </div>

            <div id="places-selectors" class="col s12 m12 l11 row"></div>

            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field ">
                        <input type="email" id="email" class="input" name="email"
                               value="{{old('email',($staff->email ?? ''))}}" required>
                        <label for="email">{{__('staff.Email')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l2">
                    <p>
                        <input type="radio" id="w" name="gender" class="with-gap"
                               value="female" {{isset($staff)&&$staff->gender==='female' ? 'checked="checked"':''}}>
                        <label for="w">{{__('staff.W')}}</label>
                    </p>
                </div>
                <div class="col s12 m12 l2">
                    <p>
                        <input type="radio" id="m" name="gender" class="with-gap"
                               value="male" {{isset($staff)&&$staff->gender==='male' ? 'checked="checked"':''}}>
                        <label for="m">{{__('staff.M')}}</label>
                    </p>
                </div>
            </div>


            <div class="row">

                <div class="col s12 m12 l6">
                    <div class="input-field  ">
                        <input type="text" id="phone" class="input " name="phone"
                               value="{{old('phone',($staff->phone ?? ''))}}">
                        <label for="phone">{{__('company.Phone')}} </label>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($staff))
            <div class="col s12 m12 card-panel panelcombos">
                <div id="special-texts" data-implement="staff"></div>
            </div>
        @endif

        <div class="row">
            <div id="special-text-form"></div>
        </div>
    </div>

    <div class="col s12 m4">
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m6">
                    <div class="file-field input-field">
                        <div class="">
                            <div class="uploadPhoto">
                                <img src="{{$staff->picture_web_list??''}}" width="180px" alt=""
                                     class="responsive-img uploadPhoto--image"/> <br>
                                <h5 class="header"><i
                                        class="material-icons small">add_a_photo</i> {{__('staff.picture-web-list')}}
                                </h5>
                                <input type='file' class="uploadPhoto--input" name="picture_web_list"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col s12 m6">
                    <div class="file-field input-field">
                        <div class="">
                            <div class="uploadPhoto">
                                <img src="{{$staff->picture_web??''}}" width="180px" alt=""
                                     class="responsive-img uploadPhoto--image"/> <br>
                                <h5 class="header"><i
                                        class="material-icons small">add_a_photo</i> {{__('staff.picture-web')}}</h5>
                                <input type='file' class="uploadPhoto--input" name="picture_web"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6">
                    <div class="file-field input-field">
                        <div class="">
                            <div class="uploadPhoto">
                                <img src="{{$staff->picture_web_over??''}}" width="180px" alt=""
                                     class="responsive-img uploadPhoto--image"/> <br>
                                <h5 class="header"><i
                                        class="material-icons small">add_a_photo</i> {{__('staff.picture-web-over')}}
                                </h5>
                                <input type='file' class="uploadPhoto--input" name="picture_web_over"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col s12 m6">
                    <div class="file-field input-field">
                        <div class="">
                            <div class="uploadPhoto">
                                <img src="{{$staff->picture_movil??''}}" width="180px" alt=""
                                     class="responsive-img uploadPhoto--image"/> <br>
                                <h5 class="header"><i
                                        class="material-icons small">add_a_photo</i> {{__('staff.picture-movil')}}</h5>
                                <input type='file' class="uploadPhoto--input" name="picture_movil"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m6">
                    <div class="file-field input-field">
                        <div class="">
                            <div class="uploadPhoto">
                                <img src="{{$staff->picture_movil_list??''}}" width="180px" alt=""
                                     class="responsive-img uploadPhoto--image"/> <br>
                                <h5 class="header"><i
                                        class="material-icons small">add_a_photo</i> {{__('staff.picture-movil-list')}}
                                </h5>
                                <input type='file' class="uploadPhoto--input" name="picture_movil_list"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col s12 m12 ">
                <div class="switch">
                    <label>
                        {{__('staff.Inactive')}}
                        <input type="checkbox"
                               @if(!!old()) {!! old('status','')==='on' ? 'checked' : '' !!}
                               @else {!! isset($staff) && $staff->isActive() ? 'checked' : '' !!}
                               @endif
                               name="status">
                        <span class="lever"></span>
                        {{__('staff.Active')}}
                    </label>
                </div>
            </div>
            @if(isset($staff) && !$staff->isActive())
                <div class="col s12 m12 l12">
                    <p style="color: var(--alert-color)">{{__('staff.ActiveWarning')}}</p>
                </div>
            @endif
        </div>


        <div class="{{$isSaas ? '' : 'col s12 m4'}}">
            <div class="col s12 m12 l7 edit-buttons input-field">
                <button type="submit" class="{{$isSaas ? 'BuqSaas-e-button is-save' : 'waves-effect waves-light btn btnguardar'}}">
                    @if($isSaas)
                        <div>
                            <i class="fal fa-save"></i>
                            <span>{{__('staff.Save')}}</span>
                        </div>
                    @else
                        <i class="material-icons right small">save</i>
                        {{__('staff.Save')}}
                    @endif
                </button>
            </div>
            <div class="row">
                @if (isset($staff))
                    <div class="col s12 m12 l7 edit-buttons">
                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::STAFF_DELETE,$company))
                           <a class="{{$isSaas ? 'BuqSaas-e-button is-delete' : 'waves-effect waves-light btn btnguardar'}}" href="#eliminar_c" style="{{$isSaas ? '' : 'background-color: grey'}}">
                              @if($isSaas)
                                 <i class="far fa-times"></i>
                                 <span>{{__('staff.Delete')}}</span>
                              @else
                                 <i class="material-icons right small">clear</i>
                                 {{__('staff.Delete')}}
                              @endif
                           </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</form>

@if (isset($staff))
    <div id="eliminar_c" class="modal modal - fixed - footer modaldelete model--border-radius" data-method="get"
         data-href="{{route('admin.company.brand.staff.delete', ['company'=>$company, 'brand' => $brand,'staff' => $staff])}}">
        <div class="modal-content"></div>
    </div>
@endif

