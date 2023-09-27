import axios from 'axios'
import './sylius/sylius-auto-complete'
import 'fomantic-ui/dist/components/transition'
import './sylius/sylius-bulk-edit-action-require-confirmation';
$.fn.destroyDropdown = function() {
    return $(this).each(function() {
        $(this).parent().dropdown( 'destroy' ).replaceWith( $(this) );
    });
};

const CONTAINER_SELECTOR = '#js-action-steps'

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
const init = async (container, formData = {}) => {
    const { url } = container.dataset

    container.querySelectorAll('.sylius-autocomplete').forEach(e => $(e).destroyDropdown())

    container.innerHTML = 'Loading...'

    const { data: { steps }, status } = await axios.post(url, formData)

    if (status === 204) {
        window.dispatchEvent(new CustomEvent('bulk_edit.sent_action'))
        init(container)
        return;
    }

    container.innerHTML = ''

    Object.entries(steps)
        .sort(([k1], [k2]) => k1 - k2)
        .forEach((ele) => setStepEl(container, ...ele))

    container.dispatchEvent(new CustomEvent('bulk_edit.updated_action'))
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
    const re = new RegExp("/asdoria_bulk_edit_form[*][value][value]/");
    el.querySelectorAll('[name^="asdoria_bulk_edit_form"]')
        .forEach(input => {
            if(input.name.endsWith('[value][value]')) return;
            input.addEventListener('change', updateValue)
        })

    container.appendChild(el)

    $(el.querySelectorAll('.sylius-autocomplete')).autoComplete()
    $(el.querySelectorAll('[data-bulk-edit-action-requires-confirmation]')).bulkEditActionRequireConfirmation();
}

/**
 *
 * @param e
 */
export const updateValue = ({ target }) => {
    const container = target.closest(CONTAINER_SELECTOR)
    const el        = target.closest('div[data-step]')
    const formData  = new FormData();
    const input     = document.querySelector('input.bulk-select-checkbox-target')
    const submit     = document.querySelector('#asdoria_bulk_edit_form_submit')

    if (input) {
      formData.set('form[resources]', input.value)
    }

    if (submit === target) {
        formData.set('asdoria_bulk_edit_form[submit]', 1)
    }

    container.querySelectorAll(`[name^="asdoria_bulk_edit_form"]:not(#asdoria_bulk_edit_form_submit)`)
        .forEach(el => {
            if (el.type !== 'checkbox' || el.checked){
                formData.append(el.name, el.value);
            }
        })

    init(container, formData)

}

/**
 *
 * @param e
 */
const onSubmitForm = e => {
    e.preventDefault()
    updateValue({ target: e.target.querySelector('[type="submit"]') })
}
