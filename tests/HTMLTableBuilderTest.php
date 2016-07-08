<?php

use DAG\JIRA\BuildsTable\HTMLTableBuilder;

/**
 * Class HTMLTableBuilderTest
 */
class HTMLTableBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testNewBuildAddedInTable()
    {
        $existingTableContent = file_get_contents(dirname(__FILE__).'/document.html');
        $content = "<tr>".
            "<td>FooBarApp</td>".
            "<td>0.1</td>".
            "<td>600</td>".
            "<td>2016-07-06</td>".
            "<td>Nada</td>".
            "</tr>";

        $builder = new HTMLTableBuilder();
        $newTableContent = $builder->build($existingTableContent, $content);

        $this->assertNotNull($newTableContent);

        $domDocument = new DOMDocument();
        $domDocument->loadHTML($newTableContent);

        $domXpath = new DOMXPath($domDocument);
        $cellsNodes = $domXpath->query("//table//tr[last()]/td");
        $this->assertEquals(5, $cellsNodes->length);

        $this->assertEquals("FooBarApp", $cellsNodes->item(0)->nodeValue);
        $this->assertEquals("0.1", $cellsNodes->item(1)->nodeValue);
        $this->assertEquals("600", $cellsNodes->item(2)->nodeValue);
        $this->assertEquals("2016-07-06", $cellsNodes->item(3)->nodeValue);
        $this->assertEquals("Nada", $cellsNodes->item(4)->nodeValue);
    }
}
