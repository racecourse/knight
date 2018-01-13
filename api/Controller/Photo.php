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

    public function create(Request $request)
    {
        $user = $request->getAttribute('session');
        $config = Config::get('upyun');
        $cfg = new UConfig($config['bucket'], $config['username'], $config['password']);
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
                $extname = end(explode('/', $type));
                $fileKey = Photo::getFileKey();
                $savePath = date('Ymd', time()) . '/' . $fileKey . '.' . $extname;
                $attr = yield $client->write($savePath, $uploaded->getStream());
                var_dump($attr);
                $extInfo = [];
                foreach($attr as $key => $value) {
                    $key = str_replace('x-upyun-', '', $key);
                    $extInfo[$key] = $value;
                }
                $url = $config['domain']  . '/' . $savePath;
                $image->url = $url;
                $image->created = time();
                $image->attr = json_encode($extInfo);
                var_dump($image);
                $image = yield $image->save();
                $success[] = $image->toArray();
            } catch (Exception $err) {
                var_dump($err);
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

    public function list()
    {
    }

    public static function getFileKey()
    {
        return mb_substr(md5(uniqid() . rand(0, 1000)), 0, 24);
    }
}
