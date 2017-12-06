
# Magento 2 Brochure

## Features:

- Backend:
  - add brochure groups
    - label
  - add brochure items
    - title
    - cover image
    - brochure file
    - position
- Frontend
  - display brochure group via widget

## Installing the Extension

    composer require magekey/module-brochure

## Deployment

    php bin/magento maintenance:enable                  #Enable maintenance mode
    php bin/magento setup:upgrade                       #Updates the Magento software
    php bin/magento setup:di:compile                    #Compile dependencies
    php bin/magento setup:static-content:deploy         #Deploys static view files
    php bin/magento cache:flush                         #Flush cache
    php bin/magento maintenance:disable                 #Disable maintenance mode

## Versions tested
> 2.2.1
