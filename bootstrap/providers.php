<?php

declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    Modules\User\Providers\UserServiceProvider::class,
    Modules\Delivery\Providers\DeliveryServiceProvider::class,
    Modules\Quotes\Providers\QuotesServiceProvider::class,
    Modules\Wordpress\Providers\WordpressServiceProvider::class,
    Modules\Order\Providers\OrderServiceProvider::class,
];
