<?php

class AuthenticationTest extends BaseTest
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAuthenticate(): void
    {
        $this->get('/');

        $this->assertEquals(
            $this->app->version(), $this->response->getContent()
        );
    }

    public function testUnExistingUserLogin(): void
    {
        $userCredentials = [
            'email'    => 'fake.test@gmail.com',
            'password' => '1231234',
        ];
        $response = $this->call('POST', '/auth/login', $userCredentials);
        $this->assertEquals(400, $response->status());
        $this->assertContains('Email does not exist.', $response->content());
    }

    public function testLoginUnauthorisedUser(): void
    {
        $userCredentials = [
            'email'    => 'test@gmail.com',
            'password' => '1231234',
        ];
        $response = $this->call('POST', '/auth/login', $userCredentials);
        $this->assertEquals(401, $response->status());
        $this->assertContains('Email or password is wrong.', $response->content());
    }

    public function testLoginSuccessful(): void
    {
        $userCredentials = [
            'email'    => 'test@gmail.com',
            'password' => '123123',
        ];
        $response = $this->call('POST', '/auth/login', $userCredentials);
        $this->assertContains('token', $response->content());
        $this->assertEquals(200, $response->status());
    }
}
