<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\ListCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Requests\StatisticRequest;
use App\Services\CommentService;

class CommentController extends Controller
{
    /** @var CommentService */
    private $commentService;

    public function __construct()
    {
        $this->commentService = app(CommentService::class);
    }

    public function all(ListCommentRequest $request)
    {
        $perPage = $request->get('perPage');

        $response['comments'] = $this->commentService->all($perPage);

        return $this->response('comment.all.success', $response);
    }

    public function show($id)
    {
        $response['comment'] = $this->commentService->show($id);

        return $this->response('comment.show.success', $response);
    }

    public function create(CreateCommentRequest $request)
    {
        $data =  $request->only('translation_id', 'content');

        $response['comment'] = $this->commentService->create($data);

        return $this->response('comment.create.success', $response);
    }

    public function update(UpdateCommentRequest $request, $id)
    {
        $data = $request->only(['content']);

        $response['comment'] = $this->commentService->update($id, $data);

        return $this->response('comment.update.success', $response);
    }

    public function delete($id)
    {
        $this->commentService->delete($id);

        return $this->response('comment.delete.success');
    }
}
