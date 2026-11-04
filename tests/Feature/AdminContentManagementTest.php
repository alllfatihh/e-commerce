<?php

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Editorial;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminContentManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@notisse.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_update_artist_with_images_and_sync_products(): void
    {
        $this->actingAs($this->admin);

        $artist = Artist::create([
            'name' => 'Original Artist',
            'city' => 'Jakarta',
            'bio' => 'Original bio text',
            'is_newcomer' => false,
            'photo' => 'artists/original_photo.jpg',
            'artwork_preview' => 'artists/original_art.jpg',
        ]);

        $product = Product::create([
            'name' => 'Collaboration Tee',
            'description' => 'A nice tee',
            'price' => 250000,
            'artist' => 'Original Artist',
        ]);

        $newPhoto = UploadedFile::fake()->create('new_artist.jpg', 500, 'image/jpeg');
        $newArtwork = UploadedFile::fake()->create('new_artwork.jpg', 800, 'image/jpeg');

        $response = $this->put("/admin/artists/{$artist->id}", [
            'name' => 'Updated Artist Name',
            'city' => 'Bandung',
            'bio' => 'Updated bio text',
            'is_newcomer' => '1',
            'photo' => $newPhoto,
            'artwork_preview' => $newArtwork,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/admin#section-artists');

        $artist->refresh();
        $this->assertEquals('Updated Artist Name', $artist->name);
        $this->assertEquals('Bandung', $artist->city);
        $this->assertEquals(true, $artist->is_newcomer);
        $this->assertNotNull($artist->photo);
        $this->assertNotNull($artist->artwork_preview);
        Storage::disk('public')->assertExists($artist->getRawOriginal('photo'));
        Storage::disk('public')->assertExists($artist->getRawOriginal('artwork_preview'));

        // Verify product catalog synchronization
        $product->refresh();
        $this->assertEquals('Updated Artist Name', $product->artist);
    }

    public function test_admin_can_update_artwork_with_image_and_price(): void
    {
        $this->actingAs($this->admin);

        $artist = Artist::create([
            'name' => 'Artwork Artist',
            'city' => 'Yogyakarta',
            'bio' => 'Bio',
            'is_newcomer' => false,
        ]);

        $artwork = Artwork::create([
            'artist_id' => $artist->id,
            'title' => 'Original Title',
            'year' => '2023',
            'medium' => 'Oil on Canvas',
            'dimensions' => '100x120 cm',
            'price' => 15000000.00,
            'status' => 'available',
            'description' => 'Original curatorial notes',
            'image' => 'artworks/original.jpg',
        ]);

        $newImage = UploadedFile::fake()->create('updated_artwork.png', 800, 'image/png');

        $response = $this->put("/admin/artworks/{$artwork->id}", [
            'artist_id' => $artist->id,
            'title' => 'Updated Masterpiece',
            'year' => '2024',
            'medium' => 'Acrylic on Linen',
            'dimensions' => '150x150 cm',
            'price' => '18500000.50',
            'status' => 'reserved',
            'description' => 'Updated curatorial notes and significance.',
            'image' => $newImage,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/admin#section-artworks');

        $artwork->refresh();
        $this->assertEquals('Updated Masterpiece', $artwork->title);
        $this->assertEquals('2024', $artwork->year);
        $this->assertEquals('Acrylic on Linen', $artwork->medium);
        $this->assertEquals('150x150 cm', $artwork->dimensions);
        $this->assertEquals(18500000.50, (float) $artwork->price);
        $this->assertEquals('reserved', $artwork->status);
        $this->assertEquals('Updated curatorial notes and significance.', $artwork->description);
        Storage::disk('public')->assertExists($artwork->getRawOriginal('image'));
    }

    public function test_admin_can_update_editorial_with_images(): void
    {
        $this->actingAs($this->admin);

        $artist = Artist::create([
            'name' => 'Editorial Artist',
            'city' => 'Surabaya',
            'bio' => 'Bio',
            'is_newcomer' => false,
        ]);

        $editorial = Editorial::create([
            'artist_id' => $artist->id,
            'title' => 'Original Headline',
            'author' => 'Curator John',
            'excerpt' => 'Short summary',
            'content' => 'Full editorial article paragraph 1',
            'content_2' => 'Paragraph 2',
            'image' => 'editorials/cover.jpg',
        ]);

        $newImage1 = UploadedFile::fake()->create('editorial_hero.jpg', 1200, 'image/jpeg');
        $newImage2 = UploadedFile::fake()->create('editorial_body.jpg', 800, 'image/jpeg');

        $response = $this->put("/admin/editorials/{$editorial->id}", [
            'artist_id' => $artist->id,
            'title' => 'Updated Headline Feature',
            'author' => 'Curator Jane',
            'excerpt' => 'Updated short summary',
            'content' => 'Updated full editorial body',
            'content_2' => 'Updated second section',
            'image' => $newImage1,
            'image_2' => $newImage2,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/admin#section-artworks');

        $editorial->refresh();
        $this->assertEquals('Updated Headline Feature', $editorial->title);
        $this->assertEquals('Curator Jane', $editorial->author);
        $this->assertEquals('Updated full editorial body', $editorial->content);
        Storage::disk('public')->assertExists($editorial->getRawOriginal('image'));
        Storage::disk('public')->assertExists($editorial->getRawOriginal('image_2'));
    }

    public function test_storefront_displays_updated_artist_artwork_and_editorial(): void
    {
        $artist = Artist::create([
            'name' => 'Storefront Artist',
            'city' => 'Semarang',
            'bio' => 'Storefront Bio',
            'is_newcomer' => true,
            'photo' => 'artists/storefront.jpg',
        ]);

        $artwork = Artwork::create([
            'artist_id' => $artist->id,
            'title' => 'Storefront Artwork Title',
            'year' => '2025',
            'medium' => 'Mixed Media',
            'dimensions' => '90x90 cm',
            'price' => 5000000,
            'status' => 'available',
            'description' => 'Dynamic Artwork Curatorial Description',
            'image' => 'artworks/storefront.jpg',
        ]);

        $editorial = Editorial::create([
            'artist_id' => $artist->id,
            'title' => 'Storefront Editorial Story',
            'author' => 'Editor in Chief',
            'excerpt' => 'An insightful retrospective',
            'content' => 'Story body text',
            'image' => 'editorials/story.jpg',
        ]);

        // 1. Check Artists collaboration page renders artist
        $collabResponse = $this->get('/artists');
        $collabResponse->assertStatus(200);
        $collabResponse->assertSee('Storefront Artist');

        // 2. Check Artwork page renders dynamic curatorial details
        $artworkResponse = $this->get("/artwork/{$artwork->id}");
        $artworkResponse->assertStatus(200);
        $artworkResponse->assertSee('Storefront Artwork Title');
        $artworkResponse->assertSee('Dynamic Artwork Curatorial Description');

        // 3. Check Editorials archive page renders story
        $editorialResponse = $this->get('/editorials');
        $editorialResponse->assertStatus(200);
        $editorialResponse->assertSee('Storefront Editorial Story');
    }
}
