<?php

namespace App\Http\Responses;

/**
 * Extends the JsonResponse class to deliver a shorter syntax of json errors.
 *
 * Class JsonErrorResponse
 * @package App\Http\Reponses
 */
class JsonErrorResponse extends \Illuminate\Http\JsonResponse
{
    /**
     * JsonErrorResponse constructor.
     *
     * @param mixed|null $data
     * @param int $status
     * @param array $headers
     * @param int $options
     */
    public function __construct($data, $status = 200, $headers = [], $options = 0)
    {
        $message = ['errors' => []];

        if (is_string($data)) {
            $message['errors'] = ['message' => $data];
        }

        if (is_array($data)) {
            $message['errors'] = $data;
        }

        parent::__construct($message, $status, $headers, $options);
    }

}