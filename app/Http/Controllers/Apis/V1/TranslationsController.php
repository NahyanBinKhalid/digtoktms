<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Apis\ApiController;
use App\Repositories\Interfaces\TranslationInterface;
use App\Http\Requests\TranslationRequest;
use App\Http\Requests\TranslationSearchRequest;

class TranslationsController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/translations",
     *     tags={"Translations"},
     *     summary="List of Translations",
     *     @OA\Parameter(
     *         name="locale",
     *         in="query",
     *         required=false,
     *         description="Locale code",
     *         @OA\Schema(type="string", example="en")
     *     ),
     *     @OA\Parameter(
     *         name="tag",
     *         in="query",
     *         required=false,
     *         description="Tag",
     *         @OA\Schema(type="string", example="mobile")
     *     ),
     *     @OA\Parameter(
     *         name="key",
     *         in="query",
     *         required=false,
     *         description="Translation key",
     *         @OA\Schema(type="string", example="home.title")
     *     ),
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         required=false,
     *         description="Search query",
     *         @OA\Schema(type="string", example="welcome")
     *     ),
     * 
     *     @OA\Response(response=200, description="Profile Details"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="Internal Server Error"),
     *
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function index(TranslationInterface $translation, TranslationSearchRequest $request)
    {
        $translations = $translation->listing($request->input());
        return $this->successResponse($translations, 'Translations retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/translations",
     *     summary="Add a Translation",
     *     tags={"Translations"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"locale", "key", "content", "tags"},
     *                 @OA\Property(property="locale", type="string", example="en"),
     *                 @OA\Property(property="key", type="string", example="home.title"),
     *                 @OA\Property(property="content", type="string", example="Welcome"),
     *                 @OA\Property(
     *                     property="tags",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"mobile", "web"}
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Translation Added Successfully"),
     *     @OA\Response(response=401, description="Data Incomplete or Invalid"),
     *     @OA\Response(response=422, description="Validation Error"),
     *     @OA\Response(response=500, description="Internal Server Error"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function store(TranslationInterface $translation, TranslationRequest $request)
    {
        $record = $translation->create($request->except('_method', '_token'));
        return $this->successResponse($record, 'Translation added successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/translations/{id}",
     *     tags={"Translations"},
     *     summary="Show Translation",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Profile Details"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="Internal Server Error"),
     *
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function show(TranslationInterface $translation, string $id)
    {
        $record = $translation->getById($id);
        return $this->successResponse($record, 'Translation added successfully');
    }

    /**
     * @OA\Put(
     *     path="/api/v1/translations/{id}",
     *     summary="Update a Translation",
     *     tags={"Translations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"locale", "key", "content", "tags"},
     *                 @OA\Property(property="locale", type="string", example="en"),
     *                 @OA\Property(property="key", type="string", example="home.title"),
     *                 @OA\Property(property="content", type="string", example="Welcome"),
     *                 @OA\Property(
     *                     property="tags",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"mobile", "web"}
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="User Registered Successfully"),
     *     @OA\Response(response=401, description="Data Incomplete or Invalid"),
     *     @OA\Response(response=422, description="Validation Error"),
     *     @OA\Response(response=500, description="Internal Server Error"),
     * 
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function update(TranslationInterface $translation, TranslationRequest $request, string $id)
    {
        $record = $translation->updateById($id, $request->except('_method', '_token'));
        return $this->successResponse($record, 'Translation updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/translations/{id}",
     *     summary="Delete a Translation",
     *     tags={"Translations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response=200, description="User Registered Successfully"),
     *     @OA\Response(response=401, description="Data Incomplete or Invalid"),
     *     @OA\Response(response=422, description="Validation Error"),
     *     @OA\Response(response=500, description="Internal Server Error"),
     * 
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function destroy(TranslationInterface $translation, string $id)
    {
        $translation->deleteById($id);
        return $this->successResponse(null, 'Translation deleted successfully');
    }
}