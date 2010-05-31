<?php

	/**
	 * CLI command class, main -> help
	 *
	 * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
	 * @version 2.0
	 * @license http://www.opensource.org/licenses/mozilla1.1.php Mozilla Public License 1.1 (MPL 1.1)
	 * @package thebuggenie
	 * @subpackage core
	 */

	/**
	 * CLI command class, main -> help
	 *
	 * @package thebuggenie
	 * @subpackage core
	 */
	class CliMainHelp extends TBGCliCommand
	{

		protected function _setup()
		{
			$this->_command_name = 'help';
		}

		public function getDescription()
		{
			return "Prints out help information";
		}

		public function do_execute()
		{
			$this->cliEcho("The Bug Genie CLI help\n", 'white', 'bold');

			if ($this->hasProvidedArgument(2))
			{
				$module_command = explode(':', $this->getProvidedArgument(2));
				$module_name = (count($module_command) == 2) ? $module_command[0] : 'main';
				$command = (count($module_command) == 2) ? $module_command[1] : $module_command[0];

				$commands = self::getAvailableCommands();

				if (array_key_exists($module_name, $commands) && array_key_exists($command, $commands[$module_name]))
				{
					$this->cliEcho("\n");
					$class = $commands[$module_name][$command];
					$this->cliEcho("Usage: ", 'white', 'bold');
					$this->cliEcho(TBGCliCommand::getCommandLineName() . " ");
					$this->cliEcho($class->getCommandName() . " ", 'green', 'bold');

					$hasArguments = false;
					foreach ($class->getRequiredArguments() as $argument => $description)
					{
						$this->cliEcho($argument . ' ', 'magenta', 'bold');
						$hasArguments = true;
					}
					foreach ($class->getOptionalArguments() as $argument => $description)
					{
						$this->cliEcho('[' . $argument . '] ', 'magenta');
						$hasArguments = true;
					}
					$this->cliEcho("\n");
					$this->cliEcho($class->getDescription(), 'white', 'bold');
					$this->cliEcho("\n\n");

					if ($hasArguments)
					{
						$this->cliEcho("Argument descriptions:\n", 'white', 'bold');
						foreach ($class->getRequiredArguments() as $argument => $description)
						{
							$this->cliEcho("  {$argument}", 'magenta', 'bold');
							if ($description != '')
							{
								$this->cliEcho(" - {$description}");
							}
							else
							{
								$this->cliEcho(" - No description provided");
							}
							$this->cliEcho("\n");
						}
						foreach ($class->getOptionalArguments() as $argument => $description)
						{
							$this->cliEcho("  [{$argument}]", 'magenta');
							if ($description != '')
							{
								$this->cliEcho(" - {$description}");
							}
							else
							{
								$this->cliEcho(" - No description provided");
							}
							$this->cliEcho("\n");
						}
						$this->cliEcho("\n");
						$this->cliEcho("Parameters must be passed either in the order described above\nor in the following format:\n");
						$this->cliEcho("--parameter_name=value", 'magenta');
						$this->cliEcho("\n\n");
					}
				}
				else
				{
					TBGCliCommand::cli_echo("\n");
					TBGCliCommand::cli_echo("Unknown command\n", 'red', 'bold');
					TBGCliCommand::cli_echo("Type " . TBGCliCommand::getCommandLineName() . ' ');
					TBGCliCommand::cli_echo('help', 'green', 'bold');
					TBGCliCommand::cli_echo(" for more information about the cli tool.\n\n");
				}
			}
			else
			{
				$this->cliEcho("Below is a list of available commands:\n\n");
				$commands = $this->getAvailableCommands();

				foreach ($commands as $module_name => $module_commands)
				{
					if ($module_name != 'main' && count($module_commands) > 0)
					{
						$this->cliEcho("{$module_name}:\n", 'green', 'bold');
					}
					foreach ($module_commands as $command_name => $command)
					{
						if ($module_name != 'main') $this->cliEcho("\t");
						$this->cliEcho("{$command_name}", 'green', 'bold');
						$this->cliEcho(" - {$command->getDescription()}\n");
					}
				}

				$this->cliEcho("\n");
			}
		}

	}