<?php
/**
 * @license   https://github.com/Init/licese.md
 * @copyright Copyright (c) 2017
 * @author    : bugbear
 * @date      : 2017/3/16
 * @time      : 下午5:39
 */

namespace Knight\Controller;

use Hayrick\Http\Response;
use Knight\Model\Category;
use Knight\Model\Comment;
use Knight\Model\Post;
use Knight\Component\Controller;
use Hayrick\Http\Request;

class Article extends Controller
{

    /**
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function posts(Request $request)
    {
        $page = abs($request->getQuery('page', 1));
        $order = $request->getQuery('order');
        $keyword = $request->getQuery('q');
        $order = $order === 'archive' ? 'created' : 'id';
        $page = $page ?: 1;
        $pageSize = 10;
        $offset = ($page - 1) * $pageSize;
        $article = new Post();
        $condition = [
            'permission' => ['$lte' => 1],
        ];
        $options = [
            'order' => [$order => 'DESC'],
            'limit' => $pageSize,
            'offset' => $offset,
        ];
//        if ($keyword) {
//            $like = ''
//            $condition['$or'] =  [
//                'title' => $keyword,
//                'tags' => $keyword,
//            ]];
//        }

        $list = yield $article->find($condition, $options);
        $list = $article->toArray($list);
        $total = yield $article->count($condition);
        $response = new Response();
        $response = $response->json([
            'message' => 'ok',
            'code' => '0',
            'data' => [
                'list' => $list,
                'total' => $total,
                'page' => $page,
                'pageSize' => $pageSize,
            ],
        ]);

        return $response;
    }

    /**
     * @security [Bearer]
     * @desc This method loads the homepage
     * @tags User
     * @param int $id path required
     * @return $ref Detail
     */
    public function detail(Request $request)
    {
        $id = $request->getParam('id');
        $article = new Post();
        $condition = [
            'id' => $id,
        ];
        $art = yield $article->findOne($condition);
        if (!$art) {
            return (new Response)
                ->withStatus(400)
                ->json([
                    'message' => 'article not found',
                    'code' => 1,
                ]);
        }

//        if ($art->isShow < 1) {
//            return (new Response)
//                ->withStatus(400)
//                ->json([
//                    'message' => 'Access denied',
//                    'code' => 2,
//                ]);
//        }
        $art = $art->toArray();

        return (new Response())->json([
            'message' => 'ok',
            'code' => '0',
            'data' => $art,
        ]);
    }

    /**
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     * @throws \Exception
     */
    public function article(Request $request)
    {
        $page = abs($request->getQuery('page'));
        $page = $page ?: 1;
        $pageSize = 20;
        $offset = ($page - 1) * $pageSize;
        $session = $request->getAttribute('session');
        $userId = $session->id;
        $article = new Post();
        $where = [
            'userId' => $userId,
        ];
        $option = [
            'offset' => $offset,
            'limit' => $pageSize,
            'order' => ['id' => 'DESC'],
        ];
        $articles = yield $article->find($where, $option);
        $list = $article->toArray($articles);
        return (new Response())->json([
            'message' => 'ok',
            'data' => [
                'page' => $page,
                'pageSize' => $pageSize,
                'list' => $list,
            ],
        ]);
    }

    /**
     * get article comment by article id
     *
     * @param int $id
     * @query int $page required
     * @query int $pageSize required
     * @return $ref comment
     * @throws \Exception
     */
    public function comments(Request $request)
    {
        $id = $request->getParam('id');
        $response = new Response();
        if (!$id) {
            return $response->withStatus(400)->json([
                'message' => 'param id required',
                'code' => 1,
            ]);
        }

        $page = abs($request->getQuery('page'));
        $page = $page ?: 1;
        $pageSize = 20;
        $offset = ($page - 1) * $pageSize;
        $comment = new Comment();
        $comments = $comment->find([
                'artId' => $id,
            ],
            [
                'limit' => $pageSize,
                'offset' => $offset,
            ]);
        $total = 0; // @todo
        $comments = $comment->toArray($comments);

        return $response->json([
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

    /**
     * @description create a new article
     * @tags article
     * @security Bearer
     * @body string $title article title
     * @body string $content article content
     * @body int $cateId
     * @body string $tags
     * @return mixed
     */
    public function create(Request $request)
    {
        $response = new Response();
        $title = $request->getPayload('title');
        $content = $request->getPayload('content');
        $tags = $request->getPayload('body');
        $cateId = $request->getPayload('cateId');
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
                    'message' => 'content can not empty',
                    'code' => 4,
                ]);
        }

        if ($cateId) {
            $category = new Category();
            $cate = $category->findById($cateId);
            if (!$cate) {
                return $response
                    ->withStatus(400)
                    ->json([
                        'message' => 'category not found',
                        'code' => 3,
                    ]);
            }
        }

        $post = [
            'tags' => $tags,
            'cateId' => $cateId,
            'title' => $title,
            'content' => $content,
            'created' => time(),
        ];
        $article = new Post();
        yield $article->insert($post);

        return $response->json([
            'message' => 'ok',
            'code' => 0,
        ]);
    }

}