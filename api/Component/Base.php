<?php
namespace Knight\Component;

use MongoDB\Client;
use MongoDb\Collection;
use Mews\Model;
use Ben\Config;

class Base extends Model {

    protected $table = '';

    public function __construct($config = [])
    {
        if (empty($config)) {
            $config = Config::get('mongo');
        }

        parent::__construct($config);
    }

    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        if (isset($data['id'])) {
            $data['id'] = (string) $data['id'];
        }

        return $data;
    }

}
