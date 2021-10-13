<?php

use Shopware\Models\Order\Order;
use Shopware\Models\Order\Detail;
use Shopware\Models\Order\Status;
use Shopware\CustomModels\Blisstribute\BlisstributePayment;

/**
 * abstract payment mapping
 *
 * @author    Julian Engler
 * @package   Shopware_Components_Blisstribute_Order_Payment_Abstract
 * @copyright Copyright (c) 2016
 * @since     1.0.0
 */
class Shopware_Components_Blisstribute_Order_Payment_Abstract
{
    /**
     * blisstribute payment code
     *
     * @var string
     */
    protected $code;

    /**
     * shopware order
     *
     * @var Order
     */
    protected $order;

    /**
     * blisstribute payment mapping
     *
     * @var BlisstributePayment
     */
    protected $payment;

    /**
     * @param Order $order
     * @param BlisstributePayment $payment
     */
    public function __construct(Order $order, BlisstributePayment $payment)
    {
        $this->order = $order;
        $this->payment = $payment;

        $this->checkPaymentStatus();
    }

    /**
     * @return bool
     */
    protected function checkPaymentStatus()
    {
        return true;
    }

    /**
     * @return bool
     */
    protected function isPaid()
    {
        if ($this->payment->getIsPayed()) {
            if ($this->order->getPaymentStatus()->getId() == Status::PAYMENT_STATE_COMPLETELY_PAID) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    /**
     * get blisstribute payment information
     *
     * @param BlisstributePayment $blisstributePayment
     * @return array
     */
    public function getPaymentInformation(BlisstributePayment $blisstributePayment)
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

        $paymentTypeCode = $blisstributePayment->getPaymentTypeCode();
        if (empty($paymentTypeCode)) {
            $paymentTypeCode = $this->code;
        }

        $isPaid = $this->isPaid();
        $payment = array(
            'total' => round($paymentCosts, 6),
            'code' => $paymentTypeCode,
            'isPayed' => $isPaid,
            'totalIsNet' => false,
        );

        if ($isPaid) {
            $payment['amountPayed'] = $this->order->getInvoiceAmount();
        }

        $payment = array_merge($payment, $this->getAdditionalPaymentInformation());
        return $payment;
    }

    /**
     * get additional payment information if necessary
     *  possible additional payment information are:
     *      cardAlias (string)
     *      resToken (string)
     *      contractAccount (string)
     *      bankOwner (string)
     *      bankName (string)
     *      iban (string)
     *      bic (string)
     *
     * @return array
     */
    protected function getAdditionalPaymentInformation()
    {
        return array();
    }
}
