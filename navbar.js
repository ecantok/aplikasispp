var body = document.getElementsByClassName("container");
var navtop = document.getElementsByClassName("navtop");
var footer = document.getElementsByClassName("footer");

var menuToggle = document.querySelector(".menu-toggle input");
var menuToggleClose = document.querySelector(".menu-toggle-close input");
var input = document.querySelector(".menu-toggle>input");
Androidscreen = window.matchMedia("(max-width: 578px)");
cek(Androidscreen) // Call listener function at run time
Androidscreen.addListener(cek) // Attach listener function on state changes

function cek(x) {
  if (x.matches) { // If media query matches
    for (let i = 0; i < body.length; i++) {
      navtop[i].style.marginLeft = "170px";
      body[i].style.marginLeft = "170px";
      footer[i].style.marginLeft = "170px";
    }
  } else {
    document.querySelector(".navbar").style.display = "flex";
  }
}

menuToggle.onclick = function () {
  if (input.checked) {
    document.querySelector(".navbar").style.display = "none";
    if (Androidscreen.matches) {
      document.querySelector(".navbar").style.display = "flex";
    } else {
      for (let i = 0; i < body.length; i++) {
        navtop[i].style.marginLeft = "0px";
        body[i].style.marginLeft = "0px";
        footer[i].style.marginLeft = "0px";
      }
    }
  } else {
    if (Androidscreen.matches) {
      document.querySelector(".navbar").style.display = "flex";
    } else {
      document.querySelector(".navbar").style.display = "flex";
      for (let i = 0; i < body.length; i++) {
        navtop[i].style.marginLeft = "170px";
        body[i].style.marginLeft = "170px";
        footer[i].style.marginLeft = "170px";
      }
    }
  }
}
menuToggleClose.onclick = function () {
  document.querySelector(".navbar").style.display = "none";
}