/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import 'semantic-ui-css/components/modal';
import $ from 'jquery';
import selectResources from "../select-resources";
import {updateValue} from "../load-steps";

$.fn.extend({
  bulkEditActionRequireConfirmation() {
    this.each((idx, el) => {
      $(el).on('click', (evt) => {
        evt.preventDefault();

        const actionButton = $(evt.currentTarget);

        if (actionButton.is('a')) {
          $('#confirmation-button').attr('href', actionButton.attr('href'));
        }

        if (actionButton.is('button')) {
          $('#confirmation-button').on('click', (event) => {
            event.preventDefault();
            updateValue({ target: evt.currentTarget })
            location.reload();
          });
        }

        $('#confirmation-modal').modal('show');
      });
    });
  },
});
