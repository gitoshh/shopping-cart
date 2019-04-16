<?php

class CategoryControllerTest extends BaseTest
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testNewCategoryCreationSuccessfully(): void
    {
        $categoryPayload = [
            'categoryName' => 'Electronics',
            'parentId'     => 1,
        ];
        $this->post('/categories', $categoryPayload, $this->headers);
        $this->assertResponseOk();
        $this->seeInDatabase('nodes', ['parentID' => 1]);
    }

    public function testNewCategoryUnsuccessfulBadRequest(): void
    {
        $categoryPayload = [
            'parentId' => 1,
        ];
        $this->post('/categories', $categoryPayload, $this->headers);
        $this->assertResponseStatus(400);
    }
}
