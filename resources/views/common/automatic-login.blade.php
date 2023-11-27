@if (isset($hasToken) && $hasToken)
    <script type="application/javascript">
        try {
            const email = "{{ $email }}";
            const password = "{!! $password !!}";

            $('#email').val(email);
            $('#password').val(password);

            $('#signin-form').submit();
        } catch (err) {
            console.log(err);
        }
    </script>
@endif
