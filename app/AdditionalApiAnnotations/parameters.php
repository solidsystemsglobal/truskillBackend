<?php

/**
 * @OA\Parameter(
 *    in="header",
 *    name="Authorization",
 *    @OA\Schema(
 *        type="string"
 *    ),
 *    required=true,
 *    description="The access token (e.g Bearer eyJ0eXAiOiJKV1...)"
 * )
 */

/**
 * @OA\Parameter(
 *    in="query",
 *    name="page",
 *    @OA\Schema(
 *        type="integer"
 *    ),
 *    required=false,
 *    description="The page number of the list"
 * )
 */

/**
 * @OA\Parameter(
 *    in="query",
 *    name="perPage",
 *    @OA\Schema(
 *        type="integer"
 *    ),
 *    required=false,
 *    description="The items number per page"
 * )
 */

/**
 * @OA\Parameter(
 *    in="query",
 *    name="sort",
 *    @OA\Schema(
 *        type="string"
 *    ),
 *    required=false,
 *    description="Set for items sorting per field (e.g. {""field"":""id"",""dir"":""desc""})"
 * )
 */

/**
 * @OA\Parameter(
 *    in="query",
 *    name="filters",
 *    @OA\Schema(
 *        type="string"
 *    ),
 *    required=false,
 *    description="Set for items filtering (e.g. [{""field"":""name"",""mode"":""like"",""value"":""test""},{""field"":""id"",""mode"":""range"",""value"":[2,5]}])"
 * )
 */

/**
 * @OA\Parameter(
 *    in="query",
 *    name="search",
 *    @OA\Schema(
 *        type="string"
 *    ),
 *    required=false,
 *    description="Set a global search for the items (e.g. {""fields"":[""firstname"", ""lastname""],""value"":""john""})",
 * )
 */

/**
 * @OA\Parameter(
 *    in="query",
 *    name="trashed",
 *    @OA\Schema(
 *        type="string"
 *    ),
 *    required=false,
 *    description="Set to 1/true to return only soft deleted(trashed) items"
 * )
 */

/**
 * @OA\Parameter(
 *    in="query",
 *    parameter="repository-search",
 *    name="search",
 *    required=false,
 *    description="Data search parameters",
 *    @OA\Schema(
 *        ref="#/components/schemas/repository-search",
 *    ),
 * )
 */

/**
 * @OA\Parameter(
 *    in="query",
 *    parameter="repository-filters",
 *    name="filters",
 *    required=false,
 *    description="Data filter parameters",
 *    @OA\Schema(
 *        ref="#/components/schemas/repository-filters",
 *    ),
 * )
 */

/**
 * @OA\Parameter(
 *    in="query",
 *    parameter="repository-sort",
 *    name="sort",
 *    required=false,
 *    description="Data sorting parameters",
 *    @OA\Schema(
 *        ref="#/components/schemas/repository-sort",
 *    ),
 * )
 */

/**
 * @OA\Parameter(
 *    in="query",
 *    parameter="repository-pagination",
 *    name="pagination",
 *    required=false,
 *    description="Data pagination parameters",
 *    @OA\Schema(
 *        ref="#/components/schemas/repository-pagination",
 *    ),
 * )
 */

/**
 * @OA\Parameter(
 *    in="query",
 *    parameter="repository-pagination-required",
 *    name="pagination",
 *    required=true,
 *    description="Data pagination parameters",
 *    @OA\Schema(
 *        ref="#/components/schemas/repository-pagination",
 *    ),
 * )
 */
