<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Log;

use function Illuminate\Log\log;

class PostContoller extends Controller
{
    //

    public function createPost(Request $request)
    {

        // dd("create post");
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'date' => 'nullable|string',
            'content' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'status' => 'required|string',
            'images.*' => 'nullable|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm|max:102400',
            'extra_details' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validator = $validator->validated();

        $imagePaths = [];


        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Manually create directory if it doesn't exist
                $directory = storage_path('app/public/posts');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                // Generate a unique filename
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();

                // Store the file
                $path = $image->storeAs('posts', $filename, 'public');

                $imagePaths[] = $path;
            }
        }



        $post = Post::create([
            'title' => $validator['title'],
            'subtitle' => $validator['subtitle'] ?? null,
            'category' => $validator['category'] ?? null,
            'date' => $validator['date'] ?? null,
            'content' => $validator['content'] ?? null,
            'author' => $validator['author'] ?? null,
            'status' => $validator['status'],
            'images' => $imagePaths,
            'extra_details' => $validator['extra_details'] ?? [],
        ]);

        return response()->json([
            'message' => 'Post created successfully!',
            'post' => $post,
        ], 201);
    }

    public function updatePost(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'status' => 'sometimes|string',
            'images.*' => 'nullable|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm|max:102400',
            'deleted_images' => 'nullable|array',
            'deleted_images.*' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // Handle image deletions
        if (!empty($validated['deleted_images'])) {
            foreach ($validated['deleted_images'] as $imageToDelete) {
                Storage::disk('public')->delete($imageToDelete);
                $post->images = array_values(array_filter($post->images, fn($image) => $image !== $imageToDelete));
            }
        }

        // Handle new image uploads
        $newImagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('posts', 'public');
                $newImagePaths[] = $path;
            }
        }

        // Update post
        $post->update([
            'title' => $validated['title'] ?? $post->title,
            'subtitle' => $validated['subtitle'] ?? $post->subtitle,
            'category' => $validated['category'] ?? $post->category,
            'content' => $validated['content'] ?? $post->content,
            'status' => $validated['status'] ?? $post->status,
            'images' => array_merge($post->images ?? [], $newImagePaths)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
            'post' => $post
        ]);
    }

    public function deletePost($id)
    {
        try {
            // Find the post or fail with 404
            $post = Post::findOrFail($id);

            // Delete images from storage
            if (!empty($post->images)) {
                foreach ($post->images as $image) {
                    try {
                        // Remove 'storage/' prefix if present
                        $imagePath = str_replace('storage/', '', $image);

                        if (Storage::disk('public')->exists($imagePath)) {
                            Storage::disk('public')->delete($imagePath);
                        }
                    } catch (\Exception $e) {
                        // Log error but continue with deletion
                        FacadesLog::error("Failed to delete image {$image}: " . $e->getMessage());
                    }
                }
            }

            // Delete the post
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully!',
            ]);
        } catch (\Exception $e) {
            FacadesLog::error("Post deletion failed: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete post. Please try again.',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function getPosts(Request $request)
    {
        $posts = Post::all();
        // $posts = Post::paginate(6);

        return response()->json([
            'message' => 'Posts retrieved successfully!',
            'posts' => $posts,
        ]);
    }
    public function getPaginatedPosts(Request $request)
    {
        // $posts = Post::all();
        $posts = Post::paginate(6);

        return response()->json([
            'message' => 'Posts retrieved successfully!',
            'posts' => $posts,
        ]);
    }

    public function getPostById($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Post retrieved successfully',
            'post' => $post,
        ]);
    }
}
