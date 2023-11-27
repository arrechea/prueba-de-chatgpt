<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            {{--<li class="tab col s2"><a--}}
            {{--href="{{route('admin.company.brand.mails.welcome.create', ['company'=>$company,'brand'=>$brand])}}"--}}
            {{--class="{{(Route::current()->getName()==='admin.company.brand.mails.welcome.create' ? 'active' : '')}}">{{__('mails.Mail')}}--}}
            {{--de {{__('mails.welcome')}}</a></li>--}}


            <li class="tab col s3"><a
                    href="{{route('admin.company.brand.mails.reservation-cancel.create', ['company'=>$company, 'brand' => $brand])}}"
                    class="{{(Route::current()->getName()==='admin.company.brand.mails.reservation-cancel.create' ? 'active' : '')}}">{{__('mails.MailsReservationCancel')}}</a>
            </li>


            <li class="tab col s3"><a
                    href="{{route('admin.company.brand.mails.reservation-confirm.create', ['company'=>$company, 'brand' => $brand])}}"
                    class="{{(Route::current()->getName()==='admin.company.brand.mails.reservation-confirm.create' ? 'active' : '')}}">{{__('mails.MailsReservationConfirm')}}</a>
            </li>

            {{--<li class="tab col s2"><a href="{{route('admin.company.brand.mails.forget-password.create', ['company'=>$company , 'brand' => $brand])}}"--}}
            {{--class="{{(Route::current()->getName()==='admin.company.brand.mails.forget-password.create' ? 'active' : '')}}">{{__('mails.Mails')}}--}}
            {{--de {{__('mails.forgetPass')}}</a></li>--}}

            <li class="tab col s2"><a
                    href="{{route('admin.company.brand.mails.mail-purchase.create', ['company'=>$company , 'brand' => $brand])}}"
                    class="{{(Route::current()->getName()==='admin.company.brand.mails.mail-purchase.create' ? 'active' : '')}}">{{__('mails.MailsPurchases')}}</a>
            </li>

            <li class="tab col s3"><a
                    href="{{route('admin.company.brand.mails.waitlist-cancel.create', ['company'=>$company, 'brand' => $brand])}}"
                    class="{{(Route::current()->getName()==='admin.company.brand.mails.waitlist-cancel.create' ? 'active' : '')}}">{{__('mails.MailsWaitlistCancel')}}</a>
            </li>


            <li class="tab col s3"><a
                    href="{{route('admin.company.brand.mails.waitlist-confirm.create', ['company'=>$company, 'brand' => $brand])}}"
                    class="{{(Route::current()->getName()==='admin.company.brand.mails.waitlist-confirm.create' ? 'active' : '')}}">{{__('mails.MailsWaitlistConfirm')}}</a>
            </li>

            <li class="tab col s3"><a
                    href="{{route('admin.company.brand.mails.subscription-error.edit', ['company'=>$company, 'brand' => $brand])}}"
                    class="{{(Route::current()->getName()==='admin.company.brand.mails.subscription-error.edit' ? 'active' : '')}}">{{__('mails.SubscriptionError')}}</a>
            </li>


            <li class="tab col s3"><a
                    href="{{route('admin.company.brand.mails.subscription-confirm.edit', ['company'=>$company, 'brand' => $brand])}}"
                    class="{{(Route::current()->getName()==='admin.company.brand.mails.subscription-confirm.edit' ? 'active' : '')}}">{{__('mails.SubscriptionConfirm')}}</a>
            </li>
            <li class="tab col s3"><a
                    href="{{route('admin.company.brand.mails.invitation-confirm.edit', ['company'=>$company, 'brand' => $brand])}}"
                    class="{{(Route::current()->getName()==='admin.company.brand.mails.invitation-confirm.edit' ? 'active' : '')}}">{{__('mails.MailsInvitationConfirm')}}</a>
            </li>
        </ul>
    </div>
</div>
<br>



