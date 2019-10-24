<?php

use Shopware\Models\Order\Order;

class Shopware_Components_Blisstribute_Order_Payment_PaymentFactory
{
    /**
     * Register extended payment methods here.
     *
     * @var array
     */
    private $extendedPaymentsByCode = [
        'amazonPayments' => Shopware_Components_Blisstribute_Order_Payment_AmazonPayments::class,
        'debitAdvice'    => Shopware_Components_Blisstribute_Order_Payment_DebitAdvice::class,
        'sofort'         => Shopware_Components_Blisstribute_Order_Payment_Sofort::class,
        'payolution'     => Shopware_Components_Blisstribute_Order_Payment_Payolution::class,
        'payolutionELV'  => Shopware_Components_Blisstribute_Order_Payment_PayolutionELV::class,
        'payone'         => Shopware_Components_Blisstribute_Order_Payment_PayOneCC::class,
        'payoneELV'      => Shopware_Components_Blisstribute_Order_Payment_PayOneELV::class,
        'paypal'         => Shopware_Components_Blisstribute_Order_Payment_PayPal::class,
        'paypalPlus'     => Shopware_Components_Blisstribute_Order_Payment_PayPalPlus::class,
    ];

    /**
     * Creates a payment based on the provided orders Blisstribute payment code.
     * If the payment code is empty, an exception will be thrown.
     *
     * @param $order Order
     * @return Shopware_Components_Blisstribute_Order_Payment_Payment
     * @throws Exception
     */
    public function createPaymentByOrder(Order $order)
    {
        $paymentCode = $order->getPayment()->getAttribute()->getBlisstributePaymentCode();

        if (empty(trim($paymentCode))) {
            throw new Exception('Blisstribute payment code is empty on order ' . $order->getNumber());
        }

        $payment = $this->extendedPaymentsByCode[$paymentCode] ?? false;

        if (!$payment) {
            return new Shopware_Components_Blisstribute_Order_Payment_Payment($order);
        }

        return $payment;
    }
}
