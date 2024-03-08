# Symfony-ES - Config bundle

Bundle to configure your connections, indexes and entities for ES.

Configuration is made with symfony tags, so you only need to create a class, and you don't need to do anything in configuration(yaml) files.

You can put classes anywhere you want, because symfony will load your configuration classes anywhere you put it, 
but convention is to create Es (src/Es) folder inside you Symfony project  and put files there.

## Configuring providers for connection

In most cases you will use only one connection to ES, but this package provide option to have multiple connections to more ES instances

```
<?php

namespace Fico7489\DataSyncBundle\Tests\Util\Es\Connections;

use FHPlatform\PersistenceBundle\TagProvider\Connection\ConnectionProvider;

class ProviderDefault extends ConnectionProvider
{
    public function getName(): string
    {
        return 'default';
    }
    
    public function getIndexPrefix(): string
    {
        return 'prefix_';
    }

    public function getElasticaConfig(): array
    {
        return [
            'servers' => [
                ['host' => 'localhost', 'port' => '9201'],
            ],
        ];
    }
}
```

- "name" - name of the connection
- "indexPrefix" - prefix for indexes on that connection
- "elasticaConfig" - is configuration which is used for creation of elastica client, see more here: https://elastica.io/getting-started/installation.html

```
$elasticaClient = new \Elastica\Client(array(
    'host' => 'mydomain.org',
    'port' => 12345
));
```

Adding more connections:

```
<?php

namespace Fico7489\DataSyncBundle\Tests\Util\Es\Connections;

use FHPlatform\PersistenceBundle\TagProvider\Connection\ConnectionProvider;

class ProviderSecond extends ConnectionProvider
{
    public function getName(): string
    {
        return 'second';
    }
    
    public function getIndexPrefix(): string
    {
        return 'prefix_';
    }

    public function getElasticaConfig(): array
    {
        return [
            'servers' => [
                ['host' => 'localhost', 'port' => '9202'],
            ],
        ];
    }
}
```

After you configure connections you can check if connection is loaded by package with:

```
php bin/console fh-platform:config:debug:connections
```

## Configuring providers for indexes and entities

All providers are class based, so each provider must have related doctrine entity class or custom class.

There are 3 types of providers: "ProviderIndex", "ProviderEntity" and "ProviderEntityRelated".

They all extend class "ProviderBasic"

You will probably use "ProviderEntity" in most cases and the other two less often.

"ProviderEntity" is in same time type of "ProviderIndex" and "ProviderEntityRelated".

#### ProviderIndex

It is used for indexes which do not relay on doctrine entities. 

They are used for custom classes and custom indexes, if you want to log something custom to log like events.

#### ProviderEntity

It is used for indexes which relay on doctrine entities.

#### ProviderEntityRelated

It is also rely on doctrine entities but doctrine entities which do not have own index.

For example if you have entities User and Role, but only want index for users and inside user ES document is for example role name,
then with ProviderEntityRelated you can tell package to update all user ES documents when role is updated and that user have relation to that role.

### ProviderBase

Here are methods from a ProviderBase which are used in all 3 providers:

```
abstract public function getClassName(): string;

public function getConnection(): string
{
    return 'default';
}

public function priority(): int
{
    return 0;
}

public function getAdditionalConfig(): array
{
    return [];
}
```

Options:

- "ClassName" -> For all there providers you must set ClassName, 
for "ProviderEntity" and "ProviderEntityRelated" it will be entity class name, for example "App\Entity\User.php"
for "ProviderIndex" it is some custom class like "App\Log.php"

- "Connection" -> you can define on which connection name for this providers

- "Priority" -> that option is not for using in providers, you will see later why it is here

- "AdditionalConfig" -> not used in this package it is designed for external packages to be able to configure something

### ProviderIndex

For "ProviderIndex" you can define configuration from ProviderBase and additionally configuration from ProviderIndex:

```
public function getIndexName(string $className, string $name): string
{
    return $name;
}

public function getIndexMapping(string $className, array $mapping): array
{
    return $mapping;
}

public function getIndexSettings(string $className, array $settings): array
{
    return $settings;
}
```

- IndexName -> by default is TODO, but you can define a custom
- Index mapping -> define index mapping for ES index
- Index settings -> define index settings for ES index
- 
Example:

```
<?php

namespace Fico7489\DataSyncBundle\Tests\Util\Es\Config\Provider;

use FHPlatform\PersistenceBundle\TagProvider\Index\ProviderIndex;
use Fico7489\DataSyncBundle\Tests\Util\Es\Config\Log;

class LogProviderIndex extends ProviderIndex
{
    public function getClassName(): string
    {
        return Log::class;
    }
}
```

```
<?php

namespace Fico7489\DataSyncBundle\Tests\Util\Es\Config;

class Log
{
}
```

### ProviderEntity

For "ProviderEntity" you can define configuration from ProviderIndex and additionally configuration from ProviderEntity:

```
public function getEntityData($entity, array $data): array
{
    return $data;
}

public function getEntityShouldBeIndexed($entity, bool $shouldBeIndexed): bool
{
    return $shouldBeIndexed;
}

public function getEntityRelatedEntities($entity, $entitiesRelated): array
{
    return $entitiesRelated;
}
```

- EntityData -> here you define an array which will be converted to JSON and store as ES document.

- EntityShouldBeIndexed -> here you define if that entity should be stored into the ES, it is used for example when you don't want to put soft deleted entities to ES.

- EntityRelatedEntities -> here you define which entities should also be synced in ES when this entity is updates

TODO example with User and Role


### Decorator

We have 2 types of decorators:

- EntityDecorator
- IndexDecorator

TODO

### Index name

TODO







