<?php
namespace Fagathe\Framework\Twig;

use Fagathe\Framework\Form\Form;
use Fagathe\Framework\Http\Session;
use Fagathe\Framework\Router\UrlGenerator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class Twig
{

    private Environment $twig;
    private Filesystem $fs;
    private Session $session;
    private Request $request;

    public function __construct(public string|array $templateDir = [TEMPLATE_DIR, VIEWS_DIR])
    {
        $this->fs = new Filesystem;
        $this->session = new Session();
        $this->request = Request::createFromGlobals();
    }

    /**
     * getLoader
     *
     * @return Environment
     */
    public function getLoader(): Environment
    {
        $loader = new FilesystemLoader($this->templateDir);

        $this->twig = new Environment($loader, [
            'cache' => false,
        ]);

        $this->addFunctions();

        return $this->twig;
    }

    /**
     * @return array|null
     */
    private function mapRequest(): ?array
    {
        return [
            'query' => $this->request->query,
            'request' => $this->request->request,
            'server' => $this->request->server,
            'files' => $this->request->files,
            'cookies' => $this->request->cookies,
            'headers' => $this->request->headers,
            'requestUri' => $this->request->getRequestUri(),
            'pathInfo' => $this->request->getPathInfo(),
            'method' => $this->request->getMethod(),
            'origin' => $this->request->getSchemeAndHttpHost(),
        ];
    }

    /**
     * addFunctions
     *
     * @return Environment
     */
    private function addFunctions(): Environment
    {

        $dump = new TwigFunction('dump', function (...$args) {
            dump(...$args);
        });

        $form_start = new TwigFunction('form_start', function (Form $form) {
            return $form->start();
        }, ['is_safe' => ['html']]);

        $form_end = new TwigFunction('form_end', function (Form $form) {
            return $form->start();
        }, ['is_safe' => ['html']]);

        $form_widget = new TwigFunction('form_widget', function (Form $form, string $name) {
            return $form->widget($name);
        }, ['is_safe' => ['html']]);

        $asset = new TwigFunction('asset', function (string $path, bool $isPublic = true) {
            if (str_contains($path, 'http')) {
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

        $type = new TwigFilter('type', function (mixed $arg): string {
            return gettype($arg);
        });

        $app = [
            'flashes' => $this->session->getFlashBag(),
            'router' => $this->session->get('router', []),
            'env' => APP_ENV,
            'debug_mode' => APP_DEBUG,
            'session' => $this->session->all(),
            'request' => $this->mapRequest(),
            'version' => phpversion(),
        ];

        $this->twig->addFunction($dump);
        $this->twig->addFunction($form_start);
        $this->twig->addFunction($form_widget);
        $this->twig->addFunction($form_end);
        $this->twig->addFunction($asset);
        $this->twig->addFunction($url);
        $this->twig->addFunction($file_get_contents);
        $this->twig->addFilter($type);
        $this->twig->addGlobal('ERROR_TEMPLATE_DIR', defined('CUSTOM_ERROR_TEMPLATE_DIR') && APP_ENV !== 'dev' ? CUSTOM_ERROR_TEMPLATE_DIR : ERROR_TEMPLATE_DIR);
        $this->twig->addGlobal('app', $app);

        return $this->twig;
    }

}