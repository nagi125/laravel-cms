<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\StorePostRequest;
use App\Http\Requests\Api\Admin\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostSummaryResource;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    public function __construct(private readonly PostService $postService) {}

    public function index(Request $request)
    {
        $status = $request->query('status');

        return PostSummaryResource::collection(
            $this->postService->paginateAdmin(is_string($status) ? $status : null, $request->query('per_page')),
        );
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        return PostResource::make($this->postService->create($user, $request->validated()))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(int $id): PostResource
    {
        return PostResource::make($this->findPost($id));
    }

    public function update(UpdatePostRequest $request, int $id): PostResource
    {
        return PostResource::make($this->postService->update($this->findPost($id), $request->validated()));
    }

    public function destroy(int $id): Response
    {
        $this->postService->delete($this->findPost($id));

        return response()->noContent();
    }

    private function findPost(int $id): Post
    {
        $post = $this->postService->findById($id);

        if ($post === null) {
            throw new NotFoundHttpException;
        }

        return $post;
    }
}
