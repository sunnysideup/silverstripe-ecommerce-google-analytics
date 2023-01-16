<?php

namespace Sunnysideup\EcommerceGoogleAnalytics;

use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Extension;
use SilverStripe\View\Requirements;
use Sunnysideup\Ecommerce\Config\EcommerceConfig;
use Sunnysideup\Ecommerce\Model\Money\EcommerceCurrency;
use Sunnysideup\Ecommerce\Pages\Product;

class CheckoutPageExtensionController extends Extension
{
    /**
     * Standard SS method.
     * Runs after the Page::init method is called.
     */
    public function onAfterInit()
    {
        $owner = $this->owner;
        if ($owner->EnableGoogleAnalytics && $owner->currentOrder && (Director::isLive() || isset($_GET['testanalytics']))) {
            $var = EcommerceConfig::get(CheckoutPageDataExtension::class, 'google_analytics_variable');
            if ($var) {
                $currencyUsedObject = $owner->currentOrder->CurrencyUsed();
                if ($currencyUsedObject) {
                    $currencyUsedString = $currencyUsedObject->Code;
                }
                if (empty($currencyUsedString)) {
                    $currencyUsedString = EcommerceCurrency::default_currency_code();
                }
                $orderItems = $owner->currentOrder->OrderItems();
                $items = '';
                if (Config::inst()->get(CheckoutPageExtensionController::class, 'include_product_items')) {
                    foreach ($orderItems as $orderItem) {
                        $product = Product::get_by_id($orderItem->BuyableID);
                        $sku = $product->InternalItemID ?: $product->ID;
                        $topParent = $product->TopParentGroup();
                        $catetory = 'Unknown';
                        if($topParent) {
                            $category = $topParent->Title;
                        }
                        $orderItemName = preg_replace("#\r|\n#", '', (string) $orderItem->TableTitle());
                        $category = preg_replace("#\r|\n#", '', (string) $category);
                        $items .=
                            'ga(
                                \'ecommerce:addItem\',
                                {
                                    \'id\': \'' . $owner->currentOrder->ID . '\',
                                    \'name\': \'' . $orderItemName . '\',
                                    \'sku\': \'' . $sku . '\',
                                    \'category\': \'' . $category . '\',
                                    \'price\': \'' . $orderItem->CalculatedTotal . '\',
                                    \'quantity\': \'' . $orderItem->Quantity . '\',
                                }
                            );';
                    }
                }
                $js = '
                jQuery("#OrderForm_OrderForm").on(
                    "submit",
                    function(e){
                        console.log(e);
                        ' . $var . '(\'require\', \'ecommerce\');
                        ' . $var . '(
                            \'ecommerce:addTransaction\',
                            {
                                \'id\': \'' . $owner->currentOrder->ID . '\',
                                \'revenue\': \'' . $owner->currentOrder->getSubTotal() . '\',
                                \'currency\': \'' . $currencyUsedString . '\'
                            }
                        );
                        ' . $items . '
                        ' . $var . '(\'ecommerce:send\');
                    }
                );';
                Requirements::customScript($js, 'GoogleAnalyticsEcommerce');
            }
        }
    }
}
