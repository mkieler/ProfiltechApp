<?php

namespace Modules\Delivery\Enums;

enum DeliveryStatus
{
    case DRAFT;
    case PROCESSING;
    case COMPLETED;
    case CANCELLED;

}
