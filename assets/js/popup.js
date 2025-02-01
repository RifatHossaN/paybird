let p_tag = document.getElementById("popup-msg");
// Check if the URL contains 'success=true'
if (window.location.search.includes("success=true")) {
    // Show the popup and disable scrolling
    document.getElementById("successPopup").style.display = "block";
    document.body.classList.add("no-scroll"); // Disable scrolling
    if(window.location.search.includes("operation=delelte")){
        p_tag.innerText = "User has been deleted successfully!"
    }
    else if(window.location.search.includes("operation=add")){
        p_tag.innerText = "User has been added successfully!"
    }else if(window.location.search.includes("operation=update")){
        p_tag.innerText = "User has been updated successfully!"
    }

    
    else if(window.location.search.includes("operation=transdelelte")){
        p_tag.innerText = "Request Money has been deleted successfully!"
    }
    else if(window.location.search.includes("operation=transaccept")){
        p_tag.innerText = "Request Money has been accepted successfully!"
    }else if(window.location.search.includes("operation=transreject")){
        p_tag.innerText = "Request Money has been rejected successfully!"
    }else if(window.location.search.includes("operation=transcancel")){
        p_tag.innerText = "Request Money has been canceled successfully!"
    }
    }
    
    

// Close popup on button click
document.getElementById("closePopup").onclick = function() {
    document.getElementById("successPopup").style.display = "none";
    document.body.classList.remove("no-scroll"); // Enable scrolling
};
