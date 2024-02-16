<?php 
namespace Fagathe\Framework\Twig;

use Fagathe\Framework\Router\UrlGenerator;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class Twig {

    private Environment $twig;
    private Filesystem $fs;

    public function __construct(public string $templateDir = TEMPLATE_DIR) {
        $this->fs = new Filesystem;
    }
    
    /**
     * getLoader
     *
     * @return Environment
     */
    public function getLoader(): Environment {
        $loader = new FilesystemLoader($this->templateDir);
        
        $this->twig = new Environment($loader, [
            'cache' => false,
        ]);

        $this->addFunctions();

        return $this->twig;
    }
    
    /**
     * addFunctions
     *
     * @return Environment
     */
    private function addFunctions(): Environment {

        $dump = new TwigFunction('dump', function (...$args) {
            dump(...$args);
        });

        $asset = new TwigFunction('asset', function (string $path, bool $isPublic = true) {
            if (str_contains($path,'http')) {
                return $path;
            }
            if ($isPublic === false) {
                return $path;
            }
            
            return DIRECTORY_SEPARATOR . $path;
        });

        $url = new TwigFunction('url', function (string $name, array $parameters = [], bool $referenceType = true) {
            return (new UrlGenerator())->generate($name, $parameters, $referenceType);
        });

        $file_get_contents = new TwigFunction('file_get_contents', function (string $path) {
            if ($this->fs->exists($path)) {
                return file_get_contents($path);
            }
            
            return '';
        });

        $type = new TwigFilter('type', function (mixed $arg):string {
            return gettype($arg);
        });

        $this->twig->addFunction($dump);
        $this->twig->addFunction($asset);
        $this->twig->addFunction($url);
        $this->twig->addFunction($file_get_contents);
        $this->twig->addFilter($type);
        $this->twig->addGlobal('ERROR_TEMPLATE_DIR', defined('CUSTOM_ERROR_TEMPLATE_DIR') && APP_ENV !== 'dev' ? CUSTOM_ERROR_TEMPLATE_DIR : ERROR_TEMPLATE_DIR);
        $this->twig->addGlobal('APP_ENV', APP_ENV);

        return $this->twig;
    } 

}