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
|abstract public|<em><strong>containerInit</strong>(): void</em><br>|


<a id="rudra_controller_controller"></a>

### Class: Rudra\Controller\Controller
##### implements [Rudra\Controller\ControllerInterface](#rudra_controller_controllerinterface)
| Visibility | Function |
|:-----------|:---------|
|public|<em><strong>__construct</strong>()</em><br>|
|public|<em><strong>init</strong>(): void</em><br>|
|public|<em><strong>before</strong>(): void</em><br>|
|public|<em><strong>after</strong>(): void</em><br>|
|public|<em><strong>csrfProtection</strong>(): void</em><br>Method to protect against CSRF attack|


<a id="rudra_controller_controllerinterface"></a>

### Class: Rudra\Controller\ControllerInterface
| Visibility | Function |
|:-----------|:---------|
|abstract public|<em><strong>init</strong>(): void</em><br>|
|abstract public|<em><strong>before</strong>(): void</em><br>|
|abstract public|<em><strong>after</strong>(): void</em><br>|


<a id="rudra_controller_shipcontrollerinterface"></a>

### Class: Rudra\Controller\ShipControllerInterface
| Visibility | Function |
|:-----------|:---------|
|abstract public|<em><strong>shipInit</strong>(): void</em><br>|
|abstract public|<em><strong>eventRegistration</strong>(): void</em><br>|
<hr>

###### created with [Rudra-Documentation-Collector](#https://github.com/Jagepard/Rudra-Documentation-Collector)
