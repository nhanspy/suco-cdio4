<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\User\UserGetArchivesRequest;
use App\Http\Requests\User\UserGetLikesRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateAvatarRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Requests\UserFilterRequest;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /** @var UserService */
    private $userService;

    public function __construct()
    {
        $this->userService = app(UserService::class);
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function all()
    {
        $data = $this->userService->all();

        return $this->response('user.all.success', $data);
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function count()
    {
        $count = $this->userService->count();

        return $this->response('user.count.success', ['count' => $count]);
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function getNames()
    {
        $names = $this->userService->getNames();

        return $this->response('user.get_names.success', ['names' => $names]);
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function getEmails()
    {
        $emails = $this->userService->getEmails();

        return $this->response('user.get_emails.success', ['emails' => $emails]);
    }

    /**
     * @param UserFilterRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function filter(UserFilterRequest $request)
    {
        $conditions = $request->only(['perPage', 'state', 'field', 'value']);

        $response = $this->userService->filter($conditions);

        return $this->response('user.filter.success', $response);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $user = $this->userService->show($id);

        return $this->response('user.show.success', ['user' => $user]);
    }

    /**
     * @param $id
     * @param UserUpdateRequest $request
     * @return JsonResponse
     */
    public function update($id, UserUpdateRequest $request)
    {
        $data = $request->only(['name', 'language', 'position']);

        $user = $this->userService->update($id, $data);

        return $this->response('user.update.success', ['user' => $user]);
    }

    /**
     * @param $id
     * @param UserUpdateAvatarRequest $request
     * @return JsonResponse
     */
    public function updateAvatar($id, UserUpdateAvatarRequest $request)
    {
        $avatar = $this->userService->updateAvatar($id, $request->file('avatar'));

        return $this->response('user.update_avatar.success', ['avatar' => $avatar]);
    }

    /**
     * @param UserStoreRequest $request
     * @return JsonResponse
     */
    public function store(UserStoreRequest $request)
    {
        $data = $request->only(['name', 'email', 'password', 'language', 'position']);
        $data['avatar'] = $request->file('avatar');

        $user = $this->userService->store($data);

        return $this->response('user.create.success', ['user' => $user]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        $this->userService->delete($id);

        return $this->response('user.delete.success');
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function restore($id)
    {
        $this->userService->restore($id);

        return $this->response('user.restore.success');
    }

    /**
     * @param $id
     * @param UserGetArchivesRequest $request
     * @return JsonResponse
     */
    public function archives($id, UserGetArchivesRequest $request)
    {
        $data = $this->userService->archive()->all( $request->get('perPage'), $id);

        return $this->response('user.get.archives.success', $data);
    }

    /**
     * @param $id
     * @param UserGetLikesRequest $request
     * @return JsonResponse
     */
    public function likes($id, UserGetLikesRequest $request)
    {
        $data = $this->userService->like()->all($request->get('perPage'), $id);

        return $this->response('user.get.likes.success', $data);
    }
}
