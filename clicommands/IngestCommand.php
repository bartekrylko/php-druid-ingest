<?php


namespace ReferralIngester\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputDefinition;

/**
 * IngestCommand runs ReferralBatchIngester
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class IngestCommand extends Command
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('ingest')
            ->setDescription('Run hardcoded ingestion task for the given time window')
            ->setDefinition($this->createDefinition())
            ->setHelp(<<<HELPBLURB
Examples:
Dates:
\t<info>php ingest.php ingest 2008-01-01 2009-01-01</info>
Dates with Time:
\t<info>php ingest.php ingest 2008-01-01T01:30:00 2009-01-01T04:20:00 -v</info>

HELPBLURB
            );
        ;
    }


    protected function ingest($formattedStartTime, $formattedEndTime, InputInterface $input, OutputInterface $output)
    {
        $output->writeln("This is a base class. Extend me and do some work here!");
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputStart = ( $input->getArgument('start') );
        $inputEnd = ( $input->getArgument('end') );

        $startTime = new \DateTime( $inputStart );
        $endTime = new \DateTime( $inputEnd );

        $formattedStartTime = $startTime->format(DATE_ISO8601);
        $formattedEndTime = $endTime->format(DATE_ISO8601);

        $output->write("<info>Ingesting referrals</info>");
        if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
            $output->write( " for the period <info>$formattedStartTime</info> to <info>$formattedEndTime</info>" );
        }
        $output->write( "\n" );

        $this->ingest($formattedStartTime, $formattedEndTime, $input, $output);

    }


    /**
     * {@inheritdoc}
     */
    public function getNativeDefinition()
    {
        return $this->createDefinition();
    }


    /**
     * {@inheritdoc}
     */
    protected function createDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('start',  InputArgument::REQUIRED,    'Start Time of Ingestion Window as ISO String'),
            new InputArgument('end',    InputArgument::REQUIRED,    'End Time of Ingestion Window as ISO String'),
        ));
    }
}