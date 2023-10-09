import $ from 'jquery';
import './sylius/sylius-check-all';
import 'fomantic-ui/dist/components/transition'
import loadSteps from './load-steps';
import selectResources from './select-resources';
import axios from 'axios'

document.addEventListener('DOMContentLoaded', async () => {
  $('[data-js-bulk-checkboxes]').checkAll();
  $('.ui.dropdown.search:not(.sylius-autocomplete)').dropdown({
    action: 'hide',
    onChange: function (value, text, $selectedItem) {
      changeInputValue()
      selectedInputValue().value = '';
    }
  })

  loadSteps()
  selectResources()
  changeInputValue(true)
});

const PREFIX_INPUT = 'criteria_asdoria_bulk_edit_search_attribute_value'
const selectedInputValue = () => document.querySelector(`#${PREFIX_INPUT}_value`)
const selectedInputLocale = () => document.querySelector(`#${PREFIX_INPUT}_localeCode`)
const selectedInputAttribute = () => document.querySelector(`#${PREFIX_INPUT}_attribute`)
const selectedInputContainer = () => selectedInputValue().closest("div.field")
const initInputValue = (name, el) => {
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  const initialValue = urlParams.get(name)
  if (el.type === 'checkbox') {
      el.checked = true
    return;
  }
  el.value = urlParams.get(name)
}
const changeInputValue = (init = false) => {
  const inputLocale = selectedInputLocale()
  const inputAttr = selectedInputAttribute()

  if (!inputLocale || !inputAttr) return;

  const localeCode = inputLocale.value
  const {renderUrl} = inputAttr.dataset
  const attrValue = inputAttr.value
  if (!localeCode || !attrValue) return;

  const url = `${renderUrl}?sylius_product_attribute_choice[]=${attrValue}&locale_code=${localeCode}`;

  axios
    .get(url)
    .then(({data}) => {
      const container = selectedInputContainer()
      container.classList.remove('error')
      container.innerHTML = data;
      const input = selectedInputValue()
      if (input) {
        const {name} = input.dataset
        input.name = name
        if (init) initInputValue(name, input)
      }
    });
}
