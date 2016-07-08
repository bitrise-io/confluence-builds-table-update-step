<?php
namespace DAG\JIRA\BuildsTable;

/**
 * Class HTMLTableBuilder
 */
final class HTMLTableBuilder
{
    /**
     * @param string $fromCode HTML code that contains the table
     */
    public function build($fromCode, $codeToAdd)
    {
        $DOMDocument = new \DOMDocument('1.0', 'utf-8');
        $DOMDocument->formatOutput = false;
        $DOMDocument->preserveWhiteSpace = false;
        $DOMDocument->loadHTML($fromCode);

        $DOMXpath = new \DOMXPath($DOMDocument);
        $tableBodyNodes = $DOMXpath->query('//table//tbody');

        if ($tableBodyNodes->length != 1) {
            throw new \InvalidArgumentException(sprintf('Expecting 1 tbody, %d found', $tableBodyNodes->length));
        }

        $tableBodyNode = $tableBodyNodes->item(0);

        $fragment = $DOMDocument->createDocumentFragment();
        $fragment->appendXML($codeToAdd);
        $tableBodyNode->appendChild($fragment);

        $html = $this->getHtmlWithoutDocType($DOMDocument);

        return $html;
    }

    /**
     * @param \DOMDocument $DOMDocument
     *
     * @return string
     */
    private function getHtmlWithoutDocType(\DOMDocument $DOMDocument)
    {
        $html = '';
        $bodyTag = $DOMDocument->documentElement->getElementsByTagName('body')->item(0);
        foreach ($bodyTag->childNodes as $rootLevelTag) {
            $html .= $DOMDocument->saveXML($rootLevelTag);
        }

        return $html;
    }
}
