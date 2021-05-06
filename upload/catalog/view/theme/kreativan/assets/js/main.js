/* =========================================================== 
  Utility
=========================================================== */

function modalSearchSubmit() {
  event.preventDefault();
  let search = document.querySelector("#modal-search input[name=search]").value;
  let url = `index.php?route=product/search`;
  url += `&search=${search}`;
  window.location.href = url;
}

function updateCartCounter(count) {
  let cartCounters = document.querySelectorAll(".cart-counter");
  cartCounters.forEach(e => {
    e.innerHTML =  count;
    if(count > 0) {
      e.classList.remove("uk-hidden");
    } else {
      e.classList.add("uk-hidden");
    }
  });
}

function loadCart() {
  fetch('index.php?route=common/cart/info')
  .then(response => response.text())
  .then(data => {
    document.querySelector("#offcanvas-cart .uk-offcanvas-bar").innerHTML = data;
  })
}

function resetFields(fields) {
  fields.forEach(e => {
    // console.log(e.type);
    if(e.type && (e.type === 'checkbox' || e.type === 'radio')) {
      e.checked = false;
    } else {
      e.value = "";
    }
  });
}

function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}

/* =========================================================== 
  Agree Modal
=========================================================== */

function agreeModal() {
  let agree = document.querySelector(".agree");
  agree.addEventListener("click", e => {
    e.preventDefault();
    let href = e.target.parentNode.getAttribute("href");
    console.log(href);
    fetch(href)
      .then(response => response.text())
      .then(data => {
        data += `<a href='#' class='uk-modal-close uk-close uk-close-large uk-position-top-right uk-position-medium' uk-close></a>`;
        UIkit.modal.alert(data);
      }); 
  });
}  

window.addEventListener("DOMContentLoaded", function() {
  const agreeLink = document.querySelector(".agree");
  if(agreeLink) agreeModal();
});

/* =========================================================== 
  Common
=========================================================== */

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
  event.preventDefault();
  const form = document.querySelector("#form-language");
  let code = form.querySelector("input[name=code]");
  code.value = language;
  form.submit();
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
    // console.log('Success:', data);
    
    if(data.success) {
      
      UIkit.notification({
        message: data.success,
        status: 'success',
        pos: 'top-center',
        timeout: 2000
      });
      
      let counter = document.querySelector("#wishlist-total");
      counter.innerHTML = data.count;
      if(data.count > 0) {
        counter.classList.remove("uk-hidden");
      } else {
        counter.classList.add("uk-hidden");
      }

    }

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

    UIkit.notification({
      message: data.success,
      status: 'success',
      pos: 'top-center',
      timeout: 2000
    });

    let counter = document.querySelector("#compare-total");
    counter.innerHTML = data.count;
    if(data.count > 0) {
      counter.classList.remove("uk-hidden");
    } else {
      counter.classList.add("uk-hidden");
    }

  })
  .catch((error) => {
    console.error('Error:', error);
  });

}


/* =========================================================== 
  Cart
=========================================================== */

/**
 *  Add to Cart
 *  @param {int} product_id
 *  @param {int} quantity
 */
function addToCart(product_id, qty) {

  let quantity = qty ? qty : 1;

  let formData = new FormData();
  formData.append("product_id", product_id);
  formData.append("quantity", quantity);

  fetch('index.php?route=checkout/cart/add', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {

    // console.log('Success:', data);

    if(data.error) {
      window.location.href = data.redirect;
    } else {

      UIkit.notification({
        message: data.success,
        status: 'success',
        pos: 'top-center',
        timeout: 2000
      });
      
      updateCartCounter(data.count);
      loadCart();

    }

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
    // console.log('Success:', data);
    updateCartCounter(data.count);
    loadCart();
    if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
		  window.location.href = 'index.php?route=checkout/cart';
    }
  })
  .catch((error) => {
    console.error('Error:', error);
  });

}


/* =========================================================== 
  Add to Cart Full
=========================================================== */

/**
 * Add product to cart with all options
 * @param {int} product_id 
 * @param {int} minimum - minimum quantity 
 */
