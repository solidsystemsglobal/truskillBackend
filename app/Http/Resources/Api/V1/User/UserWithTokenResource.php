<?php

namespace App\Http\Resources\Api\V1\User;

use App\Http\Resources\Api\JsonResource;

/**
 * @OA\Schema(
 *    schema="UserWithToken",
 *    type="object",
 *    @OA\Property(
 *        property="id",
 *        type="integer",
 *        description="User ID",
 *        example=9876,
 *    ),
 *    @OA\Property(
 *        property="name",
 *        type="string",
 *        description="User name",
 *        example="John Doe",
 *    ),
 *    @OA\Property(
 *        property="email",
 *        type="string",
 *        description="User email",
 *        example="example@test.com",
 *    ),
 *    @OA\Property(
 *        property="token",
 *        type="string",
 *        description="User api token",
 *        example="",
 *    ),
 *    @OA\Property(
 *        property="createdAt",
 *        type="string",
 *        description="User creation datetime",
 *        example="2022-04-20 13:15:31",
 *    ),
 *    @OA\Property(
 *        property="updatedAt",
 *        type="string",
 *        description="User last modification datetime",
 *        example="2022-04-24 18:00:00",
 *    ),
 * )
 */
class UserWithTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'token' => $this->plainTextToken,
            'createdAt' => $this->toDateTime($this->created_at),
            'updatedAt' => $this->toDateTime($this->updated_at),
        ];
    }
}
