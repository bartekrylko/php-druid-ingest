<?php

namespace PhpDruidIngest\Interfaces;

interface IFetcher {

    /**
     * Fetch the data to be ingested.
     *
     * @return mixed
     */
    public function fetch();

}
