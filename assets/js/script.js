



function darkModeToggle() {
    const wasDarkmode = localStorage.getItem('darkmode') === 'true';
    localStorage.setItem('darkmode', !wasDarkmode);
    const element = document.body;
    element.classList.toggle('dark', !wasDarkmode);
    if(wasDarkmode){
        darkModeToggleBtn.setAttribute('name', 'moon');
    }else{
        darkModeToggleBtn.setAttribute('name', 'sunny');
    }
}

function onload() {
    document.body.classList.toggle('dark', localStorage.getItem('darkmode') === 'true');

    if (localStorage.getItem('darkmode') === 'true'){
        darkModeToggleBtn.setAttribute('name', 'sunny');
    }else{
        darkModeToggleBtn.setAttribute('name', 'moon');
    }
}




const mainContainer = document.getElementById('main-container');
const sidebarOptions = document.getElementById('sidebar-options');

function sidebarToggle() {
    if (sidebarOptions.classList.contains('hidden')) {
        // mainContainer.classList.replace('w-full', 'w-5/6');
        sidebarOptions.classList.remove('hidden');
        mainContainer.style.marginLeft = "4rem";
        sidebarOptions.classList.add('fixed', 'flex');
    } else {
        // mainContainer.classList.replace('w-5/6', 'w-full');
        sidebarOptions.classList.remove('fixed', 'flex');
        mainContainer.style.marginLeft = "0";
        sidebarOptions.classList.add('hidden');
    }
    console.log("toggled");
}

