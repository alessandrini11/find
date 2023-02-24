const stars = document.querySelectorAll('.stars span')
const keys = Object.keys(stars)
function hover(id) {
    keys.map((key) => {
        if(key < id){
            const element = stars[key];
            element.classList.add('text-[goldenrod]')
        }
    });
}