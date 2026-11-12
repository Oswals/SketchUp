<script>

    // Check selected custom option
    window.Helpers.initCustomOptionCheck();
    window.alert = function() {};
    

    $(document).ready(function() {

        // DECLARATION DES VARIABLES
        // Email validation
        const emailInput = $('#email');
        const errorDiv = $('<div id="email-error" class="text-danger mt-1" style="font-size: 0.875em;"></div>');
        emailInput.after(errorDiv);

        let typingTimer;
        const doneTypingInterval = 1000;

        // Password validation
        const password_container =$('#password_container');
        const confirm_password_container =$('#confirm_password_container');
        const passwordInput = $('#password');
        const confirmPasswordInput = $('#password_confirmation');
        const passwordError = $('<div id="password-error" class="text-danger mt-1" style="font-size: 0.875em;"></div>');
        const confirmPasswordError = $('<div id="confirm-password-error" class="text-danger mt-1" style="font-size: 0.875em;"></div>');

        password_container.after(passwordError);
        confirm_password_container.after(confirmPasswordError);

        // Password regex pattern
        const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@#$%^&()_+\[\]{}|;:,.<>?])[A-Za-z\d@#$%^&()_+\[\]{}|;:,.<>?]{8,}$/;

        
        function validateRequiredFields() {
            let isValid = true;
            
            // Vérifier tous les champs requis de l'étape courante
            $('.content.active .required').each(function() {
                const field = $(this).closest('.col-sm-6, .col-sm-12').find('input, select, textarea');
                if (!field.val() || field.val().trim() === '') {
                    field.addClass('is-invalid');
                    isValid = false;
                } else {
                    field.removeClass('is-invalid');
                }
            });
            
            return isValid;
        }
        // Fonction qui permet de valider coté JS le mot de passe saisi par l'utilisateur selon l'expression regex passwordRegex
        function validatePassword() {
            const password = passwordInput.val();
            let isValid = true;

            if (!password) {
                passwordError.html('Le mot de passe est requis');
                passwordInput.addClass('is-invalid');
                isValid = false;
            } else if (!passwordRegex.test(password)) {
                // Utilisation de <small> pour un meilleur formatage du texte long
                passwordError.html('<small>Le mot de passe doit contenir au moins :<br>' +
                    '- 8 caractères<br>' +
                    '- Une lettre majuscule<br>' +
                    '- Une lettre minuscule<br>' +
                    '- Un chiffre<br>' +
                    '- Un caractère spécial (@#$%^&()_+[]{}|;:,.<>?)</small>');
                passwordInput.addClass('is-invalid');
                isValid = false;
            } else {
                passwordError.html('');
                passwordInput.removeClass('is-invalid');
            }

            return isValid;
        }

        // Fonction qui permet de valider la correspondance entre le mot de passe et la confirmation de mot de passe
        function validateConfirmPassword() {
            const password = passwordInput.val();
            const confirmPassword = confirmPasswordInput.val();
            let isValid = true;

            if (!confirmPassword) {
                confirmPasswordError.text('La confirmation du mot de passe est requise');
                confirmPasswordInput.addClass('is-invalid');
                isValid = false;
            } else if (password !== confirmPassword) {
                confirmPasswordError.text('Les mots de passe ne correspondent pas');
                confirmPasswordInput.addClass('is-invalid');
                isValid = false;
            } else {
                confirmPasswordError.text('');
                confirmPasswordInput.removeClass('is-invalid');
            }

            return isValid;
        }

        // Fonction qui permet de s'assurer que l'email d'enregistrement n'ai pas utilisé par un utilisateur déjà
        function checkEmail() {
            const email = emailInput.val().trim();
            let isValid = true;

            if (!email) {
                errorDiv.text('L\'email est requis');
                emailInput.addClass('is-invalid');
                isValid = false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                errorDiv.text('Veuillez entrer une adresse email valide');
                emailInput.addClass('is-invalid');
                isValid = false;
            }

            if (isValid) {
                $.ajax({
                    url: '/check-user',
                    method: 'POST',
                    data: {
                        email: email,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.exists) {
                            errorDiv.text('Cette adresse email est déjà utilisée');
                            emailInput.addClass('is-invalid');
                            $('.btn-next').prop('disabled', true);
                            isValid = false;
                        } else {
                            errorDiv.text('');
                            emailInput.removeClass('is-invalid');
                            $('.btn-next').prop('disabled', false);
                        }
                    },
                    error: function() {
                        errorDiv.text('Une erreur est survenue lors de la vérification');
                        isValid = false;
                    }
                });
            }

            return isValid;
        }

        // Fonction qui appel l'ensemble des fonctions de validation pour valider notre formulaire d'inscription
        function validateForm() {
            let isValid = true;
            const activeStep = $('.content.active').attr('id');
            
            if (activeStep === 'account-details-validation' || activeStep === 'personal-info-validation') {
                isValid = validateRequiredFields() && isValid;
                isValid = validatePassword() && isValid;
                isValid = validateConfirmPassword() && isValid;
                isValid = checkEmail() && isValid;
            }
            // Réinitialise les erreurs
            $('.is-invalid').removeClass('is-invalid');
            
            // if (activeStep === 'social-links-validation') {
            //     // Validation spécifique à l'étape 3
            //     const poste = $('[name="poste_id"]').val();
            //     const service = $('[name="service_id"]').val();
                
            //     if (!poste) {
            //         $('[name="poste_id"]').addClass('is-invalid');
            //         isValid = false;
            //     }
                
            //     if (!service) {
            //         $('[name="service_id"]').addClass('is-invalid');
            //         isValid = false;
            //     }
            // }
            if (activeStep === 'affectation-validation') {
                const affectationType = $('input[name="affectation_type"]:checked').val();
                if (!affectationType) {
                    alert('Veuillez sélectionner un type d\'affectation');
                    return false;
                }
                
                if (affectationType === 'parquet' && !$('#parquet_id').val()) {
                    alert('Veuillez sélectionner un parquet');
                    return false;
                }
                
                if (affectationType === 'tribunal' && !$('#tribunal_id').val()) {
                    alert('Veuillez sélectionner un tribunal');
                    return false;
                }
            }
            return isValid;
        }

        // Evenement qui écoute la saisie du champ email pour enclencher la fonction checkEmail
        emailInput.on('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(checkEmail, doneTypingInterval);
        });

        // Evenement qui permet de savoir si l'utlisateur ne saisie plus rien
        emailInput.on('keydown', function() {
            clearTimeout(typingTimer);
        });

        // Exécuter les fonctions de verification si on clique ailleurs en dehors des champs
        emailInput.on('blur', checkEmail);
        passwordInput.on('blur', validatePassword);
        confirmPasswordInput.on('blur', validateConfirmPassword);

        // Fonction pour naviguer entre les étapes
        function navigateToStep(step) {
            const stepper = new Stepper(document.querySelector('.bs-stepper'));
            stepper.to(step);
        }

        // Initialisez le stepper correctement
        var stepper = new Stepper(document.querySelector('.bs-stepper'));
        
        // Stockez l'instance du stepper
        $('.bs-stepper').data('bs-stepper', stepper);

        // Gestionnaire pour tous les boutons Suivant
        $(document).on('click', '.btn-next:not([type="submit"])', function(e) {
            e.preventDefault();
            
            if (validateForm()) {
                var stepper = $('.bs-stepper').data('bs-stepper');
                if (!stepper) {
                    console.error("Stepper non initialisé");
                    return;
                }
                
                // Vérifiez le nombre d'étapes
                if (stepper._steps && stepper._steps.length > 0) {
                    try {
                        if (stepper._currentIndex < stepper._steps.length - 1) {
                            stepper.next();
                        }
                    } catch (error) {
                        console.error("Erreur de navigation:", error);
                    }
                }
            }
        });

        // Gestionnaire pour tous les boutons Précédent
        $(document).on('click', '.btn-prev', function(e) {
            e.preventDefault();
            var stepper = $('.bs-stepper').data('bs-stepper');
            if (stepper && stepper._currentIndex > 0) {
                stepper.previous();
            }
        });

        // Empêcher la soumission du formulaire sauf pour le dernier bouton
        $('#wizard-validation-form').on('submit', function(e) {
            const stepper = $('.bs-stepper').data('bs-stepper');
            if (stepper._currentIndex !== 3) { // Si ce n'est pas la dernière étape
                e.preventDefault();
                return false;
            }
            return true;
        });

        $('#password').next('span').attr('id', 'password-toggle');
        $('#password_confirmation').next('span').attr('id', 'password-confirmation-toggle');

        // Affichage des icon de visualisation ou de non visialisation pour le champs de mot de passe
        $('#password-toggle').on('click', function() {
            const input = $('#password');
            const icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('ti-eye-off').addClass('ti-eye');
            } else {
                input.attr('type', 'password');
                icon.removeClass('ti-eye').addClass('ti-eye-off');
            }
        });
        // Affichage des icon de visualisation ou de non visialisation pour le champs de confirmation de mot de passe
        $('#password-confirmation-toggle').on('click', function() {
            const input = $('#password_confirmation');
            const icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('ti-eye-off').addClass('ti-eye');
            } else {
                input.attr('type', 'password');
                icon.removeClass('ti-eye').addClass('ti-eye-off');
            }
        });

        // Gestionnaire d'événements pour afficher ou masquer le mot de passe
        $(document).on('click', '.input-group-text', function() {
            var passwordInput = $(this).closest('.input-group').find('input[type="password"], input[type="text"]');
            var passwordFieldType = passwordInput.attr('type');
            passwordInput.attr('type', passwordFieldType === 'password' ? 'text' : 'password');
            var icon = $(this).find('i');
            if (passwordFieldType === 'password') {
                icon.removeClass('ti-eye-off').addClass('ti-eye');
            } else {
                icon.removeClass('ti-eye').addClass('ti-eye-off');
            }
        });

        // Initialisation des champs select en select2
        $('.select2').select2({
            placeholder: "Sélectionnez ",
            allowClear: true,
            dropdownParent: $('body')
        });

        $('[data-repeater-create]').click(function() {

            $('.select2').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
            });

            setTimeout(function() {

                $('.select2').select2({
                    placeholder: "Sélectionnez ",
                    allowClear: true,
                    dropdownParent: $('body'),

                    width: '100%'
                });
            }, 100);
        });

        $('<style>')
        .text(`
            .select2-container {
                z-index: 9999;
            }
            .select2-dropdown {
                z-index: 9999;
            }
            .select2-search {
                z-index: 9999;
            }
        `)
        .appendTo('head');
    });
