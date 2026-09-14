<?php

namespace Tests\Unit;

use App\Models\Isp;
use Tests\TestCase;

class IspCommissionPercentageTest extends TestCase
{
    public function test_isp_model_accepts_commission_percentage_attribute()
    {
        $isp = new Isp();
        $isp->fill([
            'isp_code' => 'demoisp',
            'isp_name' => 'Demo ISP',
            'isp_tagline' => 'Demo ISP',
            'currency_symbol' => '৳',
            'invoice_title' => 'MONEY RECEIPT',
            'payment_instruction' => 'Please pay through bKash/Nagad Merchant or Cash within the due date.',
            'payment_methods' => 'bKash / Nagad / Cash',
            'payment_number' => '01XXXXXXXXX',
            'invoice_footer' => 'Keep this invoice receipt for future reference. Thank you for being with us!',
            'signatory_title' => 'Authorized Signatory',
            'commission_percentage' => 35.5,
            'is_default' => true,
        ]);

        $this->assertSame(35.5, (float) $isp->getAttribute('commission_percentage'));
    }
}
