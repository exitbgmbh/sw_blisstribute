<?php

use Shopware\Models\Order\Order;

class Shopware_Components_Blisstribute_Order_Payment_PaymentFactory
{
    /**
     * Register extended payment methods here.
     * Payment codes that are not listed default to Shopware_Components_Blisstribute_Order_Payment_Payment.
     *
     * @var array
     */
    private $extendedPaymentsByCode = [
        'amazonPayments'        => Shopware_Components_Blisstribute_Order_Payment_AmazonPayments::class,
        'debitAdvice'           => Shopware_Components_Blisstribute_Order_Payment_DebitAdvice::class,
        'heidelpayCC'           => Shopware_Components_Blisstribute_Order_Payment_Heidelpay::class,
        'heidelpayIdeal'        => Shopware_Components_Blisstribute_Order_Payment_Heidelpay::class,
        'heidelpayPostFinance'  => Shopware_Components_Blisstribute_Order_Payment_Heidelpay::class,
        'heidelpaySofort'       => Shopware_Components_Blisstribute_Order_Payment_Heidelpay::class,
        'payolution'            => Shopware_Components_Blisstribute_Order_Payment_Payolution::class,
        'payolutionELV'         => Shopware_Components_Blisstribute_Order_Payment_Payolution::class,
        'payolutionInstallment' => Shopware_Components_Blisstribute_Order_Payment_Payolution::class,
        'payone'                => Shopware_Components_Blisstribute_Order_Payment_PayOne::class,
        'payoneELV'             => Shopware_Components_Blisstribute_Order_Payment_PayOne::class,
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

        return new $payment($order);
    }
}
