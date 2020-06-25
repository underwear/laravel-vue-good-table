<?php


namespace LaravelVueGoodTable\Columns;


class ComputedColumn extends Column
{
    protected $type = 'text';

    /**
     * @param string $type
     *
     * @return static
     */
    public function type(string $type)
    {
        $this->type = $type;

        return $this;
    }

    public function getValue($row, $attribute = null)
    {
        return call_user_func($this->resolveCallback, $row, $attribute);
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(),[
            'sortable' => false,
        ]);
    }
}