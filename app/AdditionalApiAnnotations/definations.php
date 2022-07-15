<?php

/**
 * @OA\Schema(
 *    schema="links",
 *    type="object",
 *    @OA\Property(
 *        property="first",
 *        type="string",
 *        example="http:\/\/api.domain\/version\/resource?page=1"
 *    ),
 *    @OA\Property(
 *        property="last",
 *        type="string",
 *        example="http:\/\/api.domain\/version\/resource?page=8"
 *    ),
 *    @OA\Property(
 *        property="prev",
 *        type="string",
 *        example="http:\/\/api.domain\/version\/resource?page=3"
 *    ),
 *    @OA\Property(
 *        property="next",
 *        type="string",
 *        example="http:\/\/api.domain\/version\/resource?page=5"
 *    )
 * )
 */

/**
 * @OA\Schema(
 *    schema="meta",
 *    type="object",
 *    @OA\Property(
 *        property="current_page",
 *        type="integer",
 *        example=4
 *    ),
 *    @OA\Property(
 *        property="from",
 *        type="integer",
 *        example=46
 *    ),
 *    @OA\Property(
 *        property="last_page",
 *        type="integer",
 *        example=8
 *    ),
 *    @OA\Property(
 *        property="path",
 *        type="string",
 *        example="http:\/\/api.domain\/version\/resource"
 *    ),
 *    @OA\Property(
 *        property="per_page",
 *        type="integer",
 *        example=15
 *    ),
 *    @OA\Property(
 *        property="to",
 *        type="integer",
 *        example=60
 *    ),
 *    @OA\Property(
 *        property="total",
 *        type="integer",
 *        example=15
 *    )
 * )
 */

/**
 * @OA\Schema(
 *    schema="repository-search",
 *    type="string",
 *    description="Should confirm the following format ""text,[field1,field2,...]"", where text is not empty and the field names are valid",
 *    example="John,[name,email]",
 * )
 */

/**
 * @OA\Schema(
 *    schema="repository-sort",
 *    type="string",
 *    description="Should confirm the following format ""field,dir"", where field name is valid and the dir is either ""asc"" or ""desc""",
 *    example="id,asc",
 * )
 */

/**
 * @OA\Schema(
 *    schema="repository-pagination",
 *    type="string",
 *    description="Should confirm the following format ""page,perPage"", where both page and perPage are positive integers",
 *    example="1,15",
 * )
 */

/**
 * @OA\Schema(
 *    schema="repository-filters",
 *    type="string",
 *    description="A valid JSON string of filters. Default modes: =, !=, like, !like, included, !included, range, !range, contains, !contains, >, >=, <, <=, null, !null",
 *    example="[{""field"":""name"",""mode"":""like"",""value"":""test""},{""field"":""id"",""mode"":""range"",""value"":[1,5]}]",
 * )
 */

/**
 * @OA\Schema(
 *    schema="ERR401",
 *    type="object",
 *    @OA\Property(
 *        property="message",
 *        type="string",
 *        example="Unauthenticated."
 *    )
 * )
 */

/**
 * @OA\Schema(
 *    schema="ERR403",
 *    type="object",
 *    @OA\Property(
 *        property="message",
 *        type="string",
 *        example="User does not have the right permissions."
 *    )
 * )
 */

/**
 * @OA\Schema(
 *    schema="ERR422",
 *    type="object",
 *    @OA\Property(
 *        property="message",
 *        type="string",
 *        example="The given data was invalid."
 *    ),
 *    @OA\Property(
 *        property="errors",
 *        type="object",
 *        @OA\Property(
 *            property="field_name",
 *            type="array",
 *            @OA\Items(
 *                type="string",
 *                example="The field_name field is required."
 *            )
 *        ),
 *    ),
 * )
 */

/**
 * @OA\Schema(
 *    schema="ERR500",
 *    type="object",
 *    @OA\Property(
 *        property="message",
 *        type="string",
 *        example="Server Error"
 *    )
 * )
 */

/**
 * @OA\Schema(
 *    schema="ERR_FETCH_FAILED",
 *    type="object",
 *    @OA\Property(
 *        property="code",
 *        type="integer",
 *        example="610"
 *    ),
 *    @OA\Property(
 *        property="error",
 *        type="string",
 *        example="Failed to fetch data."
 *    ),
 *    @OA\Property(
 *        property="details",
 *        type="object",
 *        @OA\Property(
 *            property="message",
 *            type="string",
 *            example="More detailed error message."
 *        )
 *    )
 * )
 */

/**
 * @OA\Schema(
 *    schema="ERR_CREATE_FAILED",
 *    type="object",
 *    @OA\Property(
 *        property="code",
 *        type="integer",
 *        example="600"
 *    ),
 *    @OA\Property(
 *        property="error",
 *        type="string",
 *        example="Failed to create a resource."
 *    ),
 *    @OA\Property(
 *        property="details",
 *        type="object",
 *        @OA\Property(
 *            property="message",
 *            type="string",
 *            example="More detailed error message."
 *        )
 *    )
 * )
 */

