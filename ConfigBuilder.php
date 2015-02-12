<?php

namespace yii\configbuilder;

use Yii;
use \yii\base\Object;
use \yii\helpers\ArrayHelper;

/**
 * Class ConfigBuilder combines different configuration
 * files by scheme:
 *
 * base-common => base-{{web|console}} => {{env}}-common => {{env}}-{{web|console}}
 * @see app\config file structure
 * @author Dmitri Klimenko <dmitri@daprime.com>
 */
class ConfigBuilder extends Object
{
	/**
	 * Path alias to config folder
	 * @var string
	 */
	public $configPath = '@root/config';

	/**
	 * Environment flag (constant)
	 * @var string
	 */
	public $environment = YII_ENV;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$this->configPath = Yii::getAlias($this->configPath);
	}

	/**
	 * Returns combined configuration for
	 * Yii2 web application
	 * @return array
	 */
	public function getWebConfig()
	{
		return ArrayHelper::merge(
			$this->getBaseConfig(),
			$this->getEnvConfig()
		);
	}

	/**
	 * Returns combined configuration for
	 * Yii2 console application
	 * @return array
	 */
	public function getConsoleConfig()
	{
		return ArrayHelper::merge(
			$this->getBaseConfig(true),
			$this->getEnvConfig(true)
		);
	}

	/**
	 * Returns combined common and web|console
	 * configuration set from base directory
	 * @param boolean $isConsole
	 * @return array
	 */
	protected function getBaseConfig($isConsole = false)
	{
		$primaryConfig = 'base/common.php';
		$secondaryConfig = 'base/' . ($isConsole ? 'console' : 'web') . '.php';

		return ArrayHelper::merge(
			$this->loadConfigFile($primaryConfig),
			$this->loadConfigFile($secondaryConfig)
		);
	}

	/**
	 * Returns combined common and web|console
	 * configuration set from environment directory
	 * @param boolean $isConsole
	 * @return array
	 */
	protected function getEnvConfig($isConsole = false)
	{
		$primaryConfig = $this->environment . '/common.php';
		$secondaryConfig = $this->environment . '/' . ($isConsole ? 'console' : 'web') . '.php';

		return ArrayHelper::merge(
			$this->loadConfigFile($primaryConfig),
			$this->loadConfigFile($secondaryConfig)
		);
	}

	/**
	 * Reads configuration file content
	 *
	 * @param string $filePath
	 * @return mixed
	 */
	protected function loadConfigFile($filePath)
	{
		return require($this->configPath . '/' . ltrim($filePath, '/'));
	}
}