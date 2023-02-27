const modal = document.getElementById("modal")
const closeButton = document.getElementById("close-button")
const message = document.getElementById("message")
const iconContainer = document.getElementById("icon-container")
let messageContent = "Payement effectué avec succès"
    function addPayment(id){
        fetch(
            '/add-payment/' + id,
            {
                method: 'POST',
                headers: {
                    "Content-type": "application/json",
                },
            })
            .then(response => {
                switch (response.status) {
                    case 200:
                        iconContainer.innerHTML = ` <i class="text-6xl text-green-500 fa-regular fa-circle-check"></i> `
                        message.innerText = messageContent
                        modal.classList.remove("hidden")
                        break
                    case 401:
                        iconContainer.innerHTML = ` <i class="text-6xl text-red-500 fa fa-circle-exclamation"></i> `
                        message.innerText = 'Vous devez vous connecter!'
                        modal.classList.remove("hidden")
                        break
                    case 404:
                        iconContainer.innerHTML = ` <i class="text-6xl text-red-500 fa fa-circle-exclamation"></i> `
                        message.innerText = "La déclaration n'a pas été trouvé"
                        modal.classList.remove("hidden")
                        break
                    default:
                        iconContainer.innerHTML = ` <i class="text-6xl text-red-500 fa fa-circle-exclamation"></i> `
                        message.innerText = "Une erreur est survenue"
                        modal.classList.remove("hidden")
                }
            })
            .catch(err => {
                console.log(err)
            })
    }

    closeButton.addEventListener("click", () => {
        modal.classList.add("hidden")
        window.location.href = ""
    })