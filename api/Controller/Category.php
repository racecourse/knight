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
use Slim\Http\Request;
use Slim\Http\Response;

class Category extends Controller
{

    /**
     * create category
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     */
    public function create(Request $request)
    {
        $name = $request->getPayload('name');
        if (!$name) {
            return (new Response)
                ->withStatus(400)
                ->json([
                    'message' => 'Illegal Param',
                    'code' => 1,
                ]);
        }

        $category = new Cate();
        $category->name = $name;
        $category->created = time();
        $cate = $category->save();

        return (new Response)
            ->json([
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
        $id = $request->getParam('id');
        if (!$id) {
            return (new Response)
                ->withStatus(400)
                ->json([
                    'message' => 'cate id miss',
                    'code' => 1,
                ]);
        }

        $category = new Cate();
        $cate = $category->findById($id);
        if (!$cate) {
            return (new Response)
                ->withStatus(404)
                ->json([
                    'message' => 'category not found',
                    'code' => 2,
                ]);
        }

        $cate->delete();

        return (new Response)
            ->json([
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
        $where = [
            'id' => [
                '$gt' => 0,
            ]
        ];
        $list = yield $category->find($where, $options);
        $list = $category->toArray($list);

        return (new Response)
            ->json([
                'message' => 'ok',
                'code' => 0,
                'data' => $list,
            ]);
    }
}
