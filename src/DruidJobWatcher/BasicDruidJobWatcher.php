<?php

namespace PhpDruidIngest\DruidJobWatcher;

use PhpDruidIngest\Exception\AlreadyWatchingJobException;
use PhpDruidIngest\Interfaces\IDruidJobWatcher;

class BasicDruidJobWatcher implements IDruidJobWatcher
{
    /**
     * Druid Indexing Task Job Id
     *
     * @var string
     */
    protected $watchingJobId = null;


    /**
     * Begin watching given job id.
     *
     * Calls either onJobCompleted or onJobFailed unless stopWatchingJob is called first.
     * If given job is already completed or failed, the appropriate callback will be called.
     *
     * @param string $jobId
     * @return mixed
     */
    public function watchJob($jobId)
    {
        if ( $this->watchingJobId && ( $this->watchingJobId !== $jobId ) ) {
            throw new AlreadyWatchingJobException($this->watchingJobId, $jobId);
        }

        $this->watchingJobId = $jobId;

        // TODO: Implement watchJob() method.

        // TODO This should use a DruidQueryExecutor and needs to run task status queries

        // TODO Submit request
        // TODO Monitor request
        // TODO Poll until status changes to FAILURE or SUCCESS
        // TODO Report results

    }

    /**
     * If a job watching can be cancelled, call this to cancel.
     *
     * @return mixed
     */
    protected function stopWatchingJob()
    {
        if ( $this->watchingJobId ) {
            // TODO: Implement stopWatchingJob() method.
            return true;
        }

        return false;
    }

    /**
     * Called when a job's status moves to Completed state.
     *
     * @return mixed
     */
    protected function onJobCompleted()
    {
        // TODO: Implement onJobCompleted() method.
    }

    /**
     * Called when a job's status moves to a Failed state.
     *
     * @return mixed
     */
    protected function onJobFailed()
    {
        // TODO: Implement onJobFailed() method.
    }

    /**
     * Called when a job's status is still in a Running/Pending state after watching period ends.
     *
     * @return mixed
     */
    protected function onJobPending()
    {
        // TODO: Implement onJobFailed() method.
    }
}