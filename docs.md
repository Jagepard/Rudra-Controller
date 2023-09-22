## Table of contents
- [Rudra\Controller\Controller](#rudra_controller_controller)
- [Rudra\Controller\ControllerInterface](#rudra_controller_controllerinterface)
- [Rudra\Controller\ControllerTrait](#rudra_controller_controllertrait)
<hr>

<a id="rudra_controller_controller"></a>

### Class: Rudra\Controller\Controller
##### implements [Rudra\Controller\ControllerInterface](#rudra_controller_controllerinterface)
| Visibility | Function |
|:-----------|:---------|
|public|<em><strong>__construct</strong>( Rudra\Container\Interfaces\RudraInterface $rudra )</em><br>Creates a common data container,<br>runs csrfProtection<br>Создает общий контейнер данных,<br>запускает csrfProtection|
|public|<em><strong>eventRegistration</strong>()</em><br>The method for events register<br>Метод для регистрации событий|
|public|<em><strong>generalPreCall</strong>()</em><br>General precall before initialization<br>Общий предварительный вызов до инициализации|
|public|<em><strong>init</strong>()</em><br>Initializes the necessary data<br>Инициализирует необходимые данные|
|public|<em><strong>before</strong>()</em><br>The method is executed before calling the controller<br>Метод выполняется перед вызовом контроллера|
|public|<em><strong>after</strong>()</em><br>The method is executed after calling the controller<br>Метод выполняется после вызова контроллера|
|public|<em><strong>csrfProtection</strong>(): void</em><br>Method to protect against CSRF attack<br>Метод защиты от CSRFатаки|
|public|<em><strong>fileUpload</strong>(  $key   $path )</em><br>|
|protected|<em><strong>__setRudraContainersTrait</strong>( Rudra\Container\Interfaces\RudraInterface $rudra )</em><br>Takes RudraInterface as an argument<br>Принимает в качестве аргумента RudraInterface|
|public|<em><strong>rudra</strong>(): Rudra\Container\Interfaces\RudraInterface</em><br>Gets access to the application<br>Получает доступ к приложению|


<a id="rudra_controller_controllerinterface"></a>

### Class: Rudra\Controller\ControllerInterface
| Visibility | Function |
|:-----------|:---------|
|abstract public|<em><strong>eventRegistration</strong>()</em><br>The method for events register<br>Метод для регистрации событий|
|abstract public|<em><strong>generalPreCall</strong>()</em><br>General precall before initialization<br>Общий предварительный вызов до инициализации|
|abstract public|<em><strong>init</strong>()</em><br>Initializes the necessary data<br>Инициализирует необходимые данные|
|abstract public|<em><strong>before</strong>()</em><br>The method is executed before calling the controller<br>Метод выполняется перед вызовом контроллера|
|abstract public|<em><strong>after</strong>()</em><br>The method is executed after calling the controller<br>Метод выполняется после вызова контроллера|
|abstract public|<em><strong>csrfProtection</strong>(): void</em><br>Method to protect against CSRF attack<br>Метод защиты от CSRFатаки|


<a id="rudra_controller_controllertrait"></a>

### Class: Rudra\Controller\ControllerTrait
| Visibility | Function |
|:-----------|:---------|
|public|<em><strong>fileUpload</strong>(  $key   $path )</em><br>|
<hr>

###### created with [Rudra-Documentation-Collector](#https://github.com/Jagepard/Rudra-Documentation-Collector)
