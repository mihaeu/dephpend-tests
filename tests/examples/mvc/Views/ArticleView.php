<?php

namespace Mihaeu\DephpendTests\Example\MVC\Views;

class ArticleView
{
    public function render(array $data)
    {
        echo 'I\'m a good girl!' . implode($data, ', ');
    }
}
