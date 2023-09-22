<p align="center">
</p>

![Logo Asdoria](doc/asdoria.jpg)

<h1 align="center">Asdoria BulkEdit Plugin</h1>

<p align="center">Simply BulkEdit's Managment into Sylius Shop</p>

## Features

+ Managment Quote Request for you shop

<div style="max-width: 75%; height: auto; margin: auto">

![Add to Cart](doc/presentation.gif)

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
asdoria_quick_shopping:
    resource: "@AsdoriaSyliusQuickShoppingPlugin/Resources/config/routing.yaml"
asdoria_bulk_edit:
    resource: "@AsdoriaSyliusBulkEditPlugin/config/routing.yaml"

#if you don't want to use the quick sopping page added this route
asdoria_shop_quick_shopping_index:
    path: /{_locale}/quick-shopping
    controller: Symfony\Bundle\FrameworkBundle\Controller\RedirectController::redirectAction
    defaults:
        route: asdoria_shop_bulk_edit_index
        # make a permanent redirection...
        permanent: true
        # ...and keep the original query string parameters
        keepQueryParams: true
```

4. Import config in `config/packages/_sylius.yaml`
```yaml
imports:
    - { resource: "@AsdoriaSyliusQuickShoppingPlugin/Resources/config/config.yaml"}
    - { resource: "@AsdoriaSyliusBulkEditPlugin/config/config.yaml"}
```

5. Paste the following content to the `src/Repository/ProductVariantRepository.php`:
```php
  <?php

  declare(strict_types=1);

  namespace App\Repository;

  use Asdoria\SyliusQuickShoppingPlugin\Repository\Model\ProductVariantRepositoryAwareInterface;
  use Asdoria\SyliusQuickShoppingPlugin\Repository\ProductVariantRepositoryTrait;
  use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductVariantRepository as BaseProductVariantRepository;
  
  final class ProductVariantRepository extends BaseProductVariantRepository implements ProductVariantRepositoryAwareInterface
  {
      use ProductVariantRepositoryTrait;
  }
```

6. Configure repositories in `config/packages/_sylius.yaml`:
```diff  
 sylius_product:
     resources:
         product_variant:
             classes:
                 model: App\Entity\Product\ProductVariant
+                repository: App\Repository\ProductVariantRepository
```

7. Override _addToCart.html.twig into `templates/bundles/SyliusShopBundle/Product/Show/_addToCart.html.twig`:
```diff
{% set product = order_item.variant.product %}

{% form_theme form '@SyliusShop/Form/theme.html.twig' %}

<div class="ui segment" id="sylius-product-selecting-variant" {{ sylius_test_html_attribute('product-selecting-variant') }}>
    {{ sylius_template_event('sylius.shop.product.show.before_add_to_cart', {'product': product, 'order_item': order_item}) }}

    {{ form_start(form, {'action': path('sylius_shop_ajax_cart_add_item', {'productId': product.id}), 'attr': {'id': 'sylius-product-adding-to-cart', 'class': 'ui loadable form', 'novalidate': 'novalidate', 'autocomplete': 'off', 'data-redirect': path(configuration.getRedirectRoute('summary'))}}) }}
    {{ form_errors(form) }}
    <div class="ui red label bottom pointing hidden sylius-validation-error" id="sylius-cart-validation-error" {{ sylius_test_html_attribute('cart-validation-error') }}></div>
    {% if not product.simple %}
        {% if product.variantSelectionMethodChoice %}
            {% include '@SyliusShop/Product/Show/_variants.html.twig' %}
        {% else %}
            {% include '@SyliusShop/Product/Show/_options.html.twig' %}
        {% endif %}
    {% endif %}
    {{ form_row(form.cartItem.quantity, sylius_test_form_attribute('quantity')) }}

    {{ sylius_template_event('sylius.shop.product.show.add_to_cart_form', {'product': product, 'order_item': order_item, 'form': form}) }}

-   <button type="submit" class="ui huge primary icon labeled button" {{ sylius_test_html_attribute('add-to-cart-button') }}><i class="cart icon"></i> {{ 'sylius.ui.add_to_cart'|trans }}</button>
+   <div class="ui buttons">
+       <button type="submit" class="ui huge primary icon labeled button" {{ sylius_test_html_attribute('add-to-cart-button') }}><i class="cart icon"></i> {{ 'sylius.ui.add_to_cart'|trans }}</button>
+       <div class="or" data-text="{{ 'asdoria_bulk_edit.ui.shop.or'|trans }}"></div>
+       <button type="submit"
+               id="asdoria-shop-bulk-edit-add-to-quote"
+               data-action="{{ path('sylius_shop_ajax_quote_add_item', {'productId': product.id}) }}"
+               class="ui huge primary icon labeled button" {{ sylius_test_html_attribute('add-to-quote-button') }}
+               data-redirect="{{ path('asdoria_shop_bulk_edit_index') }}"
+               data-csrf-token="{{ csrf_token('asdoria-shop-bulk-edit-add-to-quote') }}"
+       >
+           <i class="box icon"></i>
+           {{ 'asdoria_bulk_edit.ui.shop.add_to_quote'|trans }}
+       </button>
+    </div>
    {{ form_row(form._token) }}
    {{ form_end(form, {'render_rest': false}) }}
</div>  
```

## Demo

You can try the QuickShopping plugin online by following this link: [here!](https://demo-sylius.asdoria.fr/en_US/bulk-edit).

Note that we have developed several other open source plugins for Sylius, whose demos and documentation are listed on the [following page](https://asdoria.github.io/).

## Usage

1. In the shop office, go to /en_US/bulk-edit route.



