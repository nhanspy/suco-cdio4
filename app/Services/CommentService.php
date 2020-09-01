<?php

namespace App\Services;

use App\Entities\Comment;
use App\Entities\Translation;
use App\Exceptions\OwnerForbiddenException;
use App\Repositories\CommentRepository;
use App\Services\Auth\AuthService;
use App\Traits\ValidOwnerTrait;

class CommentService
{
    use ValidOwnerTrait;

    /** @var AuthService */
    private $auth;

    /** @var CommentRepository */
    private $commentRepo;

    public function __construct()
    {
        $this->auth = app(AuthService::class);
        $this->commentRepo = app(CommentRepository::class);
    }

    public function all($perPage)
    {
        return $this->commentRepo
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);
    }

    public function show($id)
    {
        return $this->commentRepo->with('user')->findOrFail($id);
    }

    public function create($data)
    {
        $data['user_id'] = $this->auth->id();

        $comment = $this->commentRepo->create($data);

        return $this->commentRepo->with('user')->findOrFail($comment->_id);
    }

    public function update($id, $data)
    {
        $comment = $this->commentRepo->findOrFail($id);

        $this->isOwner($comment->user_id, $this->auth->id());

        $this->commentRepo->update($id, $data);

        return $this->commentRepo->with('user')->findOrFail($id);
    }

    public function delete($id)
    {
        $comment = $this->commentRepo->findOrFail($id);

        $this->isOwner($comment->user_id, $this->auth->id());

        return $this->commentRepo->delete($id);
    }

    public function getByTranslation($translationId, $perPage)
    {
        $data = $this->commentRepo
            ->with('user')
            ->where(['translation_id' => (int)$translationId])
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage)->toArray();

        $data['data'] = $this->isCurrentUserComment($data['data']);

        return $data;
    }

    private function isCurrentUserComment($data)
    {
        $collection = collect($data);

        $collection->transform(function ($item) {
            $item['is_current_user'] = $item['user_id'] === $this->auth->id();

            return $item;
        });

        return $collection;
    }

    public function count()
    {
        return $this->commentRepo->count();
    }
}
