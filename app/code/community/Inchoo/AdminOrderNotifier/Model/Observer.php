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
class Inchoo_AdminOrderNotifier_Model_Observer extends Mage_Core_Helper_Abstract
{
    public function sendNotificationEmailToAdmin($observer)
    {
        $order = $observer->getEvent()->getOrder();
        $storeId = $order->getStoreId();

        $helper = Mage::helper('inchoo_adminOrderNotifier');

        if (!$helper->isModuleEnabled($storeId)) {
            return;
        }

        try {
            $templateId = $helper->getEmailTemplate($storeId);

            $mailer = Mage::getModel('core/email_template_mailer');

            if ($helper->getNotifyGeneralEmail()) {
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($helper->getStoreEmailAddressSenderOption('general', 'email'), $helper->getStoreEmailAddressSenderOption('general', 'name'));
                $mailer->addEmailInfo($emailInfo);
            }

            if ($helper->getNotifySalesEmail()) {
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($helper->getStoreEmailAddressSenderOption('sales', 'email'), $helper->getStoreEmailAddressSenderOption('sales', 'name'));
                $mailer->addEmailInfo($emailInfo);
            }

            if ($helper->getNotifySupportEmail()) {
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($helper->getStoreEmailAddressSenderOption('support', 'email'), $helper->getStoreEmailAddressSenderOption('support', 'name'));
                $mailer->addEmailInfo($emailInfo);
            }

            if ($helper->getNotifyCustom1Email()) {
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($helper->getStoreEmailAddressSenderOption('custom1', 'email'), $helper->getStoreEmailAddressSenderOption('custom1', 'name'));
                $mailer->addEmailInfo($emailInfo);
            }

            if ($helper->getNotifyCustom2Email()) {
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($helper->getStoreEmailAddressSenderOption('custom2', 'email'), $helper->getStoreEmailAddressSenderOption('custom2', 'name'));
                $mailer->addEmailInfo($emailInfo);
            }

            foreach ($helper->getNotifyEmails() as $entry) {
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($entry['email'], $entry['name']);
                $mailer->addEmailInfo($emailInfo);
            }

            $mailer->setSender(array(
                'name' => $helper->getStoreEmailAddressSenderOption('general', 'name'),
                'email' => $helper->getStoreEmailAddressSenderOption('general', 'email'),
            ));

            $mailer->setStoreId($storeId);
            $mailer->setTemplateId($templateId);
            $mailer->setTemplateParams(array(
                'order' => $order,
            ));

            $mailer->send();
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }
}
