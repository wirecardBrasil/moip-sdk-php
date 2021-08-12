# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

<a name="v3.1.0"></a>
## v3.1.0 (2018-01-17)


#### Bug Fixes

* **Accounts**: Resolves Warning on account creation. ([4a712974](https://github.com/moip/moip-sdk-php/commit/4a712974))

#### Features

* **Orders**: Adds item category argument into `addItem()` method. ([749fa85e](https://github.com/moip/moip-sdk-php/commit/749fa85e))

* **Accounts**: Adds `getPasswordLink()` method. ([fe2c4d6d](https://github.com/moip/moip-sdk-php/commit/fe2c4d6d))

* **Balances**: Get balances of Moip Account. ([37b085a8](https://github.com/moip/moip-sdk-php/commit/37b085a8))

* **Transfers**:
  * Create a transfer to a saved bank account. ([749fa85e](https://github.com/moip/moip-sdk-php/commit/749fa85e))
  * Adds set and get `ownId` to a transfer. ([1c297d14](https://github.com/moip/moip-sdk-php/commit/1c297d14))

<a name="v3.0.0"></a>
# [](https://github.com/moip/moip-sdk-php/compare/v2.2.0...v3.0.0) (2018-01-04)


#### Bug Fixes

*   Fixed errors treatment ([3862cc69](https://github.com/moip/moip-sdk-php/commit/3862cc69))

#### Features

* **Holder**  Add Holder resource and resolves Holder params. ([9f84f793](https://github.com/moip/moip-sdk-php/commit/9f84f793))
* **Order**
  *  map attributes on order creation ([8f9755ef](https://github.com/moip/moip-sdk-php/commit/8f9755ef))
  *  add attribute paid to order creation mock ([2f01ad91](https://github.com/moip/moip-sdk-php/commit/2f01ad91))
  *  remove shipping address attributes from populate ([7eda9b6f](https://github.com/moip/moip-sdk-php/commit/7eda9b6f))

#### Tests

*  Support PHPUnit 5 and 6 ([5a316192](https://github.com/moip/moip-sdk-php/commit/5a316192))
*  Test against PHP 7.1 and 7.2 ([c6983f63](https://github.com/moip/moip-sdk-php/commit/c6983f63))
* **Order**
  *  add tests for the new mapped attributes ([9c99d832](https://github.com/moip/moip-sdk-php/commit/9c99d832))
  *  add attribute paid to order creation mock ([2f01ad91](https://github.com/moip/moip-sdk-php/commit/2f01ad91))

#### BREAKING CHANGES
The type `Holder` was added to be used on payment creation and on some other features linked on funding instrument.

<a name="v2.2.0"></a>
# [](https://github.com/moip/moip-sdk-php/compare/v2.1.0...v2.2.0) (2017-11-01)


#### Bug Fixes

*   Prevent notice when tax document or receivers are not returned ([6f6c10bd](https://github.com/moip/moip-sdk-php/commit/6f6c10bd))
* **Transfers**
  *  Improved fix to issue #184 ([a6e992f2](https://github.com/moip/moip-sdk-php/commit/a6e992f2))
  *  Fix setHolder warning when called before setTransfers (#184). ([33f9126d](https://github.com/moip/moip-sdk-php/commit/33f9126d))

#### Features

* **BankAccount**  Created bank account get id, get list, delete, update (#185) ([a4ac12d8](https://github.com/moip/moip-sdk-php/commit/a4ac12d8))
* **Keys**  adds method to get public key from moip account ([fc2143a2](https://github.com/moip/moip-sdk-php/commit/fc2143a2))
* **NotificationPreferences**
  *  added method to get a notification preference ([d9f40623](https://github.com/moip/moip-sdk-php/commit/d9f40623))
  *  Added method to get a list of notifications ([9be2ae45](https://github.com/moip/moip-sdk-php/commit/9be2ae45))
* **Payment**
  *  changed 'get' method to allow get a payment without get an order first ([7874a7ed](https://github.com/moip/moip-sdk-php/commit/7874a7ed))
  *  add method 'cancel' to cancel pre-authorized payments ([858cece3](https://github.com/moip/moip-sdk-php/commit/858cece3))
  *  changed 'capture' method to allow multipayments ([32d70e76](https://github.com/moip/moip-sdk-php/commit/32d70e76))
* **Transfers**  Added get list, get id, revert and getters ([8c03c932](https://github.com/moip/moip-sdk-php/commit/8c03c932))
* **account**
  *  added getCreatedAt function ([98b1ff3b](https://github.com/moip/moip-sdk-php/commit/98b1ff3b))
  *  added missing properties to account ([14256c08](https://github.com/moip/moip-sdk-php/commit/14256c08))

<a name="v2.1.0"></a>
# [](https://github.com/moip/moip-sdk-php/compare/v2.0.0...v2.1.0) (2017-10-09)

### Bug Fixes

* Prevent notice when tax document or receivers are not returned ([6f6c10b](https://github.com/moip/moip-sdk-php/commit/6f6c10b))

### Features

* **Account:** Create method to check if an account exists ([17e6cd2](https://github.com/moip/moip-sdk-php/commit/17e6cd2))
* **Keys:** adds method to get public key from moip account ([fc2143a](https://github.com/moip/moip-sdk-php/commit/fc2143a))
* **NotificationPreferences:** Added method to get a list of notifications ([9be2ae4](https://github.com/moip/moip-sdk-php/commit/9be2ae4))
* **NotificationPreferences:** added method to get a notification preference ([d9f4062](https://github.com/moip/moip-sdk-php/commit/d9f4062))
* **Payment:** add method 'cancel' to cancel pre-authorized payments ([858cece](https://github.com/moip/moip-sdk-php/commit/858cece))
* **Payment:** changed 'capture' method to allow multipayments ([32d70e7](https://github.com/moip/moip-sdk-php/commit/32d70e7))
* **Payment:** changed 'get' method to allow get a payment without get an order first ([7874a7e](https://github.com/moip/moip-sdk-php/commit/7874a7e))


<a name="v2.0.0"></a>
### [2.0.0](https://github.com/moip/moip-sdk-php/compare/v1.3.2...v2.0.0) (2017-09-29)

### Features

* **Account:** Create method to check if an account exists ([17e6cd2](https://github.com/moip/moip-sdk-php/commit/17e6cd2))
* **Webhooks:**  Create method to get a list of webhooks([fa4a7d4](https://github.com/moip/moip-sdk-php/commit/fa4a7d49c2650813592a1f764be51e336247a9f5))
* added methods to get boleto print link and token from webhook([ab158a6](https://github.com/moip/moip-sdk-php/commit/ab158a6746cc75138111353301c5c066af8b962e))

## BREAKING CHANGES
Method `generateListPath` from MoipResource changed last parameter to array instead string.

<a name="v1.3.2"></a>
### [1.3.2](https://github.com/moip/moip-sdk-php/compare/v1.3.0...v1.3.2) (2017-09-18)

#### Bug Fixes
- **MoipResource:** Initializing variable to prevent E_NOTICE ([42ee471](https://github.com/moip/moip-sdk-php/commit/42ee471ce2b2131cb326f434fd2a105ceb7f1f45))
- **Connect:** Removing type declaration from methods to compatibility with PHP older versions ([77abe58](https://github.com/moip/moip-sdk-php/commit/77abe58da9e5b658160f1a279ba6227e9ade4409))

<a name="v1.3.1"></a>
### [1.3.1](https://github.com/moip/moip-sdk-php/compare/v1.3.0...v1.3.1) (2017-08-11)

#### Bug Fixes
* **Account:**  Fix account create without company set (#157) ([4d7f4bc5](4d7f4bc5))

<a name="1.3.0"></a>
# [1.3.0](https://github.com/moip/moip-sdk-php/compare/v1.2.0...v1.3.0) (2017-08-08)

## Bug Fixes
- **order:**
  - fix adding of installments in checkout preferences
  ([3dee9fa](https://github.com/moip/moip-sdk-php/commit/3dee9fa7b9a5863ba4828de2f03a5fd7a1254898))
- **refund:**
  - fix of bank account refund
  ([d336f9f](https://github.com/moip/moip-sdk-php/commit/d336f9f04dc92a978e3d67942091b573c9a30643))
- fix method to return HATEOAS links from API
  ([025bfde](https://github.com/moip/moip-sdk-php/commit/025bfdedde5bfe953264b24daa0ba371e73e43cd))
- fix method to get DateTime from resources
  ([3d30cbb](https://github.com/moip/moip-sdk-php/pull/152/commits/3d30cbbf49fb9c4ee1b6049dd93cd3487a9fef81))


## New Features
- **escrow:** add escrow resource
  ([ed99701](https://github.com/moip/moip-sdk-php/commit/ed9970156de1dea88a091fd33b54bcec8f91ce92)),
- **notification preferences:** add notification preferences resource
  ([e553d8b](https://github.com/moip/moip-sdk-php/commit/e553d8b9c9878009cb2d2e021043f3ebbaeb2dc5))
- **customer credit card:** add resource to add more credit cards to customer
  ([d327f03](https://github.com/moip/moip-sdk-php/commit/d327f03b5d2449dbac95f3f3cabcd17a19b8853a))

## BREAKING CHANGES
Now tests are run using OAuth authentication instead Basic Auth, because now there are tests to resources that only uses OAuth authentication.