/**
 * @OA\Schema(
 *    schema="ERR_UPDATE_FAILED",
 *    type="object",
 *    @OA\Property(
 *        property="code",
 *        type="integer",
 *        example="601"
 *    ),
 *    @OA\Property(
 *        property="error",
 *        type="string",
 *        example="Failed to update the resource."
 *    ),
 *    @OA\Property(
 *        property="details",
 *        type="object",
 *        @OA\Property(
 *            property="message",
 *            type="string",
 *            example="More detailed error message."
 *        )
 *    )
 * )
 */

/**
 * @OA\Schema(
 *    schema="ERR_DELETE_FAILED",
 *    type="object",
 *    @OA\Property(
 *        property="code",
 *        type="integer",
 *        example="602"
 *    ),
 *    @OA\Property(
 *        property="error",
 *        type="string",
 *        example="Failed to delete the resource."
 *    ),
 *    @OA\Property(
 *        property="details",
 *        type="object",
 *        @OA\Property(
 *            property="message",
 *            type="string",
 *            example="More detailed error message."
 *        )
 *    )
 * )
 */

/**
 * @OA\Schema(
 *    schema="ERR_RESTORE_FAILED",
 *    type="object",
 *    @OA\Property(
 *        property="code",
 *        type="integer",
 *        example="603"
 *    ),
 *    @OA\Property(
 *        property="error",
 *        type="string",
 *        example="Failed to restore the resource."
 *    ),
 *    @OA\Property(
 *        property="details",
 *        type="object",
 *        @OA\Property(
 *            property="message",
 *            type="string",
 *            example="More detailed error message."
 *        )
 *    )
 * )
 */

/**
 * @OA\Schema(
 *    schema="ERR_NOT_FOUND",
 *    type="object",
 *    @OA\Property(
 *        property="code",
 *        type="integer",
 *        example="604"
 *    ),
 *    @OA\Property(
 *        property="error",
 *        type="string",
 *        example="Resource not found."
 *    )
 * )
 */

/**
 * @OA\Schema(
 *    schema="ERR_UPLOAD_FAILED",
 *    type="object",
 *    @OA\Property(
 *        property="code",
 *        type="integer",
 *        example="605"
 *    ),
 *    @OA\Property(
 *        property="error",
 *        type="string",
 *        example="Failed to upload the file."
 *    ),
 *    @OA\Property(
 *        property="details",
 *        type="object",
 *        @OA\Property(
 *            property="message",
 *            type="string",
 *            example="More detailed error message."
 *        )
 *    )
 * )
 */

/**
 * @OA\Schema(
 *    schema="ERR_DOWNLOAD_FAILED",
 *    type="object",
 *    @OA\Property(
 *        property="code",
 *        type="integer",
 *        example="606"
 *    ),
 *    @OA\Property(
 *        property="error",
 *        type="string",
 *        example="Failed to download the file."
 *    ),
 *    @OA\Property(
 *        property="details",
 *        type="object",
 *        @OA\Property(
 *            property="message",
 *            type="string",
 *            example="More detailed error message."
 *        )
 *    )
 * )
 */

/**
 * @OA\Schema(
 *    schema="ERR_EXPIRED",
 *    type="object",
 *    @OA\Property(
 *        property="code",
 *        type="integer",
 *        example="607"
 *    ),
 *    @OA\Property(
 *        property="error",
 *        type="string",
 *        example="Resource is expired."
 *    ),
 *    @OA\Property(
 *        property="details",
 *        type="object",
 *        @OA\Property(
 *            property="message",
 *            type="string",
 *            example="More detailed error message."
 *        )
 *    )
 * )
 */

/**
 * @OA\Schema(
 *    schema="ERR_ACTION_FAILED",
 *    type="object",
 *    @OA\Property(
 *        property="code",
 *        type="integer",
 *        example="608"
 *    ),
 *    @OA\Property(
 *        property="error",
 *        type="string",
 *        example="Failed to perform the action."
 *    ),
 *    @OA\Property(
 *        property="details",
 *        type="object",
 *        @OA\Property(
 *            property="message",
 *            type="string",
 *            example="More detailed error message."
 *        )
 *    )
 * )
 */

/**
 * @OA\Schema(
 *    schema="ERR_ACCESS_DENIED",
 *    type="object",
 *    @OA\Property(
 *        property="code",
 *        type="integer",
 *        example="609"
 *    ),
 *    @OA\Property(
 *        property="error",
 *        type="string",
 *        example="Resource is inaccessible."
 *    ),
 *    @OA\Property(
 *        property="details",
 *        type="object",
 *        @OA\Property(
 *            property="message",
 *            type="string",
 *            example="More detailed error message."
 *        )
 *    )
 * )
 */
