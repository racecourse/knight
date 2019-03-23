<?php
/**
 * @license   https://github.com/Init/licese.md
 * @copyright Copyright (c) 2017
 * @author    : bugbear
 * @date      : 2017/4/2
 * @time      : 下午4:38
 */

namespace Knight\Controller;

use Knight\Component\Controller;
use Knight\Model\Comment;
use Knight\Model\Post;
use Knight\Model\Category;
use Linfo\Linfo;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest as Request;

class Admin extends Controller
{

    /**
     * 网站概况
     *
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|static
     * @throws \ReflectionException
     */
    public function survey(Request $request)
    {
//        $article = new Post();
//        $articleNumber = $article->count();
//        $commentNumber = (new Comment())->count();
        $photoNumber = 0;
        $albumNumber = 0;
//        $settings['dates'] = 'm/d/y h:i A (T)'; // Format for dates shown. See php.net/date for syntax
//        $settings['language'] = 'en'; // Refer to the lang/ folder for supported lanugages
//        $settings['show']['kernel'] = true;
//        $settings['show']['ip'] = true;
//        $settings['show']['os'] = true;
//        $settings['show']['load'] = true;
//        $settings['show']['ram'] = true;
//        $settings['show']['uptime'] = true;
//        $settings['show']['cpu'] = true;
//        $settings['show']['process_stats'] = true;
//        $settings['show']['hostname'] = true;
//        $settings['show']['model'] = true; # Model of system. Supported on certain OS's. ex: Macbook Pro
//        $settings['cpu_usage'] = true;
//        $system = [
//            'os' => $info['OS'],
//            'kernel' => $info['Kernel'],
//            'acccessIp' => $info['AccessIP'],
//            'memory' => $info['RAM'],
//            'hostname' => $info['HostName'],
//            'uptime' => $info['UpTime'],
//            'cpuInfo' => $info['CPU'],
//            'load' => $info['Load'],
//        ];
//        if ($info['processStats'] && $info['processStats']['exists']) {
//            $system['process'] = $info['processStats']['totals'];
//        }
//        foreach($output as $key => $value) {
//            echo $key . PHP_EOL;
//        }
//        var_dump($output);
        return $this->json([
            'message' => 'ok',
            'code' => 0,
            'data' => [
                'articleNumber' => 4,
                'commentNumber' => 3,
                'albumNumber' => 2,
                'photoNumber' => 1,
                'pv' => 1,
                'ip' => 1,
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
        $this->query = $request->getQueryParams();
        $page = abs($this->getQuery('page'));
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
        $total = yield $article->count($where);
        $articles = yield $article->find($where, $option);
        $list = $article->toArray($articles);

        return $this->json([
            'message' => 'ok',
            'data' => [
                'page' => $page,
                'pageSize' => $pageSize,
                'list' => $list,
                'total' => $total,
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
        $this->payload = $request->getParsedBody();
        $title = $body['title'] ?? '';
        $content = $this->getPayload('content');
        $tags = $this->getPayload('tags');
        $cateId = $this->getPayload('cateId');
        $permission = $this->getPayload('permission');
        if (!in_array($permission, [0, 1, 2])) {
            return $this->json([
                'message' => 'Illegal param permission',
                'code' => 1,
            ], 400);
        }

        if (!$title) {
            return $this->json([
                'message' => 'title required',
                'code' => 1,
            ]);
        }

        if (!$content) {
            return $this->json([
                'message' => 'content can not empty'
            ]);
        }

        $user = $request->getAttribute('session');
        $created = $this->getPayload('created');
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

        return $this->json([
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
        $id = $this->getParam('id');
        $title = $this->getPayload('title');
        $tags = $this->getPayload('tags');
        $content = $this->getPayload('content');
        $cateId = $this->getPayload('cateId');
        $time = $this->getPayload('created');
        $permission = $this->getPayload('permission');
        if (!$title || !$content) {
            return $this->json([
                'message' => 'content && title are required',
                'code' => 1,
            ]);
        }

        $post = new Post();
        $art = $post->findById($id);
        if (!$art) {
            return $this->json([
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

        return $this->json([
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
        $id = $this->getParam('id');
        if (!intval($id)) {
            return $this->json([
                'message' => 'Illegal ID',
                'code' => 1,
            ]);
        }

        $post = new Post();
        $art = $post->findById($id);
        if (!$art) {
            return $this->json([
                'message' => 'article not found',
                'code' => 2,
            ]);
        }

        $art->delete();

        return $this->json([
            'message' => 'ok',
            'code' => 0,
        ]);
    }

    public function detail(Request $request)
    {
        $id = $this->getParam('id');
        if (!intval($id)) {
            return $this->withStatus(400)
                ->json([
                    'message' => 'Illegal ID',
                    'code' => 1,
                ]);
        }

        $post = new Post();
        $art = $post->findById($id);
        if (!$art) {
            return $this->json([
                'message' => 'article not found',
                'code' => 2,
            ]);
        }

        $art = $art->toArray();

        return $this->json([
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
        $page = abs($this->getQuery('page'));
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
        return $this->json([
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
        $ids = $this->getPayload('ids');
        $ids = explode(',', $ids);
        if (empty(($ids))) {
            return $this->json([
                'message' => '参数错误',
                'code' => 1,
            ]);
        }

        $comment = new Comment();
        $where = ['id' => ['$in' => $ids]];
        $comment->delete($where);

        return $this->json([
            'message' => 'ok',
            'code' => 0,
        ]);
    }
}
