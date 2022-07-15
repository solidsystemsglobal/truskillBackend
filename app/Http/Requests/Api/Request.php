<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest as BaseRequest;

class Request extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Get request validated data.
     *
     * @param  array|string $keys
     * @return mixed
     */
    public function getData($keys = null)
    {
        $keys = is_array($keys) && isArrayOf($keys, 'string') || is_string($keys) ? $keys : null;

        if (!method_exists($this, 'getDataMap')) {
            if ($keys) {
                return is_string($keys)
                    ? $this->input($keys)
                    : $this->all($keys);
            }

            return $this->all();
        } else {
            $dataMap = $this->getDataMap();
            $requestParams = $this->keys();

            foreach ($dataMap as $externalKey => $value) {
                if (!in_array($externalKey, $requestParams)) {
                    continue;
                }

                if (is_a($value, RequestData::class)) {
                    $internalKey = $value->getKey();
                    $transformer = $value->getTransformer();
                    $value = $this->all($externalKey);
                    $value = call_user_func($transformer, $value[$externalKey]);
                } elseif (is_string($value)) {
                    $internalKey = $value;
                    $value = $this->all($externalKey);
                    $value = $value[$externalKey];
                } else {
                    throw new \Exception(trans('exceptions.invalid_data_map'));
                }

                if (!$keys || is_array($keys) && in_array($internalKey, $keys)) {
                    $data[$internalKey] = $value;
                } elseif (is_string($keys) && $internalKey === $keys) {
                    return $value;
                }
            }

            return $data ?? (is_string($keys) ? null : []);
        }
    }
}
