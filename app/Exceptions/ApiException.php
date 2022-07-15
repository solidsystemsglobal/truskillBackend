<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class ApiException extends Exception
{
    /**
     * API error codes.
     *
     * @var int
     */
    const CREATE_FAILED = 600;
    const UPDATE_FAILED = 601;
    const DELETE_FAILED = 602;
    const RESTORE_FAILED = 603;
    const NOT_FOUND = 604;
    const UPLOAD_FAILED = 605;
    const DOWNLOAD_FAILED = 606;
    const EXPIRED = 607;
    const ACTION_FAILED = 608;
    const ACCESS_DENIED = 609;
    const FETCH_FAILED = 610;

    /**
     * API error code.
     *
     * @var int
     */
    protected $code;

    /**
     * HTTP response code.
     *
     * @var int
     */
    protected int $httpCode;

    /**
     * Error message.
     *
     * @var string
     */
    protected $message;

    /**
     * Error details.
     *
     * @var array
     */
    protected array $details = [];

    /**
     * Constructor.
     *
     * @param  int $code
     * @param  string $attr
     * @param  string|null $message
     * @param  array|null $data
     * @return void
     */
    public function __construct(
        int $code,
        string $attr,
        string $detailedMessage = null,
        array $data = null
    ) {
        $this->code = $code;
        $this->setHttpResponseCode();
        $this->setMessage($attr);
        $message = $this->message;

        if ($detailedMessage) {
            $this->details['message'] = $detailedMessage;
            $message .= ' ' . $detailedMessage;
        }

        if (!is_null($data)) {
            $this->details['data'] = $data;
        }

        parent::__construct($message);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render(Request $request, Exception $exception = null)
    {
        return response()->json([
            'code'  => $this->code,
            'error' => $this->message,
            'details' => $this->details,
        ], $this->httpCode);
    }

    /**
     * Get error details.
     *
     * @return array
     */
    public function errors(): array
    {
        return $this->details;
    }

    /**
     * Set HTTP response code considering from API error code.
     *
     * @return void
     */
    protected function setHttpResponseCode(): void
    {
        $this->httpCode = match ($this->code) {
            self::CREATE_FAILED, self::UPDATE_FAILED, self::DELETE_FAILED,
            self::RESTORE_FAILED, self::UPLOAD_FAILED, self::DOWNLOAD_FAILED,
            self::ACTION_FAILED, self::FETCH_FAILED => 400,
            self::ACCESS_DENIED => 403,
            self::NOT_FOUND, self::EXPIRED => 404,
            default => 500,
        };
    }

    /**
     * Set HTTP response code considering from API error code.
     *
     * @param  string $attr
     * @return void
     */
    protected function setMessage(string $attr): void
    {
        $msgKey = match ($this->code) {
            self::FETCH_FAILED => 'resource_fetch_failed',
            self::CREATE_FAILED => 'resource_create_failed',
            self::UPDATE_FAILED => 'resource_update_failed',
            self::DELETE_FAILED => 'resource_delete_failed',
            self::RESTORE_FAILED => 'resource_restore_failed',
            self::NOT_FOUND => 'resource_not_found',
            self::UPLOAD_FAILED => 'file_upload_failed',
            self::DOWNLOAD_FAILED => 'file_download_failed',
            self::ACTION_FAILED => 'action_failed',
            self::EXPIRED => 'expired',
            self::ACCESS_DENIED => 'access_denied',
            default => 'something_wrong',
        };

        $this->message = trans('api_exceptions.' . $msgKey, ['attr' => __($attr)]);
    }
}
