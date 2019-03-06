<?php

namespace Calebporzio\GitDown\Tests;

use CalebPorzio\GitDown;
use PHPUnit\Framework\TestCase;

class GitDownTest extends TestCase
{
    /** @test */
    public function github_properly_parses_markdown()
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
