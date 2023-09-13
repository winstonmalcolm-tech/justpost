document.getElementById("index_register_button").addEventListener("click", (e) => {
    e.preventDefault();
   
    var registerForm = document.getElementById('index__register_form')
    var form_data = new FormData();
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const button = document.getElementById("index_register_button");
    const image = document.getElementById("file");
    const files = image.files;
    const file = files[0];

    button.style.display = 'none'
    document.getElementById('register_spinner').style.display = 'block'

    if (file == undefined) {
        button.style.display = 'block'
        document.getElementById('register_spinner').style.display = 'none'
        document.getElementById("index__error_message").innerHTML = "*Please select a profile image";
    } else if (username == '' || password == '') {
        button.style.display = 'block'
        document.getElementById('register_spinner').style.display = 'none'
        document.getElementById("index__error_message").innerHTML = "*Please provide input for all fields";
    } else {
        form_data.append('username',username)
        form_data.append("password",password)
        form_data.append("profile_pic", file)
        form_data.append("register_submit", button)
        
        xhr = new XMLHttpRequest();

        let serverLink;
        let url = document.URL
        
        if (url.includes('user_details.php')) { 
            serverLink = '../includes/userForms.include.php'
        } else {
            serverLink = './includes/userForms.include.php'
        }

        xhr.open("POST", serverLink);
        xhr.send(form_data)


        xhr.onreadystatechange = function() {
            let response = xhr.responseText;

            if(xhr.readyState == 4 && xhr.status == 200) {
                if (response == "You are registered, you can now login in") {
                    document.getElementById("index__error_message").style.color = 'green'
                    document.getElementById('register_spinner').style.display = 'none'
                    button.style.display = 'block'
                }
                
                document.getElementById("index__error_message").innerHTML = response;
            }
        }
    }
 
})