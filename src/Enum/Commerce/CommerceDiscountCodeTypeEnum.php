<?php

namespace App\Enum\Commerce;

use App\Model\CommerceTraitModel;

class CommerceDiscountCodeTypeEnum
{

    use CommerceTraitModel;

    const TYPE_AMOUNT = 1;
    const TYPE_PERCENTAGE = 2;

}