<?php
/**
 * Inchoo
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Please do not edit or add to this file if you wish to upgrade
 * Magento or this extension to newer versions in the future.
 * Inchoo developers (Inchooer's) give their best to conform to
 * "non-obtrusive, best Magento practices" style of coding.
 * However, Inchoo does not guarantee functional accuracy of
 * specific extension behavior. Additionally we take no responsibility
 * for any possible issue(s) resulting from extension usage.
 * We reserve the full right not to provide any kind of support for our free extensions.
 * Thank you for your understanding.
 *
 * @category    Inchoo
 * @package     Inchoo_AdminOrderNotifier
 * @author      Branko Ajzele <ajzele@gmail.com>
 * @copyright   Copyright (c) Inchoo (http://inchoo.net/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Inchoo_AdminOrderNotifier_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_ACTIVE = 'sales/inchoo_adminOrderNotifier/active';
    const XML_PATH_NOTIFY_GENERAL_EMAIL = 'sales/inchoo_adminOrderNotifier/notify_general_email';
    const XML_PATH_NOTIFY_SALES_EMAIL = 'sales/inchoo_adminOrderNotifier/notify_sales_email';
    const XML_PATH_NOTIFY_SUPPORT_EMAIL = 'sales/inchoo_adminOrderNotifier/notify_support_email';
    const XML_PATH_NOTIFY_CUSTOM1_EMAIL = 'sales/inchoo_adminOrderNotifier/notify_custom1_email';
    const XML_PATH_NOTIFY_CUSTOM2_EMAIL = 'sales/inchoo_adminOrderNotifier/notify_custom2_email';
    const XML_PATH_NOTIFY_EMAILS = 'sales/inchoo_adminOrderNotifier/notify_emails';
    const XML_PATH_EMAIL_TEMPLATE = 'sales/inchoo_adminOrderNotifier/email_template';

    public function isModuleEnabled($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_ACTIVE, $store);
    }

    public function getNotifyGeneralEmail($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_NOTIFY_GENERAL_EMAIL, $store);
    }

    public function getNotifySalesEmail($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_NOTIFY_SALES_EMAIL, $store);
    }

    public function getNotifySupportEmail($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_NOTIFY_SUPPORT_EMAIL, $store);
    }

    public function getNotifyCustom1Email($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_NOTIFY_CUSTOM1_EMAIL, $store);
    }

    public function getNotifyCustom2Email($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_NOTIFY_CUSTOM2_EMAIL, $store);
    }

    public function getNotifyEmails($store = null)
    {
        $entries = Mage::getStoreConfig(self::XML_PATH_NOTIFY_EMAILS, $store);
        $emails = array();

        if (!empty($entries)) {
            $entries = explode(PHP_EOL, $entries);

            if (is_array($entries)) {
                foreach ($entries as $entry) {
                    $_entry = trim($entry);
                    $_name = trim(substr($_entry, 0, strpos($_entry, '<')));
                    $_email = trim(substr($_entry, strpos($_entry, '<')+1, -1));

                    if (!empty($_name) && !empty($_email)) {
                        $emails[] = array('name'=>$_name, 'email'=>$_email);
                    }
                }
            }
        }

        return $emails;
    }

    public function getEmailTemplate($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE, $store);
    }

    /**
     * @param $identType ('general' or 'sales' or 'support' or 'custom1' or 'custom2')
     * @param $option ('name' or 'email')
     * @return string
     */
    public function getStoreEmailAddressSenderOption($identType, $option)
    {
        if (!$generalContactName = Mage::getSingleton('core/config_data')->getCollection()->getItemByColumnValue('path', 'trans_email/ident_'.$identType.'/'.$option)) {
            $conf = Mage::getSingleton('core/config')->init()->getXpath('/config/default/trans_email/ident_'.$identType.'/'.$option);
            $generalContactName = array_shift($conf);
        } else {
            $generalContactName = $generalContactName->getValue();
        }

        return (string)$generalContactName;
    }
}
