# CustomGento Tax Editor

## Installation
There are a few options. You can check out the different options in the [n98-magerun docs](https://magerun.net/introducting-the-new-n98-magerun-module-system/).

Here's the easiest:

1. Create `~/.n98-magerun/modules/` if it doesn't already exist:

        mkdir -p ~/.n98-magerun/modules/

2. Clone this repository in there:

        cd ~/.n98-magerun/modules/ && git clone https://github.com/customgento/magerun-tax-editor.git customgento-tax-editor

3. The module should be installed. To see that it was installed, check to see if one of the new commands is in there, like `tax:rates:edit`.

        n98-magerun.phar tax:rates:edit --help

## Commands

### Edit Rates Of Existing Tax Rates

    $ n98-magerun.phar tax:rates:edit [--ids[="..."]] [--rate[="..."]] [--update-titles]

#### Options

`--ids`            Comma-separated list of tax rate IDs.

`--rate`           The new rate.

`--update-titles`  Update the code and the titles of the tax rate as well.
