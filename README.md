<p align="center">
</p>

![Logo Asdoria](doc/asdoria.jpg)

<h1 align="center">Asdoria BulkEdit Plugin</h1>

<p align="center">Simply BulkEdit's Managment into Sylius Shop</p>

<div style="max-width: 75%; height: auto; margin: auto">

![Bulk edit](doc/bulk-edit-plugin.png)

</div>

<div style="max-width: 75%; height: auto; margin: auto">

</div>



## Installation

---
1. run `composer require asdoria/sylius-bulk-edit-plugin`


2. Add the bundle in `config/bundles.php`.

```PHP
Asdoria\SyliusBulkEditPlugin\AsdoriaSyliusBulkEditPlugin::class => ['all' => true],
```

3. Import routes in `config/routes.yaml`

```yaml
asdoria_bulk_edit:
    resource: "@AsdoriaSyliusBulkEditPlugin/config/routing.yaml"
```

4. Import config in `config/packages/_sylius.yaml`
```yaml
imports:
    - { resource: "@AsdoriaSyliusBulkEditPlugin/config/config.yaml"}
```


5. Synchronous messenger transport for consume message
```bash
php bin/console messenger:consume asdoria_bulk_edit
```

## Feature

```yaml

    Product Variants: 
        demo: https://demo-sylius.asdoria.fr/admin/bulk-edit/product-variants

        Shipping Categories:
            - Edit Shipping Category
            - Enabled/Disabled Shipping required
        Tax Categories:
            - Modify Tax Category 
        Product Variants:
            - Enabled/Disabled Variant
            - Enabled/Disabled Tracked
            - Modify Property (width,height,depth,weight)

    Product:
        demo: https://demo-sylius.asdoria.fr/admin/bulk-edit/products

        Association:
            - Add Association Type
            - Delete Association Type
        Attributes:
            - Add/Edit Attribute value 
            - Delete Attribute value 
        Product:
            - Enabled/Disabled Product
        Taxons:
            - Add Product Taxon
            - Delete Product Taxon
            - Edit Main Taxon

    Taxon:
        demo: https://demo-sylius.asdoria.fr/admin/bulk-edit/taxons

        Taxons:
            - Enabled/Disabled Taxon
            - Edit Parent Taxon

    Customer:
        demo: https://demo-sylius.asdoria.fr/admin/bulk-edit/customers
        
        Customer:
            - Add/Edit the group
        Shop Users:
            - Enabled/Disabled Shop User
```

## Demo

You can try the BulkEdit plugin online by following this link: [here!](https://demo-sylius.asdoria.fr/admin/bulk-edit/products).

Note that we have developed several other open source plugins for Sylius, whose demos and documentation are listed on the [following page](https://asdoria.github.io/).

## Usage

1. In the admin office, go to /admin/bulk-edit/products route.



