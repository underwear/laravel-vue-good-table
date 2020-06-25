<?php


namespace LaravelVueGoodTable\Columns;


class Boolean extends Text
{
    /**
     * @var string
     */
    protected $type = 'boolean';

    /**
     * @param mixed $row
     * @param null  $attribute
     *
     * @return bool
     */
    public function getValue($row, $attribute = null)
    {
        return (bool)parent::getValue($row, $attribute);
    }

}