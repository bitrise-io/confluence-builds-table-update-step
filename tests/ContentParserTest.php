<?php

use DAG\JIRA\BuildsTable\ContentParser;

class ContentParserTest extends PHPUnit_Framework_TestCase
{
    public function testParser()
    {
        $parser = new ContentParser();

        $content = <<<EOF
foo:\$BAR
EOF;
        $parsedContent = $parser->parse($content, ['BAR' => 123]);

        $this->assertEquals(123, $parsedContent['foo']);
    }
}

