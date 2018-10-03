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

    public function generateWord($min = 3, $max = 10, $allowDuplicates = true, $maxAttempts = 25)
    {
        $attempts = 0;
        do {
            $word = $this->generateFixedWord(random_int($min, $max));
            $isDuplicate = $this->duplicates->isDuplicate($word);
            $attempts++;
        } while (!$allowDuplicates && $isDuplicate && $attempts < $maxAttempts);

        if ($attempts >= $maxAttempts && !$allowDuplicates && $isDuplicate) {
            return false;
            // throw new \Exception("Could not generate a word with the given parameters in $attempts or fewer attempts.");
        }

        return $word;
    }

    /**
     * Create a fixed-length word from
     */
    public function generateFixedWord($length = 5)
    {
        $word = '';
        $currentNode = $this->start->getRandomNeighbor();
        while (mb_strlen($word) < $length) {
            while ($currentNode && mb_strlen($word) < $length) {
                $word .= $currentNode->getCharacter();
                $currentNode = $currentNode->getRandomNeighbor();
            }
            $currentNode = $this->start->getRandomNeighbor();
        }
        return $word;
    }
}
