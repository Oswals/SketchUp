<style>

    .required::after {
        content: " *";
        color: red;
        font-weight: bold;
    }

    .modal-xl {
            /* max-width: 60% !important; */
            margin: 1.75rem auto;
        }


        .bs-stepper {
            max-width: 100%;
            overflow-x: hidden;
        }

        .bs-stepper-header {
            flex-wrap: wrap;
            padding: 1rem 0;
        }


    @media (max-width: 1800px) {
        .bs-stepper .bs-stepper-header .step .step-trigger {
            padding: 2px 0rem;
            flex-wrap: nowrap;
            gap: 1rem;
            font-weight: 500;
        }
        .bs-stepper-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .step {
            width: auto;
            min-width: 300px;
            max-width: 80%;
            margin-bottom: 1rem;
            display: flex;
            justify-content: center;
        }

        .step .step-trigger {
            width: 100%;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .bs-stepper-circle {
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .bs-stepper-label {
            flex-grow: 1;
        }

        .line {
            display: none;
        }
    }


    /* Point de rupture pour passer en mode vertical */
    @media (max-width: 1200px) {

        .bs-stepper .bs-stepper-header .step .step-trigger {
            padding: 2px 0rem;
            flex-wrap: nowrap;
            gap: 1rem;
            font-weight: 500;
        }
        .bs-stepper-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .step {
            width: auto; /* Permet au step de s'ajuster à son contenu */
            min-width: 300px; /* Largeur minimale pour la lisibilité */
            max-width: 80%; /* Empêche les steps d'être trop larges */
            margin-bottom: 1rem;
            display: flex;
            justify-content: center; /* Centre le contenu du step */
        }

        .step .step-trigger {
            width: 100%;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: flex-start; /* Aligne le contenu à gauche dans le step */
        }

        .bs-stepper-circle {
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .bs-stepper-label {
            flex-grow: 1;
        }

        .line {
            display: none;
        }

    }

    /* Ajustements pour petits écrans */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 0.5rem;
        }

        .modal-xl {
            max-width: 100% !important;
            margin: 0.5rem;
        }

        .row.g-6 {
            margin: 0;
        }

        .col-sm-6, .col-sm-12 {
            padding: 0.5rem;
        }

        .step {
            min-width: 250px; /* Réduit la largeur minimale sur mobile */
            max-width: 90%; /* Augmente la largeur maximale sur mobile */
        }

        .btn-prev, .btn-next {
            width: 100%;
            margin: 0.25rem 0;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 0.5rem;
        }
    }

    /* Ajustements pour les champs de formulaire */
    .form-control, .form-select {
        max-width: 100%;
    }

    /* Optimisation pour tablettes */
    @media (min-width: 769px) and (max-width: 1024px) {
        .modal-xl {
            max-width: 90% !important;
        }

        .row.g-6 {
            margin: 0 -0.5rem;
        }

        .col-sm-6, .col-sm-12 {
            padding: 0.5rem;
        }
    }

    /* Amélioration de l'accessibilité et de la lisibilité */
    .form-label {
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .bs-stepper-title {
        font-size: 1rem;
        font-weight: 600;
    }

    .bs-stepper-subtitle {
        font-size: 0.875rem;
        opacity: 0.8;
    }

    /* Styles de transition */
    .bs-stepper-header {
        transition: all 0.3s ease;
    }

    .step {
        transition: all 0.3s ease;
    }

    
        .custom-option-icon {
            position: relative;
        }

        .custom-option-content {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            display: block;
            position: relative;
        }

        .custom-option-content:hover {
            border-color: #0305a2;
            box-shadow: 0 2px 8px rgba(var(--bs-primary-rgb), 0.1);
        }

        /* État sélectionné - utilise :has() pour détecter l'input coché */
        .custom-option-icon:has(input[type="radio"]:checked) .custom-option-content {
            border-color: #0305a2;
            background: linear-gradient(135deg, 
                color-mix(in srgb, #0305a2 5%, white) 0%, 
                color-mix(in srgb, #0305a2 15%, white) 100%);
            box-shadow: 0 6px 20px color-mix(in srgb, #0305a2 20%, transparent);
            transform: translateY(-3px);
        }

        /* Icône de validation */
        .custom-option-icon:has(input[type="radio"]:checked) .custom-option-content::before {
            content: '✓';
            position: absolute;
            top: 10px;
            right: 15px;
            background: #0305a2;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        }

        /* Style du titre quand sélectionné */
        .custom-option-icon:has(input[type="radio"]:checked) .custom-option-content .custom-option-title {
            color: #0305a2;
            font-weight: 600;
        }

        /* Fallback pour les navigateurs qui ne supportent pas :has() */
        @supports not selector(:has(*)) {
            .custom-option-icon input[type="radio"]:checked {
                /* Déclenche un repaint pour forcer la mise à jour */
                opacity: 0.99;
            }
        }

</style>
