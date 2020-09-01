<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class UserService
{
    /** @var UserRepository */
    private $userRepo;

    public function __construct()
    {
        $this->userRepo = app(UserRepository::class);
    }

    /**
     * @return StorageService
     */
    public function storage()
    {
        return app(StorageService::class);
    }

    /**
     * @return LikeService
     */
    public function like()
    {
        return app(LikeService::class);
    }

    /**
     * @return ArchiveService
     */
    public function archive()
    {
        return app(ArchiveService::class);
    }

    public function repo()
    {
        return $this->userRepo;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function all()
    {
        $pagination = $this->userRepo->orderBy('id', 'desc')->paginate()->toArray();

        $users = $pagination['data'];

        unset($pagination['data']);

        return [ 'users' => $users, 'pagination' => $pagination ];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function count()
    {
        return $this->userRepo->all()->count();
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getNames()
    {
        $users = $this->userRepo->all();

        $names = $users->map(function ($user) {
            return $user->name;
        });

        return $names;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getEmails()
    {
        $users = $this->userRepo->all();

        $emails = $users->map(function ($user) {
            return $user->email;
        });

        return $emails;
    }

    /**
     * @param $conditions
     * @return array
     * @throws Exception
     */
    public function filter($conditions)
    {
        if (! isset($conditions['field'])) {
            $conditions['field'] = 'name';
            $conditions['value'] = '';
        }

        switch ($conditions['state']) {
            case 'all':
                $data =  $this->userRepo->withTrashed()
                    ->whereLike($conditions['field'], $conditions['value']);
                break;
            case 'deleted':
                $data = $this->userRepo->onlyTrashed()
                    ->whereLike($conditions['field'], $conditions['value']);
                break;
            default:
                $data = $this->userRepo
                    ->whereLike($conditions['field'], $conditions['value']);
                break;
        }

        $pagination = $data->orderBy('id', 'desc')->paginate($conditions['perPage'])->toArray();
        $users = $pagination['data'];
        unset($pagination['data']);

        return ['users' => $users, 'pagination' => $pagination];
    }

    /**
     * Get specific User Data
     *
     * @param $id
     * @return Collection
     */
    public function show($id)
    {
        return $this->userRepo->withTrashed()->findOrFail($id);
    }

    /**
     * Update User Data
     *
     * @param $id
     * @param array $data
     * @return Collection
     */
    public function update($id, $data)
    {
        $this->userRepo->update($id, $data);

        $user = $this->userRepo->findOrFail($id);

        return $user;
    }

    /**
     * Only update user avatar and return avatar url
     *
     * @param $id
     * @param UploadedFile $avatar
     * @return string
     */
    public function updateAvatar($id, $avatar)
    {
        $avatar = $this->storage()->storeAvatar($avatar);

        $this->storage()->deleteAvatar($this->userRepo->findOrFail($id)->avatar);

        $this->userRepo->update($id, ['avatar' => $avatar]);

        return $avatar;
    }

    /**
     * Create New User
     *
     * @param array $data
     * @return Collection
     */
    public function store($data)
    {
        $avatar = $this->storage()->storeAvatar($data['avatar']);

        $data['avatar'] = $avatar;

        $user = $this->userRepo->create($data);

        return $user;
    }

    /**
     * @param $id
     * @return Boolean
     */
    public function delete($id)
    {
        $user = $this->userRepo->findOrFail($id);

        return $user->delete();
    }

    /**
     * @param $id
     * @return Boolean
     */
    public function restore($id)
    {
        $user = $this->userRepo->onlyTrashed()->findOrFail($id);

        return $user->restore($id);
    }
}
