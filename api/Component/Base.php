<?php
namespace Knight\Component;

use MongoDB\Client;
use MongoDb\Collection;
use Mews\Model;
use Ben\Config;

class Base extends Model {


    protected $client = null;

    protected $db = null;

    protected static $_client = null;

    public function __construct(array $config = [])
    {
        if (empty($config)) {
            $config = Config::get('mongo');
        }

        $this->client = self::getClient($config);
        $this->db = $config['db'];
    }

    /**
     * @param array $config
     * @return Client
     */
    public static function getClient(array $config)
    {
        if (self::$_client === null) {
            self::$_client = new Client($config['uri']);

        }

        return self::$_client;
    }

    public function db(string $db) {
        return $this->client->selectDatabase($db);
    }

    public function getCollection(string $collection='')
    {
        $collection = $collection ?: $this->table;
        // echo $collection . '+++' . $this->db .PHP_EOL;
        return $this->db($this->db)
            ->selectCollection($collection);
    }

    // public function builder(): Builder
    // {
    //     return new Builder($this->getCollection($this->table));
    // }


    public function find(array $where, $options=[])
    {
        $result = $this->getCollection()
            ->find([], []);

        $res = [];
        foreach ($result as $data) {
            $res[] = $this->getModel((array)$data);
        }

        return $res;
    }

    public function findOne(array $where=[], array $options=[])
    {
        $result = $this->getCollection()->findOne($where, $options);
        if (empty($result)) {
            return null;
        }

        return $result;
    }

    public function count(array $where=[]) {
        $count = $this->getCollection()->count($where);
        return (int)$count;
    }

    public function update($where=[], $update=[]) {
        $changed = $this->getChange();
        $changed = array_merge($changed, $update);
        if (empty($changed)) {
            return $this;
        }

        $this->before();
        $mapping = $this->revertFields($changed);
        $this->builder->updateMany($where, $update, $options);
        $this->result = array_merge($this->result, $changed);
        $this->after();
        $this->free();

        return $this->getModel($this->result);
    }

    public function delete($where=[], array $options=[]) {
        if (empty($where)) {
            $where = $this->pk;
        }

        $this->before();
        $where = $this->revertFields($where);
        $this->builder()
            ->deleteOne($where, $options);
        return $this->after();
    }

}
