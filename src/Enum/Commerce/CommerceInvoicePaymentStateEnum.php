<?php

namespace App\Enum\Commerce;

use App\Model\CommerceTraitModel;

class CommerceInvoicePaymentStateEnum
{

    use CommerceTraitModel;

    const INVOICE_OPEN = 1;
    const INVOICE_PAID = 2;
    const INVOICE_CANCELLED = 3;
    const INVOICE_EXPIRED = 4;
    const INVOICE_PENDING = 5;
}