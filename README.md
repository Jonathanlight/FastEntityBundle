# FastEntityBundle

[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)](https://travis-ci.org/joemccann/dillinger)

FastEntityBundle Composant Bundle to Generate entity and relation with a config yaml

### Installation
```
    $ composer require jonathankablan/fast-entity-bundle
```

### Command
```
    $ bin/console magic:entity
```

### Config Yaml
Create file of config : fast_entity.yaml

Symfony_project/config/packages/fast_entity.yaml

```
fast_entity:
    tables:
        - { name: 'admin' }
        - { name: 'formation' }
    schema:
        # User
        - { entity: 'admin', property: 'username', type: 'string', length: 255, nullable: true }
        - { entity: 'admin', property: 'password', type: 'string', length: 255, nullable: true }
        - { entity: 'admin', property: 'email', type: 'string', length: 255, nullable: true }
        - { entity: 'admin', property: 'active', type: 'boolean', nullable: true }
        - { entity: 'admin', property: 'created', type: 'datetime', nullable: true }
        - { entity: 'admin', property: 'updated', type: 'datetime', nullable: true }
        # Conference
        - { entity: 'formation', property: 'location', type: 'string', length: 255, nullable: true }
        - { entity: 'formation', property: 'price', type: 'integer', length: 11, nullable: true }
        - { entity: 'formation', property: 'created', type: 'datetime', nullable: true }
        - { entity: 'formation', property: 'updated', type: 'datetime', nullable: true }
    relations:
        - { entityTo: 'admin', entityFrom: 'formation', relation: 'OneToOne' }
```
