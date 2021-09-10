<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Validator;

/**
 * Class BookGetAllRequest
 *
 * @package App\Http\Requests
 * @author Yul <yul_klj@hotmail.com>
 */
class BookGetAllRequest extends Request
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
            'order_field' => [
                'nullable',
                'in:id,title,author'
            ],
            'order_clause' => [
                'nullable',
                'in:asc,desc'
            ],
        ];
    }
}
