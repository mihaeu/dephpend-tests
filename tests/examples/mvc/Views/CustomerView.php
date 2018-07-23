<?php

namespace Mihaeu\DephpendTests\Example\MVC\Views;

use Mihaeu\DephpendTests\Example\MVC\Models\CustomerModel;

class CustomerView
{
    public function render(array $data): string
    {
        return 'OMG Friday 5pm Hack: ' . (new CustomerModel())->loggedInCustomer();
    }
}
