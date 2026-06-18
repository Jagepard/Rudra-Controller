[![PHPunit](https://github.com/Jagepard/Rudra-Controller/actions/workflows/php.yml/badge.svg)](https://github.com/Jagepard/Rudra-Controller/actions/workflows/php.yml)
[![Maintainability](https://qlty.sh/badges/4cd3ef6e-deea-4223-884b-6ec616594d59/maintainability.svg)](https://qlty.sh/gh/Jagepard/projects/Rudra-Controller)
[![CodeFactor](https://www.codefactor.io/repository/github/jagepard/rudra-controller/badge)](https://www.codefactor.io/repository/github/jagepard/rudra-controller)
[![Coverage Status](https://coveralls.io/repos/github/Jagepard/Rudra-Controller/badge.svg?branch=master)](https://coveralls.io/github/Jagepard/Rudra-Controller?branch=master)
-----

# Rudra-Controller | [API](https://github.com/Jagepard/Rudra-Controller/blob/master/docs.md "Documentation API")

## Lightweight, Transparent, and Secure MVC Implementation

A high-performance controller abstraction for the **Rudra Framework**. Designed with a "no-magic" philosophy, strict typing, and enterprise-grade security out-of-the-box.

#### 🧩 Lifecycle Hooks
Structured execution flow to maintain separation of concerns without layer pollution:
*   `init()` — Initialization of dependencies.
*   `before()` — Pre-action checks and routing.
*   `after()` — Post-action handling.

---

### 🚀 Installation

Install via Composer (assuming the package registry):

```bash
composer require rudra/controller
```
### 📖 Quick Start

```php
<?php declare(strict_types=1);

namespace App\Containers\Container\Controller;

class SomeController extends ContainerController
{
    public function index(): void
    {
        // Your logic here - CSRF already protected
        echo "Hello, Rudra!";
    }
}
```
## License

This project is licensed under the **Mozilla Public License 2.0 (MPL-2.0)** — a free, open-source license that:

- Requires preservation of copyright and license notices,
- Allows commercial and non-commercial use,
- Requires that any modifications to the original files remain open under MPL-2.0,
- Permits combining with proprietary code in larger works.

📄 Full license text: [LICENSE](./LICENSE)  
🌐 Official MPL-2.0 page: https://mozilla.org/MPL/2.0/