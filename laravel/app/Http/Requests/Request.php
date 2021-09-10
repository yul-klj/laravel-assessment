<?php

namespace App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * App\Http\Requests
 *
 * @package App\Http\Requests
 * @author Yul <yul_klj@hotmail.com>
 */
abstract class Request extends FormRequest
{
    /**
     * Failed Validation
     *
     * @param Validator $validator validator
     * @throws UnprocessableEntityHttpException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json(
            [
                'success' => 'false',
                'code' => Controller::CODE_UNPROCESSABLE_ENTITY,
                'http_code' => 422,
                'content' => [
                    'error' => $validator->errors()
                ]
            ],
            422
        );
        $response->header('Content-Type', 'application/json');
        throw new HttpResponseException($response);
    }

    /** @inheritdoc */
    protected function failedAuthorization()
    {
        throw new HttpException(403, 'This action is unauthorized.', null, [], -1);
    }
}