function addToCartFull(product_id, minimum) {

  // we will store all fields data here
  // then we will serialize it and send via fetch req
  let fields = [
    {
      name:"product_id", 
      value: product_id
    }
  ];

  // quantity
  let quantity = document.querySelector("input[name='quantity']");
  if(quantity.value < minimum) quantity.value = minimum;
  fields.push({name:"quantity", value: quantity.value});

  // Recurring payments
  let recurring = document.querySelector("select[name='recurring_id']");
  if(recurring) fields.push({name:"recurring_id", value: recurring.value});

  // Find product options
  // Go trough all product options
  // and add them to the fields array
  const productOptions = document.querySelector("#product-options");
  if(productOptions) {
    const getFields = productOptions.querySelectorAll("select, textarea, input[type=text], input[type=radio], input[type=checkbox]");
    getFields.forEach(e => {
      let name = e.getAttribute("name");
      let value = e.value;
      fields.push({name:name, value: value});
    });
  }

  // console.log(fields);

  // Serialize, make formData from fields array
  let formData = new FormData();
  fields.forEach(e => {
    formData.append(e.name, e.value);
  });

  // Send request
  fetch('index.php?route=checkout/cart/add', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {

    // console.log('Success:', data);

    if(data.error) {
      if(data.error.option) {
        for(const e in data.error.option) {
          let error = data.error.option[e];
          UIkit.notification({
            message: error,
            status: 'danger',
            pos: 'top-center',
            timeout: 2000
          });
        }
      }
      if(data.error.recurring) {
        UIkit.notification({
          message: data.error.recurring,
          status: 'danger',
          pos: 'top-center',
          timeout: 2000
        });
      }
    } else {

      UIkit.notification({
        message: data.success,
        status: 'success',
        pos: 'top-center',
        timeout: 2000
      });

      // reset fields
      if(typeof getFields != 'undefined') resetFields(getFields);
      quantity.value = minimum;
      if(recurring) recurring.value = "";

      // update cart
      updateCartCounter(data.count);
      loadCart();

    }

  });

}

/* =========================================================== 
  Reviews
=========================================================== */

/**
 *  Submit review for a product
 *  @param {int} product_id
 */
function submitReview(product_id) {
  event.preventDefault();

  const form = document.querySelector("#form-review");
  let name = form.querySelector("input[name=name]");
  let text = form.querySelector("textarea[name=text]");
  let rating = form.querySelector("input[name=rating]");
  let captcha = form.querySelector("input[name=captcha]");
  
  let formData = new FormData();
  formData.append("name", name.value);
  formData.append("text", text.value);
  formData.append("rating", rating.value);
  if(captcha) formData.append("captcha", captcha.value);

  fetch(`index.php?route=product/product/write&product_id=${product_id}`, {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    console.log(data);
    if(data.error) {
      UIkit.notification({
        message: data.error,
        status: 'danger',
        pos: 'top-center',
        timeout: 2000
      });
    } else {
      UIkit.modal.alert(`<p class='uk-text-large'>${data.success}</p>`);
      name.value = "";
      text.value = "";
    }
  })
  .catch((error) => {
    console.error('Error:', error);
  });
}

/**
 *  Load reviews on pagination click
 *  
 */
function loadReviews() {

  const el = document.querySelector("#product-reviews");

  el.addEventListener("click", e => {

    if (e.target && e.target.tagName === 'A' && e.target.parentNode.tagName === 'LI'){
        e.preventDefault();

        let href = e.target.getAttribute("href");

        fetch(href)
        .then(response => response.text())
        .then(data => {
          // console.log(data);
          document.querySelector("#product-reviews").innerHTML = data;
          document.querySelector("#product-content").scrollIntoView();
        })
        .catch((error) => {
          console.error('Error:', error);
        });
      }

  });

}


/* =========================================================== 
  Reccuring
=========================================================== */

function selectRecurring(product_id) {

  let quantity = document.querySelector("input[name=quantity").value;
  let recurring_id = event.target.value;

  let formData = new FormData();
  formData.append("product_id", product_id);
  formData.append("quantity", quantity);
  formData.append("recurring_id", recurring_id);

  fetch(`index.php?route=product/product/getRecurringDescription`, {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    // console.log(data);
    if (data.success) {
      document.querySelector("#recurring-description").innerHTML = data.success;
    } else {
      document.querySelector("#recurring-description").innerHTML = "";
    }
  })
  .catch((error) => {
    console.error('Error:', error);
  });

}

/* =========================================================== 
  Coupon
=========================================================== */
function applyCoupon(coupon = '') {
  event.preventDefault();

  if (coupon == '') {
    let couponField = document.querySelector("input[name=coupon]");
    if (couponField) coupon = couponField.value;
  }

  let formData = new FormData();
  formData.append("coupon", coupon);

  fetch(`index.php?route=extension/total/coupon/coupon`, {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    //console.log(data);

    if (data.error) {
      UIkit.notification({
        message: data.error,
        status: 'danger',
        pos: 'top-center',
        timeout: 2000
      });
    } else if (data.redirect) {
      window.location.href = data.redirect;
    }

  })
  .catch((error) => {
    console.error('Error:', error);
  });

}

