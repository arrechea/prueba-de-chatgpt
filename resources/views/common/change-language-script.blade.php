<script src="{{asset('js/lang.js')}}"></script>
<script>
    function changeLanguage(lang) {
        var values = {
            '_token': window.Laravel.csrfToken
        };
        $.ajax({
            type: "POST",
            data: values,
            url: window.myURL + "/language-change/" + lang,
            success: function (data) {
                location.reload();
            }
        });
    }

    $("#lang-en").click(function () {
        changeLanguage('en');
    });
    $("#lang-es").click(function () {
        changeLanguage('es');
    });

    window.Laravel = {
        'csrfToken': '{{csrf_token()}}',
    };

    window.myURL = "{{isset($company)?route('admin.companyLogin.init',['company'=>$company]):route('welcome')}}";
    window.myRoute = "{{Request::path()}}";
</script>
