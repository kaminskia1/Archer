# Archer
[![Updated Badge](https://img.shields.io/github/last-commit/kaminskia1/archer)](https://github.com/kaminskia1/archer/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

## Abstract
AiO web application built on Symfony5 for managing everything to do with software licensing. This project includes an in-house eCommerce system featuring a storefront, checkout flow, and third-party processing. Software licensing management (SLM) and redemption with heartbeat support is also included, with planned support for an IRC system and support ticket system. Further documentation can be found on the [Wiki](https://github.com/kaminskia1/archer/wiki)

## Installation
  - Clone the repository
  - Ensure that production is set in the environment (.env) variables
  - Add your database location to the environment variables
  - Run `composer install` in the cloned directory
  - Run `php bin/console archer:setup` in the cloned directory
  - Voila!

## Prerequisites
  - PHP >=7.4.1
  - Composer >=2.0.0
