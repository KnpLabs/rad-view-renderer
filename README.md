Rad view renderer
=================

Allow to guess a view when the controller does not return a Response.

## Installation

Install the library:

```
$ composer require knplabs/rad-view-renderer
```

If you are using symfony2, add the bundle inside your `AppKernel.php`:

```php
$bundles = array(
    // ...
    new Knp\Rad\ViewRenderer\Bundle\ViewRendererBundle(),
);
```

## Usage

Let's take the following controller:

```php
namespace App\Controller;

class TestController
{
    public function someAction()
    {
        return ['foo' => 'bar'];
    }
}
```
