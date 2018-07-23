<?php

namespace Mihaeu\DephpendTests\Example\MVC\Controllers;

use Mihaeu\DephpendTests\Example\MVC\Models\CustomerModel;
use Mihaeu\DephpendTests\Example\MVC\Views\CustomerView;

class CustomerController
{
    /** @var CustomerView */
    private $customerView;

    /** @var CustomerModel */
    private $customerModel;

    /**
     * @param CustomerView $customerView
     * @param CustomerModel $customerModel
     */
    public function __construct(CustomerView $customerView, CustomerModel $customerModel)
    {
        $this->customerView = $customerView;
        $this->customerModel = $customerModel;
    }

    public function action()
    {
        $this->view->render($this->model->getData());
    }
}
