<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\CoreController;
use Modules\User\Http\Requests\ProfileRequest;
use Modules\User\Models\Profile;
use Modules\User\Repositories\ProfileRepository;

class ProfileController extends CoreController
{
    public function __construct(protected readonly ProfileRepository $repository) {}

    public function index(Request $request): mixed
    {
        return $this->repository->with('customer')->all();
    }

    public function store(ProfileRequest $request): mixed
    {
        $validatedData = $request->validated();

        return $this->repository->create($validatedData);
    }

    public function show(string $id): mixed
    {
        return Profile::query()->with('customer')->findOrFail($id);
    }

    public function update(ProfileRequest $request, string $id): mixed
    {
        $validatedData = $request->validated();

        return Profile::query()->findOrFail($id)->update($validatedData);
    }

    public function destroy(string $id): mixed
    {
        return Profile::query()->findOrFail($id)->delete();
    }
}
