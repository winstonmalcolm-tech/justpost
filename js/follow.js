document.addEventListener('DOMContentLoaded', function() {
    const dropDownForms = document.querySelectorAll('.index__dropdown_form')

    dropDownForms.forEach(form => form[2].addEventListener('click', function(e) {
        e.preventDefault()
        follow(form)
    }))

})

function follow(form) {
    //form.querySelector(".dropdown_follow_btn").value = "Unfollow"
    let user_post_id = form.querySelector(".dropdown_user_post_id").value
    let user_id = form.querySelector(".dropdown_user_id").value
    let follow_btn = form.querySelector(".dropdown_follow_btn")

    if (user_id == '') {
        Toastify({
            text: "Not signed In",
            duration: 3000,
            close: true,
            gravity: "bottom", // `top` or `bottom`
            position: "right", // `left`, `center` or `right`
            stopOnFocus: true, // Prevents dismissing of toast on hover
            style: {
              background: "linear-gradient(to right, #00b09b, #96c93d)",
            },
            onClick: function(){} // Callback after click
          }).showToast();

    } else {
        let formData = new FormData()
        formData.append('user_post_id', user_post_id)
        formData.append('user_id', user_id)
        formData.append('follow_btn', follow_btn)

        const xhr = new XMLHttpRequest()

        xhr.open("POST", "./includes/userForms.include.php")
        xhr.send(formData)
        
        xhr.onload = function() {
            if(this.status == 200) {
                const dropDownFormschange = document.querySelectorAll('.index__dropdown_form')
                dropDownFormschange.forEach(form => {
                    let user_post_id2 = form.querySelector(".dropdown_user_post_id").value
                    let user_id2 = form.querySelector(".dropdown_user_id").value
                    let follow_btn2 = form.querySelector(".dropdown_follow_btn")
                    if (user_post_id2 == user_post_id && user_id2 == user_id) {
                        follow_btn2.value = this.response
                    }
                })
                
            }
        }

        xhr.onerror = function() {
            console.log("Error occured")
        }
    }  
}