<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            <li class="tab col s6"><a
            href="{{route('admin.company.mails.welcome.create', ['company'=>$company])}}"
            class="{{(Route::current()->getName()==='admin.company.mails.welcome.create' ? 'active' : '')}}">{{__('mails.welcomeMail')}}</a></li>
            <li class="tab col s6"><a
            href="{{route('admin.company.mails.reset-password.create', ['company'=>$company])}}"
            class="{{(Route::current()->getName()==='admin.company.mails.reset-password.create' ? 'active' : '')}}">{{__('mails.resetPassword')}}</a></li>
        </ul>
    </div>
</div>
<br>
