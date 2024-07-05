<?php
namespace Fagathe\Framework\Form;

use Fagathe\Framework\Helpers\Helpers;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class AbstractField
{

    use Helpers;

    protected const DEFAULT_FIELD_ATTRIBUTES = ['id', 'onchange', 'onfocus', 'required', 'disabled', 'inert', 'autofocus'];

    /**
     * @var string|int|array|UploadedFile $value
     */
    public mixed $value = null;

    /**
     * @var array
     */
    protected array $htmlAttributes = [];

    /**
     * Summary of __construct
     * @param string $name name of field
     * @param mixed $attributes html attributes
     */
    public function __construct(
        protected string $name,
        protected ?string $label = null,
        protected ?array $attributes = null,
    ) {
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        $label = $this->getAttribute('label');
        if ($label === false) {
            $this->label = null;
        }
        if ($label !== null) {
            $this->label = $label;
        }

        return $this->label;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        $id = $this->getAttribute('id');

        if ($id === null) {
            $this->attributes['id'] = strtolower($this->skipAccents($this->name));
        }

        return $this->name;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
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
     * @param string $name
     * @param int|bool|array|string $default
     * 
     * @return int|bool|array|string
     */
    public function getAttribute(string $name, mixed $default = null): mixed
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * @return array|null
     */
    public function getAttributes(): ?array
    {
        if (!array_key_exists('id', $this->attributes)) {
            $this->attributes['id'] = strtolower($this->skipAccents($this->name));
        }
        if (!array_key_exists('name', $this->attributes)) {
            $this->attributes['name'] = strtolower($this->skipAccents($this->name));
        }

        return $this->attributes;
    }

    /**
     * @param string $attribute
     * 
     * @return bool
     */
    public function isAllowedAttribute(string $attribute): bool
    {
        return in_array($attribute, [
            ...static::DEFAULT_FIELD_ATTRIBUTES,
            ...[defined(static::class . '::CUSTOM_FIELD_ATTRIBUTES') ? static::CUSTOM_FIELD_ATTRIBUTES : []]
        ]);
    }

    /**
     * This function generates the HTML for field
     * @return string
     */
    public abstract function render(): string;

    /**
     * @return string|int|array|UploadedFile
     */
    public abstract function getData(): mixed;

    /**
     * @param string|int|array|UploadedFile $value
     * @return \Fagathe\Framework\Form\AbstractField
     */
    public function setData(mixed $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param string $htmlAttribute
     * 
     * @return self
     */
    protected function addHTMLAttributes(string $htmlAttribute): self
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
            $attr = match ($name) {
                'required', 'disabled', 'readonly', 'autofocus', 'multiple' => $value === true ? $name : '',
                'choices' => '',
                default => $name . '="' . $value . '"',
            };

            $this->addHTMLAttributes($attr);
        }

        return count($this->htmlAttributes) ? ' ' . join(' ', $this->htmlAttributes) : '';
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}