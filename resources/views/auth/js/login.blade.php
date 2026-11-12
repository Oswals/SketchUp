<script>
    $(document).ready(function() {
        let isFirstLogin = false;
        let userExists = false;

        // Fonction pour afficher les alertes
        function showAlert(type, message) {
            // Supprimer les alertes existantes
            $('.alert').remove();
            
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
            
            $('.card-body').prepend(alertHtml);
            
            // Auto-fermeture après 5 secondes
            setTimeout(function() {
                $('.alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);
        }

        // Fonction pour vérifier l'utilisateur
        function checkUser(email) {
            if (!email || !email.includes('@')) {
                $('#password-fields').empty();
                userExists = false;
                return;
            }

            $.ajax({
                type: 'POST',
                url: '{{ route("check.user") }}',
                data: {
                    email: email,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                beforeSend: function() {
                    // Afficher un indicateur de chargement
                    $('#password-fields').html('<div class="text-center"><span class="spinner-border spinner-border-sm" role="status"></span> Vérification...</div>');
                },
                success: function(response) {
                    if (response.exists) {
                        userExists = true;
                        
                        // Vérifier le statut du compte
                        if (response.status === 2) {
                            showAlert('warning', 'Votre compte est en attente de validation par un administrateur.');
                            $('#password-fields').empty();
                            return;
                        }
                        
                        if (response.status === 0) {
                            showAlert('danger', 'Votre compte a été désactivé. Veuillez contacter l\'administrateur.');
                            $('#password-fields').empty();
                            return;
                        }

                        isFirstLogin = response.cleanPassword;
                        
                        var passwordFields = '';
                        
                        if (isFirstLogin) {
                            // Premier login - Définir un nouveau mot de passe
                            passwordFields = `
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <i class="ti ti-info-circle me-2"></i>
                                    <strong>Première connexion !</strong> Veuillez définir votre mot de passe de connexion.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                
                                <div class="mb-3 form-password-toggle">
                                    <label class="form-label" for="password">Nouveau mot de passe</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password"
                                            id="password"
                                            class="form-control"
                                            name="password"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            required
                                            minlength="8"
                                            aria-describedby="password" />
                                        <span class="input-group-text cursor-pointer">
                                            <i class="ti ti-eye-off"></i>
                                        </span>
                                    </div>
                                    <small class="text-muted">Minimum 8 caractères</small>
                                </div>

                                <div class="mb-3 form-password-toggle">
                                    <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password"
                                            id="password_confirmation"
                                            class="form-control"
                                            name="password_confirmation"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            required
                                            aria-describedby="password_confirmation" />
                                        <span class="input-group-text cursor-pointer">
                                            <i class="ti ti-eye-off"></i>
                                        </span>
                                    </div>
                                </div>`;
                        } else {
                            // Login normal
                            passwordFields = `
                                <div class="mb-3 form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="password">Mot de passe</label>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <input type="password"
                                            id="password"
                                            class="form-control"
                                            name="password"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            required
                                            aria-describedby="password" />
                                        <span class="input-group-text cursor-pointer">
                                            <i class="ti ti-eye-off"></i>
                                        </span>
                                    </div>
                                </div>`;
                        }
                        
                        $('#password-fields').html(passwordFields);
                        
                        // Ajouter la validation en temps réel pour le premier login
                        if (isFirstLogin) {
                            setupPasswordValidation();
                        }
                        
                    } else {
                        userExists = false;
                        showAlert('danger', 'Aucun compte trouvé avec cet email.');
                        $('#password-fields').empty();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur AJAX:', error);
                    showAlert('danger', 'Une erreur est survenue. Veuillez réessayer.');
                    $('#password-fields').empty();
                    userExists = false;
                }
            });
        }

        // Fonction pour configurer la validation des mots de passe
        function setupPasswordValidation() {
            const password = $('#password');
            const passwordConfirmation = $('#password_confirmation');

            passwordConfirmation.on('input', function() {
                if (password.val() !== $(this).val()) {
                    $(this).addClass('is-invalid');
                    $(this).removeClass('is-valid');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                }
            });

            password.on('input', function() {
                if ($(this).val().length < 8) {
                    $(this).addClass('is-invalid');
                    $(this).removeClass('is-valid');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                    
                    // Revalider la confirmation si elle a été remplie
                    if (passwordConfirmation.val()) {
                        passwordConfirmation.trigger('input');
                    }
                }
            });
        }

        // Vérifier l'utilisateur lors de la saisie dans le champ email
        $('#email').on('blur', function() {
            const email = $(this).val().trim();
            checkUser(email);
        });

        // Vérifier aussi lors du changement
        $('#email').on('change', function() {
            const email = $(this).val().trim();
            checkUser(email);
        });

        // Toggle password visibility
        $(document).on('click', '.input-group-text.cursor-pointer', function() {
            const input = $(this).closest('.input-group').find('input');
            const icon = $(this).find('i');
            
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('ti-eye-off').addClass('ti-eye');
            } else {
                input.attr('type', 'password');
                icon.removeClass('ti-eye').addClass('ti-eye-off');
            }
        });

        // Validation du formulaire avant soumission
        $('#login-form').on('submit', function(e) {
            if (!userExists) {
                e.preventDefault();
                showAlert('danger', 'Veuillez entrer un email valide.');
                $('#email').focus();
                return false;
            }

            // Validation supplémentaire pour le premier login
            if (isFirstLogin) {
                const password = $('#password').val();
                const passwordConfirmation = $('#password_confirmation').val();

                if (password.length < 8) {
                    e.preventDefault();
                    showAlert('danger', 'Le mot de passe doit contenir au moins 8 caractères.');
                    $('#password').focus();
                    return false;
                }

                if (password !== passwordConfirmation) {
                    e.preventDefault();
                    showAlert('danger', 'Les mots de passe ne correspondent pas.');
                    $('#password_confirmation').focus();
                    return false;
                }
            }

            // Désactiver le bouton de soumission pour éviter les doubles clics
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true);
            submitBtn.html('<span class="spinner-border spinner-border-sm me-2" role="status"></span>Connexion...');
            
            return true;
        });

        // Charger les champs de mot de passe au chargement de la page si l'email est prérempli
        const initialEmail = $('#email').val();
        if (initialEmail) {
            checkUser(initialEmail);
        }

        // Support de Feather icons si disponible
        $(window).on('load', function() {
            if (typeof feather !== 'undefined') {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        });
    });

    // Style CSS pour la validation
    const style = document.createElement('style');
    style.textContent = `
        .is-invalid {
            border-color: #dc3545 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            padding-right: calc(1.5em + 0.75rem);
        }
        
        .is-valid {
            border-color: #28c76f !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328c76f' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            padding-right: calc(1.5em + 0.75rem);
        }
        
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.2em;
        }
    `;
    document.head.appendChild(style);
</script>