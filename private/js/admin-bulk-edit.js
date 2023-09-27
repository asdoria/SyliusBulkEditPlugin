import $ from 'jquery';
import './sylius/sylius-check-all';

import loadSteps from './load-steps';
import selectResources from './select-resources';

document.addEventListener('DOMContentLoaded', async () => {
    $('[data-js-bulk-checkboxes]').checkAll();
    loadSteps()
    selectResources()
});
