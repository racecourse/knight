<?php
/**
 * @license MIT
 * @copyright Copyright (c) 2018
 * @author: bugbear
 * @date: 2018/4/5
 * @time: 下午10:27
 */

namespace Knight\Controller;

use Knight\Component\Controller;
use Knight\Model\Album as Gallery;
use Knight\Model\Photo;
use Zend\Diactoros\ServerRequest as Request;
use Zend\Diactoros\Response\JsonResponse;
use function DI\string;

class Album extends Controller
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
        $params = $request->getAttribute('params');
        $user = $request->getAttribute('session');
        $page = $params['page'] ?? 1;
        $page = abs($page);
        $pageSize = 20;
        $pageSize = abs($pageSize);
        $album = new Gallery();
        $where = [
//            'isShow' => ['$gte' => $user ? 0 : 1],
        ];
        $options = [
            'sort' => ['created' => -1],
            'limit' => $pageSize,
            'skip' => ($page - 1) * $pageSize
        ];


        $total = $album->count($where);
        $albums = $album->find($where, $options);
        $photo = new Photo();
        foreach ($albums as $key => $value) {
            $filter = [
                'albumId' => (string)$value['id']
            ];
            $options = [
                'limit' => 6,
                'order' => ['created' => -1]
            ];
            $photos = $photo->find($filter, $options);
            $photoNumber = $photo->count($filter);
            $value['photos'] = $photos;
            $value['photoNumber'] = $photoNumber;
        }

        return $this->json([
            'message' => 'ok',
            'code' => 0,
            'data' => [
                'domain' => 'cdn.sangsay.com',
                'list' => $albums,
                'total' => $total,
                'page' => $page,
                'pageSize' => $pageSize,
            ]
        ]);
    }

    /**
     * get all album name
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function all(): JsonResponse
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

        return $this->json([
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
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $name = $request->getPayload('name');
        $detail = $request->getPayload('detail');
        $isShow = $request->getPayload('isShow');
        if (!$name) {
            return $this->json([
                    'message' => 'Illegal Param',
                    'code' => 123
                ], 400);
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

        return $this->json([
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
        $params = $request->getAttribute('params');
        $query = $request->getQueryParams();
        $albumId = $params['albumId'];
        $page = $query['page'] ?? 1;
        $pageSize = 20;
        $page = abs($page);
        $pageSize = abs($pageSize);
        $lastId = $query['last'] ?? '';
        if (!$albumId) {
            return $this->json([
                    'message' => 'illegal param albumId',
                    'code' => 1,
                ]);
        }

        $gallery = new Gallery();
        $album = $gallery->findById($albumId);
        if (!$album) {
            return $this->json([
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
            'order' => ['id' => 1]
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

        return $this->json([
            'message' => 'ok',
            'data' => [
                'page' => $page,
                'pageSize' => $pageSize,
                'list' => $photos,
                'total' => $total,
                'albumInfo' => $album,
                'domain' => 'cdn.sangsay.com',
            ],
        ]);
    }
}
