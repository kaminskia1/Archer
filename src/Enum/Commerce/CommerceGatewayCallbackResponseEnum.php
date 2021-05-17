<?php

namespace App\Enum\Commerce;

use App\Model\CommerceTraitModel;

class CommerceGatewayCallbackResponseEnum
{

    use CommerceTraitModel;

    const TYPE_SUCCESS = true;
    const TYPE_FAILURE = false;
}