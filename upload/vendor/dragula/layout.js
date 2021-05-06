window.addEventListener("DOMContentLoaded", function() {

  //
  // TOP
  //
  const dragula_top = document.querySelector("#module-content-top tbody");
  var drakeTop = dragula([dragula_top], {
    moves: function (el, container, handle) {
      return handle.classList.contains('input-group');
    }
  });
  drakeTop.on("drop", function(el, target, source, sibling) {
    let i = 0;
    let select_all = document.querySelectorAll("#module-content-top .input-group input");
    select_all.forEach(e => {
      if (e.value != "content_top") {
        let n = i++;
        e.value = n;
        //console.log(e.value);
      }
    });
  });


  //
  // BOTTOM
  //
  const dragula_bottom = document.querySelector("#module-content-bottom tbody");
  var drakeBottom = dragula([dragula_bottom], {
    moves: function (el, container, handle) {
      return handle.classList.contains('input-group');
    }
  });
  drakeBottom.on("drop", function(el, target, source, sibling) {
    let i = 0;
    let select_all = document.querySelectorAll("#module-content-bottom .input-group input");
    select_all.forEach(e => {
      if (e.value != "content_bottom") {
        let n = i++;
        e.value = n;
        //console.log(e.value);
      }
    });
  });

  //
  // LEFT
  //
  const dragula_left = document.querySelector("#module-column-left tbody");
  var drakeLeft = dragula([dragula_left], {
    moves: function (el, container, handle) {
      return handle.classList.contains('input-group');
    }
  });
  drakeLeft.on("drop", function(el, target, source, sibling) {
    let i = 0;
    let select_all = document.querySelectorAll("#module-column-left .input-group input");
    select_all.forEach(e => {
      if (e.value != "column_left") {
        let n = i++;
        e.value = n;
        //console.log(e.value);
      }
    });
  });

  //
  // RIGHT
  //
  const dragula_right = document.querySelector("#module-column-right tbody");
  var drakeRight = dragula([dragula_right], {
    moves: function (el, container, handle) {
      return handle.classList.contains('input-group');
    }
  });
  drakeRight.on("drop", function(el, target, source, sibling) {
    let i = 0;
    let select_all = document.querySelectorAll("#module-column-right .input-group input");
    select_all.forEach(e => {
      if (e.value != "column_right") {
        let n = i++;
        e.value = n;
        //console.log(e.value);
      }
    });
  });


});