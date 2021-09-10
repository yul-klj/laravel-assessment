<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Route;

/**
 * Class BookSearchRequest
 *
 * @package App\Http\Requests
 * @author Yul <yul_klj@hotmail.com>
 */
class BookSearchRequest extends Request
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
            'keyword' => [
                'required'
            ]
        ];
    }
}
