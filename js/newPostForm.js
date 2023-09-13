document.getElementById("new_post_button").addEventListener('click', (e) => {
    e.preventDefault();

    post_data = new FormData();
    const image = document.getElementById("postfile").files[0]
    const comment = document.getElementById("user_upload_comment").value
    const id = document.getElementById("index__upload_post_userid").value
    const username = document.getElementById("index__upload_post_username").value;
    const button = document.getElementById("new_post_button")

    button.style.display = 'none'
    document.getElementById('post_spinner').style.display = 'block'

    if (image == undefined) {
      document.querySelector("#index__error_message_post_upload").innerHTML = "*Please select a image to Post";
      document.getElementById('post_spinner').style.display = 'none'
      button.style.display = 'block' 
    } else if ( comment == '') {
      document.querySelector("#index__error_message_post_upload").innerHTML = "*Please enter a caption";
      document.getElementById('post_spinner').style.display = 'none'
      button.style.display = 'block' 
    } else {
      post_data.append('image',image)
      post_data.append('comment', comment)
      post_data.append('id',id)
      post_data.append('username', username)
      post_data.append('new_post_button', button)

      xhr = new XMLHttpRequest();
      
      xhr.open("POST", "../includes/userForms.include.php")
      xhr.send(post_data)

      xhr.onload = function() {
          let response = this.responseText;

          if(xhr.status == 200) {
              document.getElementById('post_spinner').style.display = 'none'
              button.style.display = 'block'
              
              location.reload();
              Toastify({
                  text: "Post uploaded",
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
            console.log("404")
          }
      } 
    }

    
})