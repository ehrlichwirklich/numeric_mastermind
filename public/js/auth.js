//event listeners
document.getElementById("uname").addEventListener("change", (event) => {validate(event, "uname", "uhint", "src/auth.php" )});
document.getElementById("gcreate").addEventListener('submit', (event) => {validate(event, "gcreate","uhint", "src/auth.php" )});

function validate (event, id, hid, validation){
    if (event.type == "change"){
        if (event.target.value.length == 0)
            document.getElementById(hid).innerText = "";
        else{
            var req = new XMLHttpRequest();
            req.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById(hid).innerText = this.responseText;
                }
            };
            req.open("GET", validation + "?" + id +"=" +  encodeURIComponent(event.target.value) , true);
            req.send();
        }
    }else if (event.type == "submit"){
        if(document.getElementById(hid).innerText !== "")
            return false;
        
        return true;
    }
    
}
