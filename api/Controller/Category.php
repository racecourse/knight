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
 use Hayrick\Http\Request;

 class Category extends Controller {

    public function create(Request $request)
    {
        $name = $request->getPayload('name');
        if (!$name) {
            return $this->response
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
        return $this->response
            ->json([
                'message' => 'ok',
                'code' => 0,
                'data' => $cate,
            ]);
    }

    public function drop(Request $request)
    {
        $id = $request->getParam('id');
        if (!$id) {
            return $this->response
                ->withStatus(400)
                ->json([
                    'message' => 'cate id miss',
                    'code' => 1,
                ]);
        }
        $category = new Cate();
        $cate = $category->findById($id);
        if (!$cate) {
            return $this->response
                ->withStatus(404)
                ->json([
                    'message' => 'category not found',
                    'code' => 2,
                ]);
        }
        $cate->delete();
        return $this->response
            ->json([
                'message' => 'ok',
                'code' => 0,
            ]);
    }
 }
