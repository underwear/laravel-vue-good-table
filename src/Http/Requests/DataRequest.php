<?php


namespace LaravelVueGoodTable\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'columnFilters' => ['array'],

            'sort.*.field' => ['filled',],
            'sort.*.type' => ['filled', Rule::in(['asc', 'desc'])],

            'page' => ['integer'],
            'perPage' => ['integer']
        ];
    }

    public function getPerPage(): int
    {
        return $this->get('perPage', 10);
    }

    public function getPage(): int
    {
        return $this->get('page', 1);
    }

    public function getColumnFilters(): array
    {
        $columnFilters = $this->get('columnFilters', []);
        $columnFilters = collect($columnFilters)
            ->filter(function ($value, $key) {
                return !empty($value);
            })->all();

        return $columnFilters;
    }

    public function getSort(): array
    {
        return $this->get('sort', []);
    }

    public function getSearchQuery(): ?string
    {
        return $this->get('q');
    }
}