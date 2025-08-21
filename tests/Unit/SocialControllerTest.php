<?php
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SocialControllerTest extends TestCase
{
    public function test_redirect_linkedin()
    {
        $response = $this->get('/api/v1/auth/linkedin');
        $response->assertRedirect(); // Verifica se há redirecionamento
    }
    public function test_redirect_facebook()
    {
        $response = $this->get('/api/v1/auth/facebook');
        $response->assertRedirect(); // Verifica se há redirecionamento
    }
    public function test_redirect_google()
    {
        $response = $this->get('/api/v1/auth/google');
        $response->assertRedirect(); // Verifica se há redirecionamento
    }
}