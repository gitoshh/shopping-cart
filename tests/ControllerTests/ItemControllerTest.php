<?php

class ItemControllerTest extends BaseTest
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testFetchItemsSuccessfully()
    {
        $this->get('/items', $this->headers);
        $this->assertResponseOk();
    }
}
