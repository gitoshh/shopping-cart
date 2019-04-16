<?php

class UserTest extends BaseTest
{
    public function setUp(): void
    {
        parent::setUp();
        $this->userCredentials = [
            'email'      => 'new.user@gmail.com',
            'firstName'  => 'new',
            'lastName'   => 'user',
            'middleName' => 'G',
            'password'   => '1231234',
        ];
    }

    public function testSuccessfulUserCreation(): void
    {
        $response = $this->call('POST', '/users', $this->userCredentials);
        $this->assertEquals(200, $response->status());
        $this->assertContains('email', $response->content());
    }

    public function testAlreadyExistingUserEmail(): void
    {
        unset($this->userCredentials['email']);
        $userCredentials['email'] = 'test@gmail.com';
        $response = $this->call('POST', '/users', $this->userCredentials);
        $this->assertEquals(400, $response->status());
    }

    public function testInvalidDataUserCreation(): void
    {
        unset($this->userCredentials['firstName']);
        $response = $this->call('POST', '/users', $this->userCredentials);
        $this->assertEquals(400, $response->status());
        $this->assertContains('The given data was invalid.', $response->content());
    }
}
