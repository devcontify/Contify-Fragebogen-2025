/* Contify PDF Fragebogen 2025 - Frontend JavaScript */

jQuery(document).ready(function($) {
    'use strict';
    
    const ConFra2025 = {
        form: null,
        totalSections: 5,
        completedSections: 0,
        
        init: function() {
            this.form = $('#conFra2025Form');
            this.bindEvents();
            this.initProgressTracking();
            this.openFirstSection();
        },
        
        bindEvents: function() {
            this.form.on('submit', this.handleFormSubmit.bind(this));
            
            $(document).on('change', 'input, textarea, select', this.updateProgress.bind(this));
            
            window.toggleSection = this.toggleSection.bind(this);
            window.toggleTextarea = this.toggleConditionalField.bind(this);
            window.toggleInput = this.toggleConditionalField.bind(this);
        },
        
        openFirstSection: function() {
            this.toggleSection(1);
        },
        
        toggleSection: function(sectionNumber) {
            const section = $('[data-section="' + sectionNumber + '"]');
            const header = section.find('.section-header');
            const content = section.find('.section-content');
            const icon = header.find('.toggle-icon');
            
            if (content.hasClass('active')) {
                content.removeClass('active').slideUp(300);
                header.removeClass('active');
                icon.text('+');
            } else {
                content.addClass('active').slideDown(300);
                header.addClass('active');
                icon.text('−');
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
            }
        },
        
        initProgressTracking: function() {
            this.updateProgress();
        },
        
        updateProgress: function() {
            let totalFields = 0;
            let filledFields = 0;
            
            this.form.find('input, textarea, select').each(function() {
                const $field = $(this);
                const type = $field.attr('type');
                
                if (type === 'hidden' || $field.attr('name') === 'con_fra_2025_nonce' || $field.attr('name') === 'con_fra_2025_submit') {
                    return;
                }
                
                totalFields++;
                
                if (type === 'checkbox' || type === 'radio') {
                    if ($field.is(':checked')) {
                        filledFields++;
                    }
                } else {
                    if ($field.val() && $field.val().trim() !== '') {
                        filledFields++;
                    }
                }
            });
            
            const percentage = Math.round((filledFields / totalFields) * 100);
            
            $('#progressFill').css('width', percentage + '%');
            $('#progressText').text(percentage + '% ' + conFra2025Ajax.messages.completed || 'abgeschlossen');
        },
        
        validateForm: function() {
            let isValid = true;
            const requiredFields = this.form.find('input[required], textarea[required], select[required]');
            
            this.clearErrors();
            
            requiredFields.each(function() {
                const $field = $(this);
                const $group = $field.closest('.form-group');
                
                if (!$field.val() || $field.val().trim() === '') {
                    isValid = false;
                    $group.addClass('error');
                    
                    if (!$group.find('.error-message').length) {
                        $group.append('<span class="error-message">' + conFra2025Ajax.messages.required + '</span>');
                    }
                }
            });
            
            const radioGroups = ['address_type', 'intonation_type', 'keywords_type'];
            radioGroups.forEach(function(groupName) {
                const $radioGroup = $('input[name="' + groupName + '"]');
                if ($radioGroup.length > 0 && !$radioGroup.is(':checked')) {
                    const $group = $radioGroup.closest('.form-group');
                    $group.addClass('error');
                    
                    if (!$group.find('.error-message').length) {
                        $group.append('<span class="error-message">' + conFra2025Ajax.messages.required + '</span>');
                    }
                    isValid = false;
                }
            });
            
            if (!isValid) {
                this.scrollToFirstError();
            }
            
            return isValid;
        },
        
        clearErrors: function() {
            this.form.find('.form-group.error').removeClass('error');
            this.form.find('.error-message').remove();
        },
        
        scrollToFirstError: function() {
            const firstError = this.form.find('.form-group.error').first();
            if (firstError.length) {
                const section = firstError.closest('.form-section');
                const sectionNumber = section.data('section');
                
                if (!section.find('.section-content').hasClass('active')) {
                    this.toggleSection(sectionNumber);
                }
                
                $('html, body').animate({
                    scrollTop: firstError.offset().top - 100
                }, 500);
            }
        },
        
        handleFormSubmit: function(e) {
            e.preventDefault();
            
            if (!this.validateForm()) {
                this.showMessage(conFra2025Ajax.messages.error, 'error');
                return;
            }
            
            this.setSubmitState(true);
            
            const formData = new FormData(this.form[0]);
            formData.append('action', 'con_fra_2025_submit');
            
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
                this.showMessage(response.data.message || conFra2025Ajax.messages.success, 'success');
                this.form[0].reset();
                this.updateProgress();
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
                $buttonText.text(conFra2025Ajax.messages.submitting);
                $buttonSpinner.show();
            } else {
                $button.prop('disabled', false);
                $buttonText.text($buttonText.data('original-text') || 'Fragebogen senden');
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
    
    ConFra2025.init();
    
    $(document).on('change', 'input[name="address_type"]', function() {
        $('input[name="address_du"], input[name="address_sie"], input[name="address_neutral"]').prop('checked', false);
        
        const value = $(this).val();
        if (value === 'du') {
            $('input[name="address_du"]').prop('checked', true);
        } else if (value === 'sie') {
            $('input[name="address_sie"]').prop('checked', true);
        } else if (value === 'neutral') {
            $('input[name="address_neutral"]').prop('checked', true);
        }
    });
    
    $(document).on('change', 'input[name="intonation_type"]', function() {
        $('input[name="intonation_wir"], input[name="intonation_ich"], input[name="intonation_firma_de"], input[name="intonation_firma"], input[name="intonation_neutral"]').prop('checked', false);
        
        const value = $(this).val();
        if (value === 'wir') {
            $('input[name="intonation_wir"]').prop('checked', true);
        } else if (value === 'ich') {
            $('input[name="intonation_ich"]').prop('checked', true);
        } else if (value === 'firma_de') {
            $('input[name="intonation_firma_de"]').prop('checked', true);
        } else if (value === 'firma') {
            $('input[name="intonation_firma"]').prop('checked', true);
        } else if (value === 'neutral') {
            $('input[name="intonation_neutral"]').prop('checked', true);
        }
    });
    
    $(document).on('change', 'input[name="keywords_type"]', function() {
        $('input[name="keywords_provided"], input[name="keywords_research"]').prop('checked', false);
        
        const value = $(this).val();
        if (value === 'provided') {
            $('input[name="keywords_provided"]').prop('checked', true);
        } else if (value === 'research') {
            $('input[name="keywords_research"]').prop('checked', true);
        }
    });
    
    $('input[type="text"], textarea').on('input', function() {
        const $group = $(this).closest('.form-group');
        if ($group.hasClass('error') && $(this).val().trim() !== '') {
            $group.removeClass('error');
            $group.find('.error-message').remove();
        }
    });
    
    $('input[type="checkbox"], input[type="radio"]').on('change', function() {
        const $group = $(this).closest('.form-group');
        if ($group.hasClass('error')) {
            $group.removeClass('error');
            $group.find('.error-message').remove();
        }
    });
});