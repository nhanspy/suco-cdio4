<?php

namespace App\Services;

use App\Exceptions\Auth\AuthDeleteLikeException;
use App\Exceptions\Auth\AuthLikeException;
use App\Repositories\LikeRepository;
use App\Services\Auth\AuthService;
use Exception;

class LikeService
{
    /** @var LikeRepository */
    private $likeRepo;

    public function __construct()
    {
        $this->likeRepo = app(LikeRepository::class);
    }

    /**
     * @return AuthService
     */
    private function auth()
    {
        return app(AuthService::class);
    }

    /**
     * @return DeviceService
     */
    private function device()
    {
        return app(DeviceService::class);
    }

    /**
     * @param null $perPage
     * @param null $userId
     * @return array
     */
    public function all($perPage = null, $userId = null)
    {
        $pagination = $this->pagination($perPage, $userId);

        foreach ($pagination as $translation) {
            $translation->project;
        }

        $pagination = $pagination->toArray();
        $archives = $pagination['data'];
        unset($pagination['data']);

        return ['likes' => $archives, 'pagination' => $pagination];
    }

    /**
     * @param null $perPage
     * @param null $userId
     * @return mixed
     */
    private function pagination($perPage = null, $userId = null)
    {
        if ($userId) {
            return $pagination = $this->user()
                ->repo()
                ->with('likes')
                ->findOrFail($userId)
                ->archives()
                ->paginate($perPage);
        }

        if ($this->auth()->check()) {
            return $pagination = $this->auth()
                ->authRepo()
                ->with('likes')
                ->findOrFail($this->auth()->id())
                ->archives()
                ->paginate($perPage);
        }

        return $pagination = $this->device()
            ->repo()
            ->with('likes')
            ->findOrFail($this->device()->id())
            ->archives()
            ->paginate($perPage);
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function hasLiked($id)
    {
        if ($this->auth()->check()) {
            return $this->likeRepo->where([
                'translation_id' => $id,
                'user_id' => $this->auth()->id()
            ])->count() ? true : false;
        }

        return $this->likeRepo->where([
            'translation_id' => $id,
            'device_id' => $this->device()->id()
        ])->count() ? true : false;
    }

    /**
     * @param $id
     * @return int
     * @throws Exception
     */
    public function count($id)
    {
        return $this->likeRepo->where(['translation_id' => $id])->count();
    }

    /**
     * @param $id
     * @return int
     * @throws AuthLikeException
     * @throws Exception
     */
    public function create($id)
    {
        if ($this->hasLiked($id)) {
            throw new AuthLikeException();
        }

        if ($this->auth()->check()) {
            $this->likeRepo->create([
                'translation_id' => $id,
                'user_id' => $this->auth()->id()
            ]);
        } else {
            $this->likeRepo->create([
                'translation_id' => $id,
                'device_id' => $this->device()->id()
            ]);
        }

        return $this->count($id);
    }

    /**
     * @param $id
     * @return int
     * @throws AuthDeleteLikeException
     * @throws Exception
     */
    public function delete($id)
    {
        if (!$this->hasLiked($id)) {
            throw new AuthDeleteLikeException();
        }

        if ($this->auth()->check()) {
            $like = $this->likeRepo->where([
                'translation_id' => $id,
                'user_id' => $this->auth()->id()
            ])->first();
        } else {
            $like = $this->likeRepo->where([
                'translation_id' => $id,
                'device_id' => $this->device()->id()
            ])->first();
        }

        $this->likeRepo->delete($like->id);

        return $this->count($id);
    }
}
