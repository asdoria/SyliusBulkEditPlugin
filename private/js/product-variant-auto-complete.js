/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import 'semantic-ui-css/components/dropdown';
import 'semantic-ui-css/components/api';
import 'semantic-ui-css/components/transition';
import $ from 'jquery';

$.fn.extend({
  productVariantAutoComplete() {
    this.each((idx, el) => {
      const element           = $(el);
      const criteriaName      = element.data('criteria-name');
      const choiceName        = element.data('choice-name');
      const choiceValue       = element.data('choice-value');
      const autocompleteValue = element.find('input.autocomplete').val();
      const loadForEditUrl    = element.data('load-edit-url');
      const menuElement       = element.find('div.menu');
      const container         = element.closest('.ui.stackable.grid').find('#details-container-variant');

      element.dropdown({
        delay: {
          search: 250,
        },
        forceSelection: false,
        apiSettings: {
          dataType: 'JSON',
          cache: false,
          type: 'imageResult',
          beforeSend(settings) {
            /* eslint-disable-next-line no-param-reassign */
            settings.data[criteriaName] = settings.urlData.query;

            return settings;
          },
          onResponse(response) {
            let results = response.map(item => ({
              name: item[choiceName],
              value: item[choiceValue],
              image: item['image'],
              // price: item['price'],
              slug: item['slug'],
            }));
            return {
              success: true,
              results: results,
            };
          },
          onSuccess(response) {
            menuElement.empty();
            response.results.forEach((item) => {
              menuElement.append((
                $(`<div class="item" data-value="${item['value']}" data-slug="${item['slug']}">
                    <img class="ui avatar image" src="${item['image']}">
                    <span class="text" >${item['name']}</span>
                  </div>`)
              ));
            });
            element.dropdown('refresh');
            element.dropdown('set selected', element.find('input.autocomplete').val().split(',').filter(String));
            if (response.results.length > 0) {
              element.dropdown('show');
            }
          },
        },
        onChange(addedValue, addedText, $addedChoice) {
          const priceTrans            = container.attr('data-price-trans');
          const quantityInput         = element.closest('.ui.stackable.grid').find(':input[type="number"]');
          const quantity              = quantityInput.val();
          const row                   = $('<div>' + addedText + '</div>')
          const image                 = row.find('img.ui.avatar.image').attr('src');

          container.empty();
          container.append($('' +
            '<div class="field" data-image="' + image + '" >' +
            '<label>' + priceTrans + '</label>' +
            '</div>')
          )
        },
        onRemove(removedValue, removedText, $removedChoice) {
          container.empty();
        }
      });
      if (autocompleteValue.split(',').filter(String).length > 0) {
        const menuElement = element.find('div.menu');

        menuElement.api({
          on: 'now',
          method: 'GET',
          url: loadForEditUrl,
          beforeSend(settings) {
            /* eslint-disable-next-line no-param-reassign */
            settings.data[choiceValue] = autocompleteValue.split(',').filter(String);

            return settings;
          },
          onSuccess(response) {
            console.log('response', response);
            response.forEach((item) => {
              debugger;
              menuElement.append(
                $(`<div class="item" data-value="${item['code']}" data-slug="${item['slug']}">
                    <img class="ui avatar image" src="${item['image']}">
                    <span class="text" >${item['descriptor']}</span>
                  </div>`)
              );
            });

            element.dropdown('refresh');
            element.dropdown('set selected', element.find('input.autocomplete').val().split(',').filter(String));
            // element.dropdown('show');
          },
        });
      }
    });
  },
});
