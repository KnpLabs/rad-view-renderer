Rad view renderer
=================

Allow to guess a view when the controller does not return a Response.

## Installation

Install the library:

```
$ composer require knplabs/rad-view-renderer
```

If you are using Symfony, add the bundle inside your `AppKernel.php`:

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

By default the rad view renderer will take a look of the **request content type**
in order to guess wich kind of render it will use. It exists many kind of renderer
that can display many content types.

You can disable some native renderers using the `renderers` parameter in your `config.yml`:
```yaml
knp_rad_view_renderer:
    renderers: [controller, twig]
```

### The twig renderer

One of the most useful renderer, it takes the controller class in order to
guess the template to display. For example:

```
App\Controller\TestController::someAction => @App/Resources/views/Test/some.%format%.twig
```
