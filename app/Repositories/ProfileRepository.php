<?php

declare(strict_types=1);

namespace Modules\User\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\User\Models\Profile;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

class ProfileRepository extends BaseRepository
{
    /**
     * @var array<string, string>
     */
    protected $fieldSearchable = [
        'contact' => 'like',
        'customer.email' => 'like',
        'customer.name' => 'like',
    ];

    public function boot(): void
    {
        try {
            $this->pushCriteria(app(RequestCriteria::class));
        } catch (RepositoryException $e) {
        }
    }

    /**
     * Configure the Model
     *
     * @return class-string<Profile>
     **/
    public function model(): string
    {
        return Profile::class;
    }
}
