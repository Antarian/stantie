---
title: >-
  Domain Driven Design in PHP
preview: null
slug: 'ddd-php-intro'
categorySlug: 'architecture'
seriesSlug: 'domain-driven-design'
seriesPart: 1
archived: false
author: 'Peter Labos'
published: '12th Dec 2025'
---
# Domain Driven Design in PHP
## Intro
When I was first looking into <abbr title="Domain Driven Design">DDD</abbr> few years ago, there was plenty of information floating around. Almost nothing was for PHP, articles were about Java mostly. I was trying to wrap my thoughts around it, to know where and how all of that theory should help. How the practical application looks like? Naming things like business does, having all the core logic in domain. I would say that many articles were trying to be smarter than they needed to be. They focused on communication between technical and non-technical people, from which many senior devs are shielded by tech leads, product owners or customer support, which may be more skilled in technical aspects than usual customers.

Yes, going through all the specs and processes with business is big part of the DDD process, naming things clearly and the same way business is naming them also. But for many programmers, it is hard to switch to that without knowing what expected code structure should be or can be.

In this article I will try to approach DDD from the software developer point of view and with some very simple examples of code using <abbr title="Model Driven Design">MDD</abbr> along the way. I will avoid <abbr title="Command and Query Responsibility Segregation">CQRS</abbr> or <abbr title="Event Sourcing">ES</abbr> in this basic approach from the start. Even those two patterns are usual addition to MDD. There will be no framework used and data layer will be just some in-memory structure.

It would be good to know PHP, Composer (PHP package/library manager) also basics of <abbr title="Object Oriented Programming">OOP</abbr> and at least <abbr title="Model-View-Controller">MVC</abbr> design pattern for you to continue comfortably with reading this article. Knowing general and OOP principles like YAGNI, KISS, DRY and SOLID are not required, but I will be mentioning them now and then.

## Model Driven Design (Model Driven Architecture)
MDD is often used as basic code design part of DDD. If you'll follow along, we will implement architecture visible on this diagram:

![Model Driven Design diagram](/public/img/ddd-diagram.svg)

<div style="width:100%;text-align:right;"><sub>Credit to: <a target="_blank" href="https://khalilstemmler.com/articles/domain-driven-design-intro/">https://khalilstemmler.com/articles/domain-driven-design-intro/</a></sub></div>

We will use simple example of Shopping List app to get most basic and understandable implementation of this diagram. We will slowly increment Shopping List app to tackle more complex things later in this tutorial.

### When MDD may not be the best fit
To save you time reading, there are situations where MDD may not be the best fit for your needs. At least not in PHP.
1. When you batch process data, e.g. if you load, change, filter, save or convert data in large amounts. You should look for framework commands run from command line and cron-jobs. 
2. When you mostly read data and/or structure for reading data is changing way too often. Or if speed is preferred on reading, you may avoid using MDD queries.
3. When you are not solving complex business process, but need technical application layer solution. Like caching, user authentication, etc.

I said "may not be", because as with any architecture, you need to decide about cons and pros of your solution(s). As for example you may need to tackle complex user registration process in financial institution with DDD or just use (in)build framework/library solution.

