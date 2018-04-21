<?php
/**
 * @license MIT
 * @copyright Copyright (c) 2018
 * @author: bugbear
 * @date: 2018/4/19
 * @time: 下午5:03
 */

namespace Knight\Component;

use Lily\Parser;
use Courser\App;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use PhpParser\NodeTraverser;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class Manure
{
    public $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }


    public function run()
    {

        $routes = $this->app->layer;
        foreach ($routes as $key => $layers) {
            $doc = [];
            if (is_array($layers)) {
                foreach ($layers as $k => $route ) {
                    $method = $route->method;
                    $doc[$method] = [];
                    if (!empty($route->paramNames)) {
                        foreach ($route->paramNames as $name) {
                            $doc[$method]['parameters'][] = [
                                'in' => 'path',
                                'name' => $name,
                                'required' => true,
                                'description' => $name,
                                'type' => 'string'
                            ];
                        }
                    }

                    $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
                    $traverser     = new NodeTraverser;
                    $myTraverser = new MyNodeVisitor;
                    $traverser->addVisitor($myTraverser);
                    foreach ((array) $route->callable as $callable) {
                        if (is_array($callable)) {
                            $reflection = new \ReflectionMethod(... $callable);
                        } else {
                            $reflection = new \ReflectionFunction($callable);
                        }
                        $start = $reflection->getStartLine();
                        $end = $reflection->getEndLine();
                        $file = $reflection->getFileName();
                        $reader = new \SplFileObject($file, 'r');
                        $reader->seek($start);
                        $code = '<?php' . PHP_EOL;
                        $main = false;
                        for ($i = $start; $i < $end; $i++) {
                            $line = $reader->current();
                            $reader->seek($i);

                            $brace = preg_replace('%[\s+]%', '', $line);
                            if (!$main && $brace === '{') {
                                continue;
                            }

                            $main = true;
                            $code .= $line;

                        }

                        $stmts = $parser->parse($code);
                        $traverser->traverse($stmts);
                        $params = $myTraverser->params;
                        if (!empty($params['body'])) {
                            $doc[$method]['parameters'][] = $params['body'];
                        }

                        if (!empty($params['query'])) {
                            var_dump($params['query']);
                            $doc[$method]['parameters'][] = $params['query'];
                        }

                    }


                    $main = [
                        'tags' => '',
                        'description' => '',
                        'security' => [],
                        'responses' => [
                            '200' => [
                                'description' => '',
                                "schema" => [
                                    "type" => "object",
                                    "properties" => [
                                        "message" => [
                                            "type" => 'string',
                                            'default' => 'ok'
                                        ],
                                        'data' => [
                                            'type' => 'object'
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ];
                    $doc[$method] = array_merge($doc[$method], $main);

                    var_dump($doc);
//                    var_dump($route->group);
                    var_dump($route->route);
                    $path = $route->route;
                    $yaml = preg_replace('#[\:|\{|}+]#', '', $path);
                    $yaml = trim($yaml, '\/');
                    $yaml = str_replace('/', '_', $yaml);
                    echo $yaml;
                }
            }
        }
    }
}

class MyNodeVisitor extends NodeVisitorAbstract
{
    public $params = [];

    public $bodyMethodName = 'getPayload';

    public $queryMethodName = 'getQuery';


    public function enterNode(Node $node) {
//        if ($node instanceof Node\Scalar\String_) {
//            $node->value = 'foo';
//        }


        if ($node instanceof Node\Expr\MethodCall) {
            if ($node->name === $this->bodyMethodName) {
                $name = $node->args[0]->value->value;
                $payload  = [
                    $name => [
                        'type' => 'string',
                    ]
                ];

                if ($node->args[1]) {
                    $default = $node->args[1]->value->value;
                    $payload[$name]['default'] = $default;
                }

                if (isset($this->params['body'])) {
                    $origin = $this->params['body']['schema']['properties'];
                    $this->params['body']['schema']['properties'] = $origin + $payload;
                } else {
                    $this->params['body'] = [
                        'in' => 'body',
                        'description' => 'payload params',
                        'schema' => [
                            'type' => 'object',
                            'properties' => $payload,
                        ]
                    ];
                }

            } elseif ($node->name === $this->queryMethodName) {
                $name = $node->args[0]->value->value;
                $this->params['query'][] = [
                    'in' => 'query',
                    'type' => 'string',
                    'description' => 'query param:' . $name,
                    'required' => false,
                    'name' => $name,
                ];
        }


//            var_dump(new Node\Name($node->toString('_')));
        }

    }
}