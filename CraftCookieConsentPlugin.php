<?php
namespace Craft;

class CraftCookieConsentPlugin extends BasePlugin
{
    function getName()
    {
        return Craft::t('Cookie Consent');
    }

    function getVersion()
    {
        return '1.0.1';
    }

    function getDeveloper()
    {
        return 'Kisu';
    }

    function getDeveloperUrl()
    {
        return 'http://keithcod.es';
    }

    protected function defineSettings(){
        return array(
            'position' => array(AttributeType::String, 'default' => 'bottom'),
            'layout' => array(AttributeType::String, 'default' => 'block'),
            'palette' => array(AttributeType::String, 'default' => 'default'),
            'palette_banner' => array(AttributeType::String, 'default' => '#000000'),
            'palette_button' => array(AttributeType::String, 'default' => '#f1d600'),
            'palette_banner_text' => array(AttributeType::String, 'default' => '#ffffff'),
            'palette_button_text' => array(AttributeType::String, 'default' => '#000000'),
            'learn_more_link' => array(AttributeType::String, 'default' => 'http://cookiesandyou.com/'),
            'message' => array(AttributeType::String, 'default' => 'This website uses cookies to ensure you get the best experience on our website.'),
            'dismiss' => array(AttributeType::String, 'default' => 'Got it!'),
            'learn' => array(AttributeType::String, 'default' => 'Learn More'),


        );
    }

    public function getSettingsHtml() {
       return craft()->templates->render('craftcookieconsent/_settings', array(
           'settings' => $this->getSettings()
       ));
    }

    function init() {

        //if ( !craft()->request->isCpRequest() ) { // we need it to run on the settings page for the plugin as well
            craft()->templates->includeCssResource('craftcookieconsent/cookieconsent.min.css');
            craft()->templates->includeJsResource('craftcookieconsent/cookieconsent.min.js', false);
            if ( !craft()->request->isCpRequest() && !craft()->request->isAjaxRequest) {
                $settings = craft()->plugins->getPlugin('craftcookieconsent')->getSettings();
                $script = '
                  <script>
                    window.addEventListener("load", function(){
                    window.cookieconsent.initialise({
                        "palette": {
                          "popup": {
                            "background": "'. $settings->palette_banner .'",
                            "text": "'. $settings->palette_banner_text .'"
                          },
                          "button": {
                              "background":  "'. $settings->layout .'" == "wire" ? "transparent" :  "'. $settings->palette_button .'",
                              "text": "'. $settings->layout .'" == "wire" ? "'. $settings->palette_button .'" : "'. $settings->palette_button_text .'",
                              "border":  "'. $settings->layout .'" == "wire" ? "'. $settings->palette_button .'" : undefined
                          }
                      },
                      "position": "'. $settings->position .'" == "toppush" ? "top" : "'. $settings->position .'",
                      "static": "'. $settings->position .'" == "toppush",
                      "theme": "'. $settings->layout .'",
                      "content": {
                          "message": "'. Craft::t($settings->message) .'",
                          "dismiss": "'. Craft::t($settings->dismiss) .'",
                          "link": "'. Craft::t($settings->learn) .'",
                          "href": "'. Craft::t($settings->learn_more_link) .'"
                      }
                    })});
                  </script>
                ';
                craft()->templates->includeFootHtml($script, $first = false);
            }
        //}
    }
}
