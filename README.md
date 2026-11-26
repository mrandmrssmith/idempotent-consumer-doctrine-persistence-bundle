# Idempotent Consumer Doctrine Persistence Bundle

This bundle provide persistence layer for idempotent consumer using doctrine

## Installation

## Requirements

| PHP          | Symfony                      |
|--------------|------------------------------|
| 7.4, 8.0–8.3 | 3.4, 4.4, 5.4, 6.4, 7.4      |

Tested in CI with: PHP 7.4 + Symfony 3.4 (lowest), PHP 7.4 + Symfony 5.4,
PHP 8.1 + Symfony 6.4 and PHP 8.3 + Symfony 7.4.

Add this package to your project
```shell
composer require mrandmrssmith/idempotent-consumer-doctrine-persistence-bundle
```

## Usage
1. Configure doctrine
    - in your `doctrine.yaml` add mapping for this bundle
```yaml
          mms_idempotent_consumer_doctrine_persistence_bundle:
              is_bundle: false
              type: annotation
              dir: '%kernel.project_dir%/vendor/mrandmrssmith/idempotent-consumer-doctrine-persistence-bundle/src/Entity'
              prefix: 'MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Entity'
              alias: IdempotentConsumerDoctrinePersistenceBundle

```
2. Configure your migrations in `doctrine_migrations.yaml` to `migration_paths` add:
```yaml
        'MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Persistence\Migrations': '%kernel.project_dir%/vendor/mrandmrssmith/idempotent-consumer-doctrine-persistence-bundle/src/Persistence/Migrations'
```
3. Run migrations as usual
```shell
  bin/console doctrine:migrations:migrate
```
