<?php


/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD:  extends DataExtension (ignore case)
  * NEW:  extends DataExtension (COMPLEX)
  * EXP: Check for use of $this->anyVar and replace with $this->anyVar[$this->owner->ID] or consider turning the class into a trait
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
class AnalyticsTransactionReversal extends DataExtension
{

    private static $test_mode = false;

    private static $analytics_code = false;

    public function onBeforeArchive($order)
    {
        //reverse the transaction if it has been cancelled
        if ($this->isAnalyticsEnabled() && $order->IsCancelled()) {
            $orderID = $order->ID;
            $memberID = $order->MemberID;
            $total = $this->negateValue($order->getSubTotal());
            

            $analytics = new Analytics();
            $analytics->setProtocolVersion('1')
                ->setTrackingId($this->getTrackingID())
                ->setUserId(base64_encode($memberID));

            $transactionResponse = $analytics
                ->setTransactionId($orderID) // transaction id. required
                ->setRevenue($total)
                ->setDebug($this->isTestMode())
                ->sendTransaction();

            $orderItems = $order->OrderItems();
            foreach ($orderItems as $orderItem) {
                $orderItemResponse = $analytics->setTransactionId($orderID) 
                    ->setItemName($orderItem->TableTitle()) // required
                    ->setItemPrice($orderItem->CalculatedTotal)
                    ->setItemQuantity($this->negateValue($orderItem->Quantity))
                    ->setDebug($this->isTestMode())
                    ->sendItem();
            }
        }
    }

    public function negateValue($value){
        return $value * -1;
    }

    public function isTestMode()
    {
        return Config::inst()->get(AnalyticsTransactionReversal::class, 'test_mode');
    }

    public function isAnalyticsEnabled()
    {
        $checkoutPage = CheckoutPage::get()->first();
        return $checkoutPage->EnableGoogleAnalytics && (Director::isLive() || $this->isTestMode());
    }

    public function getTrackingID()
    {
        return Config::inst()->get(AnalyticsTransactionReversal::class, 'analytics_code');
    }

}
