<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RunCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:run';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run a given L5-style command from the command line for testing.';

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle(\Illuminate\Contracts\Bus\Dispatcher $bus)
	{
		$commandName = "\\App\Commands\\" . $this->argument('commandName');
		$bus->dispatch(new $commandName);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('commandName', InputArgument::REQUIRED, 'Command name.'),
		);
	}
}