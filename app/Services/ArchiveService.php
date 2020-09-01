<?php

namespace App\Services;

use App\Exceptions\Auth\AuthArchiveException;
use App\Exceptions\Auth\AuthDeleteArchiveException;
use App\Repositories\ArchiveRepository;
use App\Services\Auth\AuthService;
use Exception;

class ArchiveService
{
    /** @var ArchiveRepository */
    private $archiveRepo;

    public function __construct()
    {
        $this->archiveRepo = app(ArchiveRepository::class);
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
     * @return UserService
     */
    private function user()
    {
        return app(UserService::class);
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

        return ['archives' => $archives, 'pagination' => $pagination];
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
                ->with('archives')
                ->withTrashed()
                ->findOrFail($userId)
                ->archives()
                ->paginate($perPage);
        }

        if ($this->auth()->check()) {
            return $pagination = $this->auth()
                ->repo()
                ->with('archives')
                ->findOrFail($this->auth()->id())
                ->archives()
                ->paginate($perPage);
        }

        return $pagination = $this->device()
            ->repo()
            ->with('archives')
            ->findOrFail($this->device()->id())
            ->archives()
            ->paginate($perPage);
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function hasArchived($id)
    {
        if ($this->auth()->check()) {
            return $this->archiveRepo->withTrashed()->where([
                'translation_id' => $id,
                'user_id' => $this->auth()->id()
            ])->count() ? true : false;
        }

        return $this->archiveRepo->withTrashed()->where([
            'translation_id' => $id,
            'device_id' => $this->device()->id()
        ])->count() ? true : false;
    }

    /**
     * @param $id
     * @return int
     * @throws Exception
     */
    public function count($id = null)
    {
        if ($id) {
            return $this->archiveRepo->where(['translation_id' => $id])->count();
        }

        if ($this->auth()->check()) {
            return $this->archiveRepo->where(['user_id' => $this->auth()->id()])->count();
        }

        return $this->archiveRepo->where(['device_id' => $this->device()->id()])->count();
    }

    /**
     * @param $id
     * @return int
     * @throws AuthArchiveException
     * @throws Exception
     */
    public function create($id)
    {
        if ($this->hasArchived($id)) {
            throw new AuthArchiveException();
        }

        if ($this->auth()->check()) {
            $this->archiveRepo->create([
                'translation_id' => $id,
                'user_id' => $this->auth()->id()
            ]);
        } else {
            $this->archiveRepo->create([
                'translation_id' => $id,
                'device_id' => $this->device()->id()
            ]);
        }

        return $this->count($id);
    }

    /**
     * @param $id
     * @return bool
     * @throws AuthDeleteArchiveException
     * @throws Exception
     */
    public function delete($id)
    {
        if (!$this->hasArchived($id)) {
            throw new AuthDeleteArchiveException();
        }

        if ($this->auth()->check()) {
            $archive = $this->archiveRepo->withTrashed()->where([
                'translation_id' => $id,
                'user_id' => $this->auth()->id()
            ])->first();
        } else {
            $archive = $this->archiveRepo->withTrashed()->where([
                'translation_id' => $id,
                'device_id' => $this->device()->id()
            ])->first();
        }

        $this->archiveRepo->forceDelete($archive->id);

        return $this->count($id);
    }

    /**
     * After logging in. We have to move all archives from current device
     * to current logged in user
     *
     * @throws Exception
     */
    public function moveArchivesFromDeviceToAuthAfterLogin()
    {
        // get all archived translations by device
        $translations = $this->device()->archives();

        foreach ($translations as $translation) {
            if (!$this->hasArchived($translation->id)) {
                $this->archiveRepo->where([
                    'translation_id' => $translation->id,
                    'device_id' => $this->device()->id()
                ])->first()->update([
                    'device_id' => null,
                    'user_id' => $this->auth()->id()
                ]);
            } else {
                $archive = $this->archiveRepo->where([
                    'translation_id' => $translation->id,
                    'device_id' => $this->device()->id()
                ])->first();

                $this->archiveRepo->forceDelete($archive->id);
            }
        }
    }
}
