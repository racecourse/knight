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

    public function survey(Request $request)
    {
        $article = new Post();
//        $articleNumber = $article->count();
//        $commentNumber = (new Comment())->count();
        $photoNumber = 0;
        $albumNumber = 0;
        $settings['byte_notation'] = 1024; // Either 1024 or 1000; defaults to 1024
        $settings['dates'] = 'm/d/y h:i A (T)'; // Format for dates shown. See php.net/date for syntax
        $settings['language'] = 'en'; // Refer to the lang/ folder for supported lanugages
        $settings['icons'] = true; // simple icons
        $settings['theme'] = 'default'; // Theme file (layout/theme_$n.css). Look at the contents of the layout/ folder for other themes.
        $settings['allow_changing_themes'] = false; // Allow changing the theme per user in the UI?
        /*
         * Possibly don't show stuff
         */
// For certain reasons, some might choose to not display all we can
// Set these to true to enable; false to disable. They default to false.
        $settings['show']['kernel'] = true;
        $settings['show']['ip'] = true;
        $settings['show']['os'] = true;
        $settings['show']['load'] = true;
        $settings['show']['ram'] = true;
        $settings['show']['hd'] = true;
        $settings['show']['webservice'] = false; // Might be dangerous/confidential information; disabled by default.
        $settings['show']['phpversion'] = false; // Might be dangerous/confidential information; disabled by default.
        $settings['show']['network'] = true;
        $settings['show']['uptime'] = true;
        $settings['show']['cpu'] = true;
        $settings['show']['process_stats'] = true;
        $settings['show']['hostname'] = true;
        $settings['show']['distro'] = true; # Attempt finding name and version of distribution on Linux systems
        $settings['show']['devices'] = true; # Slow on old systems
        $settings['show']['model'] = true; # Model of system. Supported on certain OS's. ex: Macbook Pro
        $settings['show']['numLoggedIn'] = true; # Number of unqiue users with shells running (on Linux)
        $settings['show']['virtualization'] = true; # whether this is a VPS/VM and what kind
// CPU Usage on Linux (per core and overall). This requires running sleep(1) once so it slows
// the entire page load down. Enable at your own inconvenience, especially since the load averages
// are more useful.
        $settings['cpu_usage'] = true;
        $response = new Response();
        $linfo = new Linfo($settings);
        $output = new \Linfo\Output\Serialized($linfo);
        var_dump($output->output());
//        foreach($output as $key => $value) {
//            echo $key . PHP_EOL;
//            var_dump($value);
//        }
//        var_dump($output);
        $linfo->scan();
        $response->json([
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

    public function article(Request $request)
    {
        $pageSize = 10;
        $page = $request->getQuery('page');
        $page = abs($page) ?: 1;
        $offset = ($page - 1) * $pageSize;
        $article = new Post();
        $condition = [
            'id' => ['$gt' => 0]
        ];
        $options = [
            'limit' => $pageSize,
            'offset' => $offset,
            'order' => [
                'created' => 'desc',
            ]
        ];
        $posts = $article->find($condition, $options);
        $posts = $article->toArray($posts);
        $total = $article->count($condition);
        $ret = [
            'total' => $total, // @fixme
            'page' => $page,
            'pageSize' => $pageSize,
            'list' => $posts,
        ];
        $response = new Response();
        
        return $response->json([
            'message' => 'ok',
            'code' => 0,
            'data' => $ret,
        ]);
    }

    /**
     * @body string title
     * @body string content
     * @body integer cateId
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

        $created = $request->getPayload('created');
        $created = $created ? strtotime($created) : time();
        $tags = \is_array($tags) ? \implode(',', $tags) : $tags;
        $post = [
            'userId' => 1,
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
