
var buttonMenu = document.getElementById('buttonMenu');
var closeMenu = document.getElementById('closeMenu');

var nav = document.getElementById('nav');

closeMenu.addEventListener("click", close_Menu);
buttonMenu.addEventListener("click", openMenu);

function openMenu() {
  nav.classList.remove('nav');
  nav.classList.add('nav2');
}

function close_Menu() {
  nav.classList.remove('nav2');
  nav.classList.add('nav');

}
