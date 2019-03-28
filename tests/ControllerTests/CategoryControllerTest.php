<?php

class CategoryControllerTest extends BaseTest
{
    public function testNewCategoryCreationSuccessfully(): void
    {
        $categoryPayload = [
            'name'     => 'Electronics',
            'parentId' => 1,
        ];
        $response = $this->call('POST', '/categories', $categoryPayload);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('nodes', ['parentID' => 1]);
    }

    public function testNewCategoryUnsuccessfulBadRequest(): void
    {
        $categoryPayload = [
            'parentId' => 1,
        ];
        $response = $this->call('POST', '/categories', $categoryPayload);
        $this->assertEquals(400, $response->status());
    }
}