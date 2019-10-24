<?php

/**
 * @author    Julian Engler
 * @package   Shopware\Components\Blisstribute\Order\Payment
 * @copyright Copyright (c) 2016
 * @since     1.0.0
 */
class Shopware_Components_Blisstribute_Order_Payment_DebitAdvice
    extends Shopware_Components_Blisstribute_Order_Payment_Payment
{
    /**
     * @inheritdoc
     * @throws Shopware_Components_Blisstribute_Exception_OrderPaymentMappingException
     */
    protected function getAdditionalPaymentInformation()
    {
        $paymentData = Shopware()->Models()->getRepository('Shopware\Models\Payment\PaymentInstance')->findOneBy([
            'orderId' => $this->order->getId()
        ]);

        if ($paymentData == null) {
            throw new Shopware_Components_Blisstribute_Exception_OrderPaymentMappingException('no bank data found');
        }

        $bankOwner = $paymentData->getAccountHolder();

        if (trim($bankOwner) == '') {
            $bankOwner = $paymentData->getFirstname() . ' ' . $paymentData->getLastname();
        }

        return [
            'bankOwner' => $bankOwner,
            'bankName'  => $paymentData->getBankName(),
            'iban'      => strtoupper($paymentData->getIban()),
            'bic'       => strtoupper($paymentData->getBic())
        ];
    }
}
