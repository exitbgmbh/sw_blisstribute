<?php

require_once __DIR__ . '/AbstractExternalPayment.php';

/**
 * klarna payment implementation
 *
 * @author    Julian Engler
 * @package   Shopware\Components\Blisstribute\Order\Payment
 * @copyright Copyright (c) 2016
 * @since     1.0.0
 */
class Shopware_Components_Blisstribute_Order_Payment_GenericAlias
    extends Shopware_Components_Blisstribute_Order_Payment_AbstractExternalPayment
{
    /**
     * @inheritdoc
     */
    protected $code = 'genericAlias';

    /**
     * @inheritdoc
     */
    protected function getAdditionalPaymentInformation()
    {
        return array(
            'tokenReference' => trim($this->order->getTransactionId()),
        );
    }
}
