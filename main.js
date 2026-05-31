// DSS Advanced Care - Main JS

function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    if (menu) menu.classList.toggle('open');
}

// Scroll reveal
function revealOnScroll() {
    const els = document.querySelectorAll('.disease-card, .doc-card, .doc-card-full, .step-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((e, i) => {
            if (e.isIntersecting) {
                setTimeout(() => {
                    e.target.style.opacity = '1';
                    e.target.style.transform = 'translateY(0)';
                }, i * 60);
                observer.unobserve(e.target);
            }
        });
    }, { threshold: 0.1 });
    els.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(el);
    });
}

// Slot selection
function selectSlot(btn, slotId) {
    document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    const input = document.getElementById('selected_slot');
    if (input) input.value = slotId;
}

// Date tab switching
function switchDate(tab, date) {
    document.querySelectorAll('.date-tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    const allSlotGroups = document.querySelectorAll('.slot-group');
    allSlotGroups.forEach(g => g.style.display = 'none');
    const target = document.getElementById('slots-' + date);
    if (target) target.style.display = 'grid';
    // Reset selected slot
    const input = document.getElementById('selected_slot');
    if (input) input.value = '';
    document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
}

// Payment option selection
function selectPayment(method) {
    document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
    const selected = document.querySelector('[data-method="' + method + '"]');
    if (selected) selected.classList.add('selected');
    const input = document.getElementById('payment_method');
    if (input) input.value = method;
    const cardForm = document.getElementById('cardForm');
    if (cardForm) cardForm.style.display = (method === 'online') ? 'block' : 'none';
}

// Form validation
function validateBookingForm() {
    const slotId = document.getElementById('selected_slot')?.value;
    if (!slotId) {
        showToast('Please select a time slot', 'error');
        return false;
    }
    const required = ['patient_name', 'age', 'gender'];
    for (const field of required) {
        const el = document.getElementById(field);
        if (el && !el.value.trim()) {
            showToast('Please fill in all required fields', 'error');
            el.focus();
            return false;
        }
    }
    return true;
}

// Toast notification
function showToast(message, type = 'info') {
    const existing = document.querySelector('.toast');
    if (existing) existing.remove();
    const toast = document.createElement('div');
    toast.className = 'toast toast-' + type;
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed; bottom: 24px; right: 24px; z-index: 9999;
        background: ${type === 'error' ? '#e74c3c' : type === 'success' ? '#2ecc71' : '#1a6fc4'};
        color: white; padding: 14px 22px; border-radius: 12px;
        font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 600;
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        animation: slideIn 0.3s ease;
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3500);
}

// Print appointment
function printAppointment() {
    window.print();
}

document.addEventListener('DOMContentLoaded', function() {
    revealOnScroll();

    // Auto-show first date tab
    const firstDateTab = document.querySelector('.date-tab');
    if (firstDateTab) {
        firstDateTab.classList.add('active');
        const firstGroup = document.querySelector('.slot-group');
        if (firstGroup) firstGroup.style.display = 'grid';
    }

    // Default payment method
    const payAtHospital = document.querySelector('[data-method="pay_at_hospital"]');
    if (payAtHospital) {
        payAtHospital.classList.add('selected');
    }
});

const style = document.createElement('style');
style.textContent = `@keyframes slideIn { from { opacity:0; transform: translateX(30px); } to { opacity:1; transform: translateX(0); } }`;
document.head.appendChild(style);
