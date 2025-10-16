// assets/script.js
// Handles interactive behaviors for the Simple Blog application.

document.addEventListener('DOMContentLoaded', () => {
    const deleteForms = document.querySelectorAll('[data-delete-form]');

    deleteForms.forEach((form) => {
        form.addEventListener('submit', (event) => {
            const postTitle = form.dataset.postTitle ?? 'this post';
            const shouldDelete = window.confirm(`Are you sure you want to delete ${postTitle}? This action cannot be undone.`);

            if (!shouldDelete) {
                event.preventDefault();
            }
        });
    });
});
