<?php

namespace Calebporzio\GitDown\Tests;

use Calebporzio\GitDown\GitDown;
use PHPUnit\Framework\TestCase;

class GitDownTest extends TestCase
{
    /** @test */
    public function basic_markdown_parsing()
    {
        $parsed = GitDown::parse(<<<EOT
**foo**

[bar](baz)
EOT
        );

        $this->assertEquals(<<<EOT
<p><strong>foo</strong></p>
<p><a href="baz">bar</a></p>

EOT
        , $parsed);
    }
}
