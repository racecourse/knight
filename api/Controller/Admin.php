<?php
/**
 * @license   https://github.com/Init/licese.md
 * @copyright Copyright (c) 2017
 * @author    : bugbear
 * @date      : 2017/4/2
 * @time      : 下午4:38
 */

namespace Knight\Controller;

use Hayrick\Http\Response;
use Knight\Component\Controller;
use Knight\Model\Comment;
use Knight\Model\Post;
use Knight\Model\Category;
use Hayrick\Http\Request;
use Linfo\Linfo;

class Admin extends Controller
{

    /**
     * 网站概况
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     * @throws \Linfo\Exceptions\FatalException
     * @throws \ReflectionException
     */
    public function survey(Request $request)
    {
        $article = new Post();
//        $articleNumber = $article->count();
//        $commentNumber = (new Comment())->count();
        $photoNumber = 0;
        $albumNumber = 0;
        $settings['dates'] = 'm/d/y h:i A (T)'; // Format for dates shown. See php.net/date for syntax
        $settings['language'] = 'en'; // Refer to the lang/ folder for supported lanugages
        $settings['show']['kernel'] = true;
        $settings['show']['ip'] = true;
        $settings['show']['os'] = true;
        $settings['show']['load'] = true;
        $settings['show']['ram'] = true;
        $settings['show']['uptime'] = true;
        $settings['show']['cpu'] = true;
        $settings['show']['process_stats'] = true;
        $settings['show']['hostname'] = true;
        $settings['show']['model'] = true; # Model of system. Supported on certain OS's. ex: Macbook Pro
        $settings['cpu_usage'] = true;
        $response = new Response();
        $linfo = new Linfo($settings);
        $linfo->scan();
        $info = $linfo->getInfo();
        $system = [
            'os' => $info['OS'],
            'kernel' => $info['Kernel'],
            'acccessIp' => $info['AccessIP'],
            'memory' => $info['RAM'],
            'hostname' => $info['HostName'],
            'uptime' => $info['UpTime'],
            'cpuInfo' => $info['CPU'],
            'load' => $info['Load'],
        ];
        if ($info['processStats'] && $info['processStats']['exists']) {
            $system['process'] = $info['processStats']['totals'];
        }
//        foreach($output as $key => $value) {
//            echo $key . PHP_EOL;
//        }
//        var_dump($output);
        return $response->json([
            'message' => 'ok',
            'code' => 0,
            'data' => [
                'articleNumber' => 4,
                'commentNumber' => 3,
                'albumNumber' => 2,
                'photoNumber' => 1,
                'pv' => 1,
                'ip' => 1,
                'system' => $system,
            ]
        ]);
    }

    /**
     * article list
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     * @throws \Exception
     */
    public function article(Request $request)
    {
        $page = abs($request->getQuery('page'));
        $page = $page ?: 1;
        $pageSize = 20;
        $offset = ($page - 1) * $pageSize;
        $session = $request->getAttribute('session');
        $userId = $session->id;
        $article = new Post();
        $where = [
            'userId' => $userId,
        ];
        $option = [
            'offset' => $offset,
            'limit' => $pageSize,
            'order' => ['id' => 'DESC'],
        ];
        $articles = yield $article->find($where, $option);
        $list = $article->toArray($articles);

        return (new Response())->json([
            'message' => 'ok',
            'data' => [
                'page' => $page,
                'pageSize' => $pageSize,
                'list' => $list,
            ],
        ]);
    }

    /**
     * create article
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     */
    public function create(Request $request)
    {
        $response = new Response();
        $title = $request->getPayload('title');
        $content = $request->getPayload('content');
        $tags = $request->getPayload('tags');
        $cateId = $request->getPayload('cateId');
        $permission = $request->getPayload('permission');
        if (!in_array($permission, [0, 1, 2])) {
            return $response->withStatus(400)
                ->json([
                    'message' => 'Illegal param permission',
                    'code' => 1,
                ]);
        }

        if (!$title) {
            return $response->withStatus(400)
                ->json([
                    'message' => 'title required',
                    'code' => 1,
                ]);
        }

        if (!$content) {
            return $response->withStatus(400)
                ->json([
                    'message' => 'content can not empty'
                ]);
        }

        $user = $request->getAttribute('session');
        $created = $request->getPayload('created');
        $created = $created ? strtotime($created) : time();
        $tags = \is_array($tags) ? \implode(',', $tags) : $tags;
        $post = [
            'userId' => $user->id,
            'title' => $title,
            'content' => $content,
            'tags' => $tags,
            'permission' => $permission,
            'cateId' => $cateId,
            'created' => $created,
        ];
        $article = new Post();
        $article->insert($post);

        return $response->json([
            'code' => 0,
            'message' => 'ok',
        ]);
    }


