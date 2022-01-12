<?php

namespace Webkul\Payment\Payment;

class Kevin extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'kevin';

    public function getRedirectUrl()
    {
        return 'https://psd2.kevin.eu/payment/confirm?paymentId=e4dd60bb-574f-4a13-910a-57c9795d905f';
    }
}