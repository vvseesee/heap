<?php

namespace Vvseesee\Heap;

use Illuminate\Config\Repository;
use Illuminate\Support\Collection;

class Heap
{
    private $config;

    private $nodes = [];

    private $type = 'min';

    private $size = 0;

    public function __construct(Repository $config)
    {
        $this->config = collect($config->get('heap'));
    }

    /**
     * 接受外部输入数组
     */
    public function setNodes(array $nodes, $type = 'max')
    {
        if (is_array($nodes) && !empty($nodes)) {
            $this->nodes = $nodes;
            if (isset($this->nodes[0])) {
                $this->nodes[] = $this->nodes[0];
                unset($this->nodes[0]);
            }

            // foreach ($nodes as $key => $value) {
            //     $this->nodes[$key + 1] = $value;
            // }

            $this->size = count($nodes);

            if (in_array($type, ['max', 'min'])) {
                $this->type = $type;
            }

            // 元素下沉，构建堆
            for ($i = floor($this->size / 2); $i > 0; $i--) {
                $this->sinkDown($i);
            }
        }

        return $this;
    }

    /**
     * 向堆中追加元素
     */
    public function push($node)
    {
        $this->nodes[++$this->size] = $node;
        $this->floatUp($this->size);
    }

    /**
     * 从堆中弹出元素
     */
    public function pop()
    {
        $node = $this->nodes[1];
        $this->nodes[1] = $this->nodes[$this->size];
        unset($this->nodes[$this->size]);
        $this->size--;
        $this->sinkDown(1);
        return $node;
    }

    /**
     * 是否为空堆
     */
    public function isEmpty()
    {
        return $this->size == 0;
    }

    /**
     * 返回堆中节点个数
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * 返回堆中所有节点
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * 索引i的值是否比索引j的值小
     */
    private function less($i, $j)
    {
        return isset($this->nodes[$i]) && isset($this->nodes[$j]) && $this->nodes[$i] < $this->nodes[$j];
    }

    /**
     * 交换位置
     */
    private function exchangeNode($i, $j)
    {
        if (isset($this->nodes[$i]) && isset($this->nodes[$j])) {
            $temp = $this->nodes[$i];
            $this->nodes[$i] = $this->nodes[$j];
            $this->nodes[$j] = $temp;
        }
    }

    /**
     * 元素上浮, 当前元素与父级元素比较
     * type = max 情况下，如果当前元素比父级元素大，则交换位置，并向上递归
     * type = min 情况下，如果当前元素比父级元素小，则交换位置，并向上递归
     */
    private function floatUp($index)
    {
        if ($this->type == 'max') {
            $this->floatUpByMax($index);
        }

        if ($this->type == 'min') {
            $this->floatUpByMin($index);
        }
    }

    private function floatUpByMax($index)
    {
        while ($index > 1) {
            $parentKey = floor($index / 2);

            // 父级元素不比当前元素小，退出
            if (!$this->less($parentKey, $index)) {
                break;
            }

            $this->exchangeNode($index, $parentKey);
            $index = $parentKey;
        }
    }

    private function floatUpByMin($index)
    {
        while ($index > 1) {
            $parentKey = floor($index / 2);

            // 当前节点不比父级节点小，退出
            if (!$this->less($index, $parentKey)) {
                break;
            }

            $this->exchangeNode($index, $parentKey);
            $index = $parentKey;
        }
    }

    /**
     * 节点下沉，当前节点与孩子节点进行比较
     * type = max 情况下，如果当前节点比孩子节点小，则交换位置，并向下递归
     * type = min 情况下，如果当前节点比孩子节点大，则交换位置，并向下递归
     */
    private function sinkDown($index)
    {
        if ($this->type == 'max') {
            $this->sinkDownByMax($index);
        }

        if ($this->type == 'min') {
            $this->sinkDownByMin($index);
        }
    }

    private function sinkDownByMax($index)
    {
        while (2 * $index <= $this->size) {
            $maxKey = 2 * $index;

            if (2 * $index + 1 <= $this->size && $this->less(2 * $index, 2 * $index + 1)) {
                $maxKey = 2 * $index + 1;
            }

            // 当前节点比孩子节点大，退出
            if (!$this->less($index, $maxKey)) {
                break;
            }

            $this->exchangeNode($index, $maxKey);
            $index = $maxKey;
        }
    }

    private function sinkDownByMin($index)
    {
        while (2 * $index <= $this->size) {
            $minKey = 2 * $index;

            if (2 * $index + 1 <= $this->size && $this->less(2 * $index + 1, 2 * $index)) {
                $minKey = 2 * $index + 1;
            }

            // 当前节点不比孩子节点小，退出
            if (!$this->less($minKey, $index)) {
                break;
            }

            $this->exchangeNode($index, $minKey);
            $index = $minKey;
        }
    }
}
