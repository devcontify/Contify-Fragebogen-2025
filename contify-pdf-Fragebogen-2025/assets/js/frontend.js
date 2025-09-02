/* contify PDF Fragebogen 2025 - Frontend JavaScript */

jQuery(document).ready(function($) {
    'use strict';

    const ConFra2025Wizard = {
        form: null,
        totalSteps: 0,
        currentStep: 1,

        init: function() {
            this.form = $('#conFra2025Form');
            if (this.form.length === 0) {
                return;
            }
            this.totalSteps = this.form.find('.form-section').length;
            this.bindEvents();
            this.showStep(this.currentStep, false); // Don't push state on initial load

            // Handle browser back/forward
            window.addEventListener('popstate', (event) => {
                const urlParams = new URLSearchParams(window.location.search);
                const step = parseInt(urlParams.get('step')) || 1;
                this.showStep(step, false);
            });
        },

        bindEvents: function() {
            this.form.on('submit', this.handleFormSubmit.bind(this));
            this.form.find('.button-next').on('click', this.nextStep.bind(this));
            this.form.find('.button-prev').on('click', this.prevStep.bind(this));

            // Conditional fields logic
            window.toggleTextarea = this.toggleConditionalField.bind(this);
            window.toggleInput = this.toggleConditionalField.bind(this);

            // Remove error on input change
            this.form.find('input, textarea, select').on('input change', function() {
                const $group = $(this).closest('.form-group');
                if ($group.hasClass('error')) {
                    $group.removeClass('error');
                    $group.find('.error-message').remove();
                }
            });
        },

        showStep: function(stepNum, pushState = true) {
            if (stepNum < 1 || stepNum > this.totalSteps) {
                return;
            }
            this.currentStep = stepNum;

            this.form.find('.form-section').removeClass('active').hide();
            this.form.find('.form-section[data-section="' + stepNum + '"]').fadeIn(300).addClass('active');
            
            this.updateProgress();
            this.updateStepIndicators();
            this.scrollToTop();

            if (pushState) {
                const url = new URL(window.location);
                url.searchParams.set('step', stepNum);
                history.pushState({step: stepNum}, `Step ${stepNum}`, url);
            }
        },

        nextStep: function() {
            if (this.validateStep(this.currentStep)) {
                if (this.currentStep < this.totalSteps) {
                    this.showStep(this.currentStep + 1);
                }
            }
        },

        prevStep: function() {
            if (this.currentStep > 1) {
                this.showStep(this.currentStep - 1);
            }
        },

        updateProgress: function() {
            const percentage = ((this.currentStep - 1) / (this.totalSteps - 1)) * 100;
            $('#progressFill').css('width', percentage + '%');
            $('#progressText').text(
                `${conFra2025Ajax.messages.step || 'Schritt'} ${this.currentStep} ${conFra2025Ajax.messages.of || 'von'} ${this.totalSteps}`
            );
        },
        
        updateStepIndicators: function() {
            $('.step-indicator').removeClass('active completed');
            for (let i = 1; i <= this.totalSteps; i++) {
                const indicator = $('.step-indicator[data-step="' + i + '"]');
                if (i < this.currentStep) {
                    indicator.addClass('completed');
                } else if (i === this.currentStep) {
                    indicator.addClass('active');
                }
            }
        },

        validateStep: function(stepNum) {
            let isValid = true;
            const currentSection = this.form.find('.form-section[data-section="' + stepNum + '"]');
            
            this.clearErrors(currentSection);

            // Validate text, textarea, and select fields
            currentSection.find('input[required], textarea[required], select[required]').not('[type="radio"]').not('[type="checkbox"]').each(function() {
                const $field = $(this);
                if ($field.is(':visible') && (!$field.val() || $field.val().trim() === '')) {
                    isValid = false;
                    const $group = $field.closest('.form-group');
                    $group.addClass('error');
                    if ($group.find('.error-message').length === 0) {
                        $group.append('<span class="error-message">' + conFra2025Ajax.messages.required + '</span>');
                    }
                }
            });

            // Validate required radio button groups
            const radioGroups = {};
            currentSection.find('input[type="radio"][required]').each(function() {
                radioGroups[this.name] = true;
            });

            for (const groupName in radioGroups) {
                if (currentSection.find('input[name="' + groupName + '"]:checked').length === 0) {
                    isValid = false;
                    const $group = currentSection.find('input[name="' + groupName + '"]').closest('.form-group');
                    $group.addClass('error');
                    if ($group.find('.error-message').length === 0) {
                        $group.append('<span class="error-message">' + conFra2025Ajax.messages.required + '</span>');
                    }
                }
            }
            
            if (!isValid) {
                this.scrollToFirstError(currentSection);
            }
            
            return isValid;
        },

        clearErrors: function(container) {
            container.find('.form-group.error').removeClass('error');
            container.find('.error-message').remove();
        },
        
        scrollToFirstError: function(container) {
            const firstError = container.find('.form-group.error').first();
            if (firstError.length) {
                $('html, body').animate({
                    scrollTop: firstError.offset().top - 100
                }, 500);
            }
        },

        toggleConditionalField: function(fieldId, show) {
            const wrapper = $('#' + fieldId + '_wrapper');
            const field = $('#' + fieldId);

            if (show) {
                wrapper.slideDown(300);
                field.prop('required', true);
            } else {
                wrapper.slideUp(300);
                field.prop('required', false).val('');
                const $group = field.closest('.form-group');
                if ($group.hasClass('error')) {
                    $group.removeClass('error');
                    $group.find('.error-message').remove();
                }
            }
        },

        handleFormSubmit: function(e) {
            e.preventDefault();
            
            // Final validation of all steps before submitting
            for (let i = 1; i <= this.totalSteps; i++) {
                if (!this.validateStep(i)) {
                    this.showStep(i);
                    this.showMessage(conFra2025Ajax.messages.error, 'error');
                    return;
                }
            }

            this.setSubmitState(true);

            const formData = new FormData(this.form[0]);
            formData.append('action', 'con_fra_2025_submit');
            formData.append('nonce', conFra2025Ajax.nonce);

            $.ajax({
                url: conFra2025Ajax.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: this.handleSubmitSuccess.bind(this),
                error: this.handleSubmitError.bind(this)
            });
        },

        handleSubmitSuccess: function(response) {
            this.setSubmitState(false);
            if (response.success) {
                this.form.parent().find('.con-fra-form-header').hide();
                this.form.replaceWith('<div class="con-fra-success-message">' + (response.data.message || conFra2025Ajax.messages.success) + '</div>');
                this.scrollToTop();
            } else {
                this.showMessage(response.data.message || conFra2025Ajax.messages.error, 'error');
            }
        },

        handleSubmitError: function() {
            this.setSubmitState(false);
            this.showMessage(conFra2025Ajax.messages.error, 'error');
        },

        setSubmitState: function(isSubmitting) {
            const $button = $('#submitButton');
            const $buttonText = $button.find('.button-text');
            const $buttonSpinner = $button.find('.button-spinner');

            if (isSubmitting) {
                $button.prop('disabled', true);
                $buttonText.hide();
                $buttonSpinner.show();
            } else {
                $button.prop('disabled', false);
                $buttonText.show();
                $buttonSpinner.hide();
            }
        },

        showMessage: function(message, type) {
            const $messages = $('#formMessages');
            $messages
                .removeClass('success error')
                .addClass(type)
                .html(message)
                .slideDown(300);

            setTimeout(function() {
                $messages.slideUp(300);
            }, 5000);
        },

        scrollToTop: function() {
            $('html, body').animate({
                scrollTop: $('.con-fra-2025-container').offset().top - 50
            }, 500);
        }
    };

    ConFra2025Wizard.init();
});