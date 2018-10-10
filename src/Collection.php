<?php


namespace app\components;


class Collection implements \ArrayAccess, \JsonSerializable, \Countable {

    protected $items;

    protected $defaultValue = null;

    function __construct($items, $defaultValue = null) {
        if ($items instanceof Collection) {
            $this->items = $items->items;
        } else {
            $this->items = $items;
        }
        $this->defaultValue = $defaultValue;
    }

    function all() {
        return $this->items;
    }

    function contains($needle) {
        return in_array($needle, $this->items);
    }

    function filter($callback) {
        $result = array_filter($this->items, $callback);
        return new Collection($result);
    }

    function map($callback) {
        if (!is_array($this->items)) {
            var_dump($this->items);
            debug_print_backtrace();
            die();
        }
        $result = array_map($callback, $this->items);
        return new Collection($result);
    }

    function mapWithKeys($callback) {
        $result = array();
        foreach ($this->items as $item) {
            $pair = $callback($item);
            foreach ($pair as $key => $value) {
                $result[$key] = $value;
            }
        }
        return new Collection($result);
    }

    function join($glue) {
        return join($glue, $this->items);
    }

    function each($callback) {
        foreach ($this->items as $item) {
            $callback($item);
        }
    }

    public function offsetExists($offset) {
        return array_key_exists($offset, $this->items);
    }

    public function offsetGet($offset) {
        return $this->offsetExists($offset) ? $this->items[$offset] : $this->defaultValue;
    }

    public function offsetSet($offset, $value) {
        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->items[$offset]);
    }

    public function jsonSerialize() {
        return $this->items;
    }

    /**
     * @inheritdoc
     */
    public function count() {
        return count($this->items);
    }
}