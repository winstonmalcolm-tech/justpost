//Code section for register upload image
const imgDiv = document.querySelector(".index__profile_pic_register")
const img = document.querySelector("#index__profile_photo")
const file = document.querySelector("#file")
const uploadBtn = document.querySelector("#uploadBtn")

//If user hover on profile div
imgDiv.addEventListener('mouseenter', function() {
    uploadBtn.style.display = 'block'
})

//If user hover out profile div
imgDiv.addEventListener('mouseleave', function() {
    uploadBtn.style.display = 'none'
})

//When image is chosen, show it it div

file.addEventListener('change', function() {
    //this refers to file
    const choosenImg = this.files[0]

    if(choosenImg) {
        const reader = new FileReader(); //FileReader os a predefined function of javascript
        reader.addEventListener('load', function() {
            img.setAttribute('src', reader.result)
        })
        reader.readAsDataURL(choosenImg)
    }
})