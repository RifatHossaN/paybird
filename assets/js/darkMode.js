// function toggleDarkMode() {
//     document.body.classList.toggle('dark-mode');
//     document.body.classList.toggle('light-mode');

//     const button = document.querySelector('.dark-mode-toggle');
//     if (document.body.classList.contains('dark-mode')) {
//         button.textContent = '☀️ Light Mode';
//     } else {
//         button.textContent = '🌙 Dark Mode';
//     }
// }


// Apply saved mode on page load
document.addEventListener('DOMContentLoaded', () => {
    const darkModeEnabled = localStorage.getItem('darkMode') === 'enabled';
    const button = document.querySelector('.dark-mode-toggle');
    
    if (darkModeEnabled) {
        document.body.classList.add('dark-mode');
        document.body.classList.remove('light-mode');
        button.textContent = '☀️ Light Mode';
    } else {
        document.body.classList.add('light-mode');
        document.body.classList.remove('dark-mode');
        button.textContent = '🌙 Dark Mode';
    }
});

// Toggle function to switch modes and save preference
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    document.body.classList.toggle('light-mode');

    const button = document.querySelector('.dark-mode-toggle');
    if (document.body.classList.contains('dark-mode')) {
        button.textContent = '☀️ Light Mode';
        localStorage.setItem('darkMode', 'enabled');
    } else {
        button.textContent = '🌙 Dark Mode';
        localStorage.setItem('darkMode', 'disabled');
    }
}




function toggleSidebar() {
    document.querySelector(".sidebar").classList.toggle("active");
}