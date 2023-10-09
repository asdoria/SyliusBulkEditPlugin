import axios from 'axios'
import './sylius/sylius-auto-complete'
import Alertify from 'alertifyjs';
import 'fomantic-ui/dist/components/transition'
import 'fomantic-ui/dist/components/popup'

$.fn.destroyDropdown = function() {
    return $(this).each(function() {
        $(this).parent().dropdown( 'destroy' ).replaceWith( $(this) );
    });
};

const CONTAINER_SELECTOR = '#js-asdoria-bulk-edit-action-steps'
const FORM_NAME = 'asdoria_bulk_edit_form'
const CREATED_ACTION_EVENT = 'bulk_edit.created_action'

export default () => {
    const el = document.querySelector(CONTAINER_SELECTOR)

    if (!el) return;

    init(el, {}, CREATED_ACTION_EVENT)
}

/**
 *
 * @param container {HTMLElement}
 * @param formData
 * @returns {Promise<void>}
 */
const init = async (container, formData = {}, eventName = 'bulk_edit.updated_action') => {

    const { url, loading, confirmationSuccess, errorMessage } = container.dataset

    container.querySelectorAll('.sylius-autocomplete, .ui.dropdown').forEach(e => $(e).destroyDropdown())

    container.innerHTML = '<div class="content" id="modal_content">\n' +
      '            <div class="ui active inverted dimmer">\n' +
      '                <div class="ui text loader">' + loading + '</div>\n' +
      '            </div>\n' +
      '        </div>'
    const response = await axios.post(url, formData).catch(e => {
        Alertify.error(errorMessage)
      debugger
        if (eventName !== CREATED_ACTION_EVENT) {
          setTimeout(() => location.reload(), "1 seconde");
        }
    })

    if(!response) return;

    const { data: { steps }, status } = response

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

    if (stepKey === 'type') el.classList.add('field')

    el.appendChild(divider)
    initAlertify(container, stepKey, el)

    container.appendChild(el)

    formUpdateFieldSelector(el).forEach(input => input.addEventListener('change', updateValue))
    initAutoComplete(el)
    initDropdown(el)

}

/**
 *
 * @param e
 */
export const updateValue = ({ target }, isSumited = false) => {

    const formData  = new FormData();
    const container = selectContainer()
    const input     = selectCheckboxTarget()

    if (input) {
      formData.set(`${FORM_NAME}[resources]`, input.value)
    }

    if (isSumited) {
        formData.set(`${FORM_NAME}[submit]`, 1)
    }

    appendToFormData(container, formData)

    init(container, formData)
}

const formFieldSelector = (container) => container.querySelectorAll(`[name^="${FORM_NAME}"]:not(#${FORM_NAME}_submit):not(#${FORM_NAME}_resources)`)
const formUpdateFieldSelector = (container) => container.querySelectorAll(`[data-form-collection="update"]`)
const initAutoComplete = (container) => $(container.querySelectorAll('.sylius-autocomplete')).autoComplete()
const initDropdown = (container) => $(container.querySelectorAll('.ui.dropdown:not(.sylius-autocomplete)')).dropdown()
const selectCheckboxTarget = () => document.querySelector('input.bulk-select-checkbox-target')
const selectContainer = () => document.querySelector(CONTAINER_SELECTOR)

const initAlertify = (container, stepKey, el) => {
  if ('submit' !== stepKey) return;
  el.querySelector('[type="submit"]').addEventListener('click', (e) => {
    e.preventDefault()
    e.stopPropagation()
    const { confirmation, validateChoice, alertEmptyResources, alert } = container.dataset

    if (!!selectCheckboxTarget().value) {
      Alertify.confirm(
        confirmation,
        validateChoice,
        () => updateValue({ target: el }, true),
        () => {},
      )
      return
    }

    Alertify.alert(alert, alertEmptyResources);
    const checkboxs  = document.querySelectorAll('.bulk-select-checkbox')
    checkboxs.forEach((ele) => ele.parentNode.classList.add('negative'))
  })
}

const appendToFormData = (container, formData) =>  {
  const elements = formFieldSelector(container);
  elements.forEach(el => {
    if (el.type !== 'checkbox' || el.checked) {
      if (el.multiple) {
        processSelectMultiple(el, formData)
      } else {
        formData.append(el.name, el.value);
      }
    }
  })
}

const processSelectMultiple = (el, formData) => {
  for (const option of el.options) {
    if (!option.selected) continue
    formData.append(el.name, option.value)
  }
}

