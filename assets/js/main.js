// Form validatie
function validateForm(formId) {
    const form = document.getElementById(formId);
    const inputs = form.getElementsByTagName('input');
    
    for (let input of inputs) {
        if (input.required && !input.value) {
            alert('Vul alle verplichte velden in');
            return false;
        }
    }
    return true;
}

// Package selectie
function selectPackage(packageId) {
    localStorage.setItem('selectedPackage', packageId);
    window.location.href = 'checkout.php';
}

// Checkout verwerking
function processCheckout() {
    // Checkout logica
    const selectedPackage = localStorage.getItem('selectedPackage');
    if (!selectedPackage) {
        alert('Selecteer eerst een pakket');
        return false;
    }
    return true;
} 