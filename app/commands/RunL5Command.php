<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RunL5Command extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'l5command:run';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run a given L5-style command from the command line for testing.';

	/**
	 * Create a new command instance.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$commandName = "\LaravelWebSite\Commands\\" . $this->argument('commandName');
		$command = App::make($commandName);
		$command->handle();
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

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
