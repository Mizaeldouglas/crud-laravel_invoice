<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;

class TesteController extends Controller
{
    use HttpResponses;
    public function index()
    {

        return $this->response('Autorized', 200);
    }
}
