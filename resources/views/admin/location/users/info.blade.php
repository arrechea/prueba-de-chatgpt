<div class="model--border-radius">
    <h5 class="" style="display: inline-block"><i
            class="material-icons medium">assignment_ind</i> {{__('users.infoUser')}}</h5>
    {{--<div class="col s12 m12" style="margin-bottom: 25px !important; margin-left: 12px;"></div>--}}
    <table class="table">
        <thead style="border: none !important;">
        <tr>
            <th class="infor--user "
                style="text-transform: uppercase !important;">{{__('users.Name')}}:
            </th>
            <td class="infor--user ">{{$profile->first_name . ' ' . $profile->last_name}}</td>
            <th class="infor--user " style="text-transform: uppercase !important;">{{__('users.Age')}}:
            </th>
            <td class="infor--user ">{{$age}}</td>
        </tr>
        <tr>
            <th class="infor--user"
                style="text-transform: uppercase !important;">{{__('users.Email')}}:
            </th>
            <td class="infor--user">{{$profile->email}}</td>
            <th class="infor--user"
                style="text-transform: uppercase !important;">{{__('users.Birthday')}}:
            </th>
            <?php $birth_date=$profile->birth_date ?>
            <td class="infor--user">{{$birth_date ? (new Carbon\Carbon($birth_date))->format('d/m/Y') : '--'}}</td>
        </tr>

        <tr>
            <th class="infor--user"
                style="text-transform: uppercase !important;">{{__('users.Address')}}:
            </th>
            <td class="infor--user">{{$profile->address.' #'.$profile->external_number}}</td>
            <th class="infor--user"
                style="text-transform: uppercase !important;">{{__('users.gender')}}:
            </th>
            <td class="infor--user">{{__("gender.$profile->gender")}}</td>
        </tr>
        <tr>
            <th class="infor--user "
                style="text-transform: uppercase !important;">{{__('users.Interior')}}:
            </th>
            <td class="infor--user ">{{$profile->internal_number}}</td>
            <th class="infor--user "
                style="text-transform: uppercase !important;">{{__('users.Postcode')}}:
            </th>
            <td class="infor--user ">{{$profile->postal_code}}</td>
        </tr>
        <tr>
            <th class="infor--user "
                style="text-transform: uppercase !important;">{{__('users.Suburb')}}:
            </th>
            <td class="infor--user ">{{$profile->municipality}}</td>
            <th class="infor--user "
                style="text-transform: uppercase !important;">{{__('users.City')}}:
            </th>
            <td class="infor--user ">{{$profile->city}}</td>
        </tr>
        <tr>
            <th class="infor--user "
                style="text-transform: uppercase !important;">{{__('users.State')}}:
            </th>
            <td class="infor--user ">{{$state}}</td>
            <th class="infor--user "
                style="text-transform: uppercase !important;">{{__('users.Country')}}</th>
            <td class="infor--user ">{{$country}}</td>

        </tr>

        <tr>
            <th class="infor--user "
                style="text-transform: uppercase !important;">{{__('users.Phone')}}:
            </th>
            <td class="infor--user ">{{$profile->phone}}</td>
            <th class="infor--user "
                style="text-transform: uppercase !important;">{{__('users.Mobile')}}</th>
            <td class="infor--user ">{{$profile->cel_phone}}</td>

        </tr>

        {{--<tr>--}}
        {{--<th class="infor--user" style="text-transform: uppercase !important;">{{__('users.ShoeSize')}}:</th>--}}
        {{--<td class="infor--user">{{$profile->shoe_size}}</td>--}}
        {{--</tr>--}}
        </thead>
    </table>


</div>

<style>
    table {
        margin-top: 25px;
    }
</style>
