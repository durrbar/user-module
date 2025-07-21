<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Exceptions\DurrbarException;
use Modules\Core\Http\Controllers\CoreController;
use Modules\User\Http\Requests\ProfileRequest;
use Modules\User\Models\Profile;
use Modules\User\Repositories\ProfileRepository;
use Prettus\Validator\Exceptions\ValidatorException;

class ProfileController extends CoreController
{
    public $repository;

    public function __construct(ProfileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Collection|Profile
     */
    public function index(Request $request)
    {
        return $this->repository->with('customer')->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     *
     * @throws ValidatorException
     */
    public function store(ProfileRequest $request)
    {
        $validatedData = $request->all();

        return $this->repository->create($validatedData);
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            return $this->repository->with('customer')->findOrFail($id);
        } catch (DurrbarException $e) {
            throw new DurrbarException(NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(ProfileRequest $request, $id)
    {
        try {
            $validatedData = $request->all();

            return $this->repository->findOrFail($id)->update($validatedData);
        } catch (DurrbarException $e) {
            throw new DurrbarException(NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            return $this->repository->findOrFail($id)->delete();
        } catch (DurrbarException $e) {
            throw new DurrbarException(NOT_FOUND);
        }
    }
}
