<?php

/**
 * @file
 * Contains \Drupal\hide_submit\Form\HideSubmitSettings.
 */

namespace Drupal\hide_submit\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class HideSubmitSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hide_submit_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
   /* $config = $this->config('hide_submit.settings');

    foreach (Element::children($form) as $variable) {
      $config->set($variable, $form_state->getValue($form[$variable]['#parents']));
    }
    $config->save();

    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }

    parent::submitForm($form, $form_state);*/
     $userInputValues = $form_state->getUserInput();
    $config = $this->configFactory->getEditable('hide_submit.settings');
    $config->set('hide_submit_method', $userInputValues['hide_submit_method']);
    $config->set('hide_submit_reset_time', $userInputValues['hide_submit_reset_time']);
    $config->set('hide_submit_abtext', $userInputValues['hide_submit_abtext']);
    $config->set('hide_submit_atext', $userInputValues['hide_submit_atext']);
    $config->set('hide_submit_hide_fx', $userInputValues['hide_submit_hide_fx']);
    $config->set('hide_submit_hide_text', $userInputValues['hide_submit_hide_text']);
    $config->set('hide_submit_indicator_style', $userInputValues['hide_submit_indicator_style']);
    $config->set('hide_submit_spinner_color', $userInputValues['hide_submit_spinner_color']);
    $config->set('hide_submit_spinner_lines', $userInputValues['hide_submit_spinner_lines']);
    $config->save();

    parent::submitForm($form, $form_state);

  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['hide_submit.settings'];
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $form = [];

    $form['hide_submit_method'] = [
      '#type' => 'select',
      '#options' => [
        'disable' => t('Disable the submit buttons.'),
        'hide' => t('Hide the submit buttons.'),
        'indicator' => t('Built-in loading indicator.'),
      ],
      '#default_value' => \Drupal::config('hide_submit.settings')->get('hide_submit_method'),
      '#title' => t('Blocking method'),
      '#description' => t('Choose the blocking method.'),
    ];

    $form['hide_submit_reset_time'] = [
      '#type' => 'textfield',
      '#title' => t('Reset buttons after some time (ms).'),
      '#description' => t('Enter a value in milliseconds after which all buttons will be enabled. To disable this enter 0.'),
      '#default_value' => \Drupal::config('hide_submit.settings')->get('hide_submit_reset_time'),
      '#element_validate' => [
        '_hide_submit_is_numeric'
        ],
      '#required' => TRUE,
    ];

    $form['hide_submit_disable'] = [
      '#type' => 'fieldset',
      '#title' => t('Disabling settings'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    ];

    $form['hide_submit_disable']['hide_submit_abtext'] = [
      '#type' => 'textfield',
      '#title' => t('Append to buttons'),
      '#description' => t('This text will be appended to each of the submit buttons.'),
      '#default_value' => \Drupal::config('hide_submit.settings')->get('hide_submit_abtext'),
    ];

    $form['hide_submit_disable']['hide_submit_atext'] = [
      '#type' => 'textarea',
      '#title' => t('Add next to buttons'),
      '#description' => t('This text will be added next to the submit buttons.'),
      '#default_value' => \Drupal::config('hide_submit.settings')->get('hide_submit_atext'),
    ];

    $form['hide_submit_hide'] = [
      '#type' => 'fieldset',
      '#title' => t('Hiding settings'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    ];

    $form['hide_submit_hide']['hide_submit_hide_fx'] = [
      '#type' => 'checkbox',
      '#title' => t('Use fade effects?'),
      '#default_value' => \Drupal::config('hide_submit.settings')->get('hide_submit_hide_fx'),
      '#description' => t('Enabling a fade in / out effect.'),
    ];

    $form['hide_submit_hide']['hide_submit_hide_text'] = [
      '#type' => 'textfield',
      '#title' => t('Processing text'),
      '#default_value' => \Drupal::config('hide_submit.settings')->get('hide_submit_hide_text'),
      '#description' => t('This text will be shown to the user instead of the submit buttons.'),
    ];

    $form['hide_submit_indicator'] = [
      '#type' => 'fieldset',
      '#title' => t('Indicator settings'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      '#description' => t('Choose the spinner style as defined by the
      <a href="!library" target="_blank" rel="noopener">ladda.js jQuery library
      </a>. Examples of these styles can be found on the <a href="!examples"
      target="_blank" rel="noopener">Ladda example page</a>.', [
        '!library' => '//github.com/hakimel/Ladda',
        '!examples' => '//lab.hakim.se/ladda/',
      ]),
    ];

    $form['hide_submit_indicator']['hide_submit_indicator_style'] = [
      '#type' => 'select',
      '#options' => [
        'expand-left' => t('expand-left'),
        'expand-right' => t('expand-right'),
        'expand-up' => t('expand-up'),
        'expand-down' => t('expand-down'),
        'contract' => t('contract'),
        'contract-overlay' => t('contract-overlay'),
        'zoom-in' => t('zoom-in'),
        'zoom-out' => t('zoom-out'),
        'slide-left' => t('slide-left'),
        'slide-right' => t('slide-right'),
        'slide-up' => t('slide-up'),
        'slide-down' => t('slide-down'),
      ],
      '#default_value' => \Drupal::config('hide_submit.settings')->get('hide_submit_indicator_style'),
      '#title' => t('Built-In Loading Indicator Style'),
    ];

    $form['hide_submit_indicator']['hide_submit_spinner_color'] = [
      '#type' => 'select',
      '#options' => [
        '#000' => t('Black'),
        '#A9A9A9' => t('Dark Grey'),
        '#808080' => t('Grey'),
        '#D3D3D3' => t('Light Grey'),
        '#fff' => t('White'),
      ],
      '#default_value' => \Drupal::config('hide_submit.settings')->get('hide_submit_spinner_color'),
      '#title' => t('Built-In Loading Indicator Spinner Color'),
    ];

    $form['hide_submit_indicator']['hide_submit_spinner_lines'] = [
      '#type' => 'textfield',
      '#title' => t('The number of lines for the spinner'),
      '#default_value' => \Drupal::config('hide_submit.settings')->get('hide_submit_spinner_lines'),
      '#element_validate' => [
        '_hide_submit_is_numeric'
        ],
    ];

    return parent::buildForm($form, $form_state);
  }

}
