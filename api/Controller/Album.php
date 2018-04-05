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
        var_dump($list);
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
}