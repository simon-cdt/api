<?php

namespace Simoncdt\Api\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use Simoncdt\Api\Model\UserModel;

class UserCtrl
{
    public function allUsers(Request $request, Response $response)
    {

        try {
            $users = UserModel::selectAll();
            $response = $response->withJson([
                'users' => $users,
            ]);
            return $response;
        } catch (PDOException $e) {
            $error = [
                "message" => $e->getMessage()
            ];

            return $this->jsonResponseData($response, $error, 500);
        }
    }

    public function oneUser(Request $request, Response $response, $args)
    {

        try {
            $user = UserModel::selectById($args["id"]);
            $response = $response->withJson([
                'user' => $user,
            ]);
            return $response;
        } catch (PDOException $e) {
            $error = [
                "message" => $e->getMessage()
            ];

            return $this->jsonResponseData($response, $error, 500);
        }
    }
    public function insertUser(Request $request, Response $response)
    {
        try {
            $data = $request->getParsedBody();

            if (!isset($data['pseudo']) || !isset($data['mdp'])) {
                $error = [
                    'message' => 'Invalid request data. Pseudo and mdp are required.',
                ];
                return $this->jsonResponseData($response, $error, 400);
            }


            $newUser = new UserModel($data);

            $validToken = $newUser->validToken($data["id"], $data["token"]);
            if ($validToken) {

                $result = $newUser->insertUser();

                if ($result) {
                    $response = $response->withJson([
                        'message' => 'User inserted successfully',
                    ]);
                    return $response->withStatus(201);
                } else {
                    $error = [
                        'message' => 'Failed to insert user',
                    ];
                    return $this->jsonResponseData($response, $error, 505);
                }
            } else {
                $error = [
                    'message' => 'Forbidden Access',
                ];
                return $this->jsonResponseData($response, $error, 403);
            }
        } catch (PDOException $e) {
            $error = [
                'message' => $e->getMessage(),
            ];
            return $this->jsonResponseData($response, $error, 502);
        }
    }

    private function jsonResponseData(Response $response, array $data, int $statusCode): Response
    {
        $response->getBody()->write(json_encode($data));

        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus($statusCode);
    }
}
