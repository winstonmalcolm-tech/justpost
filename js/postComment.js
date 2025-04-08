function htmlToElem(html) {
    let temp = document.createElement('template');
    html = html.trim(); // Never return a space text node as a result
    temp.innerHTML = html;
    return temp.content.firstChild;
  }

function comment(form_modal) {
    var form = form_modal.querySelector('.comment_form');

    let commentSubmitBtn = form[3]

    commentSubmitBtn.addEventListener('click', (e) => {
        e.preventDefault();

        var post_id = form[0].value
        var user_id = form[1].value
        let comment = form[2].value
        

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
    
        } else if (comment == '') { 
            Toastify({
                text: "Please enter text",
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
            commentSubmitBtn.innerHTML = `<div class="spinner-border" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>`
            let commentForm = new FormData();
    
            commentForm.append('post_id', post_id)
            commentForm.append('user_id', user_id)
            commentForm.append('comment', comment)
            commentForm.append('comment_btn', commentSubmitBtn)
    
            let xhr = new XMLHttpRequest()

            let serverLink;

            let url = document.URL

            if (url.includes('user_details.php') || url.includes('user_profile.php')) {
                serverLink = '../includes/userForms.include.php'
            } else {
                serverLink = './includes/userForms.include.php';
            }
    
            xhr.open('POST', serverLink)
            xhr.send(commentForm)
    
            xhr.onload = function() {
                if (this.status == 200) {
                    var response = JSON.parse(this.response);
                    response.commentContainer = form_modal.id

                    response.comment = comment; 
                    form[2].value = ''
                    commentSubmitBtn.innerHTML = `<i class="fa-regular fa-paper-plane">`
                    socket.emit('send_comment', response)
                    
                } else {
                    console.log("error")
                } 
            }
        }
    })
}


socket.off('receive_comment').on('receive_comment', (data) => {
    console.log(data);
        let url = document.URL;
        let img;
        
        if (url.includes("index.php")) {
            img = './uploads/'+data.username+'/'+data.image;
        } else {
            img = '../uploads/'+data.username+'/'+data.image;
        }

        let imgusername = `<dt><img src='./uploads/${data.username}/${data.image}' /> ${data.username}</dt>`;
        let commentoutput = "<dd>&emsp; "+data.comment+"</dd>"
        
        let commenttag = htmlToElem(imgusername)
        let commenttag2 = htmlToElem(commentoutput)

        let comment_modal = document.getElementById(data.commentContainer)
        
        comment_modal.querySelector('.user_comment').appendChild(commenttag);
        comment_modal.querySelector('.user_comment').appendChild(commenttag2);
})










//let img;

// if (url.includes("index.php")) {
//     img = './uploads/'+response.username+'/'+response.image;
// } else {
//     img = '../uploads/'+response.username+'/'+response.image;
// }

//<dt><img src='./uploads/".$poster['username']."/".$poster['profile_img']."' alt='userimg'> " . $poster['username'] . "</dt>
// let imgusername = `<dt><img src='./uploads/${response.username}/${response.image}' alt='userimg' /> ${response.username}</dt>`;
// let commentoutput = "<dd>&emsp; "+comment+"</dd>"

// let commenttag = htmlToElem(imgusername)
// let commenttag2 = htmlToElem(commentoutput)

// form_modal.querySelector('.user_comment').appendChild(commenttag);
// form_modal.querySelector('.user_comment').appendChild(commenttag2);