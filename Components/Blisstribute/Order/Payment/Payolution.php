<?php

class Shopware_Components_Blisstribute_Order_Payment_Payolution
    extends Shopware_Components_Blisstribute_Order_Payment_Payment
{
    /**
     * @inheritdoc
     * @throws Shopware_Components_Blisstribute_Exception_OrderPaymentMappingException
     */
    protected function getAdditionalPaymentInformation()
    {
        $orderAttribute = $this->order->getAttribute();
        if (trim($orderAttribute->getPayolutionUniqueId()) == '' || trim($orderAttribute->getPayolutionPaymentReferenceId()) == '') {
            throw new Shopware_Components_Blisstribute_Exception_OrderPaymentMappingException(
                'no payolution unique id or reference id given'
            );
        }

        return array(
            'token' => trim($orderAttribute->getPayolutionUniqueId()),
            'tokenReference' => trim($orderAttribute->getPayolutionPaymentReferenceId()),
        );
    }
}