/* =========================================================== 
  Voucher
=========================================================== */

function applyVoucher() {
  event.preventDefault();

  let voucher = document.querySelector("input[name=voucher]");

  let formData = new FormData();
  formData.append("voucher", voucher.value);

  fetch(`index.php?route=extension/total/voucher/voucher`, {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    // console.log(data);

    if (data.error) {
      UIkit.notification({
        message: data.error,
        status: 'danger',
        pos: 'top-center',
        timeout: 2000
      });
    } else if (data.redirect) {
      window.location.href = data.redirect;
    }

  })
  .catch((error) => {
    console.error('Error:', error);
  });

}

/* =========================================================== 
  Mobile menu
=========================================================== */

function menu(type = "") {
  event.preventDefault();
  let menu = document.querySelector("#menu-off");
  let isLoaded = menu.querySelector(".uk-offcanvas-close");
  if (isLoaded) {
    UIkit.offcanvas(menu).show();
  } else {
    UIkit.offcanvas(menu).hide();
    fetch(`index.php?route=kreativan/ajax/${type}`)
    .then(response => response.text())
    .then(data => {
      document.querySelector("#menu-off .uk-offcanvas-bar").innerHTML = data;
      UIkit.offcanvas(menu).show();
    })
  }
}

/* =========================================================== 
  Modal Confirm
=========================================================== */
/**
 *  Display modal confirm (UIkit)
 *  It's just gonna prompt you "are you sure" and redirect to the href url
 *  @example <a href="#" onclick="modalConfirm('Are you Sure?', 'My custom text here')">Example</a>
 *  @param {string} q
 *  @param {string} txt
 *
 */
function modalConfirm(q, txt) {

  event.preventDefault();

  var e = event.target;
  e = event.target.getAttribute("href") ? event.target : event.target.closest("a");
  let href = e.getAttribute('href');
  let question = (q != null) ? q : "Are you sure?";
  let heading = `<div class="tm-modal-confirm-title uk-h2 uk-text-center uk-margin-remove">${question}</div>`;
  let text = txt != null ? `<div class="tm-modal-confirm-text uk-text-center uk-margin-small-top">${txt}</div>` : "";

  UIkit.modal.confirm(heading+text).then(function () {
    window.location.replace(href);
    }, function () {
    // console.log('Rejected.')
  });

}

/* =========================================================== 
  Forms
=========================================================== */
function getFormData(form, customData = "") {
  let formData = new FormData();

  let fields = form.querySelectorAll("input, select, textarea");

  if (customData != "") {
    customData.forEach((e) => {
      formData.append(e.name, e.value);
    });
  }

  fields.forEach((e) => {
    let name = e.getAttribute("name");
    let value = e.value;
    formData.append(name, value);
  });

  return formData;
}

function emptyFormData(form) {
  let fields = form.querySelectorAll("input, select, textarea");
  fields.forEach((e) => {
    e.value = "";
  });
}

/* =========================================================== 
  Cookie
=========================================================== */

function setCookie(name, value, days) {
  if (days) {
    var d = new Date();
    d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
    var expires = "; expires=" + d.toGMTString();
  } else var expires = "";
  document.cookie = name + "=" + value + expires + "; path=/";
  //console.log("cookie is set to" + d);
}

// Get the cookie by name
function getCookie(name) {
  var dc = document.cookie;
  var prefix = name + "=";
  var begin = dc.indexOf("; " + prefix);
  if (begin == -1) {
    begin = dc.indexOf(prefix);
    if (begin != 0) return null;
  } else {
    begin += 2;
    var end = document.cookie.indexOf(";", begin);
    if (end == -1) {
      end = dc.length;
    }
  }
  // because unescape has been deprecated, replaced with decodeURI
  //return unescape(dc.substring(begin + prefix.length, end));
  return decodeURI(dc.substring(begin + prefix.length, end));
}

//  MOdal Popup
// ===========================================================
const popup = document.querySelector("#modal-popup");
if (getCookie("popup") == null && popup) {
  setTimeout(function() {
    UIkit.modal(popup).show();
  }, 2000)
}
function closePopup() {
  fetch(`index.php?route=kreativan/popup/close`);
}
