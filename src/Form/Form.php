<?php
namespace Fagathe\Framework\Form;

use Fagathe\Framework\Form\Field\AbstractField;
use Fagathe\Framework\Form\Exception\NotAllowedAttributeException;
use Symfony\Component\HttpFoundation\Request;

final class Form
{

    private const ALLOWED_FORM_ATTRIBUTES = ['action', 'method', 'name', 'onsubmit', 'autocomplete', 'enctype',];
    private const ALLOWED_FORM_METHODS = ['GET', 'POST'];

    /**
     * @var array
     */
    protected array $htmlAttributes = [];

    private array $mappedFields = [];
    private ?Request $request = null;

    public function __construct(public string $name = '', private array $data = [], public array $fields = [], public array $attributes = [])
    {
        $this->setUpAtributeFields();
        $this->mappedFields();
        $this->setData($data);
    }

    /**
     * @return void
     */
    private function setUpAtributeFields(): void
    {
        if ($this->getAttribute('method') === null) {
            $this->setAttribute('method', 'POST');
        }
    }

    /**
     * @return string
     */
    private function getMethod(): ?string
    {
        try {
            $method = strtoupper($this->getAttribute('method'));
            if (!in_array($method, self::ALLOWED_FORM_METHODS)) {
                throw new NotAllowedAttributeException('Method not allowed');
            }
            return $this->getAttribute('method');
        } catch (NotAllowedAttributeException $e) {
            return $e->render();
        }
    }

    /**
     * @return void
     */
    public function prefixFieldNames(): void
    {
        if ($this->getName() !== '') {
            foreach ($this->getFields() as $f) {
                $f->setName($this->getName() . '[' . $f->getName() . ']');
            }
        }
    }

    /**
     * @param Request $request
     * 
     * @return self
     */
    public function setRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return void
     */
    public function handleRequest(Request $request): void
    {
        $method = $request->getMethod();
        $this->request = $request;
        if ($method === $this->getMethod()) {
            $data = $request->request->all()[$this->getName()] ?? $request->query->all() ?? null;
            foreach ($data as $key => $value) {
                $field = $this->get($key);
                if (!is_null($field)) {
                    $field->setData($value);
                }
            }
        }
    }

    /**
     * Return an array of data from the form
     * 
     * @return array
     */
    public function getFormData(): array
    {
        if ($this->request === null) {
            return [];
        }
        if ($this->request->isMethod('POST')) {
            return $this->request->request->all()[$this->getName()] ?? [];
        }

        if ($this->request->isMethod('GET')) {
            return $this->request->query->all()[$this->getName()] ?? [];
        }
        return [];
    }

    /**
     * @return bool
     */
    public function isSubmitted(): bool
    {
        if ($this->request === null) {
            return false;
        }

        if ($this->request->isMethod('POST') || $this->request->isMethod('GET')) {
            return $this->request->request->has($this->getName()) || $this->request->query->has($this->getName());
        }

        return $this->getAttribute('method');
    }

    /**
     * @return void
     */
    public function mappedFields(): void
    {
        foreach ($this->getFields() as $field) {
            if (!array_key_exists($field->getName(), $this->mappedFields)) {
                $this->mappedFields[$field->getName()] = $field;
            }
        }

        return;
    }

    /**
     * @param string $htmlAttribute
     * 
     * @return self
     */
    protected function addHTMLAttributes(?string $htmlAttribute): self
    {
        $this->htmlAttributes[] = $htmlAttribute;

        return $this;
    }

    /**
     * @return string
     */
    protected function getHTMLAttributes(): string
    {
        foreach ($this->getAttributes() as $name => $value) {
            $this->addHTMLAttributes($name . '="' . $value . '"');
        }

        return count($this->htmlAttributes) ? ' ' . join(' ', $this->htmlAttributes) : '';
    }

    /**
     * @param object $field
     * 
     * @return self
     */
    public function add(object $field): self
    {
        $this->fields[] = $field;
        $this->mappedFields();
        return $this;
    }

    /**
     * @param string $attribute
     * @param int|bool|array|string $default
     * 
     * @return int|bool|array|string
     */
    public function getAttribute(string $attribute, mixed $default = null): mixed
    {
        return $this->attributes[$attribute] ?? $default;
    }

    /**
     * @param string $name
     * @param mixed $value
     * 
     * @return self
     */
    public function setAttribute(string $name, mixed $value): self
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $attribute
     * 
     * @return bool
     */
    public function isAllowedAttributes(string $attribute): bool
    {
        return in_array($attribute, self::ALLOWED_FORM_ATTRIBUTES);
    }

    /**
     * @return string
     */
    public function start(): string
    {
        $this->prefixFieldNames();
        return '<form' . $this->getHTMLAttributes() . ">";
    }

    /**
     * @param string $name
     * 
     * @return string
     */
    public function widget(string $name): string
    {
        return $this->mappedFields[$name]->render();
    }

    /**
     * @return string
     */
    public function end(): string
    {
        return '</form>';
    }


    /**
     * Get the value of fields
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param string $name
     * 
     * @return AbstractField
     */
    public function get(string $name): ?AbstractField
    {
        return $this->mappedFields[$name] ?? null;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->getFormData();
    }

    /**
     * @param array $data
     * 
     * @return self
     */
    private function __hydrate(array $data): self
    {
        foreach ($data as $key => $value) {
            $field = $this->get($key);
            if (!is_null($field)) {
                if (json_validate($value)) {
                    $value = json_decode($value);
                }
                $field->setData($value);

            }
        }

        return $this;
    }

    /**
     * @param array $data
     * 
     * @return self
     */
    public function setData(array $data): self
    {
        if ($this->isSubmitted() === true) {
            $this->__hydrate($this->getFormData());
        } else {
            $this->__hydrate($data);
        }

        return $this;
    }
}