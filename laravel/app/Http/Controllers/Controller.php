<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class Controller
 *
 * @package App\Http\Controllers
 * @author  Yul <yul_klj@hotmail.com>
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * [404] Requested resource could not be found
     *
     * @const CODE_NOT_FOUND
     */
    const CODE_NOT_FOUND = 'NOT_FOUND';
    /**
     * [422] Unprocessable Entity
     *
     * @const CODE_UNPROCESSABLE_ENTITY
     */
    const CODE_UNPROCESSABLE_ENTITY = 'UNPROCESSABLE_ENTITY';
    /**
     * [415] Server does not support media type
     *
     * @const CODE_INVALID_MEME_TYPE
     */
    const CODE_INVALID_MIME_TYPE = 'INVALID_MIME_TYPE';
    /**
     * [200] Standard response for successful HTTP requests
     *
     * @const CODE_ACCOMPLISHED
     */
    const CODE_ACCOMPLISHED = 'ACCOMPLISHED';
    /**
     * [500] Generic server error message
     *
     * @const CODE_INTERNAL_ERROR
     */
    const CODE_INTERNAL_ERROR = 'INTERNAL_ERROR';

    /**
     * Respond with success
     *
     * @param string $message message
     * @param array  $data    data
     *
     * @return \Illuminate\Http\Response
     */
    protected function respondAccomplished($message = 'Accomplished', $data = [])
    {
        $this->statusCode = 200;
        $content = [
            'data' => $data
        ];

        return $this->respond($message, self::CODE_ACCOMPLISHED, false, $content);
    }

    /**
     * Respond
     *
     * @param null   $message   message
     * @param string $errorCode error code
     * @param bool   $error     error
     * @param array  $data      data
     *
     * @return \Illuminate\Http\Response
     */
    public function respond($message, $errorCode = self::CODE_ACCOMPLISHED, $error = false, $data = [])
    {
        $return = [
            'code' => $errorCode,
            'http_code' => $this->statusCode,
            'content' => $data
        ];

        if ($error === true) {
            $return ['content'] ['error'] = $message;
        } elseif ($error === false) {
            $return ['content'] ['message'] = $message;
        }

        return $this->respondWithArray($return);
    }

    /**
     * Generates a Response with a 404 HTTP header and a given message.
     *
     * @param string $message message
     * @return \Illuminate\Http\Response
     */
    protected function errorNotFound($message = 'Resource Not Found')
    {
        $this->statusCode = 404;

        return $this->respond($message, self::CODE_NOT_FOUND, true);
    }

    /**
     * Responding to request by returning JSON format data
     *
     * @param array $array   array
     * @param array $headers headers
     *
     * @return \Illuminate\Http\Response
     */
    protected function respondWithArray(array $array, array $headers = [])
    {
        $response = response(json_encode($array), $this->statusCode, $headers);
        $response->header('Content-Type', 'application/json');

        return $response;
    }
}
