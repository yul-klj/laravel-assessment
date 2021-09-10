<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Route;

/**
 * Class BookUpdateRequest
 *
 * @package App\Http\Requests
 * @author Yul <yul_klj@hotmail.com>
 */
class BookUpdateRequest extends Request
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
            'id' => [
                'required',
                'integer'
            ],
            'title' => [
                'required'
            ],
            'author' => [
                'required'
            ],
        ];
    }

    /**
     * Push seleted route input value into part of validation data
     *
     * @return array
     */
    public function validationData()
    {
        return array_merge($this->request->all(), [
            'id' => Route::input('id'),
        ]);
    }
}
