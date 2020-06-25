<?php


namespace LaravelVueGoodTable\Columns\Options;


use LaravelVueGoodTable\Contracts\JsonSerializable;

class FilterOptions implements JsonSerializable
{
    /**
     * @var bool
     */
    public $enabled;

    /**
     * @var string
     */
    public $placeholder;

    /**
     * @var array
     */
    public $filterDropdownItems = [];

    /**
     * @var bool
     */
    public $multiSelect;

    /**
     * @var string
     */
    public $trigger;

    /**
     * FilterOptions constructor.
     *
     * @param bool|null   $enabled
     * @param string|null $placeholder
     * @param array|null  $filterDropdownItems
     * @param bool|null   $multiSelect
     * @param string|null $trigger
     */
    public function __construct(
        ?bool $enabled = true,
        ?string $placeholder = 'Filter this',
        ?array $filterDropdownItems = [],
        ?bool $multiSelect = false,
        ?string $trigger = ''
    ) {
        $this->enabled = $enabled;
        $this->placeholder = $placeholder;
        $this->filterDropdownItems = $filterDropdownItems;
        $this->multiSelect = $multiSelect;
        $this->trigger = $trigger;
    }

    public function jsonSerialize(): array
    {
        $array = [
            'enabled' => $this->enabled,
            'placeholder' => $this->placeholder,
            'trigger' => $this->trigger
        ];

        if (!empty($this->filterDropdownItems)) {
            if ($this->multiSelect) {
                $array['filterMultiselectDropdownItems'] = $this->jsonSerializeMultiSelectDropdownItems();
            } else {
                $array['filterDropdownItems'] = $this->jsonSerializeSingleSelectDropdownItems();
            }

        }

        return $array;
    }

    /**
     * @return array
     */
    protected function jsonSerializeSingleSelectDropdownItems()
    {
        $array = [];

        foreach ($this->filterDropdownItems as $text) {
            $array[] = [
                'value' => $text,
                'text' => $text
            ];
        }

        return $array;
    }

     /**
     * @return array
     */
    protected function jsonSerializeMultiSelectDropdownItems()
    {
        $array = [];

        foreach ($this->filterDropdownItems as $text) {
            $array[] = $text;
        }

        return $array;
    }
}