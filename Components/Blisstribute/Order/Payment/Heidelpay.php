<?php

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
