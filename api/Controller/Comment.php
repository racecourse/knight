<?php
/**
 * @license MIT
 * @copyright Copyright (c) 2017
 * @author: bugbear
 * @date: 2017/5/7
 * @time: 下午2:38
 */

namespace Knight\Controller;

use Knight\Component\Controller;
use Knight\Model\Comment as Discuss;
use Knight\Model\Post;
use Slim\Http\Request;

class Comment extends Controller
{

    /**
     * get article comments
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     * @throws \Exception
     */
    public function comments(Request $request)
    {
        $artId = $request->getParam('artId');
        $page = $request->getQuery('page');
        $page = abs(intval($page)) ?: 1;
        $pageSize = 20;
        $comment = new Discuss();
        $article = (new Post())->findById($artId);
        if (!$article || $article->permission > 1) {
            return $this->response
                ->withStatus(400)
                ->json([
                    'message' => 'Article not found',
                    'code' => 1,
                ]);
        }
        $where = [
            'artId' => $artId,
        ];
        $options = [
            'offset' => ($page - 1) * $pageSize,
            'limit' => $pageSize,
            'orderBy' => ['created' => 'desc'],
        ];
        $list = $comment->find($where, $options);
        $list = $comment->toArray($list);
        return $this->response->json([
            'message' => 'ok',
            'code' => 0,
            'data' => [
                'list' => $list,
                'total' => 0,
                'page' => $page,
                'pageSize' => $pageSize,
            ]
        ]);
    }

    /**
     * add a comment with article id
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     */
    public function add(Request $request)
    {
        $artId = $request->getParam('id');
        $content = $request->getPayload('content');
        $email = $request->getPayload('email');
        $site = $request->getPayload('site');
        $username = $request->getPayload('username');
        $username = preg_replace('/\s/', '', $username);
        $content = preg_replace('/\s/', '', $content);
        if (!$username || !$content) {
            return $this->response
                ->withStatus(400)
                ->json([
                    'message' => '参数错误了',
                    'code' => 1,
                ]);
        }
        $check = (new Post())->findById($artId);
        if (!$check) {
            return $this->response
                ->withStatus(400)
                ->json([
                    'message' => '文章不存在',
                    'code' => 2,
                ]);
        }
        $data = [
            'artId' => $artId,
            'email' => $email,
            'site' => $site,
            'username' => $username,
            'content' => $content,
            'created' => time(),
        ];
        $comment = new Discuss();
        $comment->insert($data);

        return $this->response->json([
            'message' => 'ok',
            'code' => 0,
        ]);
    }
}