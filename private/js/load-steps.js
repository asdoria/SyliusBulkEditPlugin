import axios from 'axios'
import './sylius/sylius-auto-complete'
import Alertify from 'alertifyjs';
import 'fomantic-ui/dist/components/transition'
$.fn.destroyDropdown = function() {
    return $(this).each(function() {
        $(this).parent().dropdown( 'destroy' ).replaceWith( $(this) );
    });
};

const CONTAINER_SELECTOR = '#js-asdoria-bulk-edit-action-steps'

export default () => {
    const el = document.querySelector(CONTAINER_SELECTOR)

    if (!el) return;

    init(el)
}

/**
 *
 * @param container {HTMLElement}
 * @param formData
 * @returns {Promise<void>}
 */
const init = async (container, formData = {}, eventName = 'bulk_edit.updated_action') => {

    const { url, loading, confirmationSuccess } = container.dataset

    container.querySelectorAll('.sylius-autocomplete').forEach(e => $(e).destroyDropdown())

    container.innerHTML = '<div class="content" id="modal_content">\n' +
      '            <div class="ui active inverted dimmer">\n' +
      '                <div class="ui text loader">' + loading + '</div>\n' +
      '            </div>\n' +
      '        </div>'

    const { data: { steps }, status } = await axios.post(url, formData)

    if (status === 204) {
        window.dispatchEvent(new CustomEvent('bulk_edit.sent_action'))
        Alertify.success(confirmationSuccess);
        location.reload();
        return;
    }

    container.innerHTML = '';

    Object.entries(steps).forEach((ele) => setStepEl(container, ...ele));

    container.dispatchEvent(new CustomEvent(eventName))
}

/**
 *
 * @param container
 * @param stepKey
 * @param stepHtml
 */
const setStepEl = (container, stepKey, stepHtml) => {
    const el        = document.createElement('div')
    el.dataset.step = stepKey
    el.innerHTML    = stepHtml

    const divider = document.createElement('div')
    divider.classList.add('ui', 'hidden', 'divider')

    el.appendChild(divider)
    initAlertify(container, stepKey, el)

    container.appendChild(el)

    formUpdateFieldSelector(el).forEach(input => input.addEventListener('change', updateValue))
    initAutoComplete(el)

}

/**
 *
 * @param e
 */
export const updateValue = ({ target }, isSumited = false) => {

    const formData  = new FormData();
    const container = document.querySelector(CONTAINER_SELECTOR)
    const input     = document.querySelector('input.bulk-select-checkbox-target')

    if (input) {
      formData.set('asdoria_bulk_edit_form[resources]', input.value)
    }

    if (isSumited) {
        formData.set('asdoria_bulk_edit_form[submit]', 1)
    }

    const elements = formFieldSelector(container);
    elements.forEach(el => {
            if (el.type !== 'checkbox' || el.checked){
                formData.append(el.name, el.value);
            }
        })

    init(container, formData)
}

const formFieldSelector = (container) => container.querySelectorAll(`[name^="asdoria_bulk_edit_form"]:not(#asdoria_bulk_edit_form_submit):not(#asdoria_bulk_edit_form_resources)`)
const formUpdateFieldSelector = (container) => container.querySelectorAll(`[data-form-collection="update"]`)
const initAutoComplete = (container) => $(container.querySelectorAll('.sylius-autocomplete')).autoComplete()
const initAlertify = (container, stepKey, el) => {
  if ('submit' !== stepKey) return;
  el.querySelector('[type="submit"]').addEventListener('click', (e) => {
    e.preventDefault()
    e.stopPropagation()
    const { confirmation, validateChoice } = container.dataset
    Alertify.confirm(
      confirmation,
      validateChoice,
      () => updateValue({ target: el }, true),
      () => {},
    )
  })
}

