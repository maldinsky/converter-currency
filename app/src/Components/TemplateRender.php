<?php

namespace App\Components;

class TemplateRender
{
    public $folder;

    public function __construct($folder = null)
    {
        if ($folder) {
            $this->setFolder($folder);
        }
    }

    public function setFolder($folder)
    {
        $this->folder = rtrim($folder, '/');
    }

    public function render($suggestions, $variables = [])
    {
        $template = $this->findTemplate($suggestions);
        $output = '';
        if ($template) {
            $output = $this->renderTemplate($template, $variables);
        }
        return $output;
    }

    public function findTemplate($suggestions)
    {
        if (!is_array($suggestions)) {
            $suggestions = array($suggestions);
        }
        $suggestions = array_reverse($suggestions);
        $found = false;
        foreach ($suggestions as $suggestion) {
            $file = "{$this->folder}/{$suggestion}.php";
            if (file_exists($file)) {
                $found = $file;
                break;
            }
        }
        return $found;
    }

    public function renderTemplate()
    {
        ob_start();
        foreach (func_get_args()[1] as $key => $value) {
            ${$key} = $value;
        }
        include func_get_args()[0];
        return ob_get_clean();
    }
}
