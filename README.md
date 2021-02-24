# FreeRouter
Painless routing for your PHP 8+ project with REST support.

#### Installation (Still WIP)
```bash
composer require miskynscze/freerouter
```

#### Example (Basic)
```php
#[Controller]
class ClassController implements IRouter {

    #[Request("/")]
    #[Method(RequestMethod::GET)]
    public function home(): string {
        return "Hello, world!";
    }
}

//Getting RouterConfig
$config = new RouterConfig();
$router = new RouterWrapper();

//Running RouterWrapper
$router->config($config)->run(new ClassController());
```

It will return
```html
Hello, world!
```

#### Example (Basic + parameters)
```php
#[Controller]
class ClassController implements IRouter {

    #[Request("/page/{id}")]
    #[Method(RequestMethod::GET)]
    public function page(string $id): string {
        return "You are on page ($id)";
    }
}
```

It will also return a string, but with parameters! For example for URL /page/10
```html
You are on page 10
```

#### Example (REST)
```php
#[RestController]
class ClassController implements IRouter {

    #[Request("/user/{id}")]
    #[Method(RequestMethod::GET)]
    public function user(string $id): string {
        return [
            "id" => $id,
            "name" => "Test"
        ];
    }
}
```

It will return (for example /user/1)
```json
{"id": 1, "name": "Test"}
```

##### Different request methods
GET, POST, PUT, DELETE
