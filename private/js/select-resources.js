export default () => {
    const input = document.querySelector('input.bulk-select-checkbox-target')

    if (!input) return;

    process(input)

    document.querySelectorAll('.bulk-select-checkbox, [data-js-bulk-checkboxes=".bulk-select-checkbox"]')
        .forEach(el => el.addEventListener('change', () => process(input)))
}

/**
 *
 * @param input {HTMLInputElement}
 */
const process = (input) => {
    input.value = [...document.querySelectorAll('.bulk-select-checkbox')]
        .filter(({ checked }) => checked)
        .map(({ value }) => value)
        .join(',')
}
