<?php 
namespace Fagathe\Framework\Twig;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class Twig {

    private $twig;

    public function __construct(public string $templateDir = TEMPLATE_DIR) {}

    public function getLoader() {
        $loader = new FilesystemLoader($this->templateDir);
        
        $this->twig = new Environment($loader, [
            'cache' => false,
        ]);

        $this->addFunctions();

        return $this->twig;
    }

    private function addFunctions () {

        $dump = new \Twig\TwigFunction('dump', function (...$args) {
            dump(...$args);
        });

        $asset = new \Twig\TwigFunction('asset', function (string $path) {
            return DIRECTORY_SEPARATOR . $path;
        });

        $this->twig->addFunction($dump);
        $this->twig->addFunction($asset);

        return $this->twig;
    } 

}