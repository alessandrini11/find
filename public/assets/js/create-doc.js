const imageUploader = document.getElementById("document_imageFile")
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

$('.doc_type').select2();

const selects = document.getElementsByClassName('select2')
for (let i = 0; i < selects.length; i++){
    selects[i].style.width = "100%"
}