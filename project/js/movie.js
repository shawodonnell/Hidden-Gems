var sideMenuOpen = document.getElementById("slideMenuButton");
var closeMenu = document.getElementById("Close");
var dc = document.getElementById("dc");


function toggleSlideMenu() {
    document.getElementById("slideSideMenu").classList.toggle('open');
    document.getElementById("slideUnderLayer").classList.toggle('open');
}

sideMenuOpen.addEventListener('click',toggleSlideMenu);
closeMenu.addEventListener('click',toggleSlideMenu);
window.addEventListener('load', ()=>{  
document.getElementById('slideUnderLayer').removeAttribute("hidden");
});

