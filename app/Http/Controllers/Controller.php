<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public $request;

    /**
     * Controller constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Retrieves payload value.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getPayload(string $key)
    {
        return $this->request->get($key);
    }
}
