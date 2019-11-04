<?php

namespace App\Components;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TemplateRender
{
    public $folder;
    private $twig;

    public function __construct($folder = null)
    {
        $loader = new FilesystemLoader($folder);
        $this->twig = new Environment($loader);
    }

    public function render(string $suggestions, array $variables = []): string
    {
        return $this->twig->render($suggestions . '.twig', $variables);
    }
}
