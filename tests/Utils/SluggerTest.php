<?php

namespace App\Tests\Utils;

use App\Service\SlugifyService;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{
    /**
     * @dataProvider getSlugs
     */
    public function testSlugify(string $string, string $slug)
    {
        $this->assertSame($slug, SlugifyService::slugify($string));
    }

    public function getSlugs()
    {
        return [
            ['Lorem Ipsum', 'lorem-ipsum'],
            ['  Lorem Ipsum  ', 'lorem-ipsum'],
            [' lOrEm  iPsUm  ', 'lorem-ipsum'],
            ['!Lorem Ipsum!', 'lorem-ipsum'],
            ['lorem-ipsum', 'lorem-ipsum'],
            ['lorem 日本語 ipsum', 'lorem-日本語-ipsum'],
            ['lorem русский язык ipsum', 'lorem-русский-язык-ipsum'],
            ['lorem العَرَبِيَّة‎‎ ipsum', 'lorem-العَرَبِيَّة‎‎-ipsum'],
        ];
    }
}
