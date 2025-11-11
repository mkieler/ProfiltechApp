<?php

declare(strict_types=1);

namespace Modules\Delivery\Enums;

enum DeliveryStatus
{
    case DRAFT;
    case PROCESSING;
    case COMPLETED;
    case CANCELLED;

}
