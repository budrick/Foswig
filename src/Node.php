<?php
namespace Textfox\Foswig;

class Node
{
    private $character = null;
    private $neighbors = [];

    public function __construct($ch)
    {
        $this->character = $ch;
    }

    public function addNeighbor($node)
    {
        array_push($this->neighbors, $node);
    }

    public function getNeighborCount()
    {
        return count($this->neighbors);
    }

    public function getNeighbor($index)
    {
        return $this->neighbors[$index] ?? null;
    }

    public function getRandomNeighbor()
    {
        $rand = random_int(0, count($this->neighbors) - 1);
        return $this->neighbors[$rand];
    }

    public function getCharacter()
    {
        return $this->character;
    }
}
