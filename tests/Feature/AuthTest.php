<?php

namespace Tests\Feature;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed level data
        LevelModel::create([
            'level_id' => 1,
            'level_kode' => 'ADM',
            'level_nama' => 'Administrator'
        ]);
        
        LevelModel::create([
            'level_id' => 2,
            'level_kode' => 'MNG',
            'level_nama' => 'Manager'
        ]);
        
        LevelModel::create([
            'level_id' => 3,
            'level_kode' => 'STF',
            'level_nama' => 'Staff/Kasir'
        ]);
    }

    /**
     * Test register page can be accessed
     */
    public function test_register_page_can_be_accessed(): void
    {
        $response = $this->get('/register');
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.regis');
        $response->assertViewHas('levels');
    }

    /**
     * Test user can register with valid data
     */
    public function test_user_can_register_with_valid_data(): void
    {
        $userData = [
            'username' => 'testuser',
            'password' => 'password123',
            'nama' => 'Test User',
            'level_id' => 1
        ];

        $response = $this->postJson('/register', $userData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User berhasil ditambahkan'
            ]);

        $this->assertDatabaseHas('m_user', [
            'username' => 'testuser',
            'nama' => 'Test User',
            'level_id' => 1
        ]);
    }

    /**
     * Test registration fails with duplicate username
     */
    public function test_registration_fails_with_duplicate_username(): void
    {
        // Create existing user
        UserModel::create([
            'username' => 'existinguser',
            'password' => Hash::make('password123'),
            'nama' => 'Existing User',
            'level_id' => 1
        ]);

        $userData = [
            'username' => 'existinguser',
            'password' => 'password123',
            'nama' => 'New User',
            'level_id' => 1
        ];

        $response = $this->postJson('/register', $userData);

        $response->assertStatus(400)
            ->assertJson([
                'status' => false,
                'message' => 'Username telah digunakan'
            ]);
    }

    /**
     * Test registration fails with invalid data
     */
    public function test_registration_fails_with_invalid_data(): void
    {
        $userData = [
            'username' => 'ab', // too short
            'password' => '123', // too short
            'nama' => '',
            'level_id' => ''
        ];

        $response = $this->postJson('/register', $userData);

        $response->assertStatus(422); // Validation error
    }

    /**
     * Test login page can be accessed
     */
    public function test_login_page_can_be_accessed(): void
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test authenticated user is redirected from login page
     */
    public function test_authenticated_user_redirected_from_login(): void
    {
        $user = UserModel::create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
            'nama' => 'Test User',
            'level_id' => 1
        ]);

        $response = $this->actingAs($user)->get('/login');
        
        $response->assertRedirect('/');
    }

    /**
     * Test user can login with correct credentials
     */
    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = UserModel::create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
            'nama' => 'Test User',
            'level_id' => 1
        ]);

        $credentials = [
            'username' => 'testuser',
            'password' => 'password123'
        ];

        $response = $this->postJson('/login', $credentials);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login Berhasil',
                'redirect' => url('/')
            ]);

        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test login fails with incorrect credentials
     */
    public function test_login_fails_with_incorrect_credentials(): void
    {
        UserModel::create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
            'nama' => 'Test User',
            'level_id' => 1
        ]);

        $credentials = [
            'username' => 'testuser',
            'password' => 'wrongpassword'
        ];

        $response = $this->postJson('/login', $credentials);

        $response->assertStatus(200)
            ->assertJson([
                'status' => false,
                'message' => 'Login Gagal'
            ]);

        $this->assertGuest();
    }

    /**
     * Test login fails with non-existent user
     */
    public function test_login_fails_with_nonexistent_user(): void
    {
        $credentials = [
            'username' => 'nonexistent',
            'password' => 'password123'
        ];

        $response = $this->postJson('/login', $credentials);

        $response->assertStatus(200)
            ->assertJson([
                'status' => false,
                'message' => 'Login Gagal'
            ]);

        $this->assertGuest();
    }

    /**
     * Test user can logout
     */
    public function test_user_can_logout(): void
    {
        $user = UserModel::create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
            'nama' => 'Test User',
            'level_id' => 1
        ]);

        $response = $this->actingAs($user)->get('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     * Test guest cannot access logout
     */
    public function test_guest_cannot_logout(): void
    {
        $response = $this->get('/logout');

        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated user can access dashboard
     */
    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = UserModel::create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
            'nama' => 'Test User',
            'level_id' => 1
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test guest cannot access dashboard
     */
    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    /**
     * Test password is hashed when creating user
     */
    public function test_password_is_hashed_when_creating_user(): void
    {
        $userData = [
            'username' => 'testuser',
            'password' => 'plainpassword',
            'nama' => 'Test User',
            'level_id' => 1
        ];

        $this->postJson('/register', $userData);

        $user = UserModel::where('username', 'testuser')->first();
        
        $this->assertNotNull($user);
        $this->assertNotEquals('plainpassword', $user->password);
        $this->assertTrue(Hash::check('plainpassword', $user->password));
    }
}
