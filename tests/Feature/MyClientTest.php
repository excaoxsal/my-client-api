<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\MyClient;

class MyClientTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    use RefreshDatabase;

    /** @test */
    public function can_create_client()
    {
        Storage::fake('s3');

        $response = $this->postJson('/api/clients', [
            'name' => 'PT Test',
            'slug' => 'pt-test',
            'is_project' => '0',
            'self_capture' => '1',
            'client_prefix' => 'PTTS',
            'client_logo' => UploadedFile::fake()->image('logo.jpg'),
            'address' => 'Jl Test',
            'phone_number' => '123456',
            'city' => 'Bandung',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('my_client', ['slug' => 'pt-test']);
    }

    /** @test */
    public function can_get_all_clients()
    {
        MyClient::factory()->create(['slug' => 'client-one']);
        $response = $this->getJson('/api/clients');
        $response->assertStatus(200)->assertJsonFragment(['slug' => 'client-one']);
    }

    /** @test */
    public function can_soft_delete_client()
    {
        $client = MyClient::factory()->create();
        $response = $this->deleteJson("/api/clients/{$client->id}");
        $response->assertStatus(200);
        $this->assertNotNull(MyClient::withTrashed()->find($client->id)->deleted_at);
    }
}
