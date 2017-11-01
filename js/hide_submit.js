/**
 * @file
 * A JavaScript file for the theme.
 * This file should be used as a template for your other js files.
 * It defines a drupal behavior the "Drupal way".
 *
 */

// JavaScript should be made compatible with libraries other than jQuery by
// wrapping it with an "anonymous closure". See:
// - https://drupal.org/node/1446420
// - http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth

(function ($, Drupal, drupalSettings) {
  'use strict';
 // var timeoutId = null;
  // To understand behaviors, see https://drupal.org/node/756722#behaviors
  Drupal.behaviors.hideSubmitBlockit = {
    attach: function(context) {
      alert('in js');
      //console.log(drupalSettings.hide_submit);
      var timeoutId = null;
      alert("after timeout");
      $('form', context).once('hideSubmitButton', function () {
        alert('in js 0');
        var $form = $(this);
        alert('in js 0');
        
        // Bind to input elements.
        if (drupalSettings.hide_submit.hide_submit_method === 'indicator') {
          // Replace input elements with buttons.
          alert('1 js');
          $('input.form-submit', $form).each(function(index, el) {
            var attrs = {};

            $.each($(this)[0].attributes, function(idx, attr) {
                attrs[attr.nodeName] = attr.nodeValue;
            });

            $(this).replaceWith(function() {
                return $("<button/>", attrs).append($(this).attr('value'));
            });
          });
          // Add needed attributes to the submit buttons.
          $('button.form-submit', $form).each(function(index, el) {
            $(this).addClass('ladda-button button').attr({
              'data-style': drupalSettings.hide_submit.hide_submit_indicator_style,
              'data-spinner-color': drupalSettings.hide_submit.hide_submit_spinner_color,
              'data-spinner-lines': drupalSettings.hide_submit.hide_submit_spinner_lines
            });
          });
          Ladda.bind('.ladda-button', $form, {
            timeout: drupalSettings.hide_submit.hide_submit_reset_time
          });
        }
        else {
          alert('2 js');
          $('input.form-submit, button.form-submit', $form).click(function (e) {
            var el = $(this);
            el.after('<input type="hidden" name="' + el.attr('name') + '" value="' + el.attr('value') + '" />');
            return true;
          });
        }

        // Bind to form submit.
        $('form', context).submit(function (e) {
          alert('3 js');
          var $inp;
          if (!e.isPropagationStopped()) {
            if (drupalSettings.hide_submit.hide_submit_method === 'disable') {
              $('input.form-submit, button.form-submit', $form).attr('disabled', 'disabled').each(function (i) {
                var $button = $(this);
                if (drupalSettings.hide_submit.hide_submit_css) {
                  $button.addClass(drupalSettings.hide_submit.hide_submit_css);
                }
                if (drupalSettings.hide_submit.hide_submit_abtext) {
                  $button.val($button.val() + ' ' + drupalSettings.hide_submit.hide_submit_abtext);
                }
                $inp = $button;
              });

              if ($inp && drupalSettings.hide_submit.hide_submit_atext) {
                $inp.after('<span class="hide-submit-text">' + Drupal.checkPlain(drupalSettings.hide_submit.hide_submit_atext) + '</span>');
              }
            }
            else if (drupalSettings.hide_submit.hide_submit_method !== 'indicator'){
              var pdiv = '<div class="hide-submit-text' + (drupalSettings.hide_submit.hide_submit_hide_css ? ' ' + Drupal.checkPlain(drupalSettings.hide_submit.hide_submit_hide_css) + '"' : '') + '>' + Drupal.checkPlain(drupalSettings.hide_submit.hide_submit_hide_text) + '</div>';
              if (drupalSettings.hide_submit.hide_submit_hide_fx) {
                $('input.form-submit, button.form-submit', $form).addClass(drupalSettings.hide_submit.hide_submit_css).fadeOut(100).eq(0).after(pdiv);
                $('input.form-submit, button.form-submit', $form).next().fadeIn(100);
              }
              else {
                $('input.form-submit, button.form-submit', $form).addClass(drupalSettings.hide_submit.hide_submit_css).hide().eq(0).after(pdiv);
              }
            }
            // Add a timeout to reset the buttons (if needed).
            if (drupalSettings.hide_submit.hide_submit_reset_time) {
              timeoutId = window.setTimeout(function() {
                hideSubmitResetButtons(null, $form);
              }, drupalSettings.hide_submit.hide_submit_reset_time);
            }
          }
          return true;
        });
      });

      // Bind to clientsideValidationFormHasErrors to support clientside validation.
      // $(document).bind('clientsideValidationFormHasErrors', function(event, form) {
        //hideSubmitResetButtons(event, form.form);
      // });

      // Reset all buttons.
      function hideSubmitResetButtons(event, form) {
        alert('4 js');
        // Clear timer.
        window.clearTimeout(timeoutId);
        timeoutId = null;
        switch (drupalSettings.hide_submit.hide_submit_method) {
          case 'disable':
            $('input.' + Drupal.checkPlain(drupalSettings.hide_submit.hide_submit_css) + ', button.' + Drupal.checkPlain(drupalSettings.hide_submit.hide_submit_css), form)
              .each(function (i, el) {
                $(el).removeClass(Drupal.checkPlain(drupalSettings.hide_submit.hide_submit_hide_css))
                  .removeAttr('disabled');
              });
            $('.hide-submit-text', form).remove();
            break;

          case 'indicator':
            Ladda.stopAll();
            break;

          default:
            $('input.' + Drupal.checkPlain(drupalSettings.hide_submit.hide_submit_css) + ', button.' + Drupal.checkPlain(drupalSettings.hide_submit.hide_submit_css), form)
              .each(function (i, el) {
                $(el).stop()
                  .removeClass(Drupal.checkPlain(drupalSettings.hide_submit.hide_submit_hide_css))
                  .show();
              });
            $('.hide-submit-text', form).remove();
        }
      }
    }
  };

})(jQuery, Drupal, drupalSettings);
