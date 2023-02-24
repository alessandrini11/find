const openMenu = document.getElementById("open-menu")
const closeMenu = document.getElementById("close-menu")
const mobileMenu = document.getElementById("mobile-menu")

openMenu.addEventListener("click", () => {
    mobileMenu.classList.remove('-translate-x-full')
})

closeMenu.addEventListener('click', () => {
    mobileMenu.classList.add('-translate-x-full')
})

