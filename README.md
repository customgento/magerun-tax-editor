# Tax Editor for Magento 1
Tax Editor for Magento 1 adds a n98-magerun command to update tax rates. This is pretty useful for general tax rate changes in a country, which must be applied exactly at a specific date. It is possible to define a new rate for a range of tax rate IDs.

## Installation
There are a few options. You can check out the different options in the [n98-magerun docs](https://magerun.net/introducting-the-new-n98-magerun-module-system/).

Here's the easiest:

1. Create `~/.n98-magerun/modules/` if it doesn't already exist:

        mkdir -p ~/.n98-magerun/modules/

2. Clone this repository in there:

        cd ~/.n98-magerun/modules/ && git clone https://github.com/customgento/magerun-tax-editor.git customgento-tax-editor

3. The module should be installed. To see that it was installed, check if one of the new commands is in there, like `tax:rates:edit`.

        n98-magerun.phar tax:rates:edit --help

## Commands

### Edit Rates Of Existing Tax Rates

    $ n98-magerun.phar tax:rates:edit [--ids[="..."]] [--rate[="..."]] [--update-titles]

#### Options

`--ids`            Comma-separated list of tax rate IDs

`--rate`           The new rate (integer or float with decimal point)

`--update-titles`  Update the code and the titles of the tax rate as well
