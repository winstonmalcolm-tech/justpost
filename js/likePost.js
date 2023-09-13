document.addEventListener('DOMContentLoaded', function() {
    var like_forms = document.querySelectorAll('.index__likes_form');

    like_forms.forEach(form => form[2].addEventListener('click', function(e) {

        //bind click handler
        e.preventDefault()
        var likes_form_data = new FormData();

        var post_id = form[0].value;
        var user_id = form[1].value;
        var button = form[2]

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
            likes_form_data.append('post_id', post_id)
            likes_form_data.append('user_id', user_id)
            likes_form_data.append('likes_button', button)

            xhr = new XMLHttpRequest();

            let url = document.URL
            let serverLink;
            
            if (url.includes('user_details.php') || url.includes('user_profile.php')) {
                serverLink = '../includes/userForms.include.php'
            } else {
                serverLink = './includes/userForms.include.php'
            }

            xhr.open("POST", serverLink)
            xhr.send(likes_form_data)

            xhr.onreadystatechange = function() {
                var response = xhr.responseText;

                if (xhr.readyState == 4 && xhr.status == 200) {
                    let heartTag = form[2].querySelector('.index__heart_icon')
                    let likesNumber = form.querySelector('.likedisplay')
                
                    if(!heartTag.classList.contains("heart")) {
                        heartTag.classList.add("heart")
                    } else {
                        heartTag.classList.remove("heart")
                    }

                    let number = JSON.parse(response);
                    likesNumber.innerHTML = number.postLikes

                }
            }
        }
        
        })
    )

})

