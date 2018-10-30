<?php
/**
 * @license MIT
 * @copyright Copyright (c) 2018
 * @author: bugbear
 * @date: 2018/4/5
 * @time: 下午10:27
 */

namespace Knight\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Knight\Model\Album as Gallery;
use Knight\Model\Photo;

class Album
{

    /**
     * get album list
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     * @throws \Exception
     */
    public function list(Request $request)
    {
        $user = $request->getAttribute('session');
        $page = $request->getParam('page', 1);
        $page = abs($page);
        $pageSize = $request->getParam('pageSize', 10);
        $pageSize = abs($pageSize);
        $album = new Gallery();
        $where = [
            'isShow' => ['$gte' => $user ? 0 : 1],
        ];
        $options = [
            'order' => ['created' => 'desc'],
            'limit' => $pageSize,
            'offset' => ($page - 1) * $pageSize
        ];

        $total = $album->count($where);
        $list = $album->find($where, $options);
        $list = $album->toArray($list);
        $photo = new Photo();
        foreach ($list as $key => &$value) {
            $filter = [
                'albumId' => $value['id']
            ];
            $options = [
                'limit' => 10,
                'order' => ['created' => 'desc']
            ];
            $photos = $photo->find($filter, $options);
            $photoNumber = $photo->count($filter);
            $value['photos'] = $photo->toArray($photos);
            $value['photoNumber'] = $photoNumber;
        }

        unset($value);
        $response = new Response();
        return $response->json([
            'message' => 'ok',
            'code' => 0,
            'data' => [
                'list' => $list,
                'total' => $total,
                'page' => $page,
                'pageSize' => $pageSize,
            ]
        ]);
    }

    /**
     * get all album name
     *
     * @return Response
     * @throws \Exception
     */
    public function all(): Response
    {
        $album = new Gallery();
        $albums = $album->findAll();
        $list = [];
        foreach ($albums as $key => $album) {
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

    /**
     * create album
     *
     * @param Request $request
     * @return Response
     */
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
        $album = new Gallery();
        $data = $album->insert($data);

        return $response->json([
            'message' => 'ok',
            'code' => 0,
            'data' => $data,
        ]);
    }

    /**
     * get photos of album by album id
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     * @throws \Exception
     */
    public function photos(Request $request)
    {
        $albumId = $request->getParam('albumId');
        $response = new Response();
        $page = $request->getQuery('page', 1);
        $pageSize = $request->getQuery('pageSize', 20);
        $page = abs($page);
        $pageSize = abs($pageSize);
        $lastId = $request->getQuery('last');
        if (!is_numeric($albumId)) {
            return $response->withStatus(400)
                ->json([
                    'message' => 'illegal param albumId',
                    'code' => 1,
                ]);
        }

        $gallery = new Gallery();
        $album = $gallery->findById($albumId);
        if (!$album) {
            return $response->withStatus(400)
                ->json([
                    'message' => 'album not found',
                    'code' => 2,
                ]);
        }

        $photo = new Photo();
        $where = [
            'albumId' => $albumId,
        ];
        $options = [
            'limit' => $pageSize,
            'order' => ['id' => 'desc']
        ];
        if ($lastId) {
            $where['id'] = [
                '$lt' => $lastId,
            ];
        } else {
            $options['offset'] = intval($page - 1) * $pageSize;
        }

        $total = yield $photo->count($where);
        $photos = [];
        if ($total > 0) {
            $photos = yield $photo->find($where, $options);
            $photos = $photo->toArray($photos);
        }

        return $response->json([
            'message' => 'ok',
            'data' => [
                'page' => $page,
                'pageSize' => $pageSize,
                'list' => $photos,
                'total' => $total,
                'albumInfo' => $album,
            ],
        ]);
    }
}