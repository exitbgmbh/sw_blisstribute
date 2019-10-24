<?php

/**
 * @author    Julian Engler
 * @package   Shopware\Components\Blisstribute\Order\Payment
 * @copyright Copyright (c) 2016
 * @since     1.0.0
 */
class Shopware_Components_Blisstribute_Order_Payment_AmazonPayments
    extends Shopware_Components_Blisstribute_Order_Payment_Payment
{
    /**
     * @inheritdoc
     * @throws Shopware_Components_Blisstribute_Exception_OrderPaymentMappingException
     */
    protected function getAdditionalPaymentInformation()
    {
        $captureNow     = Shopware()->Config()->get('captureNow', false);
        $orderAttribute = $this->order->getAttribute();

        if ((bool)$captureNow) {
            $resToken = trim($orderAttribute->getBestitAmazonCaptureId());
        } else {
            $resToken = trim($orderAttribute->getBestitAmazonAuthorizationId());
        }

        if ($resToken == '' || is_null($resToken)) {
            throw new Shopware_Components_Blisstribute_Exception_OrderPaymentMappingException(
                'no token given'
            );
        }

        return [
            'token'          => $resToken,
            'tokenReference' => trim($this->order->getTransactionId()),
        ];
    }
}
