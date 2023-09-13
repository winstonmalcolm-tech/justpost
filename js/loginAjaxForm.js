document.getElementById("index__login_button").addEventListener("click", (e) => {
    e.preventDefault();

    var login_form_data = new FormData();
    const username2 = document.getElementById("login_username").value;
    const password2 = document.getElementById("login_password").value;
    const button2 = document.getElementById("index__login_button");

    button2.style.display = 'none'
    document.getElementById("login_spinner").style.display = 'block'
    
    login_form_data.append('username', username2)
    login_form_data.append('password', password2)
    login_form_data.append('login_submit', button2)

    xhr = new XMLHttpRequest();
    
    let serverLink;
    let url = document.URL
    
    if (url.includes('user_details.php')) { 
        serverLink = '../includes/userForms.include.php'
    } else {
        serverLink = './includes/userForms.include.php'
    }

    xhr.open("POST", serverLink);
    xhr.send(login_form_data)

    xhr.onreadystatechange = function() {
        let response = xhr.responseText;

        setInterval(function() {}, 2000);
        if(xhr.readyState == 4 && xhr.status == 200) {
            if (response.includes("Successful")) {
                location.reload();
                button2.style.display = 'block'
                document.getElementById("login_spinner").style.display = 'none'
                document.querySelector(".index__not_signed_in_handle").style.display = 'none';
                document.querySelector(".index__user_handle").style.display = 'flex';
            } else {
                document.getElementById("index__error_message2").innerHTML = response;
                button2.style.display = 'block'
                document.getElementById("login_spinner").style.display = 'none'
            }   
        } 
    } 



})