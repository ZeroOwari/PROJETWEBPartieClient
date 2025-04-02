document.querySelectorAll('.toggle-btn').forEach(button => {
    button.addEventListener('click', () => {
        let list = button.nextElementSibling;
        list.classList.toggle('visible');

        let icon = button.querySelector("span");
        icon.textContent = list.classList.contains("visible") ? "➖" : "➕";
    });
});

