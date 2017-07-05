<?php

namespace App\Controller;


class DefaultController extends BaseController
{

    public function action_index()
    {
        $content = $my_html = \Michelf\Markdown::defaultTransform(file_get_contents(__DIR__ . '/../../../README.md'));
        $this->template->set('content', $content);
    }

}