<?php
/**
 * @license   https://github.com/Init/licese.md
 * @copyright Copyright (c) 2017
 * @author    : bugbear
 * @date      : 2017/4/2
 * @time      : 下午4:38
 */
namespace Knight\Controller;

use Knight\Component\Controller;
use Knight\Model\Comment;
use Knight\Model\Post;
use Knight\Model\Category;
use Photo;

class Admin extends Controller
{

    public function survey()
    {
        $article = new Post();
//        $articleNumber = $article->count();
//        $commentNumber = (new Comment())->count();
        $photoNumber = 0;
        $albumNumber = 0;
        $this->response->json([
            'message' => 'ok',
            'code' => 0,
            'data' => [
                'articleNumber' => 4,
                'commentNumber' => 3,
                'albumNumber' => 2,
                'photoNumber' => 1,
                'pv' => 1,
                'ip' => 1,
            ]
        ]);
    }

    public function article()
    {
        $pageSize = 10;
        $page = $this->request->getQuery('page');
        $page = abs($page) ?: 1;
        $offset = ($page - 1) * $pageSize;
        $article = new Post();
        $condition = [
            'id' => ['$gt' => 0]
        ];
        $options = [
            'limit' => $pageSize,
            'offset' => $offset,
            'order' => [
                'created' => 'desc',
            ]
        ];
        $posts = $article->find($condition, $options);
        $posts = $article->toArray($posts);
        $total = $article->count($condition);
        $ret = [
            'total' => $total, // @fixme
            'page' => $page,
            'pageSize' => $pageSize,
            'list' => $posts,
        ];
        $this->response->json([
            'message' => 'ok',
            'code' => 0,
            'data' => $ret,
        ]);
    }

    /**
     * @body string title
     * @body string content
     * @body integer cateId
     */
    public function create()
    {
        $request = $this->request;
        $response = $this->response;
        $title = $this->body('title');
        $content = $this->body('content');
        $tags = $this->body('tags');
        $cateId = $this->body('cateId');
        $permission = $this->body('permission');
        if (!in_array($permission, [0, 1, 2])) {
            return $this->response
                ->withStatus(400)
                ->json([
                    'message' => 'Illegal param permission',
                    'code' => 1,
                ]);
        }
        if (!$title) {
            return $response
                ->withStatus(400)
                ->json([
                    'message' => 'title required',
                    'code' => 1,
                ]);
        }
        if (!$content) {
            return $response
                ->withStatus(400)
                ->json([
                    'message' => 'content can not empty'
                ]);
        }
        $created = $this->body('created');
        $created = $created ? strtotime($created) : time();
        $tags = \is_array($tags) ? \implode(',', $tags) : $tags;
        $post = [
            'userId' => 1,
            'title' => $title,
            'content' => $content,
            'tags' => $tags,
            'permission' => $permission,
            'cateId' => $cateId,
            'created' => $created,
        ];
        $article = new Post();
        $article->insert($post);
        $response->json([
            'code' => 0,
            'message' => 'ok',
        ]);
    }

    
    public function category()
    {
        $category = new Category();
        $cate = $category->findAll();
        $list = $category->toArray($cate);
        $this->response->json([
            'message' => 'ok',
            'code' => 0,
            'data' => $list,
        ]);
    }

    public function edit()
    {
        $id = $this->request->getParam('id');
        $title = $this->body('title');
        $tags = $this->body('tags');
        $content = $this->body('content');
        $cateId = $this->body('cateId');
        $time = $this->body('time');
        $permission = $this->body('permission');
        if (!$title || !$content) {
            return $this->response
                ->withStatus(400)
                ->json([
                    'message' => 'content && title are required',
                    'code' => 1,
                ]);
        }
        $post = new Post();
        $art = $post->findById($id);
        if (!$art) {
            return $this->response
            ->withStatus(400)
            ->json([
                'message' => 'article not found',
                'code' => 2,
            ]);
        }
        if (is_array($tags)) {
            $tags = implode(',', $tags);
        }
        $art->title = $title;
        $art->content = $content;
        $art->cateId = $cateId;
        $art->tags = $tags;
        $art->permission = $permission;
        if ($time) {
            $time = mktime($time);
            $art->created = $time;
        }
        $result = $art->update();
        var_dump($result->toArray());
        $this->response->json([
            'message' => 'ok',
            'code' => 0,
        ]);
    }

    public function drop()
    {
        $id = $this->request->getParam('id');
        if (!intval($id)) {
            return $this->response
                ->withStatus(400)
                ->json([
                    'message' => 'Illegal ID',
                    'code' => 1,
                ]);
        }
        $post = new Post();
        $art = $post->findById($id);
        if (!$art) {
            return $this->response
            ->withStatus(400)
            ->json([
                'message' => 'article not found',
                'code' => 2,
            ]);
        }
        $art->delete();
        $this->response
            ->json([
                'message' => 'ok',
                'code' => 0,
            ]);
    }

    public function detail()
    {
        $id = $this->request->getParam('id');
        if (!intval($id)) {
            return $this->response
                ->withStatus(400)
                ->json([
                    'message' => 'Illegal ID',
                    'code' => 1,
                ]);
        }
        $post = new Post();
        $art = $post->findById($id);
        if (!$art) {
            return $this->response->withStatus(400)->json([
                'message' => 'article not found',
                'code' => 2,
            ]);
        }
        $art = $art->toArray();
        $this->response->json([
            'message' => 'ok',
            'code' => 0,
            'data' => $art,
        ]);
    }


    /**
     * get article comment by article id
     *
     * @param int $id
     * @query int $page required
     * @query int $pageSize required
     * @return $ref comment
     */
    public function comments()
    {
        $page = abs($this->request->getQuery('page'));
        $page = $page ?: 1;
        $pageSize = 20;
        $offset = ($page - 1) * $pageSize;
        $comment = new Comment();
        $comments = $comment->find([
            'id' => ['$gt' => 1],
        ],
            [
                'order' => ['id' => 'desc'],
                'limit' => $pageSize,
                'offset' => $offset,
            ]);
        $total = 0; // @todo
        $comments = $comment->toArray($comments);
        $this->response->json([
            'message' => 'ok',
            'code' => 0,
            'data' => [
                'list' => $comments,
                'page' => $page,
                'pageSize' => $pageSize,
                'total' => $total,
            ],
        ]);
    }

    public function dropComment()
    {
        $ids = $this->body('ids');
        $ids = explode(',', $ids);
        if (empty(($ids))) {
            return $this->response->withStatus(400)->json([
                'message' => '参数错误',
                'code' => 1,
            ]);
        }
        $comment = new Comment();
        $where = ['id' => ['$in' => $ids]];
        $comment->delete($where);
        $this->response->json([
            'message' => 'ok',
            'code' => 0,
        ]);
    }
}
