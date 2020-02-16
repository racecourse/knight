<?php
namespace Component;

use MongoDB\Collection;
use Mews\Builder as MysqlBuilder;

class Builder extends MysqlBuilder {

    public $collection = null;

    public $where = [];

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    public function where(array $where)
    {
        $this->where = array_merge($this->where, $where);
        return $this;
    }

    public function select($options=[])
    {
        return $this->collection->find($this->where, $options);
    }

    public function count($options=[])
    {
        $count = $this->collection->count($this->where, $options);
        return ['count' => $count];
    }

    public function update(array $update, $options=[])
    {
        return $this->collection->updateMany($this->where, $update, $options);
    }

    public function delete($options=[])
    {
        return $this->collection->updateMany($this->where, $options);
    }

    public function insert(array $data, array $option=[])
    {
        return $this->collection->insert($data, $options);
    }


}
