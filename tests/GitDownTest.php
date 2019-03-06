<?php

namespace GitDown\Tests;

use GitDown\GitDown;
use PHPUnit\Framework\TestCase;

class GitDownTest extends TestCase
{
    /** @test */
    public function github_properly_parses_markdown()
    {
        $parsed = (new GitDown)->parse(<<<EOT
**foo**

[bar](baz)
EOT
        );

        $this->assertEquals(<<<EOT
<p><strong>foo</strong></p>
<p><a href="baz">bar</a></p>
EOT
        , trim($parsed));
    }

    /** @test */
    public function can_provide_caching_strategy()
    {
        $numberOfTimesGitHubWasCalled = 0;

        $firstResult = (new GitDown)->parseAndCache('**foo**', $this->cacheStrategy($numberOfTimesGitHubWasCalled));
        $secondResult = (new GitDown)->parseAndCache('**foo**', $this->cacheStrategy($numberOfTimesGitHubWasCalled));

        $this->assertEquals('<p><strong>foo</strong></p>', trim($firstResult));
        $this->assertEquals('cached', $secondResult);
    }

    protected function cacheStrategy(&$callCount)
    {
        return function ($parse) use (&$callCount) {
            if ($callCount < 1) {
                $callCount++;
                return $parse();
            } else {
                return 'cached';
            }
        };
    }
}
