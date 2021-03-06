<?php
declare(strict_types=1);

namespace Tests\Integration\Simulation;

use Application\Application;
use Application\InputParser;
use Application\InputValidator;
use Application\OutputParser;
use Tests\Integration\IntegrationTestAbstract;

class SimulationIntegrationTest extends IntegrationTestAbstract
{
    /**
     * @test
     */
    public function run_simulation_for_ten_iterations_should_output_ten_simulation_boards()
    {
        $inputParser    = new InputParser();
        $outputParser   = new OutputParser();
        $inputValidator = new InputValidator();
        $iterations     = 10;

        $application = new Application(
            $this->doctrineSimulationStatusRepository,
            $this->doctrineSimulationStatusStore,
            $inputParser,
            $outputParser,
            $inputValidator,
            $iterations
        );

        $height = '5';
        $width  = '5';

        $application->init($height, $width);

        $output = '';
        for ($n = 0; $n<$iterations; $n++) {
            $application->iterate();
            $output .= $application->getBoardStatus();
        }

        $this->assertCount(51, explode("\n", $output));
    }

    /**
     * @test
     *
     * @expectedException Domain\Exception\InvalidSizeException
     */
    public function run_simulation_should_output_a_message_error_for_invalid_board_dimensions()
    {
        $inputParser    = new InputParser();
        $outputParser   = new OutputParser();
        $inputValidator = new InputValidator();
        $iterations     = 10;

        $application = new Application(
            $this->doctrineSimulationStatusRepository,
            $this->doctrineSimulationStatusStore,
            $inputParser,
            $outputParser,
            $inputValidator,
            $iterations
        );

        $height = '0';
        $width  = '0';

        $application->init($height, $width);
    }

    /**
     * @test
     */
    public function run_simulation_should_save_simulation_initial_board_into_database()
    {
        $initialSimulationStatusCount = count($this->doctrineSimulationStatusRepository->findAll());

        $inputParser    = new InputParser();
        $outputParser   = new OutputParser();
        $inputValidator = new InputValidator();
        $iterations     = 1;

        $application = new Application(
            $this->doctrineSimulationStatusRepository,
            $this->doctrineSimulationStatusStore,
            $inputParser,
            $outputParser,
            $inputValidator,
            $iterations
        );

        $height = '5';
        $width  = '5';

        $application->init($height, $width);

        sleep(1);

        $finalSimulationStatusCount = count($this->doctrineSimulationStatusRepository->findAll());

        $this->assertEquals($initialSimulationStatusCount + 1, $finalSimulationStatusCount);
    }

    /**
     * @test
     */
    public function run_simulation_loading_initial_status_from_database_should_output_an_arbitrary_final_simulation_board()
    {
        $initialSimulationStatusCollection = $this->doctrineSimulationStatusRepository->findAll();
        $initialSimulationStatusId         = $initialSimulationStatusCollection[0]->getId();

        $inputParser    = new InputParser();
        $outputParser   = new OutputParser();
        $inputValidator = new InputValidator();
        $iterations     = 1;

        $application = new Application(
            $this->doctrineSimulationStatusRepository,
            $this->doctrineSimulationStatusStore,
            $inputParser,
            $outputParser,
            $inputValidator,
            $iterations,
            $initialSimulationStatusId
        );

        $height = '5';
        $width  = '5';

        $application->init($height, $width);

        $application->iterate();

        $boardStatus = $application->getBoardStatus();

        $this->assertEquals(".       .\n         \n         \n         \n.       .\n", $boardStatus);
    }
}
