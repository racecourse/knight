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
use Knight\Lib\Logger;
use Knight\Model\Photo as Image;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest as Request;
use Ben\Config;
use Upyun\Upyun;
use Upyun\Config as UConfig;

class Photo extends Controller
{

    /**
     * upload a new photo
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     */
    public function create(Request $request)
    {
        $this->payload = $this->payload = $request->getParsedBody();
        $user = $request->getAttribute('session');
        $query = $request->getQueryParams();
        $album = $query['albumId'] ?? '';
        $files = $request->getUploadedFiles();
        $success = yield $this->upload($files, $user, $album);

        return new JsonResponse([
            'message' => 'ok',
            'data' => $success
        ], 200);
    }


    protected function upload($files, $user, $album)
    {
        $config = Config::get('upyun');
        $cfg = new UConfig($config['bucket'], $config['username'], $config['password']);
        $client = new Upyun($cfg);
        $success = [];
        foreach ($files as $key => $uploaded) {
            try {
                if (is_array($uploaded)) {
                    $success = $this->upload($uploaded, $user, $album);
                    continue;
                }

                $image = new Image();
                $image->userId = $user->id ?? 1;
                $image->name = $uploaded->getClientFilename();
                $image->size = $uploaded->getSize();
                $type = $uploaded->getClientMediaType();

                if (!$type) {
                    continue;
                }

                $extname = explode('/', $type);
                $extname = end($extname);
                $fileKey = Photo::getFileKey();
                $savePath = date('Ymd', time()) . '/' . $fileKey . '.' . $extname;
                $attr = yield $client->write($savePath, $uploaded->getStream());
                $extInfo = [];
                foreach ($attr as $field => $value) {
                    $field = str_replace('x-upyun-', '', $field);
                    $extInfo[$field] = $value;
                }

                $url = '/' . $savePath;
                $image->url = $url;
                $image->albumId = $album;
                $image->created = time();
                $image->attrs =  $extInfo;
                $image->save();
                $success[] = $image->toArray();
            } catch (\Exception $err) {
                throw $err;
//                Logger::error('upload photo error' . $err->getMessage());
//                continue;
            }
        }

        return $success;
    }

    public function drop(Request $request)
    {
    }

    public function photos(Request $request)
    {
        $this->query = $request->getQueryParams();
        $page = $this->getQuery('page', 1);
        $pageSize = $this->getQuery('pageSize', 21);
        $image = new Image();
        $where = [
            'id' => ['$gt' => 1]
        ];
        $options = [
            'order' => ['id' => -1],
            'limit' => $pageSize,
            'skip' => ($page - 1) * $pageSize,
        ];
        $list = $image->find($where, $options);
        $total = $image->count();

        return $this->json([
            'message' => 'ok',
            'data' => [
                'list' => $list,
                'total' => $total,
                'page' => $page,
                'pageSize' => $pageSize,
            ]
        ]);
    }

    public static function getFileKey()
    {
        return mb_substr(md5(uniqid() . rand(0, 1000)), 0, 24);
    }
}
