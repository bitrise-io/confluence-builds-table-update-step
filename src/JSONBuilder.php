<?php
namespace DAG\JIRA\BuildsTable;

/**
 * Class JSONBuilder
 */
final class JSONBuilder
{
    /** @var ContentParser */
    private $contentParser;

    /**
     * ContentParser constructor.
     *
     * @param ContentParser $contentParser
     */
    public function __construct(ContentParser $contentParser)
    {
        $this->contentParser = $contentParser;
    }

    public function build(array $fromBuilds, $content, array $variables)
    {
        $config = $this->contentParser->parse($content, $variables);

        $fromBuilds[] = $config;

        return $fromBuilds;
    }
}
