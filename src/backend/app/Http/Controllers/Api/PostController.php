<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostSummaryResource;
use App\Services\PostService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    public function __construct(private readonly PostService $postService) {}

    public function index(Request $request)
    {
        return PostSummaryResource::collection(
            $this->postService->paginatePublished($request->query('per_page')),
        );
    }

    public function show(string $slug): PostResource
    {
        $post = $this->postService->findPublishedBySlug($slug);

        if ($post === null) {
            throw new NotFoundHttpException;
        }

        return PostResource::make($post);
    }
}
