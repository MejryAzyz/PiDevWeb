javascript
document.addEventListener('DOMContentLoaded', () => {
    const paginationLinks = document.querySelectorAll('.pagination .page-link');
    const table = document.querySelector('#accompagnateurs-table');

    paginationLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            // Prevent clicks on disabled links
            if (link.parentElement.classList.contains('disabled')) {
                event.preventDefault();
                return;
            }

            // Smooth scroll to table
            if (table) {
                table.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Highlight active page
    const activeLink = document.querySelector('.pagination .page-item.active .page-link');
    if (activeLink) {
        activeLink.style.backgroundColor = '#007bff';
        activeLink.style.color = '#fff';
        activeLink.style.borderColor = '#007bff';
    }
});
