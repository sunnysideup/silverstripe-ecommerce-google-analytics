<?php

class CheckoutPageDataExtension extends DataExtension
{
    /**
     * standard SS variable.
     *
     * @var Array
     */
    private static $db = [
        'EnableGoogleAnalytics' => 'Boolean(1)'
    ];

    /**
     * @var String
     */
    private static $google_analytics_variable ='ga';

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab(
            'Root.Analytics', 
            CheckboxField::create(
                'EnableGoogleAnalytics', 
                'Enable E-commerce Google Analytics.  Make sure it is turned on in your Google Analytics account.'
            )
        );

        return $fields;
    }
}

