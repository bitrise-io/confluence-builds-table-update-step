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
        $cellsValues = [
            "FooBarApp",
            "0.1",
            "600",
            "2016-07-06",
            "Nada",
        ];

        $builder = new HTMLTableBuilder();
        $newTableContent = $builder->build($existingTableContent, $cellsValues);

        $this->assertNotNull($newTableContent);

        $domDocument = new DOMDocument();
        $domDocument->loadHTML($newTableContent);

        $domXpath = new DOMXPath($domDocument);
        $cellsNodes = $domXpath->query("//table//tr[last()]/td");
        $this->assertEquals(5, $cellsNodes->length);

        $this->assertEquals($cellsValues[0], $cellsNodes->item(0)->nodeValue);
        $this->assertEquals($cellsValues[1], $cellsNodes->item(1)->nodeValue);
        $this->assertEquals($cellsValues[2], $cellsNodes->item(2)->nodeValue);
        $this->assertEquals($cellsValues[3], $cellsNodes->item(3)->nodeValue);
        $this->assertEquals($cellsValues[4], $cellsNodes->item(4)->nodeValue);
    }
}
