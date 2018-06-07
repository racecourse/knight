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
use Knight\Model\Photo as Image;
use Hayrick\Http\Request;
use Hayrick\Http\Response;
use Ben\Config;
use Upyun\Upyun;
use Upyun\Config as UConfig;

class Photo extends Controller {

    /**
     * upload a new photo
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     */
    public function create(Request $request)
    {
        $user = $request->getAttribute('session');
        $album = $request->getPayload('album', 1);
        $config = Config::get('upyun');
        $cfg = new UConfig($config['bucket'], $config['username'], $config['password']);
//        $cfg->debug = true;
        $client = new Upyun($cfg);
        $files = $request->getUploadedFiles();
        $success = [];
        foreach ($files as $key => $uploaded) {
            try {
                $image = new Image();
                $image->userId = $user->id ?? 1;
                $image->name = $uploaded->getClientFilename();
                $image->size = $uploaded->getSize();
                $type = $uploaded->getClientMediaType();
                if (!$type) {
                    continue;
                }

                $extname = end(explode('/', $type));
                $fileKey = Photo::getFileKey();
                $savePath = date('Ymd', time()) . '/' . $fileKey . '.' . $extname;
                $attr = $client->write($savePath, $uploaded->getStream());
                $extInfo = [];
                foreach($attr as $field => $value) {
                    $field = str_replace('x-upyun-', '', $field);
                    $extInfo[$field] = $value;
                }
                $url = $config['domain']  . '/' . $savePath;
                $image->url = $url;
                $image->albumId = $album;
                $image->created = time();
                $image->attr = json_encode($extInfo);
                $image = $image->save();
                $success[] = $image->toArray();
            } catch (\Exception $err) {
                continue;
            }
        }

        $response = new Response;
        
        return $response->json([
            'message' => 'ok',
            'data' => $success
        ]);
    }

    public function drop(Request $request)
    {
    }

    public function list(Request $request)
    {
        $page = $request->getQuery('page', 1);
        $pageSize = $request->getQuery('pageSize', 21);
        $image = new Image();
        $where = [
            'id' => ['$gt' => 1]
        ];
        $options = [
            'order' => ['id' => 'DESC'],
            'limit' => $pageSize,
            'offset' => ($page - 1) * $pageSize, 
        ];
        $list = [];
        $list = $image->find($where, $options);
        $list = $image->toArray($list);
        $total = $image->count();

        $response = new Response();
        return $response->json([
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
