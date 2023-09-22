
document.addEventListener('DOMContentLoaded', () => {
  const addToQuote = document.querySelector("#asdoria-shop-bulk-edit-add-to-quote");
  if(!addToQuote) return;
  addToQuote.addEventListener('click',  (event) => {

    const ele = event.currentTarget;
    const {action, redirect, csrfToken} = ele.dataset
    const form = ele.closest('form');

    const addToCartFormData = new FormData(form);
    let addToQuoteFormData = new FormData();
    for (const key of addToCartFormData.keys()) {
      addToQuoteFormData.append(
        key.replace('sylius_add_to_cart','asdoria_add_to_quote'),
        key.includes('_token') ? csrfToken : addToCartFormData.get(key)
      );
    }

    fetch(action,
      {
        body: addToQuoteFormData,
        method: "post"
      })
      .then((response) => window.location.href = redirect);
    event.preventDefault();
    event.stopPropagation();
  })
});
