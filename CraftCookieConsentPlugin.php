<?php
namespace Craft;

/**
 * Class CraftCookieConsentPlugin
 * @author Kisu
 * @package Craft
 */
class CraftCookieConsentPlugin extends BasePlugin
{
    /**
     * @return string
     */
    function getName()
    {
        return Craft::t('Cookie Consent');
    }

    /**
     * @return string
     */
    function getVersion()
    {
        return '1.0.1';
    }

    /**
     * @return string
     */
    function getDeveloper()
    {
        return 'Kisu';
    }

    /**
     * @return string
     */
    function getDeveloperUrl()
    {
        return 'http://keithcod.es';
    }

    /**
     * @return array
     */
    protected function defineSettings(){
        return [
            'position' => [AttributeType::String, 'default' => 'bottom'],
            'layout' => [AttributeType::String, 'default' => 'block'],
            'palette' => [AttributeType::String, 'default' => 'default'],
            'palette_banner' => [AttributeType::String, 'default' => '#000000'],
            'palette_button' => [AttributeType::String, 'default' => '#f1d600'],
            'palette_banner_text' => [AttributeType::String, 'default' => '#ffffff'],
            'palette_button_text' => [AttributeType::String, 'default' => '#000000'],
            'learn_more_link' => [AttributeType::String, 'default' => 'http://cookiesandyou.com/'],
            'message' => [AttributeType::String, 'default' => 'This website uses cookies to ensure you get the best experience on our website.'],
            'dismiss' => [AttributeType::String, 'default' => 'Got it!'],
            'learn' => [AttributeType::String, 'default' => 'Learn More'],

        ];
    }

    /**
     * @return mixed
     */
    public function getSettingsHtml() {
       return craft()->templates->render('craftcookieconsent/_settings', [
           'settings' => $this->getSettings()
       ]);
    }

    function init() {
            if ( !craft()->request->isCpRequest() && !craft()->request->isAjaxRequest) {
                craft()->templates->includeCssResource('craftcookieconsent/cookieconsent.min.css');
                craft()->templates->includeJsResource('craftcookieconsent/cookieconsent.min.js', false);
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
                              "background":  "'. $settings->layout .'" === "wire" ? "transparent" :  "'. $settings->palette_button .'",
                              "text": "'. $settings->layout .'" === "wire" ? "'. $settings->palette_button .'" : "'. $settings->palette_button_text .'",
                              "border":  "'. $settings->layout .'" === "wire" ? "'. $settings->palette_button .'" : undefined
                          }
                      },
                      "position": "'. $settings->position .'" === "toppush" ? "top" : "'. $settings->position .'",
                      "static": "'. $settings->position .'" === "toppush",
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
