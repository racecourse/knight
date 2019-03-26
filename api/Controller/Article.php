<?php
/**
 * @license   https://github.com/Init/licese.md
 * @copyright Copyright (c) 2017
 * @author    : bugbear
 * @date      : 2017/3/16
 * @time      : 下午5:39
 */

namespace Knight\Controller;

use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest as Request;
use Knight\Model\Category;
use Knight\Model\Comment;
use Knight\Model\Post;
use Knight\Component\Controller;

class Article extends Controller
{

    /**
     * get article list
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function posts(Request $request)
    {
        $this->params = $request->getQueryParams();
        $page = $this->getParam('page', 1);
        $order = $params['order'] ?? 'id';
        $keyword = $params['q'] ?? null;
        $order = $order === 'archive' ? 'created' : 'id';
        $page = $page ?: 1;
        $pageSize = 20;
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
        $response = new JsonResponse([
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
     * get article detail
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     */
    public function detail(Request $request)
    {
        $params = $request->getAttribute('params', []);
        $id = $params['id'];
        $response = new JsonResponse([]);
        if (!$id || $id < 1) {
            return $response
                ->withPayload([
                    'message' => 'Illegal ID',
                    'code' => 1,
                ])
                ->withStatus(400);
        }
        $article = new Post();
        $condition = [
            'id' => $id,
        ];
        $art = yield $article->findOne($condition);
        if (!$art) {
            return $response->withPayload([
                'message' => 'article not found',
                'code' => 2,
            ])
                ->withStatus(400);
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

        return $response->withPayload([
            'message' => 'ok',
            'code' => '0',
            'data' => $art,
        ]);
    }

    /**
     * get article by admin
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     * @throws \Exception
     */
    public function article(Request $request)
    {
        $query = $request->getQueryParams();
        $page = abs($query['page'] ?? 1);
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
        $total = $article->count($where);
        $list = $article->toArray($articles);
        return new JsonResponse([
            'message' => 'ok',
            'data' => [
                'page' => $page,
                'pageSize' => $pageSize,
                'list' => $list,
                'total' => $total,
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
        $params = $request->getAttribute('params');
        $query = $request->getQueryParams();
        $id = $params['id'];
        $response = new JsonResponse([]);
        if (!$id) {
            return $this->json([
                'message' => 'param id required',
                'code' => 1,
            ], 400);
        }

        $page = abs($query['page'] ?? 1);
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

        return $this->json([
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
        $body = $request->getParsedBody();
        $title = $body['title'] ?? '';
        $content = $body['content'] ?? '';
        $tags = $body['body'];
        $cateId = $request->getPayload('cateId');
        if (!$title) {
            return $this->json([
                'message' => 'title required',
                'code' => 1,
            ], 400);
        }

        if (!$content) {
            return $this->json([
                'message' => 'content can not empty',
                'code' => 4,
            ], 400);
        }

        if ($cateId) {
            $category = new Category();
            $cate = $category->findById($cateId);
            if (!$cate) {
                return $this->json([
                    'message' => 'category not found',
                    'code' => 3,
                ], 400);
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

        return $this->json([
            'message' => 'ok',
            'code' => 0,
        ]);
    }

}
