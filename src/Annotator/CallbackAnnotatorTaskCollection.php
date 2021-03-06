<?php
namespace IdeHelper\Annotator;

use Cake\Core\Configure;
use IdeHelper\Annotator\CallbackAnnotatorTask\TableCallbackAnnotatorTask;
use IdeHelper\Console\Io;

class CallbackAnnotatorTaskCollection {

	/**
	 * @var array
	 */
	protected $defaultTasks = [
		TableCallbackAnnotatorTask::class => TableCallbackAnnotatorTask::class,
	];

	/**
	 * @var array
	 */
	protected $tasks;

	/**
	 * @param array $tasks
	 */
	public function __construct(array $tasks = []) {
		$defaultTasks = (array)Configure::read('IdeHelper.callbackAnnotatorTasks') + $this->defaultTasks;
		$tasks += $defaultTasks;

		foreach ($tasks as $task) {
			if (!$task) {
				continue;
			}

			$this->tasks = $tasks;
		}
	}

	/**
	 * @param \IdeHelper\Console\Io $io
	 * @param array $config
	 * @param string $path
	 * @param string $content
	 * @return \IdeHelper\Annotator\CallbackAnnotatorTask\CallbackAnnotatorTaskInterface[]
	 */
	public function tasks(Io $io, array $config, $path, $content) {
		$tasks = $this->tasks;

		$collection = [];
		foreach ($tasks as $task) {
			/** @var \IdeHelper\Annotator\CallbackAnnotatorTask\CallbackAnnotatorTaskInterface $object */
			$object = new $task($io, $config, $path, $content);
			$collection[] = $object;
		}

		return $collection;
	}

}
