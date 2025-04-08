function searchUser(letter) {
    predictionBox = document.querySelector('.index__prediction_box');
    predictionBox.style.display = 'block';
    predictionBox.style.visibility= 'visible';

    let dt_element = predictionBox.querySelectorAll('dt')
    for (let d of dt_element) {
        d.remove()
    }

    if (letter == '') {
        let output = `
                <dt style='color: #fff;'>No result</dt>
            `;
        predictionBox.querySelector('dl').insertAdjacentHTML('beforeend', output)
    } else {
        let xhr = new XMLHttpRequest();

        let serverLink;
        let url = document.URL
              
        if (url.includes('user_details.php') || url.includes('user_profile.php')) {
            serverLink = '../includes/userForms.include.php?q='+letter
        } else {
            serverLink = './includes/userForms.include.php?q='+letter
        }
        
        xhr.open('POST', serverLink)
        xhr.send()

        xhr.onload = function() {
            let responses = JSON.parse(this.response)
            //console.log(this.response)

            if (this.status == 200) {

                if (responses == '') {
                    let output = `
                            <dt style='color: #fff;'>No result</dt>
                        `;
                    predictionBox.querySelector('dl').insertAdjacentHTML('beforeend', output)
                } else {
                    
                    for(let response of responses) {
                        
                        if (url.includes('user_details.php') || url.includes('user_profile.php')) {
                           var output = `
                                <dt><a href="./user_details.php?user_id=${response.user_id}"><img src="../uploads/${response.username}/${response.profile_img}" alt="userImage">${response.username}</a></dt>
                            `;
                        } else {
                            var output = `
                                <dt><a href="./pages/user_details.php?user_id=${response.user_id}"><img src="./uploads/${response.username}/${response.profile_img}" alt="userImage">${response.username}</a></dt>
                            `;
                        }
                        
                        predictionBox.querySelector('dl').insertAdjacentHTML('beforeend', output)
                    }
                }

            }
        }
    }

    

}

function showInputbar() {
    url = document.URL

    if (url.includes('user_profile.php')) {
        document.querySelector('.index__user_handle').style.display = 'none'
    } else {
        document.querySelector('.index__not_signed_in_handle').style.display = 'none'
    }
    document.querySelector('.index__logo').style.display = 'none'
    document.querySelector('.index__user_handle').style.display = 'none'
    document.querySelector('.searchicon').style.display = 'none'
    document.querySelector('.index__search_bar').style.width = '100%'
    document.querySelector('.inputsearchbar').style.display = 'block';
    document.querySelector('.inputsearchbar').focus()
}

function resetState(username) {
    hideBox();
    if (window.matchMedia("(max-width: 600px)").matches) {
        if (!username || username == "Unknown") {
            document.querySelector('.index__not_signed_in_handle').style.display= "flex"
        } else  {
            document.querySelector('.index__user_handle').style.display= "flex"
        }
        
        
        document.querySelector('.index__logo').style.display = "block"
        document.querySelector('.searchicon').style.display= "block"
        document.querySelector('.index__search_bar').style.width = 'auto'
        document.querySelector('.inputsearchbar').style.display = 'none'
        
    } 
}


function hideBox() {
    predictionBox = document.querySelector('.index__prediction_box').style.display = 'none';
}