    /**
     * get all category
     *
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function category(Request $request)
    {
        $category = new Category();
        $cate = $category->findAll();
        $list = $category->toArray($cate);

        return [
            'message' => 'ok',
            'code' => 0,
            'data' => $list,
        ];
    }

    public function edit(Request $request)
    {
        $id = $request->getParam('id');
        $title = $request->getPayload('title');
        $tags = $request->getPayload('tags');
        $content = $request->getPayload('content');
        $cateId = $request->getPayload('cateId');
        $time = $request->getPayload('created');
        $permission = $request->getPayload('permission');
        $response = new Response();
        if (!$title || !$content) {
            return $response->withStatus(400)
                ->json([
                    'message' => 'content && title are required',
                    'code' => 1,
                ]);
        }

        $post = new Post();
        $art = $post->findById($id);
        if (!$art) {
            return $response
                ->withStatus(400)
                ->json([
                    'message' => 'article not found',
                    'code' => 2,
                ]);
        }

        if (is_array($tags)) {
            $tags = implode(',', $tags);
        }

        $art->title = $title;
        $art->content = $content;
        $art->cateId = $cateId;
        $art->tags = $tags;
        $art->permission = $permission;
        if ($time) {
            $art->created = strtotime($time);
        }

        $art->update();
        return $response->json([
            'message' => 'ok',
            'code' => 0,
        ]);
    }

    /**
     * drop a article by id
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     */
    public function drop(Request $request)
    {
        $id = $request->getParam('id');
        $response = new Response();
        if (!intval($id)) {
            return $response
                ->withStatus(400)
                ->json([
                    'message' => 'Illegal ID',
                    'code' => 1,
                ]);
        }
        $post = new Post();
        $art = $post->findById($id);
        if (!$art) {
            return $response->withStatus(400)
                ->json([
                    'message' => 'article not found',
                    'code' => 2,
                ]);
        }
        $art->delete();
        return $response->json([
            'message' => 'ok',
            'code' => 0,
        ]);
    }

    public function detail(Request $request)
    {
        $id = $request->getParam('id');
        $response = new Response();
        if (!intval($id)) {
            return $response
                ->withStatus(400)
                ->json([
                    'message' => 'Illegal ID',
                    'code' => 1,
                ]);
        }
        $post = new Post();
        $art = $post->findById($id);
        if (!$art) {
            return $response->withStatus(400)->json([
                'message' => 'article not found',
                'code' => 2,
            ]);
        }
        $art = $art->toArray();
        return $response->json([
            'message' => 'ok',
            'code' => 0,
            'data' => $art,
        ]);
    }


    /**
     * get article comment by article id
     *
     * @param int $id
     * @query int $page required
     * @query int $pageSize required
     * @return $ref comment
     * @throws \Exception
     */
    public function comments(Request $request)
    {
        $page = abs($request->getQuery('page'));
        $page = $page ?: 1;
        $pageSize = 20;
        $offset = ($page - 1) * $pageSize;
        $comment = new Comment();
        $comments = $comment->find([
            'id' => ['$gt' => 1],
        ],
            [
                'order' => ['id' => 'desc'],
                'limit' => $pageSize,
                'offset' => $offset,
            ]);
        $total = 0; // @todo
        $comments = $comment->toArray($comments);
        $response = new Response();
        return $response->json([
            'message' => 'ok',
            'code' => 0,
            'data' => [
                'list' => $comments,
                'page' => $page,
                'pageSize' => $pageSize,
                'total' => $total,
            ],
        ]);
    }

    /**
     * drop article comments by ids
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     */
    public function dropComment(Request $request)
    {
        $ids = $request->getPayload('ids');
        $ids = explode(',', $ids);
        $response = new Response();
        if (empty(($ids))) {
            return $response->withStatus(400)->json([
                'message' => '参数错误',
                'code' => 1,
            ]);
        }
        $comment = new Comment();
        $where = ['id' => ['$in' => $ids]];
        $comment->delete($where);
        return $response->json([
            'message' => 'ok',
            'code' => 0,
        ]);
    }
}