## Layered Architecture
![MDD isolate domain with Layered Architecture"](/public/img/layered-architecture.svg)
In MDD, domain is isolated by Commands. These commands can be called by app controllers or commands (CLI). To make any changes in domain, you need to use predefined Commands. These commands will be processed by CommandHandlers. Practical example of the command:

`domain/Command/AddShoppingList.php`
```php
<?php
namespace Domain\Command;

final readonly class AddShoppingList
{
    public function __construct(
        public string $title,
    ) {}
}
```
As you can see, command is a very simple class providing basic data we need to create Shopping List. Commands should be readonly DTOs with public properties. It may help you, if you think of them as API endpoints, or just to be able to populate them from json object.

These command will be processed by command handlers. In our case

`domain/CommandHandler/AddShoppingListHandler.php`
```php
<?php
namespace Domain\CommandHandler;

use Domain\Command\AddShoppingList;

final readonly class AddShoppingListHandler
{
    public function __invoke(AddShoppingList $command): void
    {
        // create entity (model)

        // store entity
    }
}
```
Invoke method should use command to create entity and to store the entity.

Command and CommandHandler are the place to implement "application business rules". They may be also referred to as "user actions" or even "user paths".

We currently don't have an entity, and we don't have a way to store the entity also.
This is our next logical step.

## Entities
![MDD express model with Entities encapsulate with Factories](/public/img/entities.svg)
Entities (Models) are main actor of Model Driven design. They contain core domain logic and store the current state of the domain. Entity also need to have a unique identifier. To satisfy condition that every Entity has unique id, we will use simple nullable integer `id` for now.

Let's create our first Entity

`domain/Entity/ShoppingList.php`
```php
<?php
namespace Domain\Entity;

class ShoppingList
{
    public function __construct(
        private ?int $id,
        private string $title,
    ) {}
}
```
Yes, that's it. At least for now. It is a very simple entity. Entities are the place where we implement "enterprise business rules". This rules may be as simple as storing "created" date, to more complex like awaiting some approvals and sending notifications.

Now back to our command handler

`domain/CommandHandler/AddShoppingListHandler.php`
```php
    public function __invoke(AddShoppingList $command): void
    {
        // create entity (model)
        $entity = new ShoppingList(null, $command->title);
    }
```
Ehh... if you know OOP principles, this does not look right. We should avoid constructing new class inside of class. Should we pass model here as a dependency in constructor? As every entity (model) has specific ID which is known only during the code runtime, that is also not a way to go.

You may use `new` keyword for DTOs and data model objects. But following Single responsibility principle, there should be only one place where we will be doing this. We need Entity Factory. Factory will help us with Entity encapsulation, as only point of creating (or populating) entity.

`domain/Factory/ShoppingListFactory.php`
```php
<?php
namespace Domain\Factory;

use Domain\Entity\ShoppingList;

class ShoppingListFactory 
{
    public function createShoppingList(string $title): ShoppingList
    {
        return new ShoppingList(null, $title);
    }
}
```
Now we update our handler to use it

`domain/CommandHandler/AddShoppingListHandler.php`
```php
<?php
namespace Domain\CommandHandler;

use Domain\Command\AddShoppingList;
use Domain\Factory\ShoppingListFactory;

final readonly class AddShoppingListHandler
{
    public function __construct(
        private ShoppingListFactory $factory,
    ) {}

    public function __invoke(AddShoppingList $command): void
    {
        // create entity
        $entity = $this->factory->createShoppingList($command->title);

        // store entity
    }
}
```
In the next part we will handle how to store an Entity

## Repositories
![Entities access with Repositories](/public/img/repositories.svg)
Our ShoppingList Entity is now created and populated with data. We want to store those data. MDD does not really care how or where we store the entity. It just expects that state of the domain entity will be properly stored and possible to retrieve. As this is Data layer of our architecture and MDD is not really caring about it, we need some contract between these two layers. Internal code contracts are defined by interfaces. Let's create one

`domain/Repository/ShoppingListRepository.php`
```php
<?php
namespace Domain\Repository;

use Domain\Entity\ShoppingList;

interface ShoppingListRepository
{
    public function store(ShoppingList $entity): void;
}
```
Any class handling our ShoppingList data will need to implement this interface. We will inject that class into our command handler. But as we do not know which class it will be, we will nicely follow Dependency inversion principle here and inject this interface in our handler. And also update `__invoke()` method.

`domain/CommandHandler/AddShoppingListHandler.php`
```php
<?php
namespace Domain\CommandHandler;

use Domain\Command\AddShoppingList;
use Domain\Factory\ShoppingListFactory;
use Domain\Repository\ShoppingListRepository;

final readonly class AddShoppingListHandler
{
    public function __construct(
        private ShoppingListFactory $factory,
        private ShoppingListRepository $repository,
    ) {}

    public function __invoke(AddShoppingList $command): void
    {
        // create entity (model)
        $entity = $this->factory->createShoppingList($command->title);

        // store entity
        $this->repository->store($entity);
    }
}
```
And we are done, at least with creating Shopping List from MDD point of view. You may be like "What? Wait. Our code may not be even working. And there is more in that diagram we haven't covered."

To check if code is working, we will add Composer to the project with command
```shell
composer init
```

After installing composer, alter PSR-4 namespaces in
`composer.json`

```json
    "autoload": {
        "psr-4": {
            "Antarian\\Ddd\\": "src/",
            "Domain\\": "domain/"
        }
    },
```
Add `src/Repository/ShoppingListMemoryRepository.php`
```php
<?php
declare(strict_types=1);
namespace Antarian\Ddd\Repository;

use Domain\Entity\ShoppingList;
use Domain\Repository\ShoppingListRepository;

class ShoppingListMemoryRepository implements ShoppingListRepository
{
    public array $shoppingLists = [];

    public function store(ShoppingList $entity): void
    {
        $this->shoppingLists[] = serialize($entity);
    }
}
```
and
`public/index.php`
```php
<?php
use Antarian\Ddd\Repository\ShoppingListMemoryRepository;
use Domain\Command\AddShoppingList;
use Domain\CommandHandler\AddShoppingListHandler;
use Domain\Factory\ShoppingListFactory;

require __DIR__ . '/../vendor/autoload.php';

$command = new AddShoppingList('My Shopping List');
$factory = new ShoppingListFactory();

$repository = new ShoppingListMemoryRepository();

$commandHandler = new AddShoppingListHandler(
    $factory,
    $repository,
);

$commandHandler($command);

var_dump($repository->shoppingLists);
```

You should be able to test that article was added by running index.php
```shell
php public/index.php
```

Code of this part is in Github [repository](https://github.com/Antarian/ddd-tutorial/tree/ddd-intro), branch `ddd-intro`

We will go through more of first diagram in future articles.
