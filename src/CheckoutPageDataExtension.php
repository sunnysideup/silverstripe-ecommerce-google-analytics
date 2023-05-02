<?php

namespace Sunnysideup\EcommerceGoogleAnalytics;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;

/**
 * Class \Sunnysideup\EcommerceGoogleAnalytics\CheckoutPageDataExtension
 *
 * @property \Sunnysideup\Ecommerce\Pages\CheckoutPage|\Sunnysideup\EcommerceGoogleAnalytics\CheckoutPageDataExtension $owner
 * @property bool $EnableGoogleAnalytics
 */
class CheckoutPageDataExtension extends DataExtension
{
    /**
     * standard SS variable.
     *
     * @var array
     */
    private static $db = [
        'EnableGoogleAnalytics' => 'Boolean(1)',
    ];

    /**
     * @var string
     */
    private static $google_analytics_variable = 'ga';

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab(
            'Root.Analytics',
            CheckboxField::create(
                'EnableGoogleAnalytics',
                'Enable E-commerce Google Analytics.  Make sure it is turned on in your Google Analytics account.'
            )
        );
    }
}
