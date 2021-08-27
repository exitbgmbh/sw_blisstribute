<?php

require_once __DIR__ . '/AbstractExternalPayment.php';

/**
 * sofortuerberweisung payment implementation
 *
 * @author    Julian Engler
 * @package   Shopware\Components\Blisstribute\Order\Payment
 * @copyright Copyright (c) 2016
 * @since     1.0.0
 */
class Shopware_Components_Blisstribute_Order_Payment_Adyen
    extends Shopware_Components_Blisstribute_Order_Payment_AbstractExternalPayment
{
    /**
     * @inheritdoc
     */
    protected $code = 'adyen';

    /**
     * @inheritdoc
     */
    protected function checkPaymentStatus()
    {
        $status = parent::checkPaymentStatus();

        $transactionId = trim($this->order->getTransactionId());
        if (trim($transactionId) == '') {
            throw new Shopware_Components_Blisstribute_Exception_OrderPaymentMappingException('no transaction id given');
        }

        if (strlen($transactionId) != 16 || !preg_match('/[0-9]{15}[A-Z]/', $transactionId)) {
            throw new Shopware_Components_Blisstribute_Exception_OrderPaymentMappingException('transaction id seems to be temporary');
        }

        return $status;
    }
}
