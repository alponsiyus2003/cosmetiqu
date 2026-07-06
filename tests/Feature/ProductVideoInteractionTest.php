<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVideo;
use App\Models\ProductVideoComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVideoInteractionTest extends TestCase
{
    use RefreshDatabase;

    public function test_shorts_video_page_uses_correct_like_and_comment_routes(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => 'Skincare',
            'slug' => 'skincare',
            'description' => 'Skincare',
            'is_active' => true,
        ]);

        $product = Product::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'name' => 'Serum Test',
            'slug' => 'serum-test',
            'description' => 'Test product',
            'price' => 150000,
            'stock' => 10,
            'is_active' => true,
        ]);

        $video = ProductVideo::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'video_path' => 'videos/products/test.mp4',
            'title' => 'Test video',
            'description' => 'Test video description',
            'views' => 0,
            'likes' => 0,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('videos.show', $video->id));

        $response->assertStatus(200)
            ->assertSee(route('videos.like', $video), false)
            ->assertSee(route('videos.comment', $video), false);
    }

    public function test_home_page_shows_short_video_section_with_link_to_full_page(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => 'Skincare',
            'slug' => 'skincare',
            'description' => 'Skincare',
            'is_active' => true,
        ]);

        $product = Product::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'name' => 'Serum Test',
            'slug' => 'serum-test',
            'description' => 'Test product',
            'price' => 150000,
            'stock' => 10,
            'is_active' => true,
        ]);

        ProductVideo::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'video_path' => 'videos/products/test.mp4',
            'title' => 'Test video',
            'description' => 'Test video description',
            'views' => 10,
            'likes' => 5,
            'is_active' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200)
            ->assertSee('Shorts Video')
            ->assertSee(route('videos.index'));
    }

    public function test_authenticated_user_can_reply_to_a_video_comment(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => 'Skincare',
            'slug' => 'skincare',
            'description' => 'Skincare',
            'is_active' => true,
        ]);

        $product = Product::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'name' => 'Serum Test',
            'slug' => 'serum-test',
            'description' => 'Test product',
            'price' => 150000,
            'stock' => 10,
            'is_active' => true,
        ]);

        $video = ProductVideo::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'video_path' => 'videos/products/test.mp4',
            'title' => 'Test video',
            'description' => 'Test video description',
            'views' => 0,
            'likes' => 0,
            'is_active' => true,
        ]);

        $comment = ProductVideoComment::create([
            'product_video_id' => $video->id,
            'user_id' => $user->id,
            'comment' => 'Komentar awal',
        ]);

        $response = $this->actingAs($user)->postJson(route('videos.reply', $video->id), [
            'comment_id' => $comment->id,
            'reply' => 'Balasan berhasil',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('reply.reply', 'Balasan berhasil');

        $this->assertDatabaseHas('product_video_comment_replies', [
            'product_video_comment_id' => $comment->id,
            'reply' => 'Balasan berhasil',
        ]);
    }
}
