## Table of contents
- [Rudra\Controller\ContainerControllerInterface](#rudra_controller_containercontrollerinterface)
- [Rudra\Controller\Controller](#rudra_controller_controller)
- [Rudra\Controller\ControllerInterface](#rudra_controller_controllerinterface)
- [Rudra\Controller\ShipControllerInterface](#rudra_controller_shipcontrollerinterface)
<hr>

<a id="rudra_controller_containercontrollerinterface"></a>

### Class: Rudra\Controller\ContainerControllerInterface
| Visibility | Function |
|:-----------|:---------|
| abstract public | `containerInit(): void`<br> |


<a id="rudra_controller_controller"></a>

### Class: Rudra\Controller\Controller
| Visibility | Function |
|:-----------|:---------|
| public | `__construct()`<br> |
| public | `init(): void`<br> |
| public | `before(): void`<br> |
| public | `after(): void`<br> |
| public | `csrfProtection(): void`<br>Method to protect against CSRF attack |


<a id="rudra_controller_controllerinterface"></a>

### Class: Rudra\Controller\ControllerInterface
| Visibility | Function |
|:-----------|:---------|
| abstract public | `init(): void`<br> |
| abstract public | `before(): void`<br> |
| abstract public | `after(): void`<br> |


<a id="rudra_controller_shipcontrollerinterface"></a>

### Class: Rudra\Controller\ShipControllerInterface
| Visibility | Function |
|:-----------|:---------|
| abstract public | `shipInit(): void`<br> |
| abstract public | `eventRegistration(): void`<br> |
<hr>

###### created with [Rudra-Documentation-Collector](#https://github.com/Jagepard/Rudra-Documentation-Collector)
