<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Shared\Requests\Concerns\HasPaginationFilter;
use Modules\Shared\Requests\Concerns\HasQueryFilters;
use Modules\Shared\Requests\Concerns\HasSearchFilter;
use Modules\Shared\Requests\Concerns\HasSortingFilter;

class ListOrdersRequest extends FormRequest
{
    use HasPaginationFilter, HasSortingFilter, HasSearchFilter, HasQueryFilters;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_on_route')) {
            $this->merge([
                'is_on_route' => filter_var($this->input('is_on_route'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'is_on_route' => ['nullable', 'boolean'],
            ...$this->paginationRules(),
            ...$this->sortingRules(),
            ...$this->searchRules()
        ];
    }
}
