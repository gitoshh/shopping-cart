<?php

class UserTest extends BaseTest
{
    public function testSuccessfulUserCreation(): void
    {
        $userCredentials = [
            'email'    => 'new.user@gmail.com',
            'firstName' => 'new',
            'lastName' => 'user',
            'middleName' => 'G',
            'password' => '1231234'
        ];
        $response = $this->call('POST', '/users', $userCredentials);
        $this->assertEquals(200, $response->status());
        $this->assertContains('email', $response->content());
    }

    public function testAlreadyExistingUserEmail(): void
    {
        $userCredentials = [
            'email'    => 'test@gmail.com',
            'firstName' => 'new',
            'lastName' => 'user',
            'middleName' => 'G',
            'password' => '1231234'
        ];
        $response = $this->call('POST', '/users', $userCredentials);
        $this->assertEquals(500, $response->status());
    }

    public function testInvalidDataUserCreation(): void
    {
        $userCredentials = [
            'email'    => 'test1@gmail.com',
            'firstName' => '',
            'lastName' => 'user',
            'middleName' => 'G',
            'password' => '1231234'
        ];
        $response = $this->call('POST', '/users', $userCredentials);
        $this->assertEquals(400, $response->status());
        $this->assertContains('The given data was invalid.', $response->content());
    }
}