<?php

namespace PhpDruidIngest\QueryParameters;

use PHPUnit_Framework_TestCase;

class IndexTaskQueryParametersTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        date_default_timezone_set('UTC');
    }


    private $mockDataSource = 'my-datasource';

    public function getMockIndexTaskQueryParameters()
    {
        $params = new IndexTaskQueryParameters();

        $params->granularityType = 'uniform';
        $params->granularity = 'DAY';
        $params->dataSource = $this->mockDataSource;
        $params->format = 'json';
        $params->timeDimension = 'date_dim';
        $params->dimensions = array('one_dim', 'two_dim');

        $params->setIntervals('1981-01-01T4:20', '2012-03-01T3:00');

        $params->setFilePath('/another/file/path/to/a/file.bebop');
        $params->setAggregators(array(
            array( 'type' => 'count', 'name' => 'count' ),
            array( 'type' => 'longSum', 'name' => 'total_referral_count', 'fieldName' => 'referral_count' )
        ));

        return $params;
    }


    public function testValidate()
    {
        $parametersInstance = $this->getMockIndexTaskQueryParameters();

        $parametersInstance->validate();

        $this->assertEquals($this->mockDataSource, $parametersInstance->dataSource);

        return $parametersInstance;
    }



    /**
     * @depends testValidate
     */
    public function testValidateWithMissingDataSource()
    {
        $parametersInstance = $this->getMockIndexTaskQueryParameters();

        $parametersInstance->dataSource = null;

        $this->setExpectedException('DruidFamiliar\Exception\MissingParametersException', 'Missing parameters: dataSource');

        $parametersInstance->validate();
    }

    /**
     * @depends testValidate
     */
    public function testValidateWithMissingIntervals()
    {
        $parametersInstance = $this->getMockIndexTaskQueryParameters();

        $parametersInstance->intervals = null;

        $this->setExpectedException('DruidFamiliar\Exception\MissingParametersException', 'Missing parameters: intervals');

        $parametersInstance->validate();
    }

    public function testSetFilePath()
    {
        $i = new IndexTaskQueryParameters();

        $i->setFilePath('some/path/to/a/file.json');

        $this->assertEquals( 'some/path/to/a', $i->baseDir);
        $this->assertEquals('file.json', $i->filePath);
    }

    public function testSetAggregators()
    {
        $i = new IndexTaskQueryParameters();

        $i->setAggregators(array(
            array( 'type' => 'count', 'name' => 'count' ),
            array( 'type' => 'longSum', 'name' => 'total_referral_count', 'fieldName' => 'referral_count' )
        ));

        $this->assertCount(2, $i->aggregators);
        $this->assertJson($i->aggregators[0]);
        $this->assertJson($i->aggregators[1]);
        $this->assertEquals('{"type":"count","name":"count"}', $i->aggregators[0]);
        $this->assertEquals('{"type":"longSum","name":"total_referral_count","fieldName":"referral_count"}', $i->aggregators[1]);
    }



}