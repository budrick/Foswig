<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Textfox\Foswig\Foswig;

final class FoswigTest extends TestCase
{
    public function testCanBeCreatedFromValidEmailAddress(): void
    {
        $this->assertInstanceOf(
            Foswig::class,
            new Foswig()
        );
    }

    public function testCanCreateWord(): void
    {
        $f = new Foswig(1);
        $f->addWordsToChain(['test', 'toast', 'taste', 'trieste']);
        $this->assertNotEmpty($f->generateWord(50));
    }

    public function testMinLength(): void
    {
        $f = new Foswig();
        $f->addWordsToChain(['test', 'toast', 'taste', 'trieste']);
        $this->assertGreaterThanOrEqual(10, mb_strlen($f->generateWord(10)));
    }

    public function testMaxLength(): void
    {
        $f = new Foswig(1);
        $f->addWordsToChain(['test', 'toast', 'taste', 'trieste']);
        $this->assertLessThanOrEqual(5, mb_strlen($f->generateWord(0, 5)));
    }
}