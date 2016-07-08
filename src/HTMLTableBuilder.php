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
    public function build($fromCode, array $cellsValues)
    {
        $DOMDocument = new \DOMDocument('1.0', 'utf8');
        $DOMDocument->loadHTML(mb_convert_encoding($fromCode, 'HTML-ENTITIES', "UTF-8"));

        $DOMXpath = new \DOMXPath($DOMDocument);
        $tableBodyNodes = $DOMXpath->query('//table//tbody');

        if ($tableBodyNodes->length != 1) {
            throw new \InvalidArgumentException(sprintf('Expecting 1 tbody, %d found', $tableBodyNodes->length));
        }

        $tableBodyNode = $tableBodyNodes->item(0);

        $this->addCellsToTableBody($DOMDocument, $tableBodyNode, $cellsValues);

        $html = $this->getHtmlWithoutDocType($DOMDocument);

        return $html;
    }

    /**
     * @param \DOMDocument $DOMDocument
     * @param array        $cellsValues
     */
    private function addCellsToTableBody(\DOMDocument $DOMDocument, \DOMNode $tableBodyNode, array $cellsValues)
    {
        $tableRow = $DOMDocument->createElement('tr', '');

        foreach ($cellsValues as $cellValue) {
            $tableCell = $DOMDocument->createElement('td', $cellValue);
            $tableCell->setAttribute('class', 'confluenceTd');
            $tableRow->appendChild($tableCell);
        }

        $tableBodyNode->appendChild($tableRow);
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
            $html .= $DOMDocument->saveHTML($rootLevelTag);
        }

        return $html;
    }
}
