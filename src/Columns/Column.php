<?php


namespace LaravelVueGoodTable\Columns;


use Illuminate\Support\Str;
use LaravelVueGoodTable\Contracts\JsonSerializable;

abstract class Column implements JsonSerializable
{
    /**
     * The displayable name of the field.
     *
     * @var string
     */
    protected $name;

    /**
     * The attribute / column name of the field.
     *
     * @var string
     */
    protected $attribute;

    /**
     * The callback to be used to resolve the field's value.
     *
     * @var \Closure
     */
    protected $resolveCallback;

    /**
     * @var \Closure
     */
    protected $displayCallback;

    /**
     * @var string|null
     */
    protected $type = 'text';

    /**
     * @var bool
     */
    protected $html = false;

    /**
     * @var string|null
     */
    protected $width = null;

    /** @var bool  */
    protected $visible = true;

    /**
     * @var string
     */
    protected $connectionType = 'postgres';

    /**
     * Create a new field.
     *
     * @param  string        $name
     * @param  string        $attribute
     * @param  callable|null $resolveCallback
     *
     * @return void
     */
    public function __construct($name, $attribute = null, ?callable $resolveCallback = null)
    {
        $this->name = $name;
        $this->attribute = $attribute ?? str_replace(' ', '_', Str::lower($name));
        $this->resolveCallback = $resolveCallback;
        $this->connectionType = \DB::connection()->getDriverName();
    }

    /**
     * Create a new Column
     *
     * @return static
     */
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAttribute(): string
    {
        return $this->attribute;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return !$this->isVisible();
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * Resolve the field's value.
     *
     * @param  mixed       $row
     * @param  string|null $attribute
     *
     * @return mixed
     */
    public function getValue($row, $attribute = null)
    {
        $attribute = $attribute ?? $this->attribute;

        if ($this->resolveCallback) {
            $value = $this->resolveAttribute($row, $attribute);
            return call_user_func($this->resolveCallback, $value, $row, $attribute);
        }

        return $this->resolveAttribute($row, $attribute);
    }

    /**
     * Define the callback that should be used to display the field's value.
     *
     * @param  callable $displayCallback
     *
     * @return $this
     */
    public function displayUsing(callable $displayCallback)
    {
        $this->displayCallback = $displayCallback;

        return $this;
    }

    /**
     * @param      $row
     * @param null $attribute
     *
     * @return mixed
     */
    public function getDisplayedValued($row, $attribute = null)
    {
        $value = $this->getValue($row, $attribute);

        if ($this->displayCallback) {
            $value = call_user_func($this->displayCallback, $value, $row, $attribute);
        }

        return $value;
    }


     /**
     * @param bool|null $value
     *
     * @return Text
     */
    public function html(?bool $value = true): self
    {
        $this->html = $value;

        return $this;
    }

    /**
     * @param string|null $value
     *
     * @return Text
     */
    public function width(?string $value): self
    {
        $this->width = $value;

        return $this;
    }

    /**
     * @param bool $visible
     * @return Column
     */
    public function hidden(bool $hidden=true): Column
    {
        $this->visible(!$hidden);
        return $this;
    }

    /**
     * @param bool $visible
     * @return Column
     */
    public function visible(bool $visible=true): Column
    {
        $this->visible = $visible;
        return $this;
    }

    /**
     * Resolve the given attribute from the given resource.
     *
     * @param  mixed  $resource
     * @param  string $attribute
     *
     * @return mixed
     */
    protected function resolveAttribute($resource, $attribute)
    {
        return data_get($resource, str_replace('->', '.', $attribute));
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'label' => $this->getName(),
            'field' => $this->getAttribute(),
            'type' => $this->getType(),
            'html' => $this->html,
            'hidden' => $this->isHidden(),
            'width' => $this->width,
        ];
    }
}
