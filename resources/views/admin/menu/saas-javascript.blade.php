<script>
   @if(isset($location))
    $(document).ready(function () {
        var currentRoute = '{{'/' . \Request::path()}}';
        if (currentRoute.indexOf('calendar') != -1) {
            currentRoute = "calendar_" + "{{ $location->slug }}";
        }
        if (currentRoute.indexOf('reservations') != -1) {
            currentRoute = "reservations_" + "{{ $location->slug }}";
        }
        if (currentRoute.indexOf('dashboard/rooms') != -1 || currentRoute.indexOf('dashboard/room-maps') != -1 ||
            currentRoute.indexOf('dashboard/maps-position') != -1) {
            currentRoute = "rooms_" + "{{ $location->slug }}";
        }

        var menusByRoute = [];
        @foreach($locations as $location)
            menusByRoute['calendar_' + "{{$location->slug}}"] = {
            'main': 'location',
            'secondary': 'calendar_{{$location->slug}}'
        };

        menusByRoute['reservations_' + "{{$location->slug}}"] = {
            'main': 'location',
            'secondary': 'reservations_{{$location->slug}}'
        };
        menusByRoute['{{route('admin.company.brand.locations.purchases.create', ['company'=>$company, 'brand'=>$brand, 'location'=>$location],false)}}'] = {
            'main': 'location',
            'secondary': 'purchases_{{$location->slug}}'
        };
        menusByRoute['{{route('admin.company.brand.locations.users.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location],false)}}'] = {
            'main': 'location',
            'secondary': 'users_{{$location->slug}}'
        };
        menusByRoute["rooms_" + "{{ $location->slug }}"] = {
            'main': 'location',
            'secondary': 'rooms_{{$location->slug}}'
        };
        menusByRoute['{{route('admin.company.brand.locations.settings.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location],false)}}'] = {
            'main': 'location',
            'secondary': 'config_{{$location->slug}}'
        };
        @endforeach

        menusByRoute['{{route('admin.company.brand.discount-code.index', ['company'=>$company,'brand'=>$brand],false)}}'] = {
            'main': 'marketing',
            'secondary': 'discounts'
        };

        menusByRoute['{{route('admin.company.brand.discount-code.create', ['company'=>$company,'brand'=>$brand],false)}}'] = {
            'main': 'marketing',
            'secondary': 'discounts',
        };

        menusByRoute['{{route('admin.company.brand.marketing.gift-card.index',['company'=>$company,'brand'=>$brand],false)}}'] = {
            'main': 'marketing',
            'secondary': 'giftcards'
        };
        menusByRoute['{{route('admin.company.brand.staff.index', ['company' => $company,'brand'=>$brand],false)}}'] = {
            'main': 'config',
            'secondary': 'staff'
        };
        menusByRoute['{{route('admin.company.brand.staff.create', ['company' => $company,'brand'=>$brand],false)}}'] = {
            'main': 'config',
            'secondary': 'staff'
        };
        menusByRoute['{{route('admin.company.brand.services.index', ['company' => $company,'brand'=>$brand],false)}}'] = {
            'main': 'config',
            'secondary': 'services'
        };
        menusByRoute['{{route('admin.company.brand.services.create', ['company' => $company,'brand'=>$brand],false)}}'] = {
            'main': 'config',
            'secondary': 'services'
        };
        menusByRoute['{{route('admin.company.brands.edit', ['company' => $company,'brand'=>($brand->id)],false)}}'] = {
            'main': 'config',
            'secondary': 'general_config'
        };

        menusByRoute['{{route('admin.company.brand.products.index', ['company'=>$company,'brand'=>$brand],false)}}'] = {
            'main': 'config',
            'secondary': 'products',
            'accordion': 'products'
        };
        menusByRoute['{{route('admin.company.brand.marketing.combos.index', ['company'=>$company,'brand'=>$brand],false)}}'] = {
            'main': 'config',
            'secondary': 'products',
            'accordion': 'combos'
        };
        menusByRoute['{{route('admin.company.brand.marketing.combos.create', ['company'=>$company,'brand'=>$brand],false)}}'] = {
            'main': 'config',
            'secondary': 'products',
            'accordion': 'combos'
        };
        menusByRoute['{{route('admin.company.brand.marketing.membership.index', ['company'=>$company,'brand'=>$brand],false)}}'] = {
            'main': 'config',
            'secondary': 'products',
            'accordion': 'memberships'
        };
        menusByRoute['{{route('admin.company.brand.marketing.membership.create', ['company'=>$company,'brand'=>$brand],false)}}'] = {
            'main': 'config',
            'secondary': 'products',
            'accordion': 'memberships'
        };
        menusByRoute['{{route('admin.company.mails.welcome.create', ['company'=>$company],false)}}'] = {
            'main': 'config',
            'secondary': 'mails',
            'accordion': 'welcome'
        };
        menusByRoute['{{route('admin.company.mails.reset-password.create', ['company'=>$company],false)}}'] = {
            'main': 'config',
            'secondary': 'mails',
            'accordion': 'reset-password'
        };
        menusByRoute['{{route('admin.company.brand.mails.reservation-cancel.create', ['company'=>$company, 'brand'=>$brand ],false)}}'] = {
            'main': 'config',
            'secondary': 'mails',
            'accordion': 'reservation-cancel'
        };
        menusByRoute['{{route('admin.company.brand.mails.reservation-confirm.create', ['company'=>$company, 'brand'=>$brand],false)}}'] = {
            'main': 'config',
            'secondary': 'mails',
            'accordion': 'reservation-confirm'
        };
        menusByRoute['{{route('admin.company.brand.mails.mail-purchase.create', ['company'=>$company, 'brand'=>$brand],false)}}'] = {
            'main': 'config',
            'secondary': 'mails',
            'accordion': 'mail-purchase'
        };
        menusByRoute['{{route('admin.company.brand.mails.waitlist-confirm.create', ['company'=>$company, 'brand'=>$brand],false)}}'] = {
            'main': 'config',
            'secondary': 'mails',
            'accordion': 'waitlist-confirm'
        };
        menusByRoute['{{route('admin.company.brand.mails.waitlist-cancel.create', ['company'=>$company, 'brand'=>$brand],false)}}'] = {
            'main': 'config',
            'secondary': 'mails',
            'accordion': 'waitlist-cancel'
        };
        menusByRoute['{{route('admin.company.brand.mails.subscription-confirm.edit', ['company'=>$company, 'brand'=>$brand],false)}}'] = {
            'main': 'config',
            'secondary': 'mails',
            'accordion': 'subscription-confirm'
        };
        menusByRoute['{{route('admin.company.brand.mails.subscription-error.edit', ['company'=>$company, 'brand'=>$brand],false)}}'] = {
            'main': 'config',
            'secondary': 'mails',
            'accordion': 'subscription-error'
        };

        var openMenu = menusByRoute[currentRoute];
        if (openMenu) {
            if (openMenu['main']) {
                var elem = $(".saas-main-menu[data-open=" + openMenu['main'] + "]");
                elem.addClass('toggle');
                $(".saas-sidebar[data-open=" + openMenu['main'] + "]").addClass('active');
                $('.transition-main-enter').toggleClass('saas-content-collapse');
                $('#headerApp').toggleClass('saas-content-collapse');
            }
            if (openMenu['secondary']) {
                var elem = $(".saas-secondary-menu[data-open=" + openMenu['secondary'] + "]");
                elem.addClass('toggle');
                elem.find('.collapsein').slideDown();
                elem.find('a').removeClass('panel-collapsed');
            }
            if (openMenu['accordion']) {
                var elem = $(".saas-accordion-menu[data-open=" + openMenu['accordion'] + "]");
                elem.addClass('toggle');
            }
        }

        $('.saas-sidebar').each(function() {
            if ($(this).hasClass('active')) {
                $('.saas-nav').addClass('is-open');
            }
        });
   });
   @endif
</script>
