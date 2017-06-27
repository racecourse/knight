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
        $page = $this->request->query('page');
        $page = abs($page) ?: 1;
        $offset = ($page - 1) * $pageSize;
        $article = new Post();
        $posts = $article->find(['id' => ['$gt' => 0]],
         ['limit' => $pageSize, 'offset' => $offset]);
        $posts = $article->toArray($posts);
        $ret = [
            'total' => 10, // @fixme
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
     *
     *
     */
    public function create()
    {
        $request = $this->request;
        $response = $this->response;
        $title = $request->body('title');
        $content = $request->body('content');
        $tags = $request->body('body');
        $cateId = $request->body('cateId');
        $permission = $request->body('permission');
        if (!in_array($permission, [0, 1, 2])) {
            return $this->response->status(400)->json([
                'message' => 'Illegal param permission',
                'code' => 1,
            ]);
        }
        if (!$title) {
            return $response->status(400)->json([
                'message' => 'title required',
                'code' => 1,
            ]);
        }
        if (!$content) {
            return $response->status(400)->json([
                'message' => 'content can not empty'
            ]);
        }
        $post = [
            'userId' => 1,
            'title' => $title,
            'content' => $content,
            'tags' => $tags,
            'permission' => $permission,
            'cateId' => $cateId,
            'created' => time(),
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
        $id = $this->request->param('id');
        $title = $this->request->body('title');
        $tags = $this->request->body('tags');
        $content = $this->request->body('content');
        $cateId = $this->request->body('cateId');
        $time = $this->request->body('time');
        $permission = $this->request->body('permission');
        if (!$title || !$content) {
            return $this->response
                ->status(400)
                ->json([
                    'message' => 'content && title are required',
                    'code' => 1,
                ]);
        }
        $post = new Post();
        $art = $post->findById($id);
        if (!$art) {
            return $this->response->status(400)->json([
                'message' => 'article not found',
                'code' => 2,
            ]);
        }
        $art['title'] = $title;
        $art['content'] = $content;
        $art['cateId'] = $cateId;
        $art['tags'] = $tags;
        $art['permission'] = $permission;
        if ($time) {
            $time = mktime($time);
            $art['created'] = $time;
        }
        $art->update();
        $this->response->json([
            'message' => 'ok',
            'code' => 0,
        ]);
    }

    public function drop()
    {
        $id = $this->request->param('id');
        if (!intval($id)) {
            return $this->response
                ->status(400)
                ->json([
                    'message' => 'Illegal ID',
                    'code' => 1,
                ]);
        }
        $post = new Post();
        $art = $post->findById($id);
        if (!$art) {
            return $this->response->status(400)->json([
                'message' => 'article not found',
                'code' => 2,
            ]);
        }
        $art->delete();
        $this->response->json([
            'message' => 'ok',
            'code' => 0,
        ]);
    }

    public function detail()
    {
        $id = $this->request->param('id');
        if (!intval($id)) {
            return $this->response
                ->status(400)
                ->json([
                    'message' => 'Illegal ID',
                    'code' => 1,
                ]);
        }
        $post = new Post();
        $art = $post->findById($id);
        if (!$art) {
            return $this->response->status(400)->json([
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
        $page = abs($this->request->query('page'));
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
        $ids = $this->request->body('ids');
        $ids = explode(',', $ids);
        if (empty(($ids))) {
            return $this->response->status(400)->json([
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
