<?php

namespace PhpDruidIngest\Fetcher;

use mysqli;
use PhpDruidIngest\Interfaces\IFetcher;

date_default_timezone_set('America/Denver');

class ReferralBatchFetcher implements IFetcher
{

    protected $timeWindowStart;
    protected $timeWindowEnd;

    protected $host = '';
    protected $user = '';
    protected $pass = '';
    protected $db = '';

    /**
     * Set the MySQL DB credentials for fetching.
     *
     * @param $host
     * @param $user
     * @param $pass
     * @param $db
     */
    public function setMySqlCredentials($host, $user, $pass, $db) {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;
    }

    /**
     * Set the time window for fetching.
     *
     * @param string $start ISO DateTime for start of ingestion window
     * @param string $end ISO DateTime for end of ingestion window
     * @return string
     */
    public function setTimeWindow($start, $end) {
        $this->timeWindowStart = $start;
        $this->timeWindowEnd = $end;
    }

    protected $contactsQuery = <<<QUERY

QUERY;


    protected $physicianQuery = <<<QUERY

QUERY;


    /**
     * Ingest data into druid.
     *
     * @return string
     */
    public function ingest()
    {
        $dataBatch = $this->fetch();

        $exampleData = print_r( $dataBatch[ 0 ], true );

        return "Fetched " . count($dataBatch) . " referrals.\nOne referral looks like: " . $exampleData . "\n";
    }


    public function fetch()
    {

        $mysqli = new mysqli($this->host, $this->user, $this->pass, $this->db);

        // Check connection
        if ($mysqli->connect_errno) {
            throw new \Exception( sprintf("Connect failed: %s\n", $mysqli->connect_error) );
        }

        echo "Connected.\n";

        $preparedQuery = $this->prepareQuery( $this->physicianQuery, $this->timeWindowStart, $this->timeWindowEnd );

//        echo $start . "\n";
//        echo $end . "\n";
//        echo $preparedQuery;
        $rows = array();

        // Select queries return a resultset
        if ($result = $mysqli->query( $preparedQuery, MYSQLI_USE_RESULT )) {


            while ($row = $result->fetch_array())
            {
                $rows[] = $row;
            }

            /* free result set */
            $result->close();

        }

        $mysqli->close();

        return $rows;

    }


    /**
     * Bind the start and end ingestion date windows to a query.
     *
     * @param String $query Query with {STARTDATE} and {ENDDATE} for value substituion
     * @param String $start ISO Date Time string
     * @param String $end ISO Date Time string
     * @return String Prepared query string
     */
    public function prepareQuery($query, $start, $end)
    {

        $startTime = new \DateTime( $start );
        $endTime = new \DateTime( $end );

        $formattedStartTime = $startTime->format(DATE_ISO8601);
        $formattedEndTime = $endTime->format(DATE_ISO8601);

        $preparedQuery = $query;
        $preparedQuery = str_replace( '{STARTDATE}', $formattedStartTime, $preparedQuery );
        $preparedQuery = str_replace( '{ENDDATE}', $formattedEndTime, $preparedQuery );

        return $preparedQuery;

    }

}