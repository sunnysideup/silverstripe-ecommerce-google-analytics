<?php

class CheckoutPageDataExtension extends DataExtension
{
    /**
     * standard SS variable.
     *
     * @var Array
     */

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * OLD: private static $db (case sensitive)
  * NEW: 
    private static $table_name = '[SEARCH_REPLACE_CLASS_NAME_GOES_HERE]';

    private static $db (COMPLEX)
  * EXP: Check that is class indeed extends DataObject and that it is not a data-extension!
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
    
    private static $table_name = 'CheckoutPageDataExtension';

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


