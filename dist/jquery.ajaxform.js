/**
 *
 */

(function($){

    $.fn.ajaxForm = function(options) {

        var settings = $.extend({
            'debug'             : false,
            'successElement'    : 'div',
            'successClass'      : 'alert alert-success',
            'errorElement'      : 'div',
            'errorClass'        : 'alert alert-danger',
            'formGroupClass'    : 'form-group',
            'formGroupErrClass' : 'has-error',
            'errorTextElement'  : 'span',
            'errorTextClass'    : 'help-block help-block-error small',
            'errorTextRequired' : 'Это поле необходимо заполнить.',
            'loaderElement'     : 'span',
            'loaderClass'       : 'af-loader',
            'url'               : 'lib/mail/mail.php', // url ajax send

        }, options);

        return this.each(function() {

            $(this).find('[aria-required=true]').focus(function(){
                var parent = $(this).parents('.' + settings.formGroupClass);

                if (parent.hasClass(settings.formGroupErrClass)) {
                    parent.removeClass(settings.formGroupErrClass);
                    parent.find('[data-af=error-text]').remove();
                }

            });

            $(this).submit(function(e) {
                e.preventDefault();

                var form = $(this),
                    error = false;


                form.find('[aria-required=true]').each(function(){
                    var parent = $(this).parents('.' + settings.formGroupClass);

                    if ($(this).val() === '') {
                        if(parent.is(':not(.'+ settings.formGroupErrClass +')')) {
                            parent.addClass(settings.formGroupErrClass);

                            $(this).after($('<'+ settings.errorTextElement +' />', {
                                'class': settings.errorTextClass,
                                'data-af': 'error-text',
                                'text': settings.errorTextRequired
                            }));
                        }

                        error = true;
                    }

                });

                if (!error) {
                    jQuery.ajax({
                        type: 'POST',
                        url: settings.url,
                        dataType: 'json',
                        data: {
                            formData: form.serialize()
                        },
                        beforeSend: function() {
                            form.find('[type=submit]').prop('disabled', true).prepend($('<'+settings.loaderElement+' />', {
                                'class': settings.loaderClass,
                                'data-af': 'loader',
                            }));
                        },
                        success: function(response) {
                            if (response['error']) {
                                form.find('[data-af=form-msg]').remove();

                                $('<' + settings.errorElement + ' />', {
                                    'class': settings.errorClass,
                                    'data-af': 'form-msg',
                                    'text': response['error'],
                                }).prependTo(form);

                                if(settings.debug === true && response['debug']) {
                                    console.log(response['debug']);
                                }
                            } else if (response['success']) {
                                form.find('[data-af=form-msg]').remove();
                                form.find('input, textarea').val('');

                                $('<' + settings.successElement + ' />', {
                                    'class': settings.successClass,
                                    'data-af': 'form-msg',
                                    'text': response['success'],
                                }).prependTo(form);

                                if(settings.debug === true && response['debug']) {
                                    console.log(response['debug']);
                                }
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            if(settings.debug === true) {
                                console.log(xhr.status);
                                console.log(thrownError);
                            }
                        },
                        complete: function() {
                            form.find('[type=submit]').prop('disabled', false);
                            form.find('[data-af=loader]').remove();
                         }
                    });

                }

            });

        });

    };

})(jQuery);