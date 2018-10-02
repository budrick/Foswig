<?php
namespace Textfox\Foswig;

class TrieNode
{
    private $children = [];

    public function add($word)
    {
        if (mb_strlen($word) > 1) {
            $this->add(substr($word, 1), $this);
        }

        $split = preg_split('//u', $word, null, PREG_SPLIT_NO_EMPTY);

        $currentnode = $this;
        foreach ($split as $index => $char) {
            $childnode = $this->children[$char] ?? new TrieNode();
            $currentnode->children[$char] = $childnode;
            $currentnode = $childnode;
        }
    }

    public function isDuplicate($word)
    {
        $word = strtolower($word);
        $currentnode = $this;

        $split = preg_split('//u', $word, null, PREG_SPLIT_NO_EMPTY);
        foreach ($split as $index => $char) {
            $childnode = $currentnode->children[$char] ?? false;
            if (!$childnode) {
                return false;
            }
            $currentnode = $childnode;
        }
        return true;
    }
}
