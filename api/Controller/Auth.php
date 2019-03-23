<?php
/**
 * @license   https://github.com/Init/licese.md
 * @copyright Copyright (c) 2017
 * @author    : bugbear
 * @date      : 2017/3/20
 * @time      : 下午8:19
 */

namespace Knight\Controller;

use Ben\Config;
use Knight\Component\Controller;
use Knight\Model\User;
use Knight\Middleware\Auth as JWTAuth;
use Zend\Diactoros\ServerRequest as Request;

class Auth extends Controller
{

    /**
     * login api
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     * @throws \Exception
     */
    public function login(Request $request)
    {
        $payload = $request->getParsedBody();
        $username = $payload['username'] ?? null;
        $password = $payload['password'] ?? null;
        if (!$username || !$password) {
            return $this->json([
                'message' => 'param error',
                'code' => 1
            ], 400);
        }

        $user = new User();
        $userInfo = yield $user->findOne(['username' => $username]);
        if (!$userInfo) {
            return $this->json([
                'message' => 'user not found',
                'code' => 2,
            ], 400);
        }

        $verify = password_verify($password, $userInfo->password);
        if (!$verify) {
            return $this->json([
                'message' => 'password incorrect',
                'code' => 3,
            ], 400);
        }

        $info = [
            'id' => $userInfo->id,
            'username' => $userInfo->username,
            'nickname' => $userInfo->nickname,
            'email' => $userInfo->email,
        ];
        $jwt = new JWTAuth(Config::get('jwt'));
        $token = $jwt->encode($info);
        $userInfo = $userInfo->toArray();
        unset($userInfo['password']);
        $userInfo['expired'] = Config::get('jwt.expired');

        return $this->json([
            'message' => 'ok',
            'data' => [
                'user' => $userInfo,
                'token' => $token,
            ]
        ]);
    }


    /**
     * register api just for test
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     */
    public function register(Request $request)
    {
        $username = $request->getPayload('username');
        $password = $request->getPayload('password');
        $email = $request->getPayload('email');
        $confirm = $request->getPayload('confirm');
        $response = new Response;
        if (!$username) {
            return $response
                ->withStatus(404)
                ->json([
                    'message' => 'Illegal Username',
                    'code' => 1,
                ]);
        }

        if (!$password || strlen($password) < 5 || $password !== $confirm) {
            return $response->withStatus(400)
                ->json([
                    'message' => 'Password Incorrect',
                    'code' => 2,
                ]);
        }

        $user = new User();
        $user->username = $username;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->email = $email;
        $user->nickname = $username;
        $user->created = time();
        $user->save();
        $userInfo = $user->toArray();

        return $response->json([
            'message' => 'ok',
            'data' => $userInfo,
        ]);
    }
}
