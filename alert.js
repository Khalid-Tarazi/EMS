function showToast(message, isSuccess) {
    const toast = document.createElement('div');
    toast.className = `toast ${isSuccess ? 'success' : 'error'}`;
    toast.innerText = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('show');
    }, 100); // Delay for transition

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300); // Delay for transition
    }, 3000); // Duration for display
}

// Listen for toast messages from PHP
document.addEventListener('DOMContentLoaded', (event) => {
    const toastMessage = document.getElementById('toast-message');
    if (toastMessage) {
        const message = toastMessage.dataset.message;
        const isSuccess = toastMessage.dataset.success === 'true';
        showToast(message, isSuccess);
    }
});
