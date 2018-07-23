<?php

namespace Mihaeu\DephpendTests\Example\MVC\Controllers;

use Mihaeu\DephpendTests\Example\MVC\Models\ArticleModel;
use Mihaeu\DephpendTests\Example\MVC\Views\ArticleView;

class ArticleController
{
    /** @var ArticleView */
    private $view;

    /** @var ArticleModel */
    private $model;

    /**
     * @param ArticleView $view
     * @param ArticleModel $model
     */
    public function __construct(ArticleView $view, ArticleModel $model)
    {
        $this->view = $view;
        $this->model = $model;
    }

    public function action()
    {
        echo $this->view->render($this->model->getData());
    }
}
