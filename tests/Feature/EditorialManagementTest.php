<?php

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\Editorial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EditorialManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::create([
            'name' => 'Admin User',
            'email' => 'admin@notisse.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
    }

    private function createArtist(): Artist
    {
        return Artist::create([
            'name' => 'Mustafa Alatas',
            'city' => 'Temanggung',
            'bio' => 'Seniman lukis ekspresionis kontemporer.',
        ]);
    }

    public function test_admin_can_store_editorial_with_two_paragraphs_and_two_images(): void
    {
        Storage::fake('public');
        $admin = $this->createAdmin();
        $artist = $this->createArtist();
        $this->actingAs($admin);

        $image1 = UploadedFile::fake()->create('portrait.jpg', 100, 'image/jpeg');
        $image2 = UploadedFile::fake()->create('landscape.jpg', 100, 'image/jpeg');

        $response = $this->post('/admin/editorials', [
            'artist_id' => $artist->id,
            'title' => 'Menafsir Ruang dan Kuasa',
            'author' => 'Laksa Dawantara',
            'excerpt' => 'Ringkasan artikel kuratorial',
            'content' => 'Paragraf pembuka di atas galeri foto.',
            'content_2' => 'Paragraf kedua lanjutan di bawah galeri foto.',
            'image' => $image1,
            'image_2' => $image2,
        ]);

        $response->assertRedirect();
        $editorial = Editorial::where('title', 'Menafsir Ruang dan Kuasa')->first();
        $this->assertNotNull($editorial);
        $this->assertEquals($artist->id, $editorial->artist_id);
        $this->assertEquals('Paragraf pembuka di atas galeri foto.', $editorial->content);
        $this->assertEquals('Paragraf kedua lanjutan di bawah galeri foto.', $editorial->content_2);
        $this->assertNull($editorial->video_url);

        // Verify storage files exist
        $rawImg1 = $editorial->getRawOriginal('image');
        $rawImg2 = $editorial->getRawOriginal('image_2');
        $this->assertNotNull($rawImg1);
        $this->assertNotNull($rawImg2);
        Storage::disk('public')->assertExists($rawImg1);
        Storage::disk('public')->assertExists($rawImg2);
    }

    public function test_admin_can_update_editorial_paragraphs_and_second_image(): void
    {
        Storage::fake('public');
        $admin = $this->createAdmin();
        $artist = $this->createArtist();
        $this->actingAs($admin);

        $image1 = UploadedFile::fake()->create('old_img1.jpg', 100, 'image/jpeg');
        $editorial = Editorial::create([
            'artist_id' => $artist->id,
            'title' => 'Initial Title',
            'author' => 'Author A',
            'content' => 'Old content paragraph 1',
            'content_2' => 'Old content paragraph 2',
            'image' => $image1->store('editorials', 'public'),
        ]);

        $newImage2 = UploadedFile::fake()->create('new_img2.jpg', 100, 'image/jpeg');

        $response = $this->put('/admin/editorials/'.$editorial->id, [
            'artist_id' => $artist->id,
            'title' => 'Updated Title',
            'author' => 'Author B',
            'content' => 'Updated paragraph 1 text',
            'content_2' => 'Updated paragraph 2 text',
            'image_2' => $newImage2,
        ]);

        $response->assertRedirect();
        $editorial->refresh();
        $this->assertEquals('Updated Title', $editorial->title);
        $this->assertEquals('Updated paragraph 1 text', $editorial->content);
        $this->assertEquals('Updated paragraph 2 text', $editorial->content_2);

        $rawImg2 = $editorial->getRawOriginal('image_2');
        $this->assertNotNull($rawImg2);
        Storage::disk('public')->assertExists($rawImg2);
    }

    public function test_editorial_detail_page_renders_both_paragraphs(): void
    {
        $artist = $this->createArtist();
        $editorial = Editorial::create([
            'artist_id' => $artist->id,
            'title' => 'Eksplorasi Warna Mentah',
            'author' => 'Laksa Dawantara',
            'content' => 'Narasi paragraf satu di atas foto.',
            'content_2' => 'Narasi paragraf dua di bawah foto.',
        ]);

        $response = $this->get('/editorial/'.$editorial->id);
        $response->assertOk();
        $response->assertSee('Eksplorasi Warna Mentah');
        $response->assertSee('Narasi paragraf satu di atas foto.');
        $response->assertSee('Narasi paragraf dua di bawah foto.');
    }
}
