<?php


namespace LaravelVueGoodTable\Columns;


use Illuminate\Support\Carbon;

class Date extends Text
{
    /**
     * @var string
     */
    protected $type = 'date';

    /**
     * @var string
     */
    protected $dateInputFormat = 't';

    /**
     * @var string
     */
    protected $dateOutputFormat = 'MMM Do yyyy';

    /**
     * @param string $dateOutputFormat
     *
     * @return Date
     */
    public function dateOutputFormat(string $dateOutputFormat): Date
    {
        $this->dateOutputFormat = $dateOutputFormat;
        return $this;
    }

    /**
     * @param mixed $row
     * @param null  $attribute
     *
     * @return int
     */
    public function getValue($row, $attribute = null)
    {
        $value = parent::getValue($row, $attribute);

        return Carbon::parse($value)->getTimestamp();
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'dateInputFormat' => $this->dateInputFormat,
            'dateOutputFormat' => $this->dateOutputFormat
        ]);
    }
}