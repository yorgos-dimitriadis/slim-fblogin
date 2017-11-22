<?php

namespace App\Controllers;

use App\Controllers\Controller;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};

class UsersController extends Controller
{
    public function index(Request $request, Response $response)
    {
	    $mapper = new \App\Models\UserMapper($this->c['db']);
	    $users = $mapper->getUsers();

	    return $users;
    }


}
