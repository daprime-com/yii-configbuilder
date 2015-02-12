# yii-configbuilder

Config builder class proposes a special configuration files structure to make it easier to configure Your Yii2 application for different environments.

## Installation

## Usage
Prepare config folder files structure to look like this:
```
- base
--- common
--- web
--- console
- prod
--- common
--- web
--- console
... any other environments
```

The workflow of ConfigBuilder is following:
- takes common file from base directory
- takes (web|console) file from base directory (depends on web or cli execution)
- takes YII_ENV constant value to know what environment is used
- takes common file from (prod|dev|test) directory
- takes (web|console) file from env directory

## Initialization
To initialize ConfigBuilder change your application entry points like that:
```
// Before
$config = require(__DIR__ . '/../config/web.php');
(new yii\web\Application($config))->run();

// After
require($rootPath . '/config/ConfigBuilder.php');
Yii::setAlias('@root', $rootPath); //root alias is required for builder to work

$configBuilder = new \app\config\ConfigBuilder();
(new yii\web\Application($configBuilder->getWebConfig()))->run();
```