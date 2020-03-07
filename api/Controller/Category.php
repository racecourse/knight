<?php
/**
 * @license   MIT
 * @copyright Copyright (c) 2017
 * @author    : bugbear
 * @date      : 2017/4/2
 * @time      : 下午4:38
 */

namespace Knight\Controller;

use Knight\Component\Controller;
use Knight\Model\Category as Cate;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest as Request;

class Category extends Controller
{

    /**
     * create category
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     */
    public function create(Request $request): JsonResponse
    {
        $body = $request->getParsedBody();
        $name = $body['name'];
        if (!$name) {
            return $this->json([
                'message' => 'Illegal Param',
                'code' => 1,
            ], 400);
        }

        $category = new Cate();
        $category->name = $name;
        $category->created = time();
        $cate = $category->save();

        return $this->json([
            'message' => 'ok',
            'code' => 0,
            'data' => $cate->toArray(),
        ]);
    }

    /**
     * drop category by id
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     */
    public function drop(Request $request)
    {
        $params = $request->getAttribute('params');
        $id = $params['id'];
        if (!$id) {
            return $this->json([
                'message' => 'cate id miss',
                'code' => 1,
            ], 400);
        }

        $category = new Cate();
        $cate = $category->findById($id);
        if (!$cate) {
            return $this->json([
                'message' => 'category not found',
                'code' => 2,
            ], 400);
        }

        $cate->delete();

        return $this->json([
            'message' => 'ok',
            'code' => 0,
        ]);
    }

    /**
     * get category list
     *
     * @return \Psr\Http\Message\ResponseInterface|static
     * @throws \Exception
     */
    public function list()
    {
        $category = new Cate();
        $pageSize = 20;
        $options = [
            'limit' => $pageSize,
        ];
        $where = [];
        $list = yield $category->find($where, $options);
        return $this->json([
            'message' => 'ok',
            'code' => 0,
            'data' => $list,
        ]);
    }
}
