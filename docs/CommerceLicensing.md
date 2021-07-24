# Objects
    Add:
    - CommercePackageLicense
        - Int id
        - String code
        - DateInterval duration
        - Boolean redeemed
        - DateTime creationDate
        - CoreUser createdBy
        - CoreUser redeemedBy
    Edit:
    - CommercePackage
        - Boolean isKeyEnabled
        - JSON keyDurationToPrice

# Controllers

### Purchase
    Edit: 'app_commerce_package'

  - Merge CommercePackage.keyDurationToPrice with CommercePackage.durationToPrice on template generation
  - Decrement stock upon key purchase. Skip pending, open => paid/exp
  - Adds product type to invoice, key/subscription. Adds type 'key' to invoice page, when paid.
    Redirects to page with generated keys

### Redemption

    /redeem => 'app_commerce_redeem'

- Verify that key exists, 3 routes after, visibility = anonymous
    - Create account, create subscription [anon]
    - Create subscription [authed]
    - Add to subscription [authed]