const imageUploader = document.getElementById("declaration_imageFile")
const imagePreviewBox = document.getElementById("img-preview-box")
const imagePreview = document.getElementById("preview-img")
const clearImage = document.getElementById("delete-img")

imageUploader.addEventListener("change", (e) => {
    imagePreview.src = URL.createObjectURL(e.target.files[0])
    imagePreviewBox.classList.remove("hidden")
})

clearImage.addEventListener("click", (e) => {
    imagePreview.value = null
    imagePreviewBox.classList.add("hidden")
    window.location.href = ""
})

// $('.townselect').select2();
// $('.municipalityselect').select2()
//
// console.log("ok")
//
// const selects = document.getElementsByClassName('select2')
// for (let i = 0; i < selects.length; i++){
//     selects[i].style.width = "100%"
//     selects[i].style.zIndex = -1
// }