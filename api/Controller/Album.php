<?php
/**
 * @license MIT
 * @copyright Copyright (c) 2018
 * @author: bugbear
 * @date: 2018/4/5
 * @time: 下午10:27
 */

namespace Knight\Controller;

use Hayrick\Http\Request;
use Hayrick\Http\Response;
use Knight\Model\Album as Gallery;
use Knight\Model\Photo;

class Album
{

    public function list(Request $request)
    {
        $page = $request->getParam('page', 1);
        $page = abs($page);
        $pageSize = $request->getParam('pageSize', 10);
        $pageSize = abs($pageSize);
        $album = new Gallery();
        $where = [
            'isShow' => 1,
        ];
        $options = [
            'order' =>[ 'created' => 'desc'],
            'limit' => $pageSize,
            'offset' => ($page - 1) * $pageSize
        ];

        $list = $album->find($where, $options);
        $list = $album->toArray($list);
        $photo = new Photo();
        foreach ($list as $key => &$value) {
            $filter = [
                'albumId' => $value['id']
            ];
            $options = [
                'limit' => 10,
            ];
            $photos = $photo->find($filter, $options);
            $value['photos'] = $photo->toArray($photos);
        }

        unset($value);
        $response = new Response();
        return $response->json([
            'message' => 'ok',
            'code' => 0,
            'data' => [
                'list' => $list,
                'page' => $page,
                'pageSize' => $pageSize,
            ]
        ]);
    }

    public function all(): Response
    {
        $album = new Gallery();
        $albums = $album->findAll();
        $list = [];
        foreach ($albums as $key => $album) {
            var_dump($album);
            $list[] = [
                'id' => $album->id,
                'name' => $album->name,
            ];
        }

        $response = new Response();
        return $response->json([
            'message' => 'ok',
            'data' => [
                'list' => $list
            ]
        ]);
    }

    public function create(Request $request): Response
    {
        $name = $request->getPayload('name');
        $detail = $request->getPayload('detail');
        $isShow = $request->getPayload('isShow');
        $response = new Response();
        if (!$name) {
            return $response->withStatus(400)
                ->json([
                    'message' => 'Illegal Param',
                    'code' => 123
                ]);
        }

        $user = $request->getAttribute('session');

        $data = [
            'userId' => $user->id,
            'name' => $name,
            'detail' => $detail,
            'isShow' => $isShow,
            'created' => time(),
        ];
        var_dump($data);
        $album = new Gallery();
        $album->insert($data);

        return $response->json([
            'message' => 'ok',
            'code' => 0,
        ]);
    }
}