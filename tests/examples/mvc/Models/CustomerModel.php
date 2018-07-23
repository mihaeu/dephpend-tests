<?php

namespace Mihaeu\DephpendTests\Example\MVC\Models;

use Mihaeu\DephpendTests\Example\MVC\Database\Database;

class CustomerModel
{
    /** @var Database */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function loggedInCustomer()
    {
        return 'fail';
    }
}
