<?php

use Shopware\Models\Order\Order;
use Shopware\Models\Order\Detail;

/**
 * abstract payment mapping
 *
 * @author    Julian Engler
 * @package   Shopware_Components_Blisstribute_Order_Payment_Abstract
 * @copyright Copyright (c) 2016
 * @since     1.0.0
 */
class Shopware_Components_Blisstribute_Order_Payment_Payment
{
    /**
     * The Shopware order.
     *
     * @var Order
     */
    protected $order;

    /**
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Returns the payment information.
     *
     * @return array
     * @throws Exception
     */
    public function getInfo()
    {
        $paymentCosts = 0.00;

        /** @var Detail $currentDetail */
        foreach ($this->order->getDetails() as $currentDetail) {
            if (!in_array($currentDetail->getArticleNumber(), ['sw-payment', 'sw-payment-absolute', 'sw-surcharge'])) {
                continue;
            }

            if ($currentDetail->getPrice() > 0) {
                $paymentCosts += $currentDetail->getPrice();
            }
        }

        $paymentCode = $this->order->getPayment()->getAttribute()->getBlisstributePaymentCode();

        if (empty(trim($paymentCode))) {
            throw new Exception('Blisstribute payment code is empty on order ' . $this->order->getNumber());
        }

        $payment = [
            'total'      => round($paymentCosts, 6),
            'code'       => $paymentCode,
            'isPayed'    => (bool)$this->order->getPayment()->getAttribute()->getBlisstributePaymentIsPayed(),
            'totalIsNet' => false,
        ];

        if ($payment['isPayed']) {
            $payment['amountPayed'] = $this->order->getInvoiceAmount();
        }

        $payment = array_merge($payment, $this->getAdditionalPaymentInformation());

        return $payment;
    }

    /**
     * Get additional payment information if necessary.
     * This includes:
     *     - cardAlias (string)
     *     - resToken (string)
     *     - contractAccount (string)
     *     - bankOwner (string)
     *     - bankName (string)
     *     - iban (string)
     *     - bic (string)
     *
     * @return array
     */
    protected function getAdditionalPaymentInformation()
    {
        $info = [];

        if (empty(trim($this->order->getTransactionId()))) {
            $info['token'] = $this->order->getTransactionId();
        }

        return $info;
    }
}
