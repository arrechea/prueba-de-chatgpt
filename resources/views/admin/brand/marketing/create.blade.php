{{csrf_field()}}
<div id="marketing-selector" class="form-group">
    <div class="row">
        <div class="col card-panel s12" id="marketing-radio">
            <div class="row">
                <h5 class="header">{{__('messages.create-marketing')}}</h5><br>
                <div class="card-content">


                    {{--<input type="radio" id="promotion" name="type" class="with-gap"--}}
                    {{--value="promotion" required>--}}
                    {{--<label for="promotion" class="opciones">{{__('marketing.Promotion')}}</label>--}}
                    @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::COMBOS_CREATE, $brand))
                        <div class="col s12 l6">
                            <div class="">
                                <input type="radio" id="combo" name="type" class="with-gap"
                                       value="combo">
                                <label for="combo">{{__('marketing.Combo')}}</label>
                            </div>
                        </div>
                    @endif
                    @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MEMBERSHIP_CREATE, $brand))
                        <div class="col s12 l6">
                            <div class="">
                                <input type="radio" id="membership" name="type" class="with-gap"
                                       value="membership">
                                <label for="membership">{{__('marketing.Membership')}}</label>
                            </div>
                        </div>
                    @endif
                    @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::DISCOUNT_CREATE, $brand))
                        <div class="col s12 l6">
                            <div class="">
                                <input type="radio" id="discount_codes" name="type" class="with-gap"
                                       value="discount_codes">
                                <label for="discount_codes">{{__('marketing.DiscountCode')}}</label>
                            </div>
                        </div>
                    @endif

                    {{--<input type="radio" id="giftcard" name="type" class="with-gap"--}}
                    {{--value="giftcard">--}}
                    {{--<label for="giftcard" >{{__('marketing.GiftCards')}}</label>--}}
                    <div class="col s12">
                        <div class="input-field"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
