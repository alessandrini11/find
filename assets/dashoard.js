// menu
const profileIcon = document.getElementById("profile-icon")
const floatingMenu = document.getElementById("floating-menu")
const openMenu = document.getElementById('openMenu')
const closeMenu = document.getElementById('closeMenu')
const mobileMenu = document.getElementById('mobileMenu')

profileIcon.addEventListener("click", () => {
    floatingMenu.classList.toggle("hidden")
})

openMenu.addEventListener('click', () => {
    console.log("ok")
    mobileMenu.classList.remove('-translate-x-full')
    setTimeout(() => {
        mobileMenu.classList.add('bg-black/10')
    }, 50)

})

closeMenu.addEventListener('click', () => {
    mobileMenu.classList.remove('bg-black/10')
    mobileMenu.classList.add('-translate-x-full')
})
