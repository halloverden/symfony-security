# Symfony Security

Symfony Security compliments [Symfony Security](https://symfony.com/doc/current/components/security.html) with:

* Access Definitions
* Symfony Guard Authenticators
* Voters

To use this in a Symfony framework project, use the [Symfony Security Bundle](https://github.com/halloverden/symfony-security-bundle).

## Installation

Via composer:

```console
$ composer install @halloverden/symfony-security
```

## Usage
### Access Definition
A class that defines access to properties for a given class, and the class itself. Here's an example with a simple user class only defining first and last name, and a status property:

```php
namespace App\Entity;

class User {
  const ROLE_USER = 'ROLE_USER';
  const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
  const ROLE_USER_ADMIN = 'ROLE_USER_ADMIN';

  /**
   * @var string 
   */
  private $firstName;

  /**
   * @var string 
   */
  private $lastName;

  /**
   * @var int
   */
  private $status;
}
```

Given the class `User`, we've defined a class `UserAccessDefinition` that specifies which roles are needed and which other conditions need to be met to be able to handle the `User` class and its properties.

```php
namespace App\Security\AccessDefinitions;

use HalloVerden\Security\AccessDefinitions\BaseAccessDefinition;
use HalloVerden\Security\Traits\BaseAccessDefinitionTrait;

class UserAccessDefinition extends BaseAccessDefinition {
  use BaseAccessDefinitionTrait;
  
  /**
   * @var string 
   * 
   * @Read(roles={User::ROLE_USER_ADMIN, User::ROLE_USER})
   * @Write(roles={User::ROLE_USER_ADMIN, User::ROLE_USER})
   */
  private $firstName;

  /**
   * @var string
   * 
   * @Read(roles={User::ROLE_USER_ADMIN, User::ROLE_USER})
   * @Write(roles={User::ROLE_USER_ADMIN, User::ROLE_USER})
   * @WriteMethod(method="canHasLastName")
   */
  private $lastName;

  /**
   * @var int
   *
   * @Read(roles={User::ROLE_USER_ADMIN, User::ROLE_USER})
   * @Write(roles={User::ROLE_USER_ADMIN})
   */
  private $status;

  /**
   * @return bool
   */
  public function canHasLastName(): bool {
    return $this->firstName !== null;
  }
  
  public function canCreate(): bool {
    return $this->security->isGrantedEitherOf([User::ROLE_USER_ADMIN]);
  }
  
  public function canRead(): bool {
    return true;
  }
  
  public function canUpdate(): bool {
    return $this->security->isGrantedEitherOf([User::ROLE_USER_ADMIN, User::ROLE_USER]);
  }
  
  public function canDelete(): bool {
    return $this->security->isGrantedEitherOf([User::ROLE_USER_ADMIN]);
  }
}
```
The Access Definition for the `User` class states that to be able to read or write to any of the `firstName` or `lastName` properties, you need to have either the user admin role, or the user role. Both the user admin role, and the user role, are allowed to read the `status` property, but only the user admin role is allowed to write to it. Also, the definition specifies that to be able to write to the `lastName` property, the `firstName` property cannot be null.

The `BaseAccessDefinition` class comes with four utility methods:
- canCreate
- canRead
- canUpdate
- canDelete

These methods define access to the class itself. In this example, we're saying that anyone can read an _arbitrary_ instance of `User`, both the user role and the user admin role are allowed to update an _arbitrary_ instance of `User` , but to be able to create or delete one, you have to have the user admin role.

---
**NOTE**

The access definition does not care about any _given_ instance. It only defines what is allowed on a class and property level. To control access to _given_ instances, use the [Symfony Voter](https://symfony.com/doc/current/security/voters.html) concept.

---
You only need to implement the `AccessDefinitionInterface` to define a class as an access definition, but in this example we've made use of the `BaseAccessDefinition` class. This class gets an instance of `SecurityInterface` injected, and does some pretty nifty magic behind the scenes for you. [Check it out](https://github.com/halloverden/symfony-security/blob/master/src/AccessDefinitions/BaseAccessDefinition.php) if you want the deets.
 
### Authenticator
An instance of a [Symfony Guard Authenticator](https://symfony.com/doc/current/security/guard_authentication.html).

We've included an `@Authenticator` annotation that let's you specify which authenticator class(es) you want to use for a given controller method. Only these authenticators will be considered for the given controller method.

```php
namespace App\Controller;

class UsersV1Controller {
  /**
   * @Route("/v1/users/{userId}", methods={"GET"}, name="v1_users_detail_get")
   * @Authenticator(authenticators={"App\Security\LoggedInAuthenticator"})
   *
   * @return JsonResponse
   */
  public function getUser(): JsonResponse {
    // ...
  }
}
```

We've added a base class `AbstractAuthenticator`, that you can extend when you create your own guard authenticators. This base class makes use of a service called `AuthenticatorDeciderService` to check for valid authenticators for a given controller method. All you need is love, and the `AbstractAuthenticator`, but if you feel adventurous, [check out the decider service](https://github.com/halloverden/symfony-security/blob/master/src/Services/AuthenticatorDeciderService.php).

```php
namespace App\Security;

class LoggedInAuthenticator extends AbstractAuthenticator {
  // ...
}
```

As per the Authenticator concept, if you make an Authenticator available, it will be considered every time a route................................ 

```php
namespace App\Security;

class AnonymousAuthenticator extends AbstractAuthenticator {
  protected function specificAuthenticatorRequired(): bool {
    return true;
  }
}
```

### Voter
```php

```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
