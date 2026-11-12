
<script>
    
    $(document).ready(function() {
        // Gestionnaire d'événements pour afficher ou masquer le mot de passe
        $(document).on('click', '.input-group-text', function() {
            var passwordInput = $(this).closest('.input-group').find('input[type="password"], input[type="text"]');
            var passwordFieldType = passwordInput.attr('type');
            passwordInput.attr('type', passwordFieldType === 'password' ? 'text' : 'password');
            var icon = $(this).find('i');
            if (passwordFieldType === 'password') {
                icon.removeClass('ti-eye-off').addClass('ti-eye'); // Icône "œil ouvert"
            } else {
                icon.removeClass('ti-eye').addClass('ti-eye-off'); // Icône "œil barré"
            }
        });
    });

    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>
