// profile form
const personnalDetailBtn = document.getElementById("personnal-detail-btn")
const changePasswordBtn = document.getElementById("change-password-btn")
const personnalDetailPage = document.getElementById("personnal-details")
const changePasswordPage = document.getElementById("change-password")

const showPersonnalDetailPage =  () => {
    personnalDetailPage.classList.remove("hidden")
    personnalDetailBtn.classList.add("border-b")
    personnalDetailBtn.classList.add("border-b-blue-500")
}

const showChangePasswordChange = () => {
    changePasswordPage.classList.remove("hidden")
    changePasswordBtn.classList.add("border-b-blue-500")
    changePasswordBtn.classList.add("border-b")
}

const hideChangePasswordChange = () => {
    changePasswordPage.classList.add("hidden")
    changePasswordBtn.classList.remove("border-b-blue-500")
    changePasswordBtn.classList.remove("border-b")
}

const hidePersonnalDetailPage = () => {
    personnalDetailPage.classList.add("hidden")
    personnalDetailBtn.classList.remove("border-b")
    personnalDetailBtn.classList.remove("border-b-blue-500")
}

personnalDetailBtn.addEventListener("click", () => {
    hideChangePasswordChange()
    showPersonnalDetailPage()
})
changePasswordBtn.addEventListener("click", () => {
    hidePersonnalDetailPage()
    showChangePasswordChange()
})
