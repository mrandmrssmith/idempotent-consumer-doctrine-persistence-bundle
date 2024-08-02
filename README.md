# Idempotent Consumer Doctrine Persistence Bundle

This bundle provide persistence layer for idempotent consumer using doctrine

## Installation

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
