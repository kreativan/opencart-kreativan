/**
 *  Change Currency
 *  @param {string} currency
 */
function changeCurrency(currency) {
  event.preventDefault();
  const form = document.querySelector("#form-currency");
  let code = form.querySelector("input[name=code]");
  code.value = currency;
  form.submit();
}

/**
 *  Change language
 *  @param {string} language
 */
function changeLanguage(language) {
  const form = document.querySelector("#form-language");
  let code = form.querySelector("input[name=code]");
  code.value = language;
  form.submit();
}

/**
 *  Add to Cart
 *  @param {int} product_id
 *  @param {int} quantity
 */
function addToCart(product_id, quantity) {

  let formData = new FormData();
  formData.append("product_id", product_id);
  formData.append("quantity", quantity);

  fetch('index.php?route=checkout/cart/add', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    console.log('Success:', data);
  })
  .catch((error) => {
    console.error('Error:', error);
  });

}

/**
 *  Remove from cart
 *  @param {int} product_cart_id
 */
function removeFromCart(product_cart_id) {

  let formData = new FormData();
  formData.append("key", product_cart_id);

  fetch('index.php?route=checkout/cart/remove', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    console.log('Success:', data);
  })
  .catch((error) => {
    console.error('Error:', error);
  });

}

/**
 *  Add product to the wishlist
 *  @param {int} product_id
 */
function wishlistAdd(product_id) {

  let formData = new FormData();
  formData.append("product_id", product_id);

  fetch('index.php?route=account/wishlist/add', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    console.log('Success:', data);
  })
  .catch((error) => {
    console.error('Error:', error);
  });

}

/**
 *  Add product to the compare
 *  @param {int} product_id
 */
function compareAdd(product_id) {

  let formData = new FormData();
  formData.append("product_id", product_id);

  fetch('index.php?route=product/compare/add', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    console.log('Success:', data);
  })
  .catch((error) => {
    console.error('Error:', error);
  });

}
