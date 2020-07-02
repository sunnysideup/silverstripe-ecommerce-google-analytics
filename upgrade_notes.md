2020-07-02 02:21

# running php upgrade upgrade see: https://github.com/silverstripe/silverstripe-upgrader
cd /c/Users/PC/Documents/www/upgrades/ecommerce-google-analytics
php /c/Users/PC/Documents/www/upgrades/upgrader_tool/vendor/silverstripe/upgrader/bin/upgrade-code upgrade /c/Users/PC/Documents/www/upgrades/ecommerce-google-analytics/ecommerce-google-analytics  --root-dir=/c/Users/PC/Documents/www/upgrades/ecommerce-google-analytics --write -vvv
Writing changes for 5 files
Running upgrades on "/c/Users/PC/Documents/www/upgrades/ecommerce-google-analytics/ecommerce-google-analytics"
[2020-07-02 14:21:28] Applying RenameClasses to AnalyticsTransactionReversal.php...
[2020-07-02 14:21:29] Applying ClassToTraitRule to AnalyticsTransactionReversal.php...
[2020-07-02 14:21:29] Applying RenameClasses to CheckoutPageDataExtension.php...
[2020-07-02 14:21:29] Applying ClassToTraitRule to CheckoutPageDataExtension.php...
[2020-07-02 14:21:29] Applying RenameClasses to CheckoutPageExtensionController.php...
[2020-07-02 14:21:29] Applying ClassToTraitRule to CheckoutPageExtensionController.php...
[2020-07-02 14:21:29] Applying UpdateConfigClasses to config.yml...
[2020-07-02 14:21:29] Applying UpdateConfigClasses to routes.yml...
PHP Warning:  Invalid argument supplied for foreach() in /c/Users/PC/Documents/www/upgrades/upgrader_tool/vendor/silverstripe/upgrader/src/UpgradeRule/YML/YMLUpgradeRule.php on line 32
PHP Stack trace:
PHP   1. {main}() /c/Users/PC/Documents/www/upgrades/upgrader_tool/vendor/silverstripe/upgrader/bin/upgrade-code:0
PHP   2. Symfony\Component\Console\Application->run() /c/Users/PC/Documents/www/upgrades/upgrader_tool/vendor/silverstripe/upgrader/bin/upgrade-code:55
PHP   3. Symfony\Component\Console\Application->doRun() /c/Users/PC/Documents/www/upgrades/upgrader_tool/vendor/symfony/console/Application.php:147
PHP   4. Symfony\Component\Console\Application->doRunCommand() /c/Users/PC/Documents/www/upgrades/upgrader_tool/vendor/symfony/console/Application.php:271
PHP   5. SilverStripe\Upgrader\Console\UpgradeCommand->run() /c/Users/PC/Documents/www/upgrades/upgrader_tool/vendor/symfony/console/Application.php:1000
PHP   6. SilverStripe\Upgrader\Console\UpgradeCommand->execute() /c/Users/PC/Documents/www/upgrades/upgrader_tool/vendor/symfony/console/Command/Command.php:255
PHP   7. SilverStripe\Upgrader\Upgrader->upgrade() /c/Users/PC/Documents/www/upgrades/upgrader_tool/vendor/silverstripe/upgrader/src/Console/UpgradeCommand.php:95
PHP   8. SilverStripe\Upgrader\UpgradeRule\YML\UpdateConfigClasses->upgradeFile() /c/Users/PC/Documents/www/upgrades/upgrader_tool/vendor/silverstripe/upgrader/src/Upgrader.php:61
PHP   9. SilverStripe\Upgrader\UpgradeRule\YML\UpdateConfigClasses->upgradeBlock() /c/Users/PC/Documents/www/upgrades/upgrader_tool/vendor/silverstripe/upgrader/src/UpgradeRule/YML/UpdateConfigClasses.php:35
PHP  10. SilverStripe\Upgrader\UpgradeRule\YML\UpdateConfigClasses->upgradeArray() /c/Users/PC/Documents/www/upgrades/upgrader_tool/vendor/silverstripe/upgrader/src/UpgradeRule/YML/UpdateConfigClasses.php:61
[2020-07-02 14:21:29] Applying RenameClasses to _config.php...
[2020-07-02 14:21:29] Applying ClassToTraitRule to _config.php...
modified:	src/AnalyticsTransactionReversal.php
@@ -2,11 +2,16 @@

 namespace Sunnysideup\EcommerceGoogleAnalytics;

-use DataExtension;
+
 use Analytics;
-use Config;
-use CheckoutPage;
-use Director;
+
+
+
+use SilverStripe\Core\Config\Config;
+use Sunnysideup\Ecommerce\Pages\CheckoutPage;
+use SilverStripe\Control\Director;
+use SilverStripe\ORM\DataExtension;
+




modified:	src/CheckoutPageDataExtension.php
@@ -2,9 +2,13 @@

 namespace Sunnysideup\EcommerceGoogleAnalytics;

-use DataExtension;
-use FieldList;
-use CheckboxField;
+
+
+
+use SilverStripe\Forms\FieldList;
+use SilverStripe\Forms\CheckboxField;
+use SilverStripe\ORM\DataExtension;
+




modified:	src/CheckoutPageExtensionController.php
@@ -2,13 +2,21 @@

 namespace Sunnysideup\EcommerceGoogleAnalytics;

-use Extension;
-use Director;
-use EcommerceConfig;
-use EcommerceCurrency;
-use Config;
-use Product;
-use Requirements;
+
+
+
+
+
+
+
+use SilverStripe\Control\Director;
+use Sunnysideup\Ecommerce\Config\EcommerceConfig;
+use Sunnysideup\Ecommerce\Model\Money\EcommerceCurrency;
+use SilverStripe\Core\Config\Config;
+use Sunnysideup\Ecommerce\Pages\Product;
+use SilverStripe\View\Requirements;
+use SilverStripe\Core\Extension;
+




modified:	_config/config.yml
@@ -1,17 +1,15 @@
 ---
 Name: ecommerce-google-analytics
 ---
-Order:
+Sunnysideup\Ecommerce\Model\Order:
   extensions:
-    - AnalyticsTransactionReversal
+    - Sunnysideup\EcommerceGoogleAnalytics\AnalyticsTransactionReversal
+Sunnysideup\Ecommerce\Pages\CheckoutPage:
+  extensions:
+    - Sunnysideup\EcommerceGoogleAnalytics\CheckoutPageDataExtension
+Sunnysideup\Ecommerce\Pages\CheckoutPageController:
+  extensions:
+    - Sunnysideup\EcommerceGoogleAnalytics\CheckoutPageExtensionController
+Sunnysideup\EcommerceGoogleAnalytics\CheckoutPageExtensionController:
+  include_product_items: true

-CheckoutPage:
-  extensions:
-    - CheckoutPageDataExtension
-
-CheckoutPageController:
-  extensions:
-    - CheckoutPageExtensionController
-
-CheckoutPageExtensionController:
-  include_product_items: true

modified:	_config/routes.yml
@@ -1,4 +1,4 @@
 ---
 Name: ecommerce-google-analytics-routes
 ---
-
+{  }

Writing changes for 5 files
✔✔✔