</script>

<script>
    $(document).ready(function() {
        // Gestion des radios boutons
        $('input[name="affectation_type"]').change(function() {
            var selectedValue = $(this).val();
            
            // Masquer les deux selects d'abord
            $('#parquet-select-container').hide();
            $('#tribunal-select-container').hide();
            
            // Afficher le select correspondant
            if (selectedValue === 'parquet') {
                $('#parquet-select-container').show();
                loadParquets();
            } else if (selectedValue === 'tribunal') {
                $('#tribunal-select-container').show();
                loadTribunaux();
            }
        });
        
        // Charger les parquets via AJAX
        function loadParquets() {
            $.ajax({
                url: '{{ route("parquets") }}',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var select = $('#parquet_id');
                    select.empty();
                    select.append('<option value="">Sélectionnez un parquet</option>');
                    
                    $.each(data, function(key, value) {
                        select.append('<option value="' + value.id + '">' + value.nom + ' (' + value.ville + ')</option>');
                    });
                },
                error: function() {
                    $('#parquet_id').empty().append('<option value="">Erreur de chargement</option>');
                }
            });
        }
        
        // Charger les tribunaux via AJAX
        function loadTribunaux() {
            $.ajax({
                url: '{{ route("tribunaux") }}',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var select = $('#tribunal_id');
                    select.empty();
                    select.append('<option value="">Sélectionnez un tribunal</option>');
                    
                    $.each(data, function(key, value) {
                        select.append('<option value="' + value.id + '">' + value.nom + ' (' + value.type + ', ' + value.ville + ')</option>');
                    });
                },
                error: function() {
                    $('#tribunal_id').empty().append('<option value="">Erreur de chargement</option>');
                }
            });
        }
    });
</script>