define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/set-shipping-information',
    'Magento_Checkout/js/model/totals',
    'mage/url',
    'mage/translate'
], function ($, ko, Component, quote, setShippingInformationAction, totals, url, $t) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Brimar_Shipping/checkout/shipping/brimar-options'
        },

        initialize: function () {
            this._super();
            
            this.selectedOption = ko.observable('');
            this.availableOptions = ko.observableArray([]);
            this.isVisible = ko.observable(false);
            this.isLoading = ko.observable(false);
            this.showValidationError = ko.observable(false); // ONLY ADDED THIS
            this.optionsLoaded = false; // ADDED: Track if options are already loaded

            var self = this;

            // Watch for shipping method changes
            quote.shippingMethod.subscribe(function (method) {
                if (method && method.carrier_code === 'brimar') {
                    // Only load options if not already loaded
                    if (!self.optionsLoaded) {
                        self.loadShippingOptions();
                    }
                    self.isVisible(true);
                    self.showValidationError(false); // ONLY ADDED THIS
                    self.setupNextButtonValidation(); // ONLY ADDED THIS
                    // Add a slight delay to ensure DOM is ready
                    setTimeout(function() {
                        self.insertOptionsIntoShippingMethod();
                    }, 100);
                } else {
                    self.isVisible(false);
                    self.selectedOption('');
                    self.showValidationError(false); // ONLY ADDED THIS
                    self.removeNextButtonValidation(); // ONLY ADDED THIS
                    self.removeOptionsFromShippingMethod();
                    self.optionsLoaded = false; // Reset when switching away
                }
            });

            // Watch for option changes
            this.selectedOption.subscribe(function (optionCode) {
                if (optionCode) {
                    self.showValidationError(false); // ONLY ADDED THIS
                    self.saveOptionToQuote(optionCode);
                }
            });

            // Check current shipping method on load
            var currentMethod = quote.shippingMethod();
            if (currentMethod && currentMethod.carrier_code === 'brimar') {
                this.loadShippingOptions();
                this.isVisible(true);
                this.setupNextButtonValidation(); // ONLY ADDED THIS
                setTimeout(function() {
                    self.insertOptionsIntoShippingMethod();
                }, 100);
            }

            return this;
        },

        // ONLY ADDED THESE THREE FUNCTIONS
        setupNextButtonValidation: function() {
            var self = this;
            
            setTimeout(function() {
                var $nextButton = $('.button.action.continue.primary');
                if ($nextButton.length > 0) {
                    $nextButton.off('click.brimar-validation');
                    
                    $nextButton.on('click.brimar-validation', function(e) {
                        var currentMethod = quote.shippingMethod();
                        if (currentMethod && currentMethod.carrier_code === 'brimar') {
                            if (!self.selectedOption()) {
                                e.preventDefault();
                                e.stopPropagation();
                                e.stopImmediatePropagation();
                                
                                self.showValidationError(true);
                                self.scrollToShippingOptions();
                                
                                return false;
                            }
                        }
                    });
                }
            }, 500);
        },

        removeNextButtonValidation: function() {
            var $nextButton = $('.button.action.continue.primary');
            if ($nextButton.length > 0) {
                $nextButton.off('click.brimar-validation');
            }
        },

        scrollToShippingOptions: function() {
            var $optionsContainer = $('.brimar-shipping-options-inline');
            if ($optionsContainer.length > 0) {
                $('html, body').animate({
                    scrollTop: $optionsContainer.offset().top - 100
                }, 500);
            }
        },
        // END OF ADDED FUNCTIONS

        insertOptionsIntoShippingMethod: function() {
            var self = this;
            
            // Find the Brimar shipping method row
            var $brimarRow = $('input[value="brimar_brimar"]').closest('tr');
            
            if ($brimarRow.length > 0) {
                // Remove any existing options row
                $brimarRow.next('.brimar-options-row').remove();
                
                // Create options row
                var $optionsRow = $('<tr class="brimar-options-row"><td colspan="3"></td></tr>');
                var $optionsContainer = $('<div class="brimar-shipping-options-inline"><div class="shipping-options-container"></div></div>');
                
                $optionsRow.find('td').append($optionsContainer);
                $brimarRow.after($optionsRow);
                
                // Hide the original component
                $('.brimar-shipping-options').hide();
                
                // Render options immediately if available
                this.renderInlineOptions($optionsContainer);
            }
        },

        renderInlineOptions: function($container) {
            var self = this;
            var options = this.availableOptions();
            var $optionsContainer = $container.find('.shipping-options-container');
            
            if (this.isLoading()) {
                $optionsContainer.html('<div class="loading-options"><span>Loading shipping options...</span></div>');
                return;
            }
            
            var html = '';
            
            if (options.length > 0) {
                html += '<div class="options-list">';
                
                // ONLY ADDED THIS ERROR MESSAGE
                html += '<div class="brimar-validation-error" style="color: #e02b27; font-size: 14px; margin-bottom: 10px; padding: 10px; background-color: #ffeaea; border: 1px solid #e02b27; border-radius: 3px; display: none;">';
                html += '<strong>Error:</strong> Please select a shipping option to continue.';
                html += '</div>';
                // END OF ADDED ERROR MESSAGE
                
                for (var i = 0; i < options.length; i++) {
                    var option = options[i];
                    var isChecked = self.selectedOption() === option.code ? 'checked' : '';
                    
                    html += '<div class="shipping-option-item">';
                    html += '<label class="shipping-option-label">';
                    html += '<input type="radio" name="brimar_shipping_option" value="' + option.code + '" ' + isChecked + ' class="shipping-option-radio" />';
                    html += '<span class="option-content">';
                    html += '<span class="option-name">' + option.label + '</span>';
                    html += '<span class="option-price">' + self.getFormattedPrice(option.price) + '</span>';
                    html += '</span>';
                    html += '</label>';
                    html += '</div>';
                }
                html += '</div>';
            } else {
                html = '<div class="no-options-message"><p>No additional shipping options available.</p></div>';
            }
            
            $optionsContainer.html(html);
            this.bindEventsToInlineContent($container);
            
            // ONLY ADDED THIS ERROR HANDLING - but prevent multiple subscriptions
            if (!this.errorSubscriptionAdded) {
                this.errorSubscriptionAdded = true;
                this.showValidationError.subscribe(function(showError) {
                    if (showError) {
                        $container.find('.brimar-validation-error').show();
                    } else {
                        $container.find('.brimar-validation-error').hide();
                    }
                });
            }
            // END OF ADDED ERROR HANDLING
        },

        bindEventsToInlineContent: function($container) {
            var self = this;
            
            $container.find('input[name="brimar_shipping_option"]').off('change').on('change', function() {
                var optionCode = $(this).val();
                var optionData = null;
                
                // Find the option data
                var options = self.availableOptions();
                for (var i = 0; i < options.length; i++) {
                    if (options[i].code === optionCode) {
                        optionData = options[i];
                        break;
                    }
                }
                
                if (optionData) {
                    self.selectedOption(optionCode);
                    self.onOptionChange(optionData);
                }
            });
        },

        removeOptionsFromShippingMethod: function() {
            $('.brimar-options-row').remove();
            $('.brimar-shipping-options').show();
        },

        loadShippingOptions: function () {
            var self = this;
            this.isLoading(true);

            // Update inline display to show loading state
            var $inlineContainer = $('.brimar-shipping-options-inline');
            if ($inlineContainer.length > 0) {
                this.renderInlineOptions($inlineContainer);
            }

            $.ajax({
                url: url.build('brimar/shipping/options'),
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success && response.options) {
                        self.availableOptions(response.options);
                        self.optionsLoaded = true; // Mark as loaded
                    } else {
                        self.availableOptions([]);
                        console.error('No options returned from server');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error loading shipping options:', error);
                    self.availableOptions([]);
                },
                complete: function () {
                    self.isLoading(false);
                    
                    // ONLY render inline options if container exists and options were just loaded for first time
                    var $inlineContainer = $('.brimar-shipping-options-inline');
                    if ($inlineContainer.length > 0 && self.availableOptions().length > 0) {
                        self.renderInlineOptions($inlineContainer);
                    }
                }
            });
        },

        onOptionChange: function (option, event) {
            this.selectedOption(option.code);
            return true;
        },

        // YOUR ORIGINAL WORKING saveOptionToQuote - UNCHANGED
        saveOptionToQuote: function (optionCode) {
            var self = this;
            var selectedOptionData = null;

            // Find the selected option data
            var options = this.availableOptions();
            for (var i = 0; i < options.length; i++) {
                if (options[i].code === optionCode) {
                    selectedOptionData = {
                        code: options[i].code,
                        label: options[i].label,
                        price: options[i].price
                    };
                    break;
                }
            }

            if (!selectedOptionData) {
                console.error('Selected option not found');
                return;
            }

            // Don't show loading spinner to prevent re-rendering
            // totals.isLoading(true);

            $.ajax({
                url: url.build('brimar/shipping/save'),
                type: 'POST',
                data: {
                    option: JSON.stringify(selectedOptionData),
                    form_key: $.mage.cookies.get('form_key')
                },
                success: function (response) {
                    if (response.success) {
                        // Update the shipping method description in the UI
                        self.updateShippingMethodDescription(selectedOptionData.label, selectedOptionData.price);
                        
                        // Update the shipping method label in quote
                        var currentShippingMethod = quote.shippingMethod();
                        if (currentShippingMethod) {
                            currentShippingMethod.method_title = selectedOptionData.label;
                            quote.shippingMethod.valueHasMutated();
                        }
                        
                        // No need for setShippingInformationAction - just save silently
                        console.log('Option saved successfully');
                        
                    } else {
                        console.error('Failed to save option:', response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error saving option:', error);
                }
            });
        },

        updateShippingMethodDescription: function(label, price) {
            // Update the shipping method description in the table
            var $brimarRow = $('input[value="brimar_brimar"]').closest('tr');
            if ($brimarRow.length > 0) {
                var $methodCell = $brimarRow.find('td:eq(1)');
                var $priceCell = $brimarRow.find('td:eq(2)');
                // Update method description
                $methodCell.find('.method-description').text(label);
                // Update price
                $priceCell.text(this.getFormattedPrice(price));
            }
        },

        getFormattedPrice: function (price) {
            return '$' + parseFloat(price).toFixed(2);
        },

        isOptionSelected: function(option) {
            return this.selectedOption() === option.code;
        }
    });
});