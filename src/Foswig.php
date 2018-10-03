<?php
namespace Mudbrick\Foswig;

class Foswig
{
    private $order = 3;
    private $duplicates = null;
    private $start = null;
    private $map = [];

    public function __construct($order = 1)
    {
        $this->duplicates = new TrieNode();
        $this->start = new Node('');
        $this->order = $order;
    }

    public function addWordsToChain($words)
    {
        foreach ($words as $word) {
            $this->addWordToChain($word);
        }
    }

    private function addWordToChain($word)
    {
        $this->duplicates->add(strtolower($word));

        $prev = $this->start;
        $key = '';
        $split = preg_split('//u', $word, null, PREG_SPLIT_NO_EMPTY);
        foreach ($split as $index => $char) {
            $key .= $char;
            if (mb_strlen($key) > $this->order) {
                $key = substr($key, 1);
            }
            $newnode = $this->map[$key] ?? new Node($char);
            $this->map[$key] = $newnode;
            $prev->addNeighbor($newnode);
            $prev = $newnode;
        }
        $prev->addNeighbor(null);
    }

    public function generateWord($min = 1, $max = 10, $allowDuplicates = true, $maxAttempts = 5)
    {
        $word = '';
        $repeat = false;
        $attempts = 0;

        do {
            $repeat = false;

            $currentnode = $this->start->getRandomNeighbor();

            while ($currentnode && ($max < 0 || mb_strlen($word) <= $max)) {
                $word .= $currentnode->getCharacter();
                $currentnode = $currentnode->getRandomNeighbor();

                if (mb_strlen($word) > $max || mb_strlen($word) < $min) {
                    $repeat = true;
                }
            }
        } while ($repeat || (!$allowDuplicates && ++$attempts < $maxAttempts && $this->duplicates->isDuplicate($word)));
        if ($attempts >= $maxAttempts) {
            throw new Exception('Could not generate a word after ' . $attempts . ' attempts');
        }
        return $word;
    }
}
