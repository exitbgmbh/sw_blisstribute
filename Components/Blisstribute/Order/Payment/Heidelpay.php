<?php

/**
 * @author    Florian Ressel
 * @package   Shopware\Components\Blisstribute\Order\Payment
 * @copyright Copyright (c) 2017
 * @since     1.0.0
 */
class Shopware_Components_Blisstribute_Order_Payment_Heidelpay
    extends Shopware_Components_Blisstribute_Order_Payment_Payment
{
    /**
     * @inheritdoc
     */
    protected function getAdditionalPaymentInformation()
    {
        return [
            'token'          => trim($this->order->getTemporaryId()),
            'tokenReference' => trim($this->order->getTransactionId()),
        ];
    }